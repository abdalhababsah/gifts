<?php

declare(strict_types=1);

namespace App\Services\Api;

use App\Http\Resources\ProductResource;
use App\Models\Cart;
use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\OrderDiscount;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderPlacementService
{
    public function __construct(
        private AppliedDiscountStore $appliedDiscountStore,
    ) {}

    /**
     * Finalize an order after payment succeeds:
     * - Re-validate cart / stock for user
     * - Create order items
     * - Apply discount if any
     * - Decrement stock
     * - Set order total + status=paid
     * - Clear cart + forget saved discount
     */
    public function finalizePaidOrder(Payment $payment): array
    {
        /** @var Order $order */
        $order = $payment->order()->lockForUpdate()->firstOrFail();

        // Idempotency: if already paid and items exist, return current summary
        if ($order->status === 'paid' && $order->items()->exists()) {
            return $this->summary($order);
        }

        /** @var User $user */
        $user = $order->user()->firstOrFail();

        // Collect/validate cart rows
        $cartItems = Cart::with(['product' => function ($q) {
                $q->with(['brand','category']);
            }])
            ->where('user_id', $user->id)
            ->orderBy('added_at', 'desc')
            ->get();

        if ($cartItems->isEmpty()) {
            throw new \RuntimeException('Cart is empty; cannot finalize order.');
        }

        $validated = [];
        foreach ($cartItems as $ci) {
            $product = $ci->product;

            if (!$product || !$product->is_active) {
                throw new \RuntimeException("Product #{$ci->product_id} is unavailable.");
            }

            if ($ci->quantity > $product->stock) {
                throw new \RuntimeException("Insufficient stock for '{$product->name_en}'.");
            }

            $validated[] = [
                'product'   => $product,
                'quantity'  => $ci->quantity,
                'unit_price'=> (float) $product->price,
                'total'     => (float) $product->price * $ci->quantity,
            ];
        }

        // Compute subtotal
        $subtotal = array_sum(array_column($validated, 'total'));

        // Re-apply saved discount (if any) against current subtotal
        $discountPayload = null;
        $total = $subtotal;

        $saved = $this->appliedDiscountStore->get($user->id);
        if ($saved && !empty($saved['code'])) {
            $eval = $this->evaluateDiscount($saved['code'], $subtotal, $user->id);
            if ($eval['ok']) {
                $discountPayload = $eval['discount'];
                $total = $eval['total'];
            } else {
                // remove invalid code silently
                $this->appliedDiscountStore->forget($user->id);
            }
        }

        // Transaction: create items, decrement stock, set totals
        DB::transaction(function () use ($order, $payment, $validated, $subtotal, $total, $discountPayload, $user) {
            // Create order items
            foreach ($validated as $row) {
                /** @var Product $product */
                $product = $row['product'];
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => $row['quantity'],
                    'unit_price' => $row['unit_price'],
                    'total_price'=> $row['total'],
                ]);

                // decrement stock
                $product->decrement('stock', $row['quantity']);
            }

            // Apply discount, if any
            if ($discountPayload) {
                OrderDiscount::create([
                    'order_id'         => $order->id,
                    'discount_code_id' => DiscountCode::where('code', $discountPayload['code'])->value('id'),
                    'applied_value'    => (float) $discountPayload['amount'],
                ]);
            }

            // Update order totals + status
            $order->update([
                'total_amount' => $total,
                'status'       => 'paid',
            ]);

            // Update payment record
            $payment->update([
                'amount'   => $total, // in case discount applied after intent
                'status'   => 'succeeded',
                'paid_at'  => now(),
            ]);

            // Clear cart + forget discount
            Cart::where('user_id', $user->id)->delete();
            $this->appliedDiscountStore->forget($user->id);
        });

        return $this->summary($order);
    }

    private function summary(Order $order): array
    {
        return [
            'order_id'  => $order->id,
            'status'    => $order->status,
            'total'     => (float) $order->total_amount,
            'currency'  => 'JOD',
            'delivery'  => [
                'date'  => $order->delivery_date?->toDateString(),
                'slot'  => optional($order->deliveryTimeSlot)->only(['id','code','name_en','name_ar']),
                'city'  => optional($order->city)->only(['id','name_en','name_ar']),
            ],
            'items'     => $order->items()->with('product')->get()->map(fn ($i) => [
                'product_id' => $i->product_id,
                'name'       => $i->product?->name_en,
                'sku'        => $i->product?->sku,
                'qty'        => $i->quantity,
                'unit_price' => (float) $i->unit_price,
                'total'      => (float) $i->total_price,
            ])->values(),
        ];
    }

    /**
     * Copied from/compatible with CheckoutController logic
     */
    public function evaluateDiscount(string $codeRaw, float $subtotal, ?int $userId): array
    {
        $messagesInvalid = [
            'en' => 'The discount code is invalid or expired.',
            'ar' => 'رمز الخصم غير صالح أو منتهي الصلاحية.',
        ];
        $messagesUsage = [
            'en' => 'This discount code usage limit has been reached.',
            'ar' => 'تم الوصول إلى الحد الأقصى لاستخدام رمز الخصم.',
        ];
        $messagesPerUser = [
            'en' => 'You have reached the per-user usage limit for this code.',
            'ar' => 'لقد وصلت إلى الحد المسموح به لكل مستخدم لهذا الرمز.',
        ];
        $messagesMinOrder = [
            'en' => 'Order total does not meet the minimum required for this code.',
            'ar' => 'إجمالي الطلب لا يحقق الحد الأدنى المطلوب لهذا الرمز.',
        ];

        $code = DiscountCode::where('code', $codeRaw)->valid()->first();
        if (!$code) {
            return ['ok' => false, 'message' => $messagesInvalid['en']];
        }

        if ($code->usage_limit) {
            $totalUsed = $code->orderDiscounts()->count();
            if ($totalUsed >= (int)$code->usage_limit) {
                return ['ok' => false, 'message' => $messagesUsage['en']];
            }
        }

        if ($code->per_user_limit && $userId) {
            $perUserUsed = $code->orderDiscounts()
                ->whereHas('order', fn ($q) => $q->where('user_id', $userId))
                ->count();
            if ($perUserUsed >= (int)$code->per_user_limit) {
                return ['ok' => false, 'message' => $messagesPerUser['en']];
            }
        }

        $minOrder = (float)$code->min_order_total;
        if ($subtotal < $minOrder) {
            return [
                'ok' => false,
                'message' => $messagesMinOrder['en'],
                'meta' => [
                    'required_min_order_total' => $minOrder,
                    'current_subtotal' => $subtotal,
                ],
            ];
        }

        $amount = (float) $code->calculateDiscount($subtotal);
        $amount = max(0.0, min($amount, $subtotal));
        $total  = max(0.0, $subtotal - $amount);

        return [
            'ok' => true,
            'discount' => [
                'code'   => $code->code,
                'type'   => $code->type,
                'value'  => (float) $code->value,
                'amount' => $amount,
            ],
            'total' => $total,
        ];
    }
}
