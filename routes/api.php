<?php

use App\Http\Controllers\Api\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\Auth\NewPasswordController;
use App\Http\Controllers\Api\Auth\PasswordResetCodeController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Http\Controllers\Api\Auth\VerifyEmailWithCodeController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Guest routes
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

// Public API routes
Route::get('/categories', [CategoryController::class, 'index'])
    ->name('categories.index');

Route::get('/brands', [BrandController::class, 'index'])
    ->name('brands.index');

// Product routes
Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');
Route::get('/products/featured', [ProductController::class, 'featured'])
    ->name('products.featured');
Route::get('/products/{id}', [ProductController::class, 'show'])
    ->name('products.show')
    ->where('id', '[0-9]+');



// Authenticated routes
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