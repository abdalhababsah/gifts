<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'string', 'email'],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()           // Must contain letters
                    ->mixedCase()         // Must contain both upper and lower case
                    ->numbers()           // Must contain numbers
                    ->symbols()           // Must contain symbols
            ],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        $locale = app()->getLocale();

        $validationMessages = [
            'en' => [
                'email.required' => 'Email address is required',
                'email.email' => 'Please enter a valid email address',
                'password.required' => 'Password is required',
                'password.min' => 'Password must be at least 8 characters long',
                'password' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&)',
            ],
            'ar' => [
                'email.required' => 'عنوان البريد الإلكتروني مطلوب',
                'email.email' => 'يرجى إدخال عنوان بريد إلكتروني صحيح',
                'password.required' => 'كلمة المرور مطلوبة',
                'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
                'password' => 'كلمة المرور يجب أن تحتوي على حرف كبير واحد على الأقل، وحرف صغير واحد، ورقم واحد، ورمز خاص واحد (@$!%*?&)',
            ],
        ];

        return $validationMessages[$locale] ?? $validationMessages['en'];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            $locale = app()->getLocale();
            $errorMessages = [
                'en' => 'The provided credentials are incorrect.',
                'ar' => 'بيانات الاعتماد المقدمة غير صحيحة.',
            ];

            throw ValidationException::withMessages([
                'email' => $errorMessages[$locale] ?? $errorMessages['en'],
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());
        $locale = app()->getLocale();

        $throttleMessages = [
            'en' => 'Too many login attempts. Please try again in :seconds seconds.',
            'ar' => 'محاولات تسجيل دخول كثيرة جداً. يرجى المحاولة مرة أخرى خلال :seconds ثانية.',
        ];

        $message = str_replace(':seconds', $seconds, $throttleMessages[$locale] ?? $throttleMessages['en']);

        throw ValidationException::withMessages([
            'email' => $message,
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}