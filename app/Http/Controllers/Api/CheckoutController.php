<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\ApplyDiscountRequest;
use App\Models\City;
use App\Models\DeliveryTimeSlot;
use App\Models\DiscountCode;
use App\Models\OrderDiscount;
use App\Services\Api\CartService;
use App\Services\Api\AppliedDiscountStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutController extends ApiController
{
    public function __construct(
        private CartService $cartService,
        private AppliedDiscountStore $appliedDiscountStore
    ) {}

    /**
     * Checkout summary (auth only).
     * If a code was applied previously, we re-validate & show it automatically.
     * You can still pass ?discount_code=CODE to preview another code (without saving).
     */
    public function show(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $cart = $this->cartService->getCartItems($request);
        $subtotal = (float)($cart['total_amount'] ?? 0);
        $locale = app()->getLocale();

        $discountPayload = null;
        $total = $subtotal;

        // 1) Optional preview override from client (query/header) - does NOT persist
        $inputCode = $request->header('X-Discount-Code')
            ?? $request->query('discount_code')
            ?? $request->query('code');

        if ($inputCode) {
            $eval = $this->evaluateDiscount($inputCode, $subtotal, $userId);
            if ($eval['ok']) {
                $discountPayload = $eval['discount'];
                $total = $eval['total'];
            }
        }
        // 2) Otherwise fall back to the user's last applied code (persisted)
        else {
            $saved = $this->appliedDiscountStore->get($userId);
            if ($saved && !empty($saved['code'])) {
                $eval = $this->evaluateDiscount($saved['code'], $subtotal, $userId);
                if ($eval['ok']) {
                    $discountPayload = $eval['discount'];
                    $total = $eval['total'];
                } else {
                    // If it became invalid (min total changed, usage limit hit, expired…), forget it
                    $this->appliedDiscountStore->forget($userId);
                }
            }
        }

        $timeSlots = DeliveryTimeSlot::active()->ordered()->get([
            'id','code','name_en','name_ar','window_start','window_end','crosses_midnight','sort_order'
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'cart' => $cart,
                'summary' => [
                    'currency' => 'JOD',
                    'subtotal' => $subtotal,
                    'discount' => $discountPayload, // may be null
                    'total' => $total,
                ],
                'time_slots' => $timeSlots->map(fn ($t) => [
                    'id' => $t->id,
                    'code' => $t->code,
                    'name' => $locale === 'ar' ? $t->name_ar : $t->name_en,
                    'window_start' => $t->window_start,
                    'window_end' => $t->window_end,
                    'crosses_midnight' => (bool)$t->crosses_midnight,
                    'sort_order' => (int)$t->sort_order,
                ]),
            ],
        ]);
    }

    /**
     * Apply a discount and PERSIST it for the authenticated user (cache-backed).
     * Subsequent GET /checkout will show it automatically.
     */
    public function applyDiscount(ApplyDiscountRequest $request): JsonResponse
    {
        $userId = $request->user()->id;
        $cart = $this->cartService->getCartItems($request);
        $subtotal = (float)($cart['total_amount'] ?? 0);

        $eval = $this->evaluateDiscount($request->validated('code'), $subtotal, $userId);
        if (! $eval['ok']) {
            return response()->json([
                'success' => false,
                'message' => $eval['message'],
                'data' => $eval['meta'] ?? null,
            ], 422);
        }

        // Persist minimal payload; we always re-calc on read
        $this->appliedDiscountStore->put($userId, [
            'code' => $eval['discount']['code'],
        ]);

        return response()->json([
            'success' => true,
            'message' => $this->getLocalizedMessage([
                'en' => 'Discount applied successfully.',
                'ar' => 'تم تطبيق الخصم بنجاح.',
            ]),
            'data' => [
                'currency' => 'JOD',
                'subtotal' => $subtotal,
                'discount' => $eval['discount'],
                'total' => $eval['total'],
            ],
        ]);
    }

    /**
     * Clear the persisted discount for the user.
     */
    public function clearDiscount(Request $request): JsonResponse
    {
        $this->appliedDiscountStore->forget($request->user()->id);

        return response()->json([
            'success' => true,
            'message' => $this->getLocalizedMessage([
                'en' => 'Discount cleared.',
                'ar' => 'تم إلغاء الخصم.',
            ]),
        ]);
    }

    /**
     * Internal: validate code + compute totals. No persistence here.
     */
    private function evaluateDiscount(string $codeRaw, float $subtotal, ?int $userId): array
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
        if (! $code) {
            return ['ok' => false, 'message' => $this->getLocalizedMessage($messagesInvalid)];
        }

        if ($code->usage_limit) {
            $totalUsed = OrderDiscount::where('discount_code_id', $code->id)->count();
            if ($totalUsed >= (int)$code->usage_limit) {
                return ['ok' => false, 'message' => $this->getLocalizedMessage($messagesUsage)];
            }
        }

        if ($code->per_user_limit && $userId) {
            $perUserUsed = OrderDiscount::where('discount_code_id', $code->id)
                ->whereHas('order', fn ($q) => $q->where('user_id', $userId))
                ->count();
            if ($perUserUsed >= (int)$code->per_user_limit) {
                return ['ok' => false, 'message' => $this->getLocalizedMessage($messagesPerUser)];
            }
        }

        $minOrder = (float)$code->min_order_total;
        if ($subtotal < $minOrder) {
            return [
                'ok' => false,
                'message' => $this->getLocalizedMessage($messagesMinOrder),
                'meta' => [
                    'required_min_order_total' => $minOrder,
                    'current_subtotal' => $subtotal,
                ],
            ];
        }

        $amount = (float) $code->calculateDiscount($subtotal);
        $amount = max(0.0, min($amount, $subtotal));
        $total = max(0.0, $subtotal - $amount);

        return [
            'ok' => true,
            'discount' => [
                'code' => $code->code,
                'type' => $code->type,
                'value' => (float) $code->value,
                'amount' => $amount,
            ],
            'total' => $total,
        ];
    }
}
