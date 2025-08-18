<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Models\PasswordResetCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class NewPasswordController extends ApiController
{
    /**
     * Handle an incoming new password request with OTP code.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $locale = $this->getLocale();

        $validationMessages = [
            'en' => [
                'code.required' => 'Verification code is required',
                'code.size' => 'Verification code must be exactly 5 digits',
                'email.required' => 'Email address is required',
                'email.email' => 'Please enter a valid email address',
                'password.required' => 'Password is required',
                'password.confirmed' => 'Password confirmation does not match',
                'password.min' => 'Password must be at least 8 characters long',
                'password' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character',
            ],
            'ar' => [
                'code.required' => 'رمز التحقق مطلوب',
                'code.size' => 'رمز التحقق يجب أن يكون 5 أرقام بالضبط',
                'email.required' => 'عنوان البريد الإلكتروني مطلوب',
                'email.email' => 'يرجى إدخال عنوان بريد إلكتروني صحيح',
                'password.required' => 'كلمة المرور مطلوبة',
                'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
                'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
                'password' => 'كلمة المرور يجب أن تحتوي على حرف كبير واحد على الأقل، وحرف صغير واحد، ورقم واحد، ورمز واحد',
            ],
        ];

        try {
            $validated = $request->validate([
                'code' => ['required', 'string', 'size:5'],
                'email' => ['required', 'email'],
                'password' => [
                    'required',
                    'confirmed',
                    'min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
                ],
            ], $validationMessages[$locale]);

        } catch (ValidationException $e) {
            $errorMessages = [
                'en' => 'Please check the form and correct the errors',
                'ar' => 'يرجى مراجعة النموذج وتصحيح الأخطاء',
            ];

            return response()->json([
                'success' => false,
                'message' => $this->getLocalizedMessage($errorMessages),
                'errors' => $e->errors(),
            ], 422);
        }

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            $errorMessages = [
                'en' => 'We can\'t find a user with that email address',
                'ar' => 'لا يمكننا العثور على مستخدم بهذا عنوان البريد الإلكتروني',
            ];

            return response()->json([
                'success' => false,
                'message' => $this->getLocalizedMessage($errorMessages),
                'errors' => [
                    'email' => [$this->getLocalizedMessage($errorMessages)]
                ],
            ], 404);
        }

        // Verify the reset code
        $resetCode = PasswordResetCode::where('email', $validated['email'])
            ->where('code', $validated['code'])
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$resetCode) {
            $errorMessages = [
                'en' => 'The password reset code is invalid or expired',
                'ar' => 'رمز إعادة تعيين كلمة المرور غير صحيح أو منتهي الصلاحية',
            ];

            return response()->json([
                'success' => false,
                'message' => $this->getLocalizedMessage($errorMessages),
                'errors' => [
                    'code' => [$this->getLocalizedMessage($errorMessages)]
                ],
            ], 422);
        }

        try {
            // Reset the password
            $user->forceFill([
                'password' => Hash::make($validated['password']),
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
                'message' => $this->getLocalizedMessage($successMessages),
            ], 200);

        } catch (\Exception $e) {
            $errorMessages = [
                'en' => 'Failed to reset password. Please try again.',
                'ar' => 'فشل في إعادة تعيين كلمة المرور. يرجى المحاولة مرة أخرى.',
            ];

            return response()->json([
                'success' => false,
                'message' => $this->getLocalizedMessage($errorMessages),
            ], 500);
        }
    }
}