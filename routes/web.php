<?php

use App\Models\News;
use App\Models\Banner;
use App\Models\GameImage;
use App\Models\BrandImage;
use App\Models\GameService;
use App\Models\PaymentMethod;
use App\Models\PrepaidService;
use App\Models\WebsiteSetting;
use App\Models\GameAccountField;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ProfileController;

// Share website settings with all views
View::composer('*', function ($view) {
    $view->with('websiteLogo', WebsiteSetting::get('website_logo'));
    $view->with('websiteName', WebsiteSetting::get('website_name', 'NVD STORE'));
    $view->with('websiteDescription', WebsiteSetting::get('website_description', 'Top Up Games Favoritmu'));
    $view->with('websitePhone', WebsiteSetting::get('website_phone', '6282227113307'));
    $view->with('websiteAddress', WebsiteSetting::get('website_address', 'Medan Sunggal, Kota Medan, Sumatera Utara 20122'));
});

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
    
    // Get popular games for carousel
    $popularGames = ['Mobile Legends (Global)', 'Free Fire', 'PUBG Mobile (GLOBAL)', 'Genshin Impact'];
    $popularGameData = GameImage::whereIn('game_name', $popularGames)
        ->get()
        ->keyBy('game_name');
    
    // Get latest published news (limit 3)
    $news = News::published()
        ->orderBy('published_at', 'desc')
        ->limit(3)
        ->get();
    
    return view('welcome', compact('gameServices', 'gameImages', 'prepaidServices', 'brandImages', 'banners', 'popularGameData', 'news'));
});
Route::middleware('guest')->group(function () {
    Route::get('/auth/login', [AuthenticatedSessionController::class, 'showLoginForm'])->name('login');
    Route::post('/auth/login', [AuthenticatedSessionController::class, 'login'])->name('login.attempt');

    Route::get('/auth/register', [AuthenticatedSessionController::class, 'showRegistrationForm'])->name('register');
    Route::post('/auth/register', [AuthenticatedSessionController::class, 'register'])->name('register.store');
});

Route::post('/auth/logout', [AuthenticatedSessionController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
    
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    
    // Top Up routes
    Route::post('/topup/create', [App\Http\Controllers\TopUpController::class, 'create'])->name('topup.create');
    
    // Game Order routes
    Route::post('/order/game', [App\Http\Controllers\GameOrderController::class, 'store'])->name('order.game.store');
    
    // Prepaid Order routes
    Route::post('/order/prepaid', [App\Http\Controllers\PrepaidOrderController::class, 'store'])->name('order.prepaid.store');

});

// Duitku Callback - Server to Server (POST)
// Protected by IP whitelist middleware
Route::post('/payment/duitku/callback', [App\Http\Controllers\TopUpController::class, 'callback'])
    ->middleware('duitku.ip')
    ->name('topup.callback');

// Unified Payment Callback - Handles TopUp, Game, and Prepaid transactions
Route::post('/payment/callback', [App\Http\Controllers\PaymentCallbackController::class, 'handle'])
    ->middleware('duitku.ip')
    ->name('payment.callback');

// Duitku Redirect - User Return URL (GET)
// No authentication required as user may be redirected from external payment page
Route::get('/payment/duitku/redirect', [App\Http\Controllers\TopUpController::class, 'redirect'])
    ->name('topup.redirect');

// Transaction Status Check - For frontend polling
Route::get('/api/transaction/status/{trxid}', [App\Http\Controllers\TransactionStatusController::class, 'check'])
    ->middleware('auth')
    ->name('transaction.status');

Route::get('/invoices', [App\Http\Controllers\InvoiceController::class, 'index'])->name('invoices');
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
Route::get('/leaderboard/data/{period}', [LeaderboardController::class, 'getData'])->name('leaderboard.data');
Route::get('/article', [ArticleController::class, 'index'])->name('article');
Route::get('/contact-us', [ContactController::class, 'index'])->name('contact-us');
Route::post('/contact-us', [ContactController::class, 'store'])->name('contact-us.store');

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
    
    $accountFields = GameAccountField::forGame($matchedGame)->get();

    if ($accountFields->isEmpty()) {
        $accountFields = collect([
            GameAccountField::make([
                'game_name' => $matchedGame,
                'field_key' => 'user_id',
                'label' => 'User ID',
                'placeholder' => 'Masukkan User ID',
                'input_type' => 'text',
                'is_required' => true,
                'helper_text' => 'Pastikan User ID benar agar top up berhasil.',
                'sort_order' => 1,
            ]),
        ]);
    }

    return view('order.game', [
        'game' => $matchedGame,
        'services' => $services,
        'gameImage' => $gameImage,
        'paymentMethods' => $paymentMethods,
        'accountFields' => $accountFields,
    ]);
})->name('order.game');

// Order Prepaid Route (Pulsa & Data)
Route::get('/order/prepaid/{brandSlug}', function ($brandSlug) {
    // Convert slug back to brand name
    $allBrands = PrepaidService::select('brand')
        ->where('is_active', true)
        ->where('status', 'available')
        ->distinct()
        ->get()
        ->pluck('brand');
    
    $matchedBrand = null;
    foreach ($allBrands as $brandName) {
        $brandSlugFromDb = strtolower(str_replace(' ', '-', $brandName));
        if ($brandSlugFromDb === $brandSlug) {
            $matchedBrand = $brandName;
            break;
        }
    }
    
    // If no match found, abort 404
    if (!$matchedBrand) {
        abort(404, 'Produk tidak ditemukan atau tidak tersedia');
    }
    
    // Get prepaid services for this brand
    $services = PrepaidService::where('brand', $matchedBrand)
        ->where('is_active', true)
        ->where('status', 'available')
        ->orderBy('price_basic')
        ->get();
    
    // Get brand image
    $brandImage = BrandImage::where('brand_name', $matchedBrand)->first();
    
    // Get active payment methods grouped by category
    $paymentMethods = PaymentMethod::with('paymentGateway')
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get()
        ->groupBy(function($item) {
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

    return view('order.prepaid', [
        'brand' => $matchedBrand,
        'services' => $services,
        'brandImage' => $brandImage,
        'paymentMethods' => $paymentMethods,
    ]);
})->name('order.prepaid');