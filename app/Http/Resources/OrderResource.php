<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        // Sum discounts if relation is loaded
        $discountTotal = 0.0;
        if ($this->relationLoaded('discounts')) {
            $discountTotal = (float) $this->discounts->sum(fn ($d) => (float) $d->applied_value);
        }

        return [
            'id'                   => $this->id,
            'status'               => $this->status,
            'receiver_name'        => $this->receiver_name,
            'receiver_phone'       => $this->receiver_phone,
            'delivery_status'      => $this->delivery_status ?? null,
            'location_description' => $this->location_description,
            'extra_notes'          => $this->extra_notes,
            'is_anonymous_delivery'=> (bool) $this->is_anonymous_delivery,
            'total_amount'         => (float) $this->total_amount, // stored order total (should be after discounts)
            'discount_total'       => $discountTotal,              // computed from relation
            'payment_method'       => $this->payment_method,
            'shipping_address'     => $this->shipping_address,
            'is_gift'              => (bool) $this->is_gift,
            'created_at' => optional($this->created_at)->toDayDateTimeString(),
            // 'updated_at'           => $this->updated_at,

            'city' => $this->whenLoaded('city', fn () => new CityResource($this->city)),

            'delivery_time_slot' => $this->whenLoaded('deliveryTimeSlot', fn () =>
                new DeliveryTimeSlotResource($this->deliveryTimeSlot)
            ),

            'payment' => $this->whenLoaded('payment', fn () => new PaymentResource($this->payment)),

            'items'   => $this->whenLoaded('orderItems', fn () =>
                OrderItemResource::collection($this->orderItems)
            ),

            'discounts' => $this->whenLoaded('discounts', fn () =>
                OrderDiscountResource::collection($this->discounts->loadMissing('discountCode'))
            ),
        ];
    }
}
