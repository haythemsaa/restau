<?php

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
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes (no authentication required for now)
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'service' => 'RestauBoost API',
    ]);
});

// Protected API routes (will require authentication in production)
Route::prefix('v1')->group(function () {

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

    // User profile (requires authentication)
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user()->load('businesses');
    });
});

// Future routes for authentication (to be implemented)
Route::prefix('auth')->group(function () {
    // Route::post('/register', [AuthController::class, 'register']);
    // Route::post('/login', [AuthController::class, 'login']);
    // Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    // Route::post('/mfa/enable', [MfaController::class, 'enable'])->middleware('auth:sanctum');
    // Route::post('/mfa/verify', [MfaController::class, 'verify']);
});
