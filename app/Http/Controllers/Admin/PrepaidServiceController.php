<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrepaidService;
use App\Models\BrandImage;
use App\Services\VipResellerService;
use Illuminate\Http\Request;

class PrepaidServiceController extends Controller
{
    protected $vipResellerService;

    public function __construct(VipResellerService $vipResellerService)
    {
        $this->vipResellerService = $vipResellerService;
    }

    /**
     * Display a listing of prepaid services
     */
    public function index(Request $request)
    {
        $query = PrepaidService::query();

        // Filter by brand
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
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

        $services = $query->orderBy('brand')->orderBy('category')->orderBy('price_basic')->paginate(50)->appends($request->all());
        $brands = PrepaidService::select('brand')->distinct()->whereNotNull('brand')->orderBy('brand')->pluck('brand');
        $categories = PrepaidService::select('category')->distinct()->whereNotNull('category')->orderBy('category')->pluck('category');
        $types = PrepaidService::select('type')->distinct()->whereNotNull('type')->orderBy('type')->pluck('type');
        $brandImages = BrandImage::all()->keyBy('brand_name');

        return view('admin.prepaid-services.index', compact('services', 'brands', 'categories', 'types', 'brandImages'));
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

        $filterBrand = $request->input('filter_brand');
        $filterType = $request->input('filter_type');
        $marginType = $request->input('margin_type');
        $marginValue = $request->input('margin_value');
        $limit = $request->input('limit', 1000);
        
        if ($filterBrand) {
            $response = $this->vipResellerService->getPrepaidServicesByBrand($filterBrand);
        } elseif ($filterType) {
            $response = $this->vipResellerService->getPrepaidServicesByType($filterType);
        } else {
            $response = $this->vipResellerService->getPrepaidServices();
        }

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
            $existingService = PrepaidService::where('code', $service['code'])->first();

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

            $priceBasic = PrepaidService::calculatePriceWithMargin(
                $service['price']['basic'], 
                $marginType, 
                $marginValue
            );
            $pricePremium = PrepaidService::calculatePriceWithMargin(
                $service['price']['premium'], 
                $marginType, 
                $marginValue
            );
            $priceSpecial = PrepaidService::calculatePriceWithMargin(
                $service['price']['special'], 
                $marginType, 
                $marginValue
            );

            $prepaidService = PrepaidService::updateOrCreate(
                ['code' => $service['code']],
                [
                    'brand' => $service['brand'] ?? null,
                    'name' => $service['name'],
                    'note' => $service['note'] ?? null,
                    'price_basic' => $priceBasic,
                    'price_premium' => $pricePremium,
                    'price_special' => $priceSpecial,
                    'price_basic_original' => $service['price']['basic'],
                    'price_premium_original' => $service['price']['premium'],
                    'price_special_original' => $service['price']['special'],
                    'margin_type' => $marginType,
                    'margin_value' => $marginValue,
                    'multi_trx' => $service['multi_trx'] ?? false,
                    'maintenance' => $service['maintenace'] ?? null, // Note: typo in API
                    'category' => $service['category'] ?? null,
                    'prepost' => $service['prepost'] ?? 'prepaid',
                    'type' => $service['type'] ?? null,
                    'status' => $service['status'],
                ]
            );

            if ($prepaidService->wasRecentlyCreated) {
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
        $service = PrepaidService::findOrFail($id);
        $service->is_active = !$service->is_active;
        $service->save();

        return redirect()->back()->with('success', 'Status layanan berhasil diubah!');
    }

    /**
     * Delete service
     */
    public function destroy($id)
    {
        $service = PrepaidService::findOrFail($id);
        $service->delete();

        return redirect()->back()->with('success', 'Layanan berhasil dihapus!');
    }

    /**
     * Upload brand image
     */
    public function uploadImage(Request $request, $brandName)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $uploadPath = public_path('storage/brand-images');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $brandImage = BrandImage::where('brand_name', $brandName)->first();

        // Delete old image if exists
        if ($brandImage && $brandImage->image) {
            $oldImagePath = $uploadPath . '/' . $brandImage->image;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        // Upload new image
        $filename = time() . '_' . str_replace(' ', '_', $brandName) . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->move($uploadPath, $filename);

        // Create or update brand image record
        BrandImage::updateOrCreate(
            ['brand_name' => $brandName],
            ['image' => $filename]
        );

        // Count affected services
        $servicesCount = PrepaidService::where('brand', $brandName)->count();

        return redirect()->back()->with('success', "Gambar brand '{$brandName}' berhasil diupload! ({$servicesCount} layanan terpengaruh)");
    }

    /**
     * Delete brand image
     */
    public function deleteImage($brandName)
    {
        $brandImage = BrandImage::where('brand_name', $brandName)->first();

        if (!$brandImage) {
            return redirect()->back()->with('error', 'Gambar brand tidak ditemukan!');
        }

        // Delete image file
        $imagePath = public_path('storage/brand-images/' . $brandImage->image);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Delete record
        $brandImage->delete();

        return redirect()->back()->with('success', "Gambar brand '{$brandName}' berhasil dihapus!");
    }
}
