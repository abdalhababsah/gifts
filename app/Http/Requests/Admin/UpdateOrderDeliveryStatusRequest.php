<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderDeliveryStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && optional(auth()->user()->role)->id === 1; // admin
    }

    public function rules(): array
    {
        return [
            'delivery_status' => 'required|string|in:pending,processing,out_for_delivery,delivered,cancelled',
        ];
    }
}
