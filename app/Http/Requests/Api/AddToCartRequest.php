<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:100'],
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
                'quantity.integer' => 'Quantity must be a valid number',
                'quantity.min' => 'Quantity must be at least 1',
                'quantity.max' => 'Quantity cannot exceed 100',
            ],
            'ar' => [
                'product_id.required' => 'معرف المنتج مطلوب',
                'product_id.integer' => 'معرف المنتج يجب أن يكون رقم صحيح',
                'product_id.exists' => 'المنتج المحدد غير موجود',
                'quantity.integer' => 'الكمية يجب أن تكون رقم صحيح',
                'quantity.min' => 'الكمية يجب أن تكون 1 على الأقل',
                'quantity.max' => 'الكمية لا يمكن أن تتجاوز 100',
            ],
        ];

        return $messages[$locale] ?? $messages['en'];
    }
}
