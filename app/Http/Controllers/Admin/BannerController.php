<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'link' => 'nullable|url',
            'sort_order' => 'nullable|integer',
        ]);

        $banner = new Banner();
        $banner->title = $request->title;
        $banner->link = $request->link;
        $banner->sort_order = $request->sort_order ?? 0;
        $banner->is_active = $request->has('is_active');

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Make sure directory exists
            $directory = storage_path('app/public/banners');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Move uploaded file directly
            $image->move($directory, $imageName);
            
            // Verify file was created
            $filePath = $directory . '/' . $imageName;
            Log::info('Banner image uploaded', [
                'filename' => $imageName,
                'file_exists' => file_exists($filePath),
                'file_path' => $filePath,
                'file_size' => file_exists($filePath) ? filesize($filePath) : 0
            ]);
            
            $banner->image = $imageName;
        }

        $banner->save();

        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'link' => 'nullable|url',
            'sort_order' => 'nullable|integer',
        ]);

        $banner->title = $request->title;
        $banner->link = $request->link;
        $banner->sort_order = $request->sort_order ?? 0;
        $banner->is_active = $request->has('is_active');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->image && Storage::exists('public/banners/' . $banner->image)) {
                Storage::delete('public/banners/' . $banner->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/banners', $imageName);
            $banner->image = $imageName;
        }

        $banner->save();

        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil diupdate');
    }

    public function toggleStatus($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->is_active = !$banner->is_active;
        $banner->save();

        return response()->json([
            'success' => true,
            'message' => 'Status banner berhasil diubah',
            'is_active' => $banner->is_active
        ]);
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        // Delete image file
        if ($banner->image && Storage::exists('public/banners/' . $banner->image)) {
            Storage::delete('public/banners/' . $banner->image);
        }

        $banner->delete();

        return response()->json([
            'success' => true,
            'message' => 'Banner berhasil dihapus'
        ]);
    }
}
