
<?php

use Illuminate\Support\Facades\Route;

// API v1 routes
Route::prefix('v1')->group(function () {
    
    Route::get('/test-base-response', function () {
        return response()->json([
            'success' => true,
            'message' => 'API base structure working',
            'data' => null
        ]);
    });

    Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register'])->middleware('throttle:api');
    Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login'])->middleware('throttle:api');

    
    Route::middleware('auth:api')->group(function () {
        Route::get('/profile', [\App\Http\Controllers\Api\ProfileController::class, 'profile']);
        Route::put('/change-password', [\App\Http\Controllers\Api\ProfileController::class, 'changePassword'])->middleware('throttle:api');
        Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
        Route::get('/services', [\App\Http\Controllers\Api\ServiceController::class, 'index']);
        Route::get('/subscriptions', [\App\Http\Controllers\Api\SubscriptionController::class, 'index']);
    });

    
    Route::fallback(function () {
        return response()->json([
            'success' => false,
            'message' => 'Not Found'
        ], 404);
    });

});
