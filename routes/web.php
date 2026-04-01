<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/register', 'auth.register')->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::match(['get', 'post'], '/checkout-details', [CartController::class, 'showDetails'])->name('checkout.details');
Route::post('/checkout-details/store', [CartController::class, 'storeDetails'])->name('checkout.details.store');
Route::view('/checkout-payment-method', 'checkout-payment-method')->name('checkout.payment');
Route::match(['get', 'post'], '/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');

Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

Route::get('/search', [ProductController::class, 'search'])->name('search');

Route::get('/filter/{category?}', function($category = null) {
    if ($category) {
        $category = str_replace('-', '_', $category);
    }
    return app('App\Http\Controllers\FilterController')->show($category);
})->name('filter.show');

// Settings routes
Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'show'])->name('settings.show');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    
    // Orders routes
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrdersController::class, 'show'])->name('orders.show');
});
