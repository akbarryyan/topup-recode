<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VipResellerSetting;
use Illuminate\Http\Request;
use App\Services\VipResellerService;

class VipResellerSettingController extends Controller
{
    public function index()
    {
        $setting = VipResellerSetting::first();

        return view('admin.vip-reseller-settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'api_url' => ['required', 'url'],
            'api_id' => ['required', 'string', 'max:255'],
            'api_key' => ['required', 'string', 'max:255'],
            'sign' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $setting = VipResellerSetting::first();

        if ($setting) {
            $setting->update($validated);
        } else {
            $setting = VipResellerSetting::create($validated);
        }

        VipResellerSetting::refreshCache();

        return redirect()->back()->with('success', 'Konfigurasi VIP Reseller berhasil disimpan.');
    }

    public function checkProfile(VipResellerService $vipResellerService)
    {
        $response = $vipResellerService->getProfile();

        if ($response['success']) {
            return redirect()->back()->with([
                'profile' => $response['data'],
                'profile_message' => $response['message'] ?? 'Profil berhasil diambil.',
            ]);
        }

        return redirect()->back()->with('profile_error', $response['message'] ?? 'Gagal mengambil profil.');
    }
}
