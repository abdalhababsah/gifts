<?php

use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;

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
            Route::post('/{brand}', [BrandController::class, 'update'])->name('update'); // POST for file uploads
            Route::delete('/{brand}', [BrandController::class, 'destroy'])->name('destroy');
            Route::patch('/{brand}/toggle-status', [BrandController::class, 'toggleStatus'])->name('toggleStatus');
            Route::get('/select/options', [BrandController::class, 'getForSelect'])->name('select');
        });

            Route::resource('products', ProductController::class)->names('products');
            Route::patch('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
            Route::get('products-for-select', [ProductController::class, 'getForSelect'])->name('products.getForSelect');
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
        'is_admin' => $user ? ($user->role_id === 1) : false
    ]);
})->name('admin.test');

require __DIR__.'/auth.php';
