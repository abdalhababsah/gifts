<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerificationCode;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class VerifyEmailWithCodeController extends Controller
{
    /**
     * Verify email with OTP code
     *
     * @throws ValidationException
     */
    public function verify(Request $request): JsonResponse
    {
        $locale = $this->getLocale($request);

        $validationMessages = [
            'en' => [
                'code.required' => 'Verification code is required',
                'code.size' => 'Verification code must be exactly 5 digits',
            ],
            'ar' => [
                'code.required' => 'رمز التحقق مطلوب',
                'code.size' => 'رمز التحقق يجب أن يكون 5 أرقام بالضبط',
            ],
        ];

        $request->validate([
            'code' => ['required', 'string', 'size:5'],
        ], $validationMessages[$locale]);

        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $messages = [
                'en' => 'Your email is already verified',
                'ar' => 'تم التحقق من بريدك الإلكتروني مسبقاً',
            ];

            return response()->json([
                'success' => true,
                'message' => $messages[$locale],
            ]);
        }

        $verificationCode = EmailVerificationCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (! $verificationCode) {
            $errorMessages = [
                'en' => 'The verification code is invalid or expired',
                'ar' => 'رمز التحقق غير صحيح أو منتهي الصلاحية',
            ];

            throw ValidationException::withMessages([
                'code' => [$errorMessages[$locale]],
            ]);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // Mark the code as used
        $verificationCode->update(['used' => true]);

        $successMessages = [
            'en' => 'Email verified successfully! Your account is now fully activated.',
            'ar' => 'تم التحقق من البريد الإلكتروني بنجاح! حسابك مفعل بالكامل الآن.',
        ];

        return response()->json([
            'success' => true,
            'message' => $successMessages[$locale],
        ]);
    }

    /**
     * Resend the verification code
     */
    public function resend(Request $request): JsonResponse
    {
        $locale = $this->getLocale($request);
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $messages = [
                'en' => 'Your email is already verified',
                'ar' => 'تم التحقق من بريدك الإلكتروني مسبقاً',
            ];

            return response()->json([
                'success' => true,
                'message' => $messages[$locale],
            ]);
        }

        // Generate and send a new verification code
        $user->sendEmailVerificationCode();

        $successMessages = [
            'en' => 'A new verification code has been sent to your email',
            'ar' => 'تم إرسال رمز تحقق جديد إلى بريدك الإلكتروني',
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
