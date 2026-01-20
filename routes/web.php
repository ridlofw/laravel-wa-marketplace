<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/api/products/search', [PublicController::class, 'searchProducts'])->name('api.products.search');
Route::get('/product/{product}', [PublicController::class, 'show'])->name('public.product.show');
Route::get('/product/{product}/checkout', [PublicController::class, 'checkout'])->name('public.checkout');
Route::post('/product/{product}/checkout', [PublicController::class, 'processCheckout'])->name('public.checkout.process');

// Seller Routes
Route::prefix('seller')->name('seller.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('seller.dashboard');
    });

    Route::middleware(['auth', 'verified', 'seller'])->group(function () {
        Route::get('/dashboard', [SellerController::class, 'dashboard'])->name('dashboard');
        Route::get('/settings', [SellerController::class, 'settings'])->name('settings');
        Route::put('/settings', [SellerController::class, 'updateSettings'])->name('settings.update');
        Route::resource('products', ProductController::class);
        Route::delete('/products/image/{productImage}', [ProductController::class, 'destroyImage'])->name('products.image.destroy');
        
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    require __DIR__.'/auth.php';
});
