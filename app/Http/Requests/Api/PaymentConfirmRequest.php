<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PaymentConfirmRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_id' => ['required', 'integer', 'exists:payments,id'],
            'session_id' => ['required', 'string'],
        ];
    }
}