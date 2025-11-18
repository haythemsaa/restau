<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BusinessController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SocialPostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'service' => 'RestauBoost API',
        'version' => '1.0.0',
    ]);
});

// Authentication routes (public)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Protected auth routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });
});

// Protected API routes (require authentication)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // Businesses
    Route::apiResource('businesses', BusinessController::class);

    // Reviews
    Route::apiResource('reviews', ReviewController::class);

    // Social Posts
    Route::apiResource('social-posts', SocialPostController::class);
    Route::post('social-posts/{socialPost}/publish', [SocialPostController::class, 'publish'])
        ->name('social-posts.publish');

    // Conversations & Messages
    Route::apiResource('conversations', ConversationController::class);
    Route::post('conversations/{conversation}/messages', [ConversationController::class, 'storeMessage'])
        ->name('conversations.messages.store');
    Route::get('conversations/{conversation}/messages', [ConversationController::class, 'messages'])
        ->name('conversations.messages.index');
});
