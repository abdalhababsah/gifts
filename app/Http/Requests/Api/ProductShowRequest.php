<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ProductShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:products,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $locale = app()->getLocale();
        
        if ($locale === 'ar') {
            return [
                'id.required' => 'معرف المنتج مطلوب',
                'id.integer' => 'معرف المنتج يجب أن يكون رقمًا صحيحًا',
                'id.exists' => 'المنتج غير موجود',
            ];
        }
        
        return [
            'id.required' => 'Product ID is required',
            'id.integer' => 'Product ID must be an integer',
            'id.exists' => 'Product not found',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        $locale = app()->getLocale();
        
        $message = $locale === 'ar' 
            ? 'المنتج غير موجود'
            : 'Product not found';
            
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => $message,
                'errors' => $validator->errors(),
            ], JsonResponse::HTTP_NOT_FOUND)
        );
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $product = $this->route('id');
            if ($product && !$product->is_active) {
                $locale = app()->getLocale();
                $message = $locale === 'ar' 
                    ? 'المنتج غير متاح حاليًا'
                    : 'Product is currently unavailable';
                    
                $validator->errors()->add('id', $message);
            }
        });
    }
}
