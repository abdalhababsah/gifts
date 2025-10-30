<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\PaymentConfirmRequest;
use App\Http\Requests\Api\PaymentIntentRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Services\Api\CartService;
use App\Services\Api\OrderPlacementService;
use App\Services\Api\PaymentGatewayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends ApiController
{
    public function __construct(
        private CartService $cartService,
        private PaymentGatewayService $gateway,
        private OrderPlacementService $orderPlacement,
    ) {}

    /**
     * Create a payment session for the mobile SDK
     */
    public function intent(PaymentIntentRequest $request): JsonResponse
    {
        $user = $request->user();

        // Validate cart items
        $cart = $this->cartService->getCartItems($request);
        if (empty($cart['cart_items'])) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty',
            ], 422);
        }

        // Calculate total
        $total = (float)($cart['total_amount'] ?? 0.0);
        $payload = $request->validated();

        // Check for existing pending order with same details to prevent duplicates
        $existingOrder = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('total_amount', $total)
            ->where('shipping_address', $payload['shipping_address'])
            ->where('city_id', (int)$payload['city_id'])
            ->where('delivery_date', $payload['delivery_date'])
            ->where('delivery_time_slot_id', (int)$payload['delivery_time_slot_id'])
            ->whereHas('payment', function($query) {
                $query->where('status', 'initiated');
            })
            ->with('payment')
            ->first();

        if ($existingOrder && $existingOrder->payment) {
            // Return existing session instead of creating new one
            return response()->json([
                'success' => true,
                'message' => 'Using existing payment session',
                'data' => [
                    'order_id' => $existingOrder->id,
                    'payment_id' => $existingOrder->payment->id,
                    'amount' => (float)$existingOrder->payment->amount,
                    'currency' => $existingOrder->payment->currency,
                    'session_id' => $existingOrder->payment->session_id,
                ],
            ], 200);
        }

        // Create new order and payment
        try {
            return DB::transaction(function () use ($user, $payload, $total, $cart) {
                // Create pending order
                $order = Order::create([
                    'user_id' => $user->id,
                    'status' => 'pending',
                    'payment_method' => 'card',
                    'total_amount' => $total,
                    'shipping_address' => $payload['shipping_address'],
                    'city_id' => (int)$payload['city_id'],
                    'delivery_date' => $payload['delivery_date'],
                    'delivery_time_slot_id' => (int)$payload['delivery_time_slot_id'],
                    'is_gift' => $payload['is_gift'] ?? false,
                    'location_description' => $payload['location_description'] ?? null,
                    'extra_notes' => $payload['extra_notes'] ?? null,
                ]);

                // Create payment record
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'provider' => config('payments.provider', 'mpgs'),
                    'amount' => $total,
                    'currency' => config('payments.currency', 'JOD'),
                    'status' => 'initiated',
                    'raw_snapshot' => ['cart' => $cart],
                ]);

                // Create payment session via gateway
                $session = $this->gateway->createSession($payment);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment session created',
                    'data' => [
                        'order_id' => $order->id,
                        'payment_id' => $payment->id,
                        'amount' => (float)$payment->amount,
                        'currency' => $payment->currency,
                        'session_id' => $session['session_id'],
                    ],
                ], 201);
            });
        } catch (\Exception $e) {
            Log::error('Payment session creation failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'cart' => $cart
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
        $payment = Payment::with('order')->findOrFail((int)$request->validated('payment_id'));

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
                'order_id' => $payment->order_id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}