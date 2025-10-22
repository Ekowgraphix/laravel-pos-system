<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProductApiController;
use App\Http\Controllers\Api\V1\OrderApiController;
use App\Http\Controllers\Api\V1\StoreApiController;
use App\Http\Controllers\Api\V1\LoyaltyApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    
    // Public routes
    Route::get('/products', [ProductApiController::class, 'index']);
    Route::get('/products/{id}', [ProductApiController::class, 'show']);
    Route::get('/products/barcode/{barcode}', [ProductApiController::class, 'findByBarcode']);
    Route::get('/stores', [StoreApiController::class, 'index']);
    Route::get('/stores/{id}', [StoreApiController::class, 'show']);
    Route::get('/discount-codes', [LoyaltyApiController::class, 'discountCodes']);
    
    // Protected routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        
        // Products
        Route::post('/products', [ProductApiController::class, 'store']);
        Route::put('/products/{id}', [ProductApiController::class, 'update']);
        Route::delete('/products/{id}', [ProductApiController::class, 'destroy']);
        Route::get('/products/low-stock/list', [ProductApiController::class, 'lowStock']);
        Route::get('/products/expiring/list', [ProductApiController::class, 'expiring']);
        
        // Orders
        Route::get('/orders', [OrderApiController::class, 'index']);
        Route::post('/orders', [OrderApiController::class, 'store']);
        Route::get('/orders/{id}', [OrderApiController::class, 'show']);
        Route::patch('/orders/{id}/status', [OrderApiController::class, 'updateStatus']);
        Route::get('/orders/statistics/summary', [OrderApiController::class, 'statistics']);
        
        // Stores
        Route::post('/stores', [StoreApiController::class, 'store']);
        Route::put('/stores/{id}', [StoreApiController::class, 'update']);
        Route::get('/stores/{id}/inventory', [StoreApiController::class, 'inventory']);
        Route::get('/stores/{id}/low-stock', [StoreApiController::class, 'lowStock']);
        
        // Loyalty
        Route::get('/loyalty/{userId}/status', [LoyaltyApiController::class, 'status']);
        Route::get('/loyalty/{userId}/transactions', [LoyaltyApiController::class, 'transactions']);
        Route::post('/loyalty/{userId}/redeem', [LoyaltyApiController::class, 'redeem']);
        Route::post('/loyalty/validate-code', [LoyaltyApiController::class, 'validateCode']);
        Route::post('/discount-codes', [LoyaltyApiController::class, 'createDiscountCode']);
        
        // User info
        Route::get('/user', function (Request $request) {
            return response()->json([
                'success' => true,
                'data' => $request->user(),
            ]);
        });
    });
});

// API Documentation
Route::get('/docs', function () {
    return response()->json([
        'name' => 'Laravel POS API',
        'version' => '1.0.0',
        'description' => 'RESTful API for POS System',
        'endpoints' => [
            'products' => '/api/v1/products',
            'orders' => '/api/v1/orders',
            'stores' => '/api/v1/stores',
            'loyalty' => '/api/v1/loyalty',
            'documentation' => '/api/v1/documentation',
        ],
    ]);
});
