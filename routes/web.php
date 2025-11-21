<?php

use App\Models\Banner;
use App\Models\GameImage;
use App\Models\BrandImage;
use App\Models\GameService;
use App\Models\PaymentMethod;
use App\Models\PrepaidService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Get active game services grouped by game
    $gameServices = GameService::where('is_active', true)
        ->where('status', 'available')
        ->orderBy('game')
        ->get()
        ->groupBy('game');
    
    // Get game images
    $gameImages = GameImage::all()->keyBy('game_name');
    
    // Get active prepaid services grouped by brand
    $prepaidServices = PrepaidService::where('is_active', true)
        ->where('status', 'available')
        ->orderBy('brand')
        ->get()
        ->groupBy('brand');
    
    // Get brand images
    $brandImages = BrandImage::all()->keyBy('brand_name');
    
    // Get active banners
    $banners = Banner::where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('created_at', 'desc')
        ->get();
    
    return view('welcome', compact('gameServices', 'gameImages', 'prepaidServices', 'brandImages', 'banners'));
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

// Order Game Route
Route::get('/order/{gameSlug}', function ($gameSlug) {
    // Convert slug back to game name (e.g., 'free-fire' -> 'Free Fire')
    // Try to find game by matching slug pattern
    $allGames = GameService::select('game')
        ->where('is_active', true)
        ->where('status', 'available')
        ->distinct()
        ->get()
        ->pluck('game');
    
    $matchedGame = null;
    foreach ($allGames as $gameName) {
        $gameSlugFromDb = strtolower(str_replace(' ', '-', $gameName));
        if ($gameSlugFromDb === $gameSlug) {
            $matchedGame = $gameName;
            break;
        }
    }
    
    // If no match found, abort 404
    if (!$matchedGame) {
        abort(404, 'Game tidak ditemukan atau tidak tersedia');
    }
    
    // Get game services for this game
    $services = GameService::where('game', $matchedGame)
        ->where('is_active', true)
        ->where('status', 'available')
        ->orderBy('price_basic')
        ->get();
    
    // Get game image
    $gameImage = GameImage::where('game_name', $matchedGame)->first();
    
    // Get active payment methods grouped by category
    $paymentMethods = PaymentMethod::with('paymentGateway')
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get()
        ->groupBy(function($item) {
            // Categorize based on code/name
            $code = strtolower($item->code);
            $name = strtolower($item->name);
            
            if (strpos($code, 'qris') !== false || strpos($name, 'qris') !== false) {
                return 'qris';
            } elseif (strpos($name, 'virtual account') !== false || strpos($code, 'va') !== false || 
                      strpos($name, 'bank') !== false) {
                return 'virtual_account';
            } elseif (strpos($name, 'alfamart') !== false || strpos($name, 'indomaret') !== false ||
                      strpos($name, 'retail') !== false) {
                return 'retail';
            } else {
                return 'ewallet';
            }
        });
    
    return view('order.game', [
        'game' => $matchedGame,
        'services' => $services,
        'gameImage' => $gameImage,
        'paymentMethods' => $paymentMethods
    ]);
})->name('order.game');