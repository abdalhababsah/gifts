<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDiscountResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'applied_value'  => (float) $this->applied_value,
            'code'           => $this->whenLoaded('discountCode', fn () => $this->discountCode->code ?? null),
            'description'    => $this->whenLoaded('discountCode', fn () => $this->discountCode->description ?? null),
            'type'           => $this->whenLoaded('discountCode', fn () => $this->discountCode->type ?? null), // 'percent' | 'fixed'
        ];
    }
}
