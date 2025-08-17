<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $locale = $this->getLocale($request);

        $validationMessages = [
            'en' => [
                'name.required' => 'Full name is required',
                'name.max' => 'Name cannot exceed 255 characters',
                'email.required' => 'Email address is required',
                'email.email' => 'Please enter a valid email address',
                'email.unique' => 'This email is already registered',
                'phone_number.regex' => 'Please enter a valid Jordanian phone number (078xxxxxxx, 077xxxxxxx, or 079xxxxxxx)',
                'phone_number.size' => 'Phone number must be exactly 10 digits',
                'phone_number.unique' => 'This phone number is already registered',
                'password.required' => 'Password is required',
                'password.confirmed' => 'Password confirmation does not match',
            ],
            'ar' => [
                'name.required' => 'الاسم الكامل مطلوب',
                'name.max' => 'الاسم لا يمكن أن يتجاوز 255 حرف',
                'email.required' => 'عنوان البريد الإلكتروني مطلوب',
                'email.email' => 'يرجى إدخال عنوان بريد إلكتروني صحيح',
                'email.unique' => 'هذا البريد الإلكتروني مسجل مسبقاً',
                'phone_number.regex' => 'يرجى إدخال رقم هاتف أردني صحيح (078xxxxxxx أو 077xxxxxxx أو 079xxxxxxx)',
                'phone_number.size' => 'رقم الهاتف يجب أن يكون 10 أرقام بالضبط',
                'phone_number.unique' => 'رقم الهاتف هذا مسجل مسبقاً',
                'password.required' => 'كلمة المرور مطلوبة',
                'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            ],
        ];

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone_number' => [
                'nullable',
                'string',
                'regex:/^(078|077|079)\d{7}$/',
                'size:10',
                'unique:'.User::class,
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], $validationMessages[$locale]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->string('password')),
            'role_id' => 2, // Default role_id is 2
        ]);
        
        event(new Registered($user));

        Auth::login($user);

        // Create a token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        $successMessages = [
            'en' => 'Registration completed successfully! Welcome aboard.',
            'ar' => 'تم إنشاء الحساب بنجاح! أهلاً بك معنا.',
        ];

        return response()->json([
            'success' => true,
            'message' => $successMessages[$locale],
            'user' => $user,
            'token' => $token,
        ], 201);
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
