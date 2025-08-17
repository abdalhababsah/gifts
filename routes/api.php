<?php

use App\Http\Controllers\Api\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\Auth\NewPasswordController;
use App\Http\Controllers\Api\Auth\PasswordResetCodeController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Http\Controllers\Api\Auth\VerifyEmailWithCodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->name('register');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->name('login');

    Route::post('/forgot-password', [PasswordResetCodeController::class, 'store'])
        ->name('password.email');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('throttle:6,1')->group(function () {
        Route::post('/verify-email', [VerifyEmailWithCodeController::class, 'verify'])
            ->name('verification.verify');

        Route::post('/resend-verification-code', [VerifyEmailWithCodeController::class, 'resend'])
            ->name('verification.resend');
    });

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
