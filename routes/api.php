
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    // Test route
    Route::get('/test', function () {
        return response()->json(['message' => 'API is working!']);
    });
    
    // Test route for base structure
    Route::get('/test-base-response', function () {
        return response()->json([
            'success' => true,
            'message' => 'Base response working',
            'data' => null
        ]);
    });
    
    // Test route for error response
    Route::get('/test-error-response', function () {
        return response()->json([
            'success' => false,
            'message' => 'Test error message',
            'data' => null
        ], 400);
    });

    // Auth routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:api')->group(function () {
        // Profile routes
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::get('/user', [AuthController::class, 'profile']); // Alias para compatibilidade com testes
        Route::put('/change-password', [AuthController::class, 'changePassword']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // Services routes
        Route::get('/services', [ServiceController::class, 'index']);
        Route::post('/services', [ServiceController::class, 'store']);
        Route::get('/services/{id}', [ServiceController::class, 'show']);
        Route::put('/services/{id}', [ServiceController::class, 'update']);
        Route::patch('/services/{id}', [ServiceController::class, 'partialUpdate']);
        Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
        
        // Categories route
        Route::get('/categories', [ServiceController::class, 'categories']);

        // Subscriptions routes
        Route::get('/subscriptions', [SubscriptionController::class, 'index']);
        Route::post('/subscriptions', [SubscriptionController::class, 'store']);
        Route::get('/subscriptions/{id}', [SubscriptionController::class, 'show']);
        Route::put('/subscriptions/{id}', [SubscriptionController::class, 'update']);
        Route::delete('/subscriptions/{id}', [SubscriptionController::class, 'destroy']);
        Route::patch('/subscriptions/{id}/cancel', [SubscriptionController::class, 'cancel']);
        Route::patch('/subscriptions/{id}/reactivate', [SubscriptionController::class, 'reactivate']);

        // Reports routes
        Route::get('/reports/my-expenses', [ReportController::class, 'myExpenses']);
    });
});

// Fallback route
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Not Found'
    ], 404);
});
