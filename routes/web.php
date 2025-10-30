<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('welcome');

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Login routes (accessible to guests only)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Protected admin routes (require authentication + admin role)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // In routes/web.php inside the authenticated admin group:
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('index');
            Route::get('/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('show');
            Route::put('/{order}/delivery-status', [\App\Http\Controllers\Admin\OrderController::class, 'updateDeliveryStatus'])->name('updateDeliveryStatus');
            Route::get('/{order}/print', [\App\Http\Controllers\Admin\OrderController::class, 'print'])->name('print');
        });

        // Categories Management
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
            Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
            Route::patch('/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('toggleStatus');
            Route::get('/select/options', [CategoryController::class, 'getForSelect'])->name('select');
        });

        // Brands Management
        Route::prefix('brands')->name('brands.')->group(function () {
            Route::get('/', [BrandController::class, 'index'])->name('index');
            Route::post('/', [BrandController::class, 'store'])->name('store');
            Route::get('/{brand}', [BrandController::class, 'show'])->name('show');
            Route::put('/{brand}', [BrandController::class, 'update'])->name('update'); // POST for file uploads
            Route::delete('/{brand}', [BrandController::class, 'destroy'])->name('destroy');
            Route::patch('/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])->name('toggleStatus');
            Route::get('/select/options', [BrandController::class, 'getForSelect'])->name('select');
        });

        // Products Management
        Route::resource('products', ProductController::class)->names('products');
        Route::patch('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
        Route::patch('products/{product}/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('products.toggleFeatured');
        Route::get('products-for-select', [ProductController::class, 'getForSelect'])->name('products.getForSelect');

        // Discount Codes Management
        Route::prefix('discount-codes')->name('discount-codes.')->group(function () {
            Route::get('/', [DiscountCodeController::class, 'index'])->name('index');
            Route::post('/', [DiscountCodeController::class, 'store'])->name('store');
            Route::get('/{discountCode}', [DiscountCodeController::class, 'show'])->name('show');
            Route::put('/{discountCode}', [DiscountCodeController::class, 'update'])->name('update');
            Route::delete('/{discountCode}', [DiscountCodeController::class, 'destroy'])->name('destroy');
            Route::post('/{discountCode}/toggle-status', [DiscountCodeController::class, 'toggleStatus'])->name('toggleStatus');
            Route::get('/select/options', [DiscountCodeController::class, 'getForSelect'])->name('select');
        });

        // Users Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });
    });
});

// Login page route (for redirects from middleware)
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Test admin route without middleware (for comparison)
Route::get('/admin/test', function () {
    $user = auth()->user();

    return response()->json([
        'message' => 'Test route without middleware',
        'authenticated' => auth()->check(),
        'user' => $user ? $user->load('role') : null,
        'is_admin' => $user ? ($user->role_id === 1) : false,
    ]);
})->name('admin.test');

require __DIR__.'/auth.php';
