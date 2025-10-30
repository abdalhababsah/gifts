<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PaymentIntentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'shipping_address'        => ['required','string','max:2000'],
            'city_id'                 => ['required','integer','exists:cities,id'],
            'delivery_date'           => ['required','date','after_or_equal:today'],
            'delivery_time_slot_id'   => ['required','integer','exists:delivery_time_slots,id'],

            'is_gift'                 => ['sometimes','boolean'],
            'receiver_name'           => ['required_if:is_gift,true','nullable','string','max:150'],
            'receiver_phone'          => [
                'required_if:is_gift,true','nullable','string',
                'regex:/^(078|077|079)\d{7}$/','size:10'
            ],
            'location_description'    => ['sometimes','nullable','string','max:2000'],
            'extra_notes'             => ['sometimes','nullable','string','max:2000'],
            'is_anonymous_delivery'   => ['sometimes','boolean'],
        ];
    }

    public function messages(): array
    {
        $locale = app()->getLocale();
        $msgs = [
            'en' => [
                'shipping_address.required' => 'Shipping address is required',
                'city_id.required' => 'Please select a city',
                'delivery_date.after_or_equal' => 'Delivery date cannot be in the past',
                'delivery_time_slot_id.required' => 'Please choose a delivery time slot',
                'receiver_name.required_if' => 'Receiver name is required for gifts',
                'receiver_phone.required_if' => 'Receiver phone is required for gifts',
                'receiver_phone.regex' => 'Please provide a valid Jordanian phone number',
            ],
            'ar' => [
                'shipping_address.required' => 'عنوان الشحن مطلوب',
                'city_id.required' => 'يرجى اختيار المدينة',
                'delivery_date.after_or_equal' => 'لا يمكن أن يكون تاريخ التسليم في الماضي',
                'delivery_time_slot_id.required' => 'يرجى اختيار وقت التسليم',
                'receiver_name.required_if' => 'اسم المستلم مطلوب للهدايا',
                'receiver_phone.required_if' => 'رقم هاتف المستلم مطلوب للهدايا',
                'receiver_phone.regex' => 'يرجى إدخال رقم هاتف أردني صحيح',
            ],
        ];
        return $msgs[$locale] ?? $msgs['en'];
    }
}
