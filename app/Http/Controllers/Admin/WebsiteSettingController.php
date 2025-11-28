<?php

namespace App\Http\Controllers\Admin;

use App\Models\WebsiteSetting;
use App\Models\MaintenanceSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class WebsiteSettingController extends Controller
{
    public function index()
    {
        $settings = WebsiteSetting::all()->keyBy('key');
        $maintenanceSetting = MaintenanceSetting::current();

        return view('admin.website-settings.index', compact('settings', 'maintenanceSetting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'website_name' => 'required|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'website_description' => 'nullable|string',
            'website_phone' => 'nullable|string|max:20',
            'website_address' => 'nullable|string|max:500',
            'website_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            // Update website name
            WebsiteSetting::set('website_name', $request->website_name, 'text');

            // Update slogan
            WebsiteSetting::set('slogan', $request->slogan, 'text');

            // Update website description
            WebsiteSetting::set('website_description', $request->website_description, 'textarea');

            // Update phone
            WebsiteSetting::set('website_phone', $request->website_phone, 'text');

            // Update address
            WebsiteSetting::set('website_address', $request->website_address, 'textarea');

            // Handle logo upload
            if ($request->hasFile('website_logo')) {
                $logo = $request->file('website_logo');
                
                // Delete old logo if exists
                $oldLogo = WebsiteSetting::where('key', 'website_logo')->first();
                if ($oldLogo && $oldLogo->value && Storage::exists($oldLogo->value)) {
                    Storage::delete($oldLogo->value);
                }

                // Store new logo
                $path = $logo->store('website-settings', 'public');
                WebsiteSetting::set('website_logo', $path, 'image');
            }

            return redirect()->back()->with('success', 'Pengaturan website berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui pengaturan: ' . $e->getMessage());
        }
    }

    public function deleteLogo()
    {
        try {
            $logo = WebsiteSetting::where('key', 'website_logo')->first();
            
            if ($logo && $logo->value && Storage::exists($logo->value)) {
                Storage::delete($logo->value);
                $logo->delete();
                
                return response()->json(['success' => true, 'message' => 'Logo berhasil dihapus']);
            }

            return response()->json(['success' => false, 'message' => 'Logo tidak ditemukan']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
