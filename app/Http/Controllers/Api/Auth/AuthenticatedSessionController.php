<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends ApiController
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        $messages = [
            'en' => 'Login successful',
            'ar' => 'تم تسجيل الدخول بنجاح',
        ];

        return response()->json([
            'success' => true,
            'message' => $this->getLocalizedMessage($messages),
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        // Revoke the token that was used to authenticate the current request
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        $messages = [
            'en' => 'Logged out successfully',
            'ar' => 'تم تسجيل الخروج بنجاح',
        ];

        return response()->json([
            'success' => true,
            'message' => $this->getLocalizedMessage($messages),
        ], 200);
    }
}