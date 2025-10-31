<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'       => $this->id,
            'url'      => $this->url ?? ($this->image_path ? asset('storage/' . $this->image_path) : null),
            // 'alt_text' => $this->alt_text ?? null,
        ];
    }
}
