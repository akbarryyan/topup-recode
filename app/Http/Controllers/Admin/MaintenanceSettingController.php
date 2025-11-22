<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceSetting;
use Illuminate\Http\Request;

class MaintenanceSettingController extends Controller
{
    public function update(Request $request)
    {
        $data = $request->validate([
            'is_active' => ['nullable', 'boolean'],
            'title' => ['required', 'string', 'max:255'],
            'message' => ['nullable', 'string'],
            'button_text' => ['nullable', 'string', 'max:100'],
            'button_url' => ['nullable', 'string', 'max:255'],
        ]);

        $data['is_active'] = $request->boolean('is_active', false);

        $setting = MaintenanceSetting::current();
        $setting->update($data);

        return redirect()->back()->with('success', 'Pengaturan maintenance berhasil diperbarui.');
    }
}
