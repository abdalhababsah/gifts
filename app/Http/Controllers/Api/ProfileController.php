<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\ProfileUpdateRequest;
use App\Http\Requests\Api\DeleteAccountRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends ApiController
{
    /**
     * Update name / phone / (optional) email / fcm_token.
     * If email changes, email is unverified and a new verification code is sent.
     */
    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        $user = $request->user();

        DB::transaction(function () use ($request, $user) {
            $data = $request->safe()->only(['name','phone_number','fcm_token','email']);

            $emailChanged = isset($data['email']) && $data['email'] !== $user->email;

            $user->fill($data);

            if ($emailChanged) {
                $user->email_verified_at = null;
            }

            $user->save();

            if ($emailChanged) {
                // Use your existing OTP flow
                $user->sendEmailVerificationCode();
            }
        });

        $messages = [
            'en' => 'Profile updated successfully.',
            'ar' => 'تم تحديث الملف الشخصي بنجاح.',
        ];

        return response()->json([
            'success' => true,
            'message' => $this->getLocalizedMessage($messages),
            'user' => $request->user(),
        ]);
    }

    /**
     * Delete account (requires current password).
     * Revokes current token and deletes user (cascades take care of relations).
     */
    public function destroy(DeleteAccountRequest $request): JsonResponse
    {
        $user = $request->user();

        // Verify password
        if (! Hash::check($request->validated('password'), $user->password)) {
            $messages = [
                'en' => 'Incorrect password.',
                'ar' => 'كلمة المرور غير صحيحة.',
            ];
            return response()->json([
                'success' => false,
                'message' => $this->getLocalizedMessage($messages),
            ], 422);
        }

        // Revoke current token
        if ($request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        $user->delete();

        $messages = [
            'en' => 'Your account has been deleted.',
            'ar' => 'تم حذف حسابك.',
        ];

        return response()->json([
            'success' => true,
            'message' => $this->getLocalizedMessage($messages),
        ]);
    }
}
