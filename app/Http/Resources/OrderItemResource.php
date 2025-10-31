<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'quantity'    => (int) $this->quantity,
            'unit_price'  => (float) $this->unit_price,
            'total_price' => (float) $this->total_price,
            'product'     => new ProductMiniResource($this->whenLoaded('product')),
        ];
    }
}
