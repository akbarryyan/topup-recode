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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentSuccessController;
use App\Http\Controllers\GameOrderController;
use App\Http\Controllers\PrepaidOrderController;


// Share website settings with all views
View::composer('*', function ($view) {
    $view->with('websiteLogo', WebsiteSetting::get('website_logo'));
    $view->with('websiteName', WebsiteSetting::get('website_name', 'NVD STORE'));
    $view->with('websiteDescription', WebsiteSetting::get('website_description', 'Top Up Games Favoritmu'));
    $view->with('websitePhone', WebsiteSetting::get('website_phone', '6282227113307'));
    $view->with('websiteAddress', WebsiteSetting::get('website_address', 'Medan Sunggal, Kota Medan, Sumatera Utara 20122'));
});

// Locale switching route (outside locale group)
Route::get('/locale/{locale}', [App\Http\Controllers\LocaleController::class, 'switch'])->name('locale.switch');

// Payment Callback Routes (MUST be outside locale group - no /id or /en prefix)
// Duitku Callback - Server to Server (POST)
Route::post('/payment/duitku/callback', [App\Http\Controllers\TopUpController::class, 'callback'])
    ->middleware('duitku.ip')
    ->name('topup.callback');

// Unified Payment Callback - Handles TopUp, Game, and Prepaid transactions
Route::post('/payment/callback', [App\Http\Controllers\PaymentCallbackController::class, 'handle'])
    ->middleware('duitku.ip')
    ->name('payment.callback');

// VIP Reseller Webhook Callback - Real-time status update from provider
Route::post('/webhook/vipresel', [App\Http\Controllers\VipResellerWebhookController::class, 'handle'])
    ->middleware('vipresel.ip')
    ->name('vipresel.webhook');

// Transaction Detail API (outside locale group for direct access)
Route::get('/api/transaction-detail/{trxid}', [App\Http\Controllers\ProfileController::class, 'getTransactionDetail'])
    ->middleware('auth')
    ->name('transaction.detail.api');

// Duitku Redirect - User Return URL (GET)
Route::get('/payment/duitku/redirect', [App\Http\Controllers\TopUpController::class, 'redirect'])
    ->name('topup.redirect');

// Invoices route (outside locale group for direct access from payment gateway)
Route::any('/invoices', function () {
    if (!Auth::check()) {
        // Detect locale from session or default to 'id'
        $locale = session('locale', 'id');
        return redirect()->to('/' . $locale . '/auth/login');
    }
    
    // Redirect to profile with locale
    $locale = session('locale', 'id');
    return redirect()->to('/' . $locale . '/profile?' . http_build_query(array_merge(['tab' => 'transactions'], request()->query())));
})->name('invoices');

// API route for game search (outside locale group)
Route::get('/api/games/search', function () {
    $gameServices = GameService::where('is_active', true)
        ->where('status', 'available')
        ->select('game')
        ->distinct()
        ->get()
        ->pluck('game');
    
    $gameImages = GameImage::all()->keyBy('game_name');
    
    $games = $gameServices->map(function($gameName) use ($gameImages) {
        return [
            'name' => $gameName,
            'slug' => strtolower(str_replace(' ', '-', $gameName)),
            'image' => isset($gameImages[$gameName]) 
                ? asset('storage/game-images/' . $gameImages[$gameName]->image)
                : asset('storage/game-images/game-placeholder.svg'),
            'category' => 'Game'
        ];
    })->values();
    
    return response()->json($games);
})->name('api.games.search');

// Payment Success Routes (outside locale group - NO /id or /en prefix)
Route::get('/payment/success/{trxid}', [PaymentSuccessController::class, 'show'])->name('payment.success');
Route::get('/payment/invoice/{trxid}/pdf', [PaymentSuccessController::class, 'downloadPdf'])->name('payment.invoice.pdf');

