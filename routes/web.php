<?php

use Illuminate\Support\Facades\Route;
use App\Models\GameService;
use App\Models\GameImage;

Route::get('/', function () {
    // Get active game services grouped by game
    $gameServices = GameService::where('is_active', true)
        ->where('status', 'available')
        ->orderBy('game')
        ->get()
        ->groupBy('game');
    
    // Get game images
    $gameImages = GameImage::all()->keyBy('game_name');
    
    return view('welcome', compact('gameServices', 'gameImages'));
});
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/invoices', function () {
    return view('check-invoice');
})->name('invoices');
Route::get('/leaderboard', function () {
    return view('leaderboard');
})->name('leaderboard');
Route::get('/article', function () {
    return view('article');
})->name('article');