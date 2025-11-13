<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('admin')->middleware('web')->group(function () {
    // Login routes (guest only)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');
    });

    // Protected admin routes (auth + admin role required)
    Route::middleware('admin')->group(function () {
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

        Route::get('/users', function () {
            return view('admin.users.index');
        })->name('admin.users');

        Route::get('/settings', function () {
            return view('admin.settings.index');
        })->name('admin.settings');
    });
});
