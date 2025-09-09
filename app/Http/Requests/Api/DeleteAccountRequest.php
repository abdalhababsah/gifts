<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class DeleteAccountRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'password' => ['required','string','min:8'],
        ];
    }

    public function messages(): array
    {
        $locale = app()->getLocale();
        $msgs = [
            'en' => [
                'password.required' => 'Password is required to delete the account',
                'password.min' => 'Password must be at least 8 characters',
            ],
            'ar' => [
                'password.required' => 'كلمة المرور مطلوبة لحذف الحساب',
                'password.min' => 'يجب أن تكون كلمة المرور 8 أحرف على الأقل',
            ],
        ];
        return $msgs[$locale] ?? $msgs['en'];
    }
}
