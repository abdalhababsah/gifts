<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ProductIndexRequest extends FormRequest
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
            'search' => 'nullable|string|max:255',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'sort' => 'nullable|string|in:price_asc,price_desc',
            'per_page' => 'nullable|integer|min:1|max:50',
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
                'brand_id.exists' => 'العلامة التجارية المحددة غير موجودة',
                'category_id.exists' => 'الفئة المحددة غير موجودة',
                'sort.in' => 'قيمة الترتيب غير صالحة',
                'per_page.integer' => 'يجب أن يكون عدد العناصر في الصفحة رقمًا صحيحًا',
                'per_page.min' => 'يجب أن يكون عدد العناصر في الصفحة على الأقل 1',
                'per_page.max' => 'يجب أن لا يتجاوز عدد العناصر في الصفحة 50',
            ];
        }
        
        return [
            'brand_id.exists' => 'The selected brand does not exist',
            'category_id.exists' => 'The selected category does not exist',
            'sort.in' => 'The sort value is invalid',
            'per_page.integer' => 'The per page must be an integer',
            'per_page.min' => 'The per page must be at least 1',
            'per_page.max' => 'The per page may not be greater than 50',
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
            ? 'تم تقديم معلمات غير صالحة'
            : 'Invalid parameters provided';
            
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => $message,
                'errors' => $validator->errors(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
