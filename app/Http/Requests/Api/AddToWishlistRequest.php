<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AddToWishlistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ];
    }

    public function messages(): array
    {
        $locale = app()->getLocale();
        
        $messages = [
            'en' => [
                'product_id.required' => 'Product ID is required',
                'product_id.integer' => 'Product ID must be a valid number',
                'product_id.exists' => 'Selected product does not exist',
            ],
            'ar' => [
                'product_id.required' => 'معرف المنتج مطلوب',
                'product_id.integer' => 'معرف المنتج يجب أن يكون رقم صحيح',
                'product_id.exists' => 'المنتج المحدد غير موجود',
            ],
        ];

        return $messages[$locale] ?? $messages['en'];
    }
}