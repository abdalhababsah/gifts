<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\PaymentConfirmRequest;
use App\Http\Requests\Api\PaymentIntentRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Services\Api\AppliedDiscountStore;
use App\Services\Api\CartService;
use App\Services\Api\OrderPlacementService;
use App\Services\Api\PaymentGatewayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends ApiController
{
    public function __construct(
        private CartService $cartService,
        private PaymentGatewayService $gateway,
        private OrderPlacementService $orderPlacement,
        private AppliedDiscountStore $appliedDiscounts,
    ) {
    }

    /**
     * Create a payment session for the mobile SDK
     */
    // ...
    public function intent(PaymentIntentRequest $request): JsonResponse
    {
        $user = $request->user();

        // 1) Validate cart items (server is the source of truth)
        $cart = $this->cartService->getCartItems($request);
        if (empty($cart['cart_items'])) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty',
            ], 422);
        }

        // 2) Compute subtotal from cart and apply any saved single discount BEFORE session
        $subtotal = (float) ($cart['total_amount'] ?? 0.0); // cart returns subtotal here
        $payable = $subtotal;                              // default payable = subtotal

        $discountPayload = null; // ['code','type','value','amount'] if valid

        if ($saved = $this->appliedDiscounts->get($user->id)) {
            if (!empty($saved['code'])) {
                $eval = $this->orderPlacement->evaluateDiscount($saved['code'], $subtotal, $user->id);
                if ($eval['ok']) {
                    $discountPayload = $eval['discount'];
                    $payable = (float) $eval['total']; // discounted total
                } else {
                    // invalid/expired/limits not met -> forget saved code
                    $this->appliedDiscounts->forget($user->id);
                }
            }
        }

        // 3) Prevent duplicates: reuse pending order with the SAME discounted total
        $payload = $request->validated();

        $existingOrder = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('total_amount', $payable) // must match discounted total
            ->where('shipping_address', $payload['shipping_address'])
            ->where('city_id', (int) $payload['city_id'])
            ->where('delivery_date', $payload['delivery_date'])
            ->where('delivery_time_slot_id', (int) $payload['delivery_time_slot_id'])
            ->whereHas('payment', fn($q) => $q->where('status', 'initiated'))
            ->with(['payment', 'discounts.discountCode'])
            ->first();

        if ($existingOrder && $existingOrder->payment) {
            return response()->json([
                'success' => true,
                'message' => 'Using existing payment session',
                'data' => [
                    'order_id' => $existingOrder->id,
                    'payment_id' => $existingOrder->payment->id,
                    'amount' => (float) $existingOrder->payment->amount, // already discounted
                    'currency' => $existingOrder->payment->currency,
                    'session_id' => $existingOrder->payment->session_id,
                ],
            ], 200);
        }

        // 4) Create order (total = discounted), persist ONE discount, create payment (discounted), create session
        try {
            return DB::transaction(function () use ($user, $payload, $payable, $cart, $discountPayload) {
                // Create pending order with the final (discounted) total
                $order = Order::create([
                    'user_id' => $user->id,
                    'status' => 'pending',
                    'payment_method' => 'card',
                    'total_amount' => $payable, // discounted total
                    'shipping_address' => $payload['shipping_address'],
                    'city_id' => (int) $payload['city_id'],
                    'delivery_date' => $payload['delivery_date'],
                    'delivery_time_slot_id' => (int) $payload['delivery_time_slot_id'],
                    'is_gift' => $payload['is_gift'] ?? false,
                    'location_description' => $payload['location_description'] ?? null,
                    'extra_notes' => $payload['extra_notes'] ?? null,
                ]);

                // Persist exactly ONE discount row if present
                if ($discountPayload) {
                    $discountCodeId = \App\Models\DiscountCode::where('code', $discountPayload['code'])->value('id');
                    if ($discountCodeId) {
                        \App\Models\OrderDiscount::updateOrCreate(
                            ['order_id' => $order->id],
                            [
                                'discount_code_id' => (int) $discountCodeId,
                                'applied_value' => (float) $discountPayload['amount'],
                            ]
                        );
                    }
                }

                // Create payment with the discounted amount
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'provider' => config('payments.provider', 'mpgs'),
                    'amount' => $payable, // discounted
                    'currency' => config('payments.currency', 'JOD'),
                    'status' => 'initiated',
                    'raw_snapshot' => ['cart' => $cart],
                ]);

                // Create gateway session
                $session = $this->gateway->createSession($payment);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment session created',
                    'data' => [
                        'order_id' => $order->id,
                        'payment_id' => $payment->id,
                        'amount' => (float) $payment->amount, // discounted
                        'currency' => $payment->currency,
                        'session_id' => $session['session_id'],
                    ],
                ], 201);
            });
        } catch (\Throwable $e) {
            Log::error('Payment session creation failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'cart' => $cart,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment session: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Confirm payment after mobile SDK has processed card details
     */
    public function confirm(PaymentConfirmRequest $request): JsonResponse
    {
        $user = $request->user();
        $payment = Payment::with('order')->findOrFail((int) $request->validated('payment_id'));
        // $payment->status = 'succeeded';
        // Security check
        if ($payment->order?->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Already processed?
        if ($payment->status === 'succeeded') {
            $summary = $this->orderPlacement->finalizePaidOrder($payment);

            return response()->json([
                'success' => true,
                'message' => 'Payment already confirmed',
                'data' => $summary,
            ]);
        }

        // Check if payment is still in valid state
        if (!in_array($payment->status, ['initiated', 'pending'])) {
            return response()->json([
                'success' => false,
                'message' => 'Payment is no longer valid for processing',
            ], 422);
        }

        // Complete payment with session updated by mobile SDK
        $sessionId = $request->validated('session_id');

        try {
            return DB::transaction(function () use ($payment, $sessionId) {
                // Process payment with session
                $payResult = $this->gateway->payWithSession($payment, $sessionId);

                if (!$payResult['ok']) {
                    return response()->json([
                        'success' => false,
                        'message' => $payResult['message'] ?? 'Payment failed',
                        'data' => $payResult,
                    ], 422);
                }

                // Update payment record
                $payment->update([
                    'provider_txn_id' => $payResult['provider_txn_id'] ?? null,
                    'status' => 'succeeded',
                    'paid_at' => now(),
                    'raw_snapshot' => array_merge($payment->raw_snapshot ?? [], [
                        'confirm' => $payResult['raw'] ?? [],
                    ]),
                ]);

                // Update order status
                $payment->order->update(['status' => 'confirmed']);

                // Finalize order
                $summary = $this->orderPlacement->finalizePaidOrder($payment);

                return response()->json([
                    'success' => true,
                    'message' => 'Order placed successfully',
                    'data' => $summary,
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Payment confirmation failed', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
                'order_id' => $payment->order_id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
