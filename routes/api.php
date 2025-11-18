<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BusinessController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SocialPostController;
use App\Http\Controllers\Api\AIController;
use App\Http\Controllers\Api\CustomerController;
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

    // AI Features
    Route::prefix('ai')->group(function () {
        // Content Generation
        Route::post('/generate-content', [AIController::class, 'generateContent']);
        Route::post('/generate-variations', [AIController::class, 'generateVariations']);
        Route::post('/suggest-hashtags', [AIController::class, 'suggestHashtags']);

        // Sentiment Analysis
        Route::post('/analyze-sentiment', [AIController::class, 'analyzeSentiment']);
        Route::post('/reviews/{review}/analyze', [AIController::class, 'analyzeReview']);

        // Review Responses
        Route::post('/reviews/{review}/generate-response', [AIController::class, 'generateReviewResponse']);
        Route::post('/reviews/{review}/suggest-responses', [AIController::class, 'suggestReviewResponses']);
        Route::post('/reviews/{review}/auto-reply', [AIController::class, 'autoReply']);
    });

    // CRM & Customers
    Route::apiResource('customers', CustomerController::class);
    Route::get('/customers-segments', [CustomerController::class, 'segments']);
    Route::get('/customers-at-risk', [CustomerController::class, 'atRisk']);
    Route::get('/customers-vips', [CustomerController::class, 'vips']);
    Route::get('/customers-birthdays', [CustomerController::class, 'birthdays']);
});
