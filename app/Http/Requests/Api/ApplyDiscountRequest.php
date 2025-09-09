<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ApplyDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // already behind auth:sanctum
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        $locale = app()->getLocale();
        $msgs = [
            'en' => ['code.required' => 'Discount code is required'],
            'ar' => ['code.required' => 'رمز الخصم مطلوب'],
        ];
        return $msgs[$locale] ?? $msgs['en'];
    }
}
