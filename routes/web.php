<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Web\CustomerController;
use App\Http\Controllers\Web\CampaignController;
use App\Http\Controllers\Web\AIContentController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\SettingsController;
use App\Http\Controllers\Auth\WebAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/login', [WebAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login']);

    Route::get('/register', [WebAuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [WebAuthController::class, 'register']);

    Route::get('/forgot-password', [WebAuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [WebAuthController::class, 'sendResetLink'])->name('password.email');
});

// Authenticated routes
Route::middleware(['auth', 'web'])->group(function () {

    // Logout
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');

    // Customers
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
        Route::post('/{customer}/visits', [CustomerController::class, 'addVisit'])->name('visits.store');
        Route::get('/export/csv', [CustomerController::class, 'exportCsv'])->name('export.csv');
        Route::get('/export/pdf', [CustomerController::class, 'exportPdf'])->name('export.pdf');
        Route::post('/import/csv', [CustomerController::class, 'importCsv'])->name('import.csv');
    });

    // Email Campaigns
    Route::prefix('campaigns')->name('campaigns.')->group(function () {
        Route::get('/', [CampaignController::class, 'index'])->name('index');
        Route::get('/create', [CampaignController::class, 'create'])->name('create');
        Route::post('/', [CampaignController::class, 'store'])->name('store');
        Route::get('/{campaign}', [CampaignController::class, 'show'])->name('show');
        Route::get('/{campaign}/edit', [CampaignController::class, 'edit'])->name('edit');
        Route::put('/{campaign}', [CampaignController::class, 'update'])->name('update');
        Route::delete('/{campaign}', [CampaignController::class, 'destroy'])->name('destroy');
        Route::post('/{campaign}/send', [CampaignController::class, 'send'])->name('send');
        Route::post('/{campaign}/test', [CampaignController::class, 'sendTest'])->name('test');
        Route::get('/{campaign}/stats', [CampaignController::class, 'stats'])->name('stats');
    });

    // AI Content Generator
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/content-generator', [AIContentController::class, 'index'])->name('content-generator');
        Route::post('/generate', [AIContentController::class, 'generate'])->name('generate');
        Route::post('/analyze-sentiment', [AIContentController::class, 'analyzeSentiment'])->name('analyze-sentiment');
        Route::post('/respond-review', [AIContentController::class, 'respondToReview'])->name('respond-review');
        Route::get('/history', [AIContentController::class, 'history'])->name('history');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/customers', [ReportController::class, 'customers'])->name('customers');
        Route::get('/campaigns', [ReportController::class, 'campaigns'])->name('campaigns');
        Route::get('/rfm-analysis', [ReportController::class, 'rfmAnalysis'])->name('rfm-analysis');
        Route::post('/generate', [ReportController::class, 'generate'])->name('generate');
        Route::get('/export/{type}', [ReportController::class, 'export'])->name('export');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('settings');
        Route::get('/profile', [SettingsController::class, 'profile'])->name('profile');
        Route::put('/profile', [SettingsController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [SettingsController::class, 'updatePassword'])->name('password.update');
        Route::get('/business', [SettingsController::class, 'business'])->name('business');
        Route::put('/business', [SettingsController::class, 'updateBusiness'])->name('business.update');
        Route::get('/integrations', [SettingsController::class, 'integrations'])->name('integrations');
        Route::put('/integrations', [SettingsController::class, 'updateIntegrations'])->name('integrations.update');
    });

});
