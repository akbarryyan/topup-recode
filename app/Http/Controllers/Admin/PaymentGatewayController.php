<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $paymentGateways = PaymentGateway::orderBy('name')->get();
        
        // Get all payment methods with their gateway relationship
        $paymentMethods = PaymentMethod::with('paymentGateway')->orderBy('name')->get();
        
        return view('admin.payment-gateway.index', compact('paymentGateways', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'merchant_code' => 'required|string|max:255',
            'api_key' => 'required|string',
            'private_key' => 'nullable|string',
            'environment' => 'required|in:sandbox,production',
            'icon' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'callback_url' => 'nullable|url',
            'return_url' => 'nullable|url',
            'is_active' => 'required|boolean',
        ]);

        // Generate code from name (e.g., Midtrans -> midtrans_config)
        $code = strtolower(str_replace(' ', '_', $validated['name'])) . '_config';

        // Check if code already exists
        if (PaymentGateway::where('code', $code)->exists()) {
            return back()->with('error', 'Payment gateway dengan nama tersebut sudah ada.');
        }

        // Handle icon upload
        $iconUrl = null;
        if ($request->hasFile('icon')) {
            // Create directory if not exists
            $uploadPath = public_path('storage/payment-icons');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Generate unique filename
            $icon = $request->file('icon');
            $iconName = time() . '_' . $code . '.' . $icon->getClientOriginalExtension();
            
            // Move uploaded file
            $icon->move($uploadPath, $iconName);
            
            // Save filename only
            $iconUrl = $iconName;
        }

        PaymentGateway::create([
            'name' => $validated['name'],
            'code' => $code,
            'merchant_code' => $validated['merchant_code'],
            'api_key' => $validated['api_key'],
            'private_key' => $validated['private_key'] ?? null,
            'environment' => $validated['environment'],
            'icon_url' => $iconUrl,
            'callback_url' => $validated['callback_url'] ?? null,
            'return_url' => $validated['return_url'] ?? null,
            'is_active' => $validated['is_active'],
        ]);

        return back()->with('success', 'Payment gateway berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $paymentGateway = PaymentGateway::findOrFail($id);
        
        // Delete icon file if exists
        if ($paymentGateway->icon_url) {
            $iconPath = public_path('storage/payment-icons/' . $paymentGateway->icon_url);
            if (file_exists($iconPath)) {
                unlink($iconPath);
            }
        }
        
        $paymentGateway->delete();
        return back()->with('success', 'Payment gateway berhasil dihapus.');
    }

    public function config()
    {
        $duitkuConfig = PaymentGateway::where('code', 'duitku_config')->first();
        return view('admin.payment-gateway.config', compact('duitkuConfig'));
    }

    public function updateConfig(Request $request)
    {
        $validated = $request->validate([
            'merchant_code' => 'required|string',
            'api_key' => 'required|string',
            'environment' => 'required|in:sandbox,production',
            'callback_url' => 'nullable|url',
            'return_url' => 'nullable|url',
        ]);

        PaymentGateway::updateOrCreate(
            ['code' => 'duitku_config'],
            [
                'name' => 'Duitku Configuration',
                'merchant_code' => $validated['merchant_code'],
                'api_key' => $validated['api_key'],
                'environment' => $validated['environment'],
                'callback_url' => $validated['callback_url'] ?? null,
                'return_url' => $validated['return_url'] ?? null,
                'is_active' => true,
            ]
        );

        return back()->with('success', 'Konfigurasi Duitku berhasil disimpan.');
    }

    public function sync()
    {
        // Get Duitku configuration from database
        $duitkuConfig = PaymentGateway::where('code', 'duitku_config')->first();

        if (!$duitkuConfig || !$duitkuConfig->merchant_code || !$duitkuConfig->api_key) {
            return back()->with('error', 'Konfigurasi Duitku belum diatur. Silakan atur konfigurasi terlebih dahulu.');
        }

        $merchantCode = $duitkuConfig->merchant_code;
        $apiKey = $duitkuConfig->api_key;
        $environment = $duitkuConfig->environment;
        
        // Set API URL based on environment
        $apiUrl = $environment === 'production' 
            ? 'https://passport.duitku.com/webapi/api/merchant/paymentmethod/getpaymentmethod'
            : 'https://sandbox.duitku.com/webapi/api/merchant/paymentmethod/getpaymentmethod';

        // Generate datetime and signature according to Duitku API docs
        $datetime = date('Y-m-d H:i:s');
        $paymentAmount = 10000; // Amount required by API (minimal amount for testing)
        $signature = hash('sha256', $merchantCode . $paymentAmount . $datetime . $apiKey);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($apiUrl, [
                'merchantcode' => $merchantCode,
                'amount' => $paymentAmount,
                'datetime' => $datetime,
                'signature' => $signature,
            ]);

            if ($response->failed()) {
                return back()->with('error', 'Gagal mengambil data dari Duitku. HTTP Code: ' . $response->status() . '. Response: ' . $response->body());
            }

            $responseData = $response->json();

            // Check response code
            if (!isset($responseData['responseCode']) || $responseData['responseCode'] !== '00') {
                $errorMsg = $responseData['responseMessage'] ?? 'Unknown error';
                return back()->with('error', 'Duitku API Error: ' . $errorMsg);
            }

            if (!isset($responseData['paymentFee']) || empty($responseData['paymentFee'])) {
                return back()->with('error', 'Tidak ada payment method yang tersedia dari Duitku.');
            }

            $paymentMethods = $responseData['paymentFee'];
            $syncedCount = 0;
            $updatedCount = 0;

            foreach ($paymentMethods as $method) {
                // Skip if this is the config entry
                if ($method['paymentMethod'] === 'duitku_config') {
                    continue;
                }
                
                $paymentGateway = PaymentGateway::updateOrCreate(
                    ['code' => $method['paymentMethod']],
                    [
                        'name' => $method['paymentName'],
                        'icon_url' => $method['paymentImage'],
                        'is_active' => false, // Default inactive, admin can activate manually
                    ]
                );

                if ($paymentGateway->wasRecentlyCreated) {
                    $syncedCount++;
                } else {
                    $updatedCount++;
                }
            }

            $message = "Sinkronisasi berhasil! {$syncedCount} payment gateway baru ditambahkan";
            if ($updatedCount > 0) {
                $message .= ", {$updatedCount} payment gateway diperbarui";
            }
            $message .= ".";

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Request $request, $id)
    {
        $paymentGateway = PaymentGateway::findOrFail($id);
        $paymentGateway->update(['is_active' => !$paymentGateway->is_active]);

        $status = $paymentGateway->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Payment gateway {$paymentGateway->name} berhasil {$status}.");
    }

    public function update(Request $request, $id)
    {
        $paymentGateway = PaymentGateway::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'merchant_code' => 'required|string|max:255',
            'api_key' => 'nullable|string',
            'private_key' => 'nullable|string',
            'environment' => 'required|in:sandbox,production',
            'icon' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'callback_url' => 'nullable|url',
            'return_url' => 'nullable|url',
            'is_active' => 'required|boolean',
        ]);

        // Prepare update data
        $updateData = [
            'name' => $validated['name'],
            'merchant_code' => $validated['merchant_code'],
            'environment' => $validated['environment'],
            'callback_url' => $validated['callback_url'] ?? null,
            'return_url' => $validated['return_url'] ?? null,
            'is_active' => $validated['is_active'],
        ];

        // Update API Key only if provided
        if (!empty($validated['api_key'])) {
            $updateData['api_key'] = $validated['api_key'];
        }

        // Update Private Key only if provided
        if (!empty($validated['private_key'])) {
            $updateData['private_key'] = $validated['private_key'];
        }

        // Handle icon upload if new file provided
        if ($request->hasFile('icon')) {
            // Delete old icon if exists
            if ($paymentGateway->icon_url) {
                $oldIconPath = public_path('storage/payment-icons/' . $paymentGateway->icon_url);
                if (file_exists($oldIconPath)) {
                    unlink($oldIconPath);
                }
            }

            // Create directory if not exists
            $uploadPath = public_path('storage/payment-icons');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Generate unique filename
            $icon = $request->file('icon');
            $iconName = time() . '_' . $paymentGateway->code . '.' . $icon->getClientOriginalExtension();
            
            // Move uploaded file
            $icon->move($uploadPath, $iconName);
            
            // Save filename only
            $updateData['icon_url'] = $iconName;
        }

        $paymentGateway->update($updateData);

        return back()->with('success', "Payment gateway {$paymentGateway->name} berhasil diperbarui.");
    }

    public function fetchPaymentMethods()
    {
        // Get Duitku configuration from database
        $duitkuConfig = PaymentGateway::where('code', 'duitku_config')->first();

        if (!$duitkuConfig || !$duitkuConfig->merchant_code || !$duitkuConfig->api_key) {
            return response()->json([
                'success' => false,
                'message' => 'Konfigurasi Duitku belum diatur. Silakan atur konfigurasi terlebih dahulu.'
            ], 400);
        }

        $merchantCode = $duitkuConfig->merchant_code;
        $apiKey = $duitkuConfig->api_key;
        $environment = $duitkuConfig->environment;
        
        // Set API URL based on environment
        $apiUrl = $environment === 'production' 
            ? 'https://passport.duitku.com/webapi/api/merchant/paymentmethod/getpaymentmethod'
            : 'https://sandbox.duitku.com/webapi/api/merchant/paymentmethod/getpaymentmethod';

        // Generate datetime and signature according to Duitku API docs
        $datetime = date('Y-m-d H:i:s');
        $paymentAmount = 10000; // Amount required by API
        $signature = hash('sha256', $merchantCode . $paymentAmount . $datetime . $apiKey);

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($apiUrl, [
                'merchantcode' => $merchantCode,
                'amount' => $paymentAmount,
                'datetime' => $datetime,
                'signature' => $signature,
            ]);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data dari Duitku. HTTP Code: ' . $response->status()
                ], 500);
            }

            $responseData = $response->json();

            // Check response code
            if (!isset($responseData['responseCode']) || $responseData['responseCode'] !== '00') {
                $errorMsg = $responseData['responseMessage'] ?? 'Unknown error';
                return response()->json([
                    'success' => false,
                    'message' => 'Duitku API Error: ' . $errorMsg
                ], 500);
            }

            if (!isset($responseData['paymentFee']) || empty($responseData['paymentFee'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada payment method yang tersedia dari Duitku.'
                ], 404);
            }

            $paymentMethods = $responseData['paymentFee'];
            
            // Get Duitku gateway
            $duitkuGateway = PaymentGateway::where('code', 'duitku_config')->first();
            
            if (!$duitkuGateway) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi Duitku tidak ditemukan.'
                ], 404);
            }
            
            // Check which methods already exist in payment_methods table
            $existingCodes = PaymentMethod::where('payment_gateway_id', $duitkuGateway->id)
                ->whereIn('code', array_column($paymentMethods, 'paymentMethod'))
                ->pluck('code')
                ->toArray();

            // Mark existing methods
            foreach ($paymentMethods as &$method) {
                $method['exists'] = in_array($method['paymentMethod'], $existingCodes);
            }

            return response()->json([
                'success' => true,
                'data' => $paymentMethods
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function savePaymentMethods(Request $request)
    {
        $validated = $request->validate([
            'payment_methods' => 'required|array',
            'payment_methods.*.paymentMethod' => 'required|string',
            'payment_methods.*.paymentName' => 'required|string',
            'payment_methods.*.paymentImage' => 'required|string',
            'payment_methods.*.totalFee' => 'nullable|numeric',
        ]);

        // Get Duitku config to use as parent payment gateway
        $duitkuGateway = PaymentGateway::where('code', 'duitku_config')->first();
        
        if (!$duitkuGateway) {
            return response()->json([
                'success' => false,
                'message' => 'Konfigurasi Duitku tidak ditemukan. Silakan setup konfigurasi terlebih dahulu.'
            ], 400);
        }

        $savedCount = 0;
        $updatedCount = 0;

        foreach ($validated['payment_methods'] as $method) {
            $paymentMethod = PaymentMethod::updateOrCreate(
                [
                    'payment_gateway_id' => $duitkuGateway->id,
                    'code' => $method['paymentMethod']
                ],
                [
                    'name' => $method['paymentName'],
                    'image_url' => $method['paymentImage'],
                    'total_fee' => $method['totalFee'] ?? 0,
                    'is_active' => false, // Default inactive, admin can activate manually
                ]
            );

            if ($paymentMethod->wasRecentlyCreated) {
                $savedCount++;
            } else {
                $updatedCount++;
            }
        }

        $message = "Berhasil menyimpan payment methods! ";
        if ($savedCount > 0) {
            $message .= "{$savedCount} baru ditambahkan";
        }
        if ($updatedCount > 0) {
            if ($savedCount > 0) $message .= ", ";
            $message .= "{$updatedCount} diperbarui";
        }
        $message .= ".";

        return response()->json([
            'success' => true,
            'message' => $message,
            'saved' => $savedCount,
            'updated' => $updatedCount
        ]);
    }

    public function toggleMethodStatus($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->update(['is_active' => !$paymentMethod->is_active]);

        $status = $paymentMethod->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Payment method {$paymentMethod->name} berhasil {$status}.");
    }

    public function updateMethod(Request $request, $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        $validated = $request->validate([
            'fee_customer_flat' => 'required|numeric|min:0',
            'fee_customer_percent' => 'required|numeric|min:0|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $paymentMethod->update($validated);

        return back()->with('success', "Payment method {$paymentMethod->name} berhasil diperbarui.");
    }

    public function destroyMethod($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->delete();
        
        return back()->with('success', 'Payment method berhasil dihapus.');
    }

    public function massDestroyMethods(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer|exists:payment_methods,id'
        ]);

        $count = PaymentMethod::whereIn('id', $validated['ids'])->delete();

        return response()->json([
            'success' => true,
            'message' => "{$count} payment method berhasil dihapus.",
            'count' => $count
        ]);
    }


    public function fetchTripayPaymentChannels()
    {
        // Get Tripay configuration from database
        $tripayConfig = PaymentGateway::where('code', 'tripay_config')->first();

        if (!$tripayConfig || !$tripayConfig->api_key) {
            return response()->json([
                'success' => false,
                'message' => 'Konfigurasi Tripay belum diatur. Silakan atur konfigurasi terlebih dahulu.'
            ], 400);
        }

        $apiKey = $tripayConfig->api_key;
        $environment = $tripayConfig->environment;
        
        // Set API URL based on environment
        $apiUrl = $environment === 'production' 
            ? 'https://tripay.co.id/api/merchant/payment-channel'
            : 'https://tripay.co.id/api-sandbox/merchant/payment-channel';

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->get($apiUrl);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data dari Tripay. HTTP Code: ' . $response->status()
                ], 500);
            }

            $responseData = $response->json();

            // Check response success
            if (!isset($responseData['success']) || $responseData['success'] !== true) {
                $errorMsg = $responseData['message'] ?? 'Unknown error';
                return response()->json([
                    'success' => false,
                    'message' => 'Tripay API Error: ' . $errorMsg
                ], 500);
            }

            if (!isset($responseData['data']) || empty($responseData['data'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada payment channel yang tersedia dari Tripay.'
                ], 404);
            }

            $paymentChannels = $responseData['data'];
            
            // Get Tripay gateway
            $tripayGateway = PaymentGateway::where('code', 'tripay_config')->first();
            
            if (!$tripayGateway) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi Tripay tidak ditemukan.'
                ], 404);
            }
            
            // Check which channels already exist in payment_methods table
            $existingCodes = PaymentMethod::where('payment_gateway_id', $tripayGateway->id)
                ->whereIn('code', array_column($paymentChannels, 'code'))
                ->pluck('code')
                ->toArray();

            // Mark existing channels and format data
            foreach ($paymentChannels as &$channel) {
                $channel['exists'] = in_array($channel['code'], $existingCodes);
                // Format fee untuk display
                $channel['fee_merchant_display'] = 'Rp ' . number_format($channel['fee_merchant']['flat'], 0, ',', '.') . 
                    ($channel['fee_merchant']['percent'] > 0 ? ' + ' . $channel['fee_merchant']['percent'] . '%' : '');
                $channel['fee_customer_display'] = 'Rp ' . number_format($channel['fee_customer']['flat'], 0, ',', '.') . 
                    ($channel['fee_customer']['percent'] > 0 ? ' + ' . $channel['fee_customer']['percent'] . '%' : '');
            }

            return response()->json([
                'success' => true,
                'data' => $paymentChannels
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveTripayPaymentChannels(Request $request)
    {
        $validated = $request->validate([
            'payment_channels' => 'required|array',
            'payment_channels.*.code' => 'required|string',
            'payment_channels.*.name' => 'required|string',
            'payment_channels.*.group' => 'required|string',
            'payment_channels.*.icon_url' => 'required|string',
            'payment_channels.*.fee_merchant' => 'required|array',
            'payment_channels.*.fee_customer' => 'required|array',
            'payment_channels.*.total_fee' => 'required|array',
            'payment_channels.*.minimum_fee' => 'nullable|numeric',
            'payment_channels.*.maximum_fee' => 'nullable|numeric',
            'payment_channels.*.active' => 'required|in:true,false,1,0',
        ]);

        // Get Tripay config to use as parent payment gateway
        $tripayGateway = PaymentGateway::where('code', 'tripay_config')->first();
        
        if (!$tripayGateway) {
            return response()->json([
                'success' => false,
                'message' => 'Konfigurasi Tripay tidak ditemukan. Silakan setup konfigurasi terlebih dahulu.'
            ], 400);
        }

        $savedCount = 0;
        $updatedCount = 0;

        foreach ($validated['payment_channels'] as $channel) {
            // Convert active to proper boolean
            $isActive = filter_var($channel['active'], FILTER_VALIDATE_BOOLEAN);
            
            $paymentMethod = PaymentMethod::updateOrCreate(
                [
                    'payment_gateway_id' => $tripayGateway->id,
                    'code' => $channel['code']
                ],
                [
                    'name' => $channel['name'],
                    'image_url' => $channel['icon_url'],
                    'fee_merchant_flat' => $channel['fee_merchant']['flat'] ?? 0,
                    'fee_merchant_percent' => $channel['fee_merchant']['percent'] ?? 0,
                    'fee_customer_flat' => $channel['fee_customer']['flat'] ?? 0,
                    'fee_customer_percent' => $channel['fee_customer']['percent'] ?? 0,
                    'total_fee' => $channel['total_fee']['flat'] ?? 0,
                    'is_active' => $isActive,
                    'description' => 'Group: ' . $channel['group'] . 
                        ' | Min: Rp ' . number_format($channel['minimum_fee'] ?? 0, 0, ',', '.') . 
                        ' | Max: Rp ' . number_format($channel['maximum_fee'] ?? 0, 0, ',', '.'),
                ]
            );

            if ($paymentMethod->wasRecentlyCreated) {
                $savedCount++;
            } else {
                $updatedCount++;
            }
        }

        $message = "Berhasil menyimpan payment channels! ";
        if ($savedCount > 0) {
            $message .= "{$savedCount} baru ditambahkan";
        }
        if ($updatedCount > 0) {
            if ($savedCount > 0) $message .= ", ";
            $message .= "{$updatedCount} diperbarui";
        }
        $message .= ".";

        return response()->json([
            'success' => true,
            'message' => $message,
            'saved' => $savedCount,
            'updated' => $updatedCount
        ]);
    }
}
