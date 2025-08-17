<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class NewPasswordController extends Controller
{
    /**
     * Handle an incoming new password request with OTP code.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $locale = $this->getLocale($request);

        $validationMessages = [
            'en' => [
                'code.required' => 'Verification code is required',
                'code.size' => 'Verification code must be exactly 5 digits',
                'email.required' => 'Email address is required',
                'email.email' => 'Please enter a valid email address',
                'password.required' => 'Password is required',
                'password.confirmed' => 'Password confirmation does not match',
            ],
            'ar' => [
                'code.required' => 'رمز التحقق مطلوب',
                'code.size' => 'رمز التحقق يجب أن يكون 5 أرقام بالضبط',
                'email.required' => 'عنوان البريد الإلكتروني مطلوب',
                'email.email' => 'يرجى إدخال عنوان بريد إلكتروني صحيح',
                'password.required' => 'كلمة المرور مطلوبة',
                'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            ],
        ];

        $request->validate([
            'code' => ['required', 'string', 'size:5'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], $validationMessages[$locale]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            $errorMessages = [
                'en' => 'We can\'t find a user with that email address',
                'ar' => 'لا يمكننا العثور على مستخدم بهذا عنوان البريد الإلكتروني',
            ];

            throw ValidationException::withMessages([
                'email' => [$errorMessages[$locale]],
            ]);
        }

        // Verify the reset code
        $resetCode = PasswordResetCode::where('email', $request->email)
            ->where('code', $request->code)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (! $resetCode) {
            $errorMessages = [
                'en' => 'The password reset code is invalid or expired',
                'ar' => 'رمز إعادة تعيين كلمة المرور غير صحيح أو منتهي الصلاحية',
            ];

            throw ValidationException::withMessages([
                'code' => [$errorMessages[$locale]],
            ]);
        }

        // Reset the password
        $user->forceFill([
            'password' => Hash::make($request->string('password')),
            'remember_token' => Str::random(60),
        ])->save();

        // Mark the code as used
        $resetCode->update(['used' => true]);

        // Fire the password reset event
        event(new PasswordReset($user));

        $successMessages = [
            'en' => 'Password has been reset successfully',
            'ar' => 'تم إعادة تعيين كلمة المرور بنجاح',
        ];

        return response()->json([
            'success' => true,
            'message' => $successMessages[$locale],
        ]);
    }

    /**
     * Get locale from request headers
     */
    private function getLocale(Request $request): string
    {
        $locale = $request->header('Accept-Language', 'en');

        return in_array($locale, ['ar', 'en']) ? $locale : 'en';
    }
}
