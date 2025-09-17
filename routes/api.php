
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

    
    Route::middleware('auth:api')->group(function () {
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
