<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => ['sometimes','string','max:255'],
            'phone_number' => [
                'sometimes','nullable','string',
                'regex:/^(078|077|079)\d{7}$/',
                'size:10',
                Rule::unique('users','phone_number')->ignore($this->user()->id),
            ],
            'fcm_token' => ['sometimes','nullable','string'],
            'email' => [
                'sometimes','string','lowercase','email','max:255',
                Rule::unique('users','email')->ignore($this->user()->id),
            ],
        ];
    }

    public function messages(): array
    {
        $locale = app()->getLocale();
        $msgs = [
            'en' => [
                'phone_number.regex' => 'Please enter a valid Jordanian phone number (078xxxxxxx, 077xxxxxxx, or 079xxxxxxx)',
                'phone_number.size' => 'Phone number must be exactly 10 digits',
                'phone_number.unique' => 'This phone number is already registered',
                'email.email' => 'Please enter a valid email address',
                'email.unique' => 'This email is already in use',
            ],
            'ar' => [
                'phone_number.regex' => 'يرجى إدخال رقم هاتف أردني صحيح (078xxxxxxx أو 077xxxxxxx أو 079xxxxxxx)',
                'phone_number.size' => 'رقم الهاتف يجب أن يكون 10 أرقام بالضبط',
                'phone_number.unique' => 'رقم الهاتف هذا مسجل مسبقاً',
                'email.email' => 'يرجى إدخال عنوان بريد إلكتروني صحيح',
                'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل',
            ],
        ];
        return $msgs[$locale] ?? $msgs['en'];
    }
}
