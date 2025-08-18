<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends ApiController
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $locale = $this->getLocale();

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
                'password.min' => 'Password must be at least 8 characters long',
                'password' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&)',
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
                'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
                'password' => 'كلمة المرور يجب أن تحتوي على حرف كبير واحد على الأقل، وحرف صغير واحد، ورقم واحد، ورمز خاص واحد (@$!%*?&)',
            ],
        ];

        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'phone_number' => [
                    'nullable',
                    'string',
                    'regex:/^(078|077|079)\d{7}$/',
                    'size:10',
                    'unique:' . User::class,
                ],
                'password' => [
                    'required',
                    'confirmed',
                    Password::min(8)
                        ->letters()           // Must contain letters
                        ->mixedCase()         // Must contain both upper and lower case
                        ->numbers()           // Must contain numbers
                        ->symbols()           // Must contain symbols
                        ->uncompromised()     // Must not be in data breach database
                ],
            ], $validationMessages[$locale]);

        } catch (\Illuminate\Validation\ValidationException $e) {
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

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'] ?? null,
                'password' => Hash::make($validated['password']),
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
                'message' => $this->getLocalizedMessage($successMessages),
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number,
                    'role_id' => $user->role_id,
                    'created_at' => $user->created_at,
                ],
                'token' => $token,
            ], 201);

        } catch (\Exception $e) {
            $errorMessages = [
                'en' => 'Registration failed. Please try again.',
                'ar' => 'فشل في إنشاء الحساب. يرجى المحاولة مرة أخرى.',
            ];

            return response()->json([
                'success' => false,
                'message' => $this->getLocalizedMessage($errorMessages),
            ], 500);
        }
    }
}