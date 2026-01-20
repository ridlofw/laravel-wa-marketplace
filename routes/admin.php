<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

// Admin Auth Routes (Guest)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    });

    // Protected Admin Routes
    Route::middleware('superadmin')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });
        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Sellers Management
        Route::get('/sellers', [SellerController::class, 'index'])->name('sellers.index');
        Route::get('/sellers/{user}', [SellerController::class, 'show'])->name('sellers.show');
        Route::delete('/sellers/{user}', [SellerController::class, 'destroy'])->name('sellers.destroy');
        
        // Products Management
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
