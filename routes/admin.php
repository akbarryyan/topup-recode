<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GameServiceController;
use App\Http\Controllers\Admin\PrepaidServiceController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\GameTransactionController;
use App\Http\Controllers\Admin\PrepaidTransactionController;

Route::prefix('admin')->middleware('web')->group(function () {
    // Login routes (guest only)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');
    });

    // Protected admin routes (auth + admin role required)
    Route::middleware('admin')->group(function () {
        Route::get('/', function () {
            $stats = [
                'total_users' => \App\Models\User::count(),
                'total_news' => \App\Models\News::count(),
                'total_prepaid_services' => \App\Models\PrepaidService::count(),
                'total_game_services' => \App\Models\GameService::count(),
            ];
            return view('admin.dashboard', compact('stats'));
        })->name('admin.dashboard');

        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

        // Users Management
        Route::get('/users', [UserController::class, 'index'])->name('admin.users');
        Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

        Route::get('/settings', function () {
            return view('admin.settings.index');
        })->name('admin.settings');

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

        // Game Transaction Routes
        Route::prefix('game-transactions')->name('admin.game-transactions.')->group(function () {
            Route::get('/', [GameTransactionController::class, 'index'])->name('index');
        });

        // Prepaid Transaction Routes
        Route::prefix('prepaid-transactions')->name('admin.prepaid-transactions.')->group(function () {
            Route::get('/', [PrepaidTransactionController::class, 'index'])->name('index');
        });
    });
});
