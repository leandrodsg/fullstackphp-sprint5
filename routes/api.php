
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SubscriptionController;

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

    // Auth routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:api')->group(function () {
        // Profile routes
        Route::get('/profile', [AuthController::class, 'profile']);
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
    });
});

// Fallback route
Route::fallback(function () {
    return response()->json(['message' => 'Not Found'], 404);
});
