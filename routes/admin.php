<?php

use App\Models\News;
use App\Models\User;
use App\Models\GameService;
use App\Models\PrepaidService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\GameServiceController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\GameAccountFieldController;
use App\Http\Controllers\Admin\PrepaidServiceController;
use App\Http\Controllers\Admin\GameTransactionController;
use App\Http\Controllers\Admin\PrepaidTransactionController;
use App\Http\Controllers\Admin\WebsiteSettingController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\VipResellerSettingController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\MaintenanceSettingController;
use App\Http\Controllers\Admin\DashboardController;

Route::prefix('admin')->middleware('web')->group(function () {
    // Login routes (guest only)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');
    });

    // Protected admin routes (auth + admin role required)
    Route::middleware('admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::put('/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');

        // Users Management
        Route::get('/users', [UserController::class, 'index'])->name('admin.users');
        Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

        // Website Settings Routes
        Route::prefix('website-settings')->name('admin.website-settings.')->group(function () {
            Route::get('/', [WebsiteSettingController::class, 'index'])->name('index');
            Route::post('/', [WebsiteSettingController::class, 'update'])->name('update');
            Route::delete('/logo', [WebsiteSettingController::class, 'deleteLogo'])->name('delete-logo');
            Route::put('/maintenance', [MaintenanceSettingController::class, 'update'])->name('maintenance');
        });

        // VIP Reseller Settings Routes
        Route::prefix('vip-reseller')->name('admin.vip-reseller-settings.')->group(function () {
            Route::get('/', [VipResellerSettingController::class, 'index'])->name('index');
            Route::put('/', [VipResellerSettingController::class, 'update'])->name('update');
            Route::post('/check-profile', [VipResellerSettingController::class, 'checkProfile'])->name('check-profile');
        });

        // Game Account Fields Routes
        Route::prefix('game-account-fields')->name('admin.game-account-fields.')->group(function () {
            Route::get('/', [GameAccountFieldController::class, 'index'])->name('index');
            Route::post('/', [GameAccountFieldController::class, 'store'])->name('store');
            Route::put('/{gameAccountField}', [GameAccountFieldController::class, 'update'])->name('update');
            Route::delete('/{gameAccountField}', [GameAccountFieldController::class, 'destroy'])->name('destroy');
        });

        // Contact Management Routes
        Route::prefix('contacts')->name('admin.contacts.')->group(function () {
            Route::get('/', [ContactController::class, 'index'])->name('index');
            Route::get('/{id}', [ContactController::class, 'show'])->name('show');
            Route::patch('/{id}/status', [ContactController::class, 'updateStatus'])->name('update-status');
            Route::delete('/{id}', [ContactController::class, 'destroy'])->name('destroy');
        });

        // Game Services Routes
        Route::prefix('game-services')->name('admin.game-services.')->group(function () {
            Route::get('/', [GameServiceController::class, 'index'])->name('index');
            Route::post('/sync', [GameServiceController::class, 'sync'])->name('sync');
            Route::post('/bulk-check-stock', [GameServiceController::class, 'bulkCheckStock'])->name('bulk-check-stock');
            Route::post('/upload-image/{gameName}', [GameServiceController::class, 'uploadImage'])->name('upload-image');
            Route::delete('/delete-image/{gameName}', [GameServiceController::class, 'deleteImage'])->name('delete-image');
            Route::patch('/{id}/toggle', [GameServiceController::class, 'toggleStatus'])->name('toggle');
            Route::patch('/{id}/check-stock', [GameServiceController::class, 'checkStock'])->name('check-stock');
            Route::delete('/{id}', [GameServiceController::class, 'destroy'])->name('destroy');
        });

        // Prepaid Services Routes (Pulsa & PPOB)
        Route::prefix('prepaid-services')->name('admin.prepaid-services.')->group(function () {
            Route::get('/', [PrepaidServiceController::class, 'index'])->name('index');
            Route::post('/sync', [PrepaidServiceController::class, 'sync'])->name('sync');
            Route::post('/upload-image/{brandName}', [PrepaidServiceController::class, 'uploadImage'])->name('upload-image');
            Route::delete('/delete-image/{brandName}', [PrepaidServiceController::class, 'deleteImage'])->name('delete-image');
            Route::patch('/{id}/toggle', [PrepaidServiceController::class, 'toggleStatus'])->name('toggle');
            Route::delete('/{id}', [PrepaidServiceController::class, 'destroy'])->name('destroy');
        });

        // News Routes
        Route::prefix('news')->name('admin.news.')->group(function () {
            Route::get('/', [NewsController::class, 'index'])->name('index');
            Route::get('/create', [NewsController::class, 'create'])->name('create');
            Route::post('/', [NewsController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [NewsController::class, 'edit'])->name('edit');
            Route::put('/{id}', [NewsController::class, 'update'])->name('update');
            Route::delete('/{id}', [NewsController::class, 'destroy'])->name('destroy');
        });

        // Banner Routes
        Route::prefix('banners')->name('admin.banners.')->group(function () {
            Route::get('/', [BannerController::class, 'index'])->name('index');
            Route::post('/', [BannerController::class, 'store'])->name('store');
            Route::put('/{id}', [BannerController::class, 'update'])->name('update');
            Route::patch('/{id}/toggle', [BannerController::class, 'toggleStatus'])->name('toggle');
            Route::delete('/{id}', [BannerController::class, 'destroy'])->name('destroy');
        });

        // Game Transaction Routes
        Route::prefix('game-transactions')->name('admin.game-transactions.')->group(function () {
            Route::get('/', [GameTransactionController::class, 'index'])->name('index');
        });

        // Prepaid Transaction Routes
        Route::prefix('prepaid-transactions')->name('admin.prepaid-transactions.')->group(function () {
            Route::get('/', [PrepaidTransactionController::class, 'index'])->name('index');
        });

        // Payment Gateway Routes
        Route::prefix('payment-gateways')->name('admin.payment-gateways.')->group(function () {
            Route::get('/', [PaymentGatewayController::class, 'index'])->name('index');
            Route::post('/', [PaymentGatewayController::class, 'store'])->name('store');
            Route::get('/config', [PaymentGatewayController::class, 'config'])->name('config');
            Route::put('/config', [PaymentGatewayController::class, 'updateConfig'])->name('config.update');
            Route::post('/sync', [PaymentGatewayController::class, 'sync'])->name('sync');
            
            // Duitku Payment Methods
            Route::post('/fetch-methods', [PaymentGatewayController::class, 'fetchPaymentMethods'])->name('fetch-methods');
            Route::post('/save-methods', [PaymentGatewayController::class, 'savePaymentMethods'])->name('save-methods');
            
            // Tripay Payment Channels
            Route::post('/fetch-tripay-channels', [PaymentGatewayController::class, 'fetchTripayPaymentChannels'])->name('fetch-tripay-channels');
            Route::post('/save-tripay-channels', [PaymentGatewayController::class, 'saveTripayPaymentChannels'])->name('save-tripay-channels');
            
            Route::patch('/{id}/toggle', [PaymentGatewayController::class, 'toggleStatus'])->name('toggle');
            Route::put('/{id}', [PaymentGatewayController::class, 'update'])->name('update');
            Route::delete('/{id}', [PaymentGatewayController::class, 'destroy'])->name('destroy');
        });

        // Payment Methods Routes
        Route::prefix('payment-methods')->name('admin.payment-methods.')->group(function () {
            Route::patch('/{id}/toggle', [PaymentGatewayController::class, 'toggleMethodStatus'])->name('toggle');
            Route::put('/{id}', [PaymentGatewayController::class, 'updateMethod'])->name('update');
            Route::delete('/{id}', [PaymentGatewayController::class, 'destroyMethod'])->name('destroy');
        });
    });
});
