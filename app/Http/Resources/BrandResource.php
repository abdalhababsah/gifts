<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();
        $nameField = 'name_' . ($locale === 'ar' ? 'ar' : 'en');

        return [
            'id' => $this->id,
            'name' => $this->$nameField,
            'image_url' => $this->image_path ? asset('storage/' . $this->image_path) : null,
        ];
    }
}
