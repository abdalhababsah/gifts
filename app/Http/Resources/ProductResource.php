<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
        $descriptionField = 'description_' . ($locale === 'ar' ? 'ar' : 'en');

        return [
            'id' => $this->id,
            'name' => $this->$nameField,
            'description' => $this->$descriptionField,
            'price' => $this->price,
            'stock' => $this->stock,
            'sku' => $this->sku,
            'is_featured' => $this->is_featured,
            'cover_image' => $this->cover_image_path ? asset('storage/' . $this->cover_image_path) : null,
            'brand' => [
                'id' => $this->brand->id,
                'name' => $this->brand->{'name_' . ($locale === 'ar' ? 'ar' : 'en')},
            ],
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->{'name_' . ($locale === 'ar' ? 'ar' : 'en')},
            ],
            'images' => $this->whenLoaded('images', function() {
                return $this->images->map(function($image) {
                    return [
                        'id' => $image->id,
                        'url' => asset('storage/' . $image->image_path),
                    ];
                });
            }),
        ];
    }
}
