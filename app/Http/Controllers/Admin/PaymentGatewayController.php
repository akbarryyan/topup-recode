<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $paymentGateways = PaymentGateway::orderBy('name')->get();
        return view('admin.payment-gateway.index', compact('paymentGateways'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'merchant_code' => 'required|string|max:255',
            'api_key' => 'required|string',
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

        $datetime = now()->toDateTimeString();
        $signature = hash('sha256', $merchantCode . $datetime . $apiKey);

        try {
            $response = Http::post($apiUrl, [
                'merchantcode' => $merchantCode,
                'datetime' => $datetime,
                'signature' => $signature,
            ]);

            if ($response->failed() || !isset($response->json()['paymentFee'])) {
                return back()->with('error', 'Gagal mengambil data dari Duitku. Response: ' . $response->body());
            }

            $paymentMethods = $response->json()['paymentFee'];
            $syncedCount = 0;

            foreach ($paymentMethods as $method) {
                // Skip if this is the config entry
                if ($method['paymentMethod'] === 'duitku_config') {
                    continue;
                }
                
                PaymentGateway::updateOrCreate(
                    ['code' => $method['paymentMethod']],
                    [
                        'name' => $method['paymentName'],
                        'icon_url' => $method['paymentImage'],
                        'type' => 'Duitku',
                        'group' => 'Payment Gateway',
                    ]
                );
                $syncedCount++;
            }

            return back()->with('success', $syncedCount . ' payment gateway berhasil disinkronisasi.');
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
}
