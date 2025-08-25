<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\Api\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\Auth\NewPasswordController;
use App\Http\Controllers\Api\Auth\PasswordResetCodeController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Http\Controllers\Api\Auth\VerifyEmailWithCodeController;

// Catalog
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;

// Cart / Wishlist
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\WishlistController;

// Cookie/Session middleware (to keep API mostly stateless, but enable cookies/sessions only where needed)
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;

// -------------------------------
// Shared middleware for stateful guest features (cookies + session)
// -------------------------------
$stateful = [EncryptCookies::class, AddQueuedCookiesToResponse::class, StartSession::class];

// -------------------------------
// Guest Auth routes
// -------------------------------
Route::middleware('guest')->group(function () {
    
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

    Route::post('/forgot-password', [PasswordResetCodeController::class, 'store'])->name('password.email');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// -------------------------------
// Public Catalog routes
// -------------------------------
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/featured', [ProductController::class, 'featured'])->name('products.featured');
    Route::get('/{id}', [ProductController::class, 'show'])
        ->name('products.show')
        ->where('id', '[0-9]+');
});

// -------------------------------
// Authenticated routes
// -------------------------------
Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('throttle:6,1')->group(function () {
        Route::post('/verify-email', [VerifyEmailWithCodeController::class, 'verify'])->name('verification.verify');
        Route::post('/resend-verification-code', [VerifyEmailWithCodeController::class, 'resend'])->name('verification.resend');
    });

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// -------------------------------
// Cart routes (guests + auth) — stateful to persist guest cookies
// -------------------------------
Route::prefix('cart')->middleware($stateful)->group(function () {
    Route::get('/', [CartController::class, 'index']);           // get items
    Route::post('/', [CartController::class, 'store']);          // add
    Route::put('/{productId}', [CartController::class, 'update']); // update qty
    Route::delete('/{productId}', [CartController::class, 'destroy']); // remove one
    Route::delete('/', [CartController::class, 'clear']);        // clear all
    Route::get('/count', [CartController::class, 'count']);      // count
});

// -------------------------------
// Wishlist routes (guests + auth) — also stateful (session or cookies)
// -------------------------------
Route::prefix('wishlist')->middleware($stateful)->group(function () {
    Route::get('/', [WishlistController::class, 'index']);
    Route::post('/', [WishlistController::class, 'store']);
    Route::delete('/{productId}', [WishlistController::class, 'destroy']);
    Route::delete('/', [WishlistController::class, 'clear']);
    Route::get('/count', [WishlistController::class, 'count']);
    Route::get('/check/{productId}', [WishlistController::class, 'check']);
});

// -------------------------------
// Debug route (optional) — stateful so you can inspect cookies/session
// -------------------------------
Route::middleware($stateful)->get('/debug-session', function () {
    return response()->json([
        'session_id' => session()->getId(),
        'guest_cart_cookie' => request()->cookie('guest_cart'),
        'all_session' => session()->all(),
    ]);
});
