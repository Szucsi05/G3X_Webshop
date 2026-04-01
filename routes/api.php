<?php

use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\ProductOfferController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// Basic API endpoints for managing products (games).
// Protect these routes by sending a valid X-API-TOKEN header.

Route::prefix('v1')->group(function () {
    // Products
    Route::get('/products', [ProductApiController::class, 'index']);
    Route::get('/products/{id}', [ProductApiController::class, 'show']);
    Route::post('/products', [ProductApiController::class, 'store']);
    Route::put('/products/{id}', [ProductApiController::class, 'update']);
    Route::delete('/products/{id}', [ProductApiController::class, 'destroy']);

    // Product Offers (NEW - IMPORTANT)
    Route::get('/product-offers', [ProductOfferController::class, 'index']);
    Route::get('/product-offers/{id}', [ProductOfferController::class, 'show']);
    Route::post('/product-offers', [ProductOfferController::class, 'store']);
    Route::put('/product-offers/{id}', [ProductOfferController::class, 'update']);
    Route::delete('/product-offers/{id}', [ProductOfferController::class, 'destroy']);

    // Product Offers by Product
    Route::get('/products/{productId}/offers', [ProductOfferController::class, 'byProduct']);

    // Product Offers by Vendor
    Route::get('/vendors/{vendorId}/offers', [ProductOfferController::class, 'byVendor']);

    // Orders (NEW - requires authentication)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
        Route::get('/orders/{orderId}/items/{itemId}', [OrderController::class, 'getItem']);
        Route::post('/orders/{orderId}/items/{itemId}/license', [OrderController::class, 'addLicenseKey']);
    });
});
