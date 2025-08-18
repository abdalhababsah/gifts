<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PasswordResetCodeController extends ApiController
{
    /**
     * Handle an incoming password reset code request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $locale = $this->getLocale();

        $validationMessages = [
            'en' => [
                'email.required' => 'Email address is required',
                'email.email' => 'Please enter a valid email address',
            ],
            'ar' => [
                'email.required' => 'عنوان البريد الإلكتروني مطلوب',
                'email.email' => 'يرجى إدخال عنوان بريد إلكتروني صحيح',
            ],
        ];

        $request->validate([
            'email' => ['required', 'email'],
        ], $validationMessages[$locale]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            $errorMessages = [
                'en' => 'We can\'t find a user with that email address',
                'ar' => 'لا يمكننا العثور على مستخدم بهذا عنوان البريد الإلكتروني',
            ];

            throw ValidationException::withMessages([
                'email' => [$this->getLocalizedMessage($errorMessages)],
            ]);
        }

        // Send password reset code
        User::sendPasswordResetCode($request->email);

        $successMessages = [
            'en' => 'Password reset code has been sent to your email',
            'ar' => 'تم إرسال رمز إعادة تعيين كلمة المرور إلى بريدك الإلكتروني',
        ];

        return response()->json([
            'success' => true,
            'message' => $this->getLocalizedMessage($successMessages),
        ], 200);
    }
}