<?php

use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\ProductOfferController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;



Route::prefix('v1')->group(function () {

    Route::post('/login', [AuthController::class, 'apiLogin']);
    

    Route::get('/products', [ProductApiController::class, 'index']);
    Route::get('/products/{id}', [ProductApiController::class, 'show']);
    Route::post('/products', [ProductApiController::class, 'store']);
    Route::put('/products/{id}', [ProductApiController::class, 'update']);
    Route::delete('/products/{id}', [ProductApiController::class, 'destroy']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    Route::get('/platforms', [PlatformController::class, 'index']);
    Route::get('/platforms/{id}', [PlatformController::class, 'show']);
    Route::post('/platforms', [PlatformController::class, 'store']);
    Route::put('/platforms/{id}', [PlatformController::class, 'update']);
    Route::delete('/platforms/{id}', [PlatformController::class, 'destroy']);


    Route::get('/vendors', [VendorController::class, 'index']);
    Route::get('/vendors/{id}', [VendorController::class, 'show']);
    Route::post('/vendors', [VendorController::class, 'store']);
    Route::put('/vendors/{id}', [VendorController::class, 'update']);
    Route::delete('/vendors/{id}', [VendorController::class, 'destroy']);


    Route::get('/product-offers', [ProductOfferController::class, 'index']);
    Route::get('/product-offers/{id}', [ProductOfferController::class, 'show']);
    Route::post('/product-offers', [ProductOfferController::class, 'store']);
    Route::put('/product-offers/{id}', [ProductOfferController::class, 'update']);
    Route::delete('/product-offers/{id}', [ProductOfferController::class, 'destroy']);

    Route::get('/products/{productId}/offers', [ProductOfferController::class, 'byProduct']);

 
    Route::get('/vendors/{vendorId}/offers', [ProductOfferController::class, 'byVendor']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
        Route::get('/orders/{orderId}/items/{itemId}', [OrderController::class, 'getItem']);
        Route::post('/orders/{orderId}/items/{itemId}/license', [OrderController::class, 'addLicenseKey']);
    });
});
