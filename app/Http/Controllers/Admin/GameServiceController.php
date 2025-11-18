<?php

namespace App\Http\Controllers\Admin;

use App\Models\GameService;
use App\Models\GameImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Services\VipResellerService;

class GameServiceController extends Controller
{
    protected $vipResellerService;

    public function __construct(VipResellerService $vipResellerService)
    {
        $this->vipResellerService = $vipResellerService;
    }

    /**
     * Display a listing of game services
     */
    public function index(Request $request)
    {
        $query = GameService::query();

        // Filter by game
        if ($request->filled('game')) {
            $query->where('game', $request->game);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by active
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Search by name or code
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $services = $query->orderBy('game')->orderBy('price_basic')->paginate(50)->appends($request->all());
        $games = GameService::select('game')->distinct()->orderBy('game')->pluck('game');

        return view('admin.game-services.index', compact('services', 'games'));
    }

    /**
     * Sync services from VIP Reseller API
     */
    public function sync(Request $request)
    {
        // Set longer execution time
        set_time_limit(300); // 5 minutes
        ini_set('max_execution_time', 300);

        $request->validate([
            'margin_type' => 'required|in:fixed,percent',
            'margin_value' => 'required|numeric|min:0',
            'limit' => 'nullable|integer|min:1|max:10000',
        ]);

        $filterGame = $request->input('filter_game');
        $marginType = $request->input('margin_type');
        $marginValue = $request->input('margin_value');
        $limit = $request->input('limit', 1000);
        
        $response = $filterGame 
            ? $this->vipResellerService->getServicesByGame($filterGame)
            : $this->vipResellerService->getGameServices();

        if (!$response['success']) {
            return redirect()->back()->with('error', $response['message']);
        }

        $synced = 0;
        $updated = 0;
        $skipped = 0;
        $skippedEmpty = 0;
        $deleted = 0;

        foreach ($response['data'] as $service) {
            // Check if service already exists in database
            $existingService = GameService::where('code', $service['code'])->first();

            // If service is empty/unavailable
            if ($service['status'] !== 'available') {
                // Delete if already exists in database
                if ($existingService) {
                    $existingService->delete();
                    $deleted++;
                } else {
                    // Skip if doesn't exist (don't add empty services)
                    $skippedEmpty++;
                }
                continue;
            }

            // Check limit
            if (($synced + $updated) >= $limit) {
                $skipped++;
                continue;
            }

            $priceBasic = GameService::calculatePriceWithMargin(
                $service['price']['basic'], 
                $marginType, 
                $marginValue
            );
            $pricePremium = GameService::calculatePriceWithMargin(
                $service['price']['premium'], 
                $marginType, 
                $marginValue
            );
            $priceSpecial = GameService::calculatePriceWithMargin(
                $service['price']['special'], 
                $marginType, 
                $marginValue
            );

            $gameService = GameService::updateOrCreate(
                ['code' => $service['code']],
                [
                    'game' => $service['game'],
                    'name' => $service['name'],
                    'price_basic' => $priceBasic,
                    'price_premium' => $pricePremium,
                    'price_special' => $priceSpecial,
                    'price_basic_original' => $service['price']['basic'],
                    'price_premium_original' => $service['price']['premium'],
                    'price_special_original' => $service['price']['special'],
                    'margin_type' => $marginType,
                    'margin_value' => $marginValue,
                    'server' => $service['server'],
                    'status' => $service['status'],
                ]
            );

            if ($gameService->wasRecentlyCreated) {
                $synced++;
            } else {
                $updated++;
            }
        }

        $marginText = $marginType === 'percent' ? "{$marginValue}%" : "Rp " . number_format($marginValue, 0, ',', '.');
        $message = "Sync berhasil dengan margin {$marginText}! {$synced} layanan baru ditambahkan, {$updated} layanan diperbarui";
        
        if ($deleted > 0) {
            $message .= ", {$deleted} layanan empty dihapus";
        }
        
        if ($skippedEmpty > 0) {
            $message .= " ({$skippedEmpty} layanan empty baru dilewati)";
        }
        
        if ($skipped > 0) {
            $message .= " ({$skipped} layanan dilewati karena limit)";
        }
        
        $message .= ".";

        return redirect()->back()->with('success', $message);
    }

    /**
     * Toggle service active status
     */
    public function toggleStatus($id)
    {
        $service = GameService::findOrFail($id);
        $service->is_active = !$service->is_active;
        $service->save();

        return redirect()->back()->with('success', 'Status layanan berhasil diubah!');
    }

    /**
     * Delete service
     */
    public function destroy($id)
    {
        $service = GameService::findOrFail($id);
        $service->delete();

        return redirect()->back()->with('success', 'Layanan berhasil dihapus!');
    }

    /**
     * Check stock for specific service
     */
    public function checkStock($id)
    {
        $service = GameService::findOrFail($id);
        
        $response = $this->vipResellerService->getServiceStock($service->code);

        if (!$response['success']) {
            return redirect()->back()->with('error', 'Gagal cek stock: ' . $response['message']);
        }

        $stockData = $response['data'];
        
        // Update service with stock info
        $service->update([
            'stock' => $stockData['stock'] ?? 0,
            'description' => $stockData['description'] ?? null,
            'stock_updated_at' => now(),
        ]);

        $stockStatus = $stockData['stock'] > 0 ? 'tersedia' : 'habis';
        $message = "Stock berhasil diperbarui! Stok saat ini: {$stockData['stock']} ({$stockStatus})";

        return redirect()->back()->with('success', $message);
    }

    /**
     * Bulk check stock for all services
     */
    public function bulkCheckStock(Request $request)
    {
        // Set unlimited execution time
        set_time_limit(0); // Unlimited
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '512M'); // Increase memory

        $filterGame = $request->input('game');
        $limit = $request->input('limit', 50); // Reduced default to 50
        
        $query = GameService::query();
        if ($filterGame) {
            $query->where('game', $filterGame);
        }
        
        // Limit the number of services to check
        $services = $query->limit($limit)->get();
        $updated = 0;
        $failed = 0;
        $unsupported = 0;
        $timeout_count = 0;
        $unsupported_codes = [];

        // Collect sample responses for debugging
        $sampleResponses = [];
        $collectSamples = $request->input('debug') == '1';

        foreach ($services as $service) {
            try {
                $response = $this->vipResellerService->getServiceStock($service->code);
                
                // Collect first 3 responses (1 success, 2 failures) for debugging
                if ($collectSamples && count($sampleResponses) < 3) {
                    $sampleResponses[] = [
                        'code' => $service->code,
                        'response' => $response,
                    ];
                }
                
                if ($response['success']) {
                    $stockData = $response['data'];
                    $service->update([
                        'stock' => $stockData['stock'] ?? 0,
                        'description' => $stockData['description'] ?? null,
                        'stock_updated_at' => now(),
                    ]);
                    $updated++;
                } else {
                    // Check if it's an IP whitelist or API access issue
                    if (str_contains($response['message'], 'tidak support') || str_contains($response['message'], 'tidak di temukan')) {
                        $unsupported++;
                        if (count($unsupported_codes) < 10) {
                            $unsupported_codes[] = $service->code;
                        }
                    } else {
                        $failed++;
                    }
                    
                    // Log detailed error for first few failures
                    if ($unsupported + $failed <= 5) {
                        Log::warning("Stock check failed for {$service->code}", [
                            'service_code' => $service->code,
                            'service_name' => $service->name,
                            'response' => $response,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                $failed++;
                if (str_contains($e->getMessage(), 'timeout') || str_contains($e->getMessage(), 'timed out')) {
                    $timeout_count++;
                }
                
                Log::error("Stock check exception for {$service->code}", [
                    'service_code' => $service->code,
                    'error' => $e->getMessage(),
                ]);
            }
            
            // Reduced delay to speed up process
            usleep(100000); // 100ms delay
            
            // Clear memory periodically
            if (($updated + $failed + $unsupported) % 10 == 0) {
                gc_collect_cycles();
            }
        }

        // Detect if all services failed - likely IP whitelist issue
        $allFailed = ($updated == 0 && ($unsupported > 0 || $failed > 0));
        
        // Fixed IP for shared hosting
        $currentIp = '195.88.211.226';
        
        if ($allFailed && $unsupported > 0) {
            $message = "⚠️ SEMUA layanan gagal dicek! Kemungkinan besar IP Anda ({$currentIp}) belum di-whitelist di VIP Reseller.\n\n";
            $message .= "Silakan tambahkan IP ini ke whitelist di dashboard VIP Reseller:\n";
            $message .= "https://vip-reseller.co.id/setting/ip-whitelist\n\n";
            $message .= "Hasil: 0 berhasil, {$unsupported} layanan mengembalikan error dari {$services->count()} layanan.";
            return redirect()->back()->with('error', $message);
        }
        
        $message = "Bulk check stock selesai! {$updated} berhasil diperbarui";
        
        if ($unsupported > 0) {
            $message .= ", {$unsupported} layanan tidak support/error";
        }
        
        if ($failed > 0) {
            $message .= ", {$failed} gagal";
        }
        
        if ($timeout_count > 0) {
            $message .= " ({$timeout_count} timeout)";
        }
        
        $message .= " dari {$services->count()} layanan.";
        
        if ($unsupported > 0 && $updated == 0) {
            $message .= "\n\n💡 Tip: Jika semua gagal, pastikan IP Anda ({$currentIp}) sudah di-whitelist di VIP Reseller.";
        }
        
        // If debug mode, store sample responses in session
        if ($collectSamples && !empty($sampleResponses)) {
            session()->put('debug_responses', $sampleResponses);
            $message .= "\n\n🔍 Debug mode: Sample responses telah disimpan. Cek di tab 'Debug Info'.";
        }
        
        return redirect()->back()->with($updated > 0 ? 'success' : 'warning', $message);
    }

    /**
     * Upload image for game (will apply to all services of this game)
     */
    public function uploadImage(Request $request, $gameName)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Get or create GameImage record
        $gameImage = GameImage::firstOrNew(['game_name' => $gameName]);
        
        // Delete old image if exists
        if ($gameImage->image) {
            $oldImagePath = public_path('storage/game-images/' . $gameImage->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        // Create directory if not exists
        $uploadPath = public_path('storage/game-images');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Generate unique filename
        $filename = time() . '_' . str_replace(' ', '_', $gameName) . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
        
        // Move uploaded file
        $request->file('image')->move($uploadPath, $filename);
        
        // Save or update game image
        $gameImage->image = $filename;
        $gameImage->save();

        // Count affected services
        $serviceCount = GameService::where('game', $gameName)->count();

        return redirect()->back()->with('success', "Gambar berhasil diupload untuk game {$gameName}! ({$serviceCount} layanan menggunakan gambar ini)");
    }

    /**
     * Delete image for game (will affect all services of this game)
     */
    public function deleteImage($gameName)
    {
        $gameImage = GameImage::where('game_name', $gameName)->first();
        
        if ($gameImage && $gameImage->image) {
            $imagePath = public_path('storage/game-images/' . $gameImage->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            $gameImage->delete();
            
            $serviceCount = GameService::where('game', $gameName)->count();
            
            return redirect()->back()->with('success', "Gambar berhasil dihapus untuk game {$gameName}! ({$serviceCount} layanan terpengaruh)");
        }
        
        return redirect()->back()->with('error', 'Tidak ada gambar untuk dihapus.');
    }
}