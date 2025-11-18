<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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