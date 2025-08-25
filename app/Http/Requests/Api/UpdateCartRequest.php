<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => ['required', 'integer', 'min:0', 'max:100'],
        ];
    }

    public function messages(): array
    {
        $locale = app()->getLocale();
        
        $messages = [
            'en' => [
                'quantity.required' => 'Quantity is required',
                'quantity.integer' => 'Quantity must be a valid number',
                'quantity.min' => 'Quantity must be 0 or greater (0 to remove)',
                'quantity.max' => 'Quantity cannot exceed 100',
            ],
            'ar' => [
                'quantity.required' => 'الكمية مطلوبة',
                'quantity.integer' => 'الكمية يجب أن تكون رقم صحيح',
                'quantity.min' => 'الكمية يجب أن تكون 0 أو أكثر (0 للحذف)',
                'quantity.max' => 'الكمية لا يمكن أن تتجاوز 100',
            ],
        ];

        return $messages[$locale] ?? $messages['en'];
    }
}