// Routes with optional locale prefix (supports both /id and /en)
Route::group(['prefix' => '{locale?}', 'where' => ['locale' => 'id|en']], function () {

Route::get('/', function () {
    // Get active game services
    $allGameServices = GameService::where('is_active', true)
        ->where('status', 'available')
        ->orderBy('game')
        ->get();
    
    // Group by normalized game name (case-insensitive, trimmed)
    // This prevents duplicates like "Call Of Duty Mobile" and "Call of Duty Mobile"
    $gameServices = $allGameServices->groupBy(function ($item) {
        return strtolower(trim($item->game));
    })->map(function ($group) {
        // Use the first item's original game name as the display name
        return $group;
    })->mapWithKeys(function ($group, $key) {
        // Get the original game name (with proper casing) from the first item
        $originalName = $group->first()->game;
        return [$originalName => $group];
    });
    
    // Get game images - create a case-insensitive lookup
    $gameImagesRaw = GameImage::all();
    $gameImages = collect();
    foreach ($gameImagesRaw as $img) {
        $gameImages[trim($img->game_name)] = $img;
    }
    
    // Get active prepaid services
    $allPrepaidServices = PrepaidService::where('is_active', true)
        ->where('status', 'available')
        ->orderBy('brand')
        ->get();
    
    // Group by normalized brand name
    $prepaidServices = $allPrepaidServices->groupBy(function ($item) {
        return strtolower(trim($item->brand));
    })->mapWithKeys(function ($group, $key) {
        $originalName = $group->first()->brand;
        return [$originalName => $group];
    });
    
    // Get brand images
    $brandImagesRaw = BrandImage::all();
    $brandImages = collect();
    foreach ($brandImagesRaw as $img) {
        $brandImages[trim($img->brand_name)] = $img;
    }
    
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

});

// Order routes - No authentication required (guest checkout)
Route::post('/order/game', [GameOrderController::class, 'store'])->name('order.game.store');
Route::post('/order/prepaid', [PrepaidOrderController::class, 'store'])->name('order.prepaid.store');

// Transaction Status Check - For frontend polling
Route::get('/api/transaction/status/{trxid}', [App\Http\Controllers\TransactionStatusController::class, 'check'])
    ->middleware('auth')
    ->name('transaction.status');

Route::get('/check-invoice', [App\Http\Controllers\InvoiceController::class, 'index'])->name('check-invoice');
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
Route::get('/leaderboard/data/{period}', [LeaderboardController::class, 'getData'])->name('leaderboard.data');
Route::get('/article', [ArticleController::class, 'index'])->name('article');
Route::get('/contact-us', [ContactController::class, 'index'])->name('contact-us');
Route::post('/contact-us', [ContactController::class, 'store'])->name('contact-us.store');

// API Documentation Route
Route::get('/api-docs', function () {
    return view('api-docs');
})->name('api-docs');

// Win Rate Calculator Routes
Route::get('/calculator/win-rate', [App\Http\Controllers\WinRateController::class, 'index'])->name('calculator.win-rate');
Route::post('/calculator/win-rate/calculate', [App\Http\Controllers\WinRateController::class, 'calculate'])->name('calculator.win-rate.calculate');

// Magic Wheel Calculator Routes
Route::get('/calculator/magic-wheel', [App\Http\Controllers\MagicWheelController::class, 'index'])->name('calculator.magic-wheel');
Route::post('/calculator/magic-wheel/calculate', [App\Http\Controllers\MagicWheelController::class, 'calculate'])->name('calculator.magic-wheel.calculate');

// Zodiac Calculator Routes
Route::get('/calculator/zodiac', [App\Http\Controllers\ZodiacController::class, 'index'])->name('calculator.zodiac');
Route::post('/calculator/zodiac/calculate', [App\Http\Controllers\ZodiacController::class, 'calculate'])->name('calculator.zodiac.calculate');

// Price List Route
Route::get('/price-list', [App\Http\Controllers\PriceListController::class, 'index'])->name('price-list');

// Order Game Route
Route::get('/order/{gameSlug}', function ($locale = null, $gameSlug) {
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
            } elseif (strpos($name, 'virtual account') !== false || 
                      strpos($code, 'va') !== false || 
                      strpos($name, 'bank') !== false ||
                      // Common VA codes from Duitku: BC (BCA), BN (BNI), BR (BRI), etc.
                      in_array($code, ['bc', 'bn', 'br', 'bni', 'bri', 'bca', 'mandiri', 'permata', 'cimb', 'danamon', 'atm'])) {
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
Route::get('/order/prepaid/{brandSlug}', function ($locale = null, $brandSlug) {
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
            } elseif (strpos($name, 'virtual account') !== false || 
                      strpos($code, 'va') !== false || 
                      strpos($name, 'bank') !== false ||
                      // Common VA codes from Duitku: BC (BCA), BN (BNI), BR (BRI), etc.
                      in_array($code, ['bc', 'bn', 'br', 'bni', 'bri', 'bca', 'mandiri', 'permata', 'cimb', 'danamon', 'atm'])) {
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

}); // End of locale route group