<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductMiniResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();
        $nameField = 'name_' . ($locale === 'ar' ? 'ar' : 'en');

        return [
            'id'     => $this->id,
            'name'   => $this->$nameField ?? $this->name,
            'sku'    => $this->sku,
            'images' => ProductImageResource::collection($this->whenLoaded('images')),
        ];
    }
}
