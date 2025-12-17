<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Jobs\SendPromoNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\CacheService;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::latest()->get();
        return view('cashier.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $path = $request->file('image')->store('banners', 'public');

        $banner = Banner::create([
            'image_path' => $path,
            'title' => $request->title,
            'description' => $request->description,
            'is_active' => true,
        ]);

        // Send email notification if checkbox is checked
        if ($request->has('send_notification')) {
            SendPromoNotification::dispatch('banner', [
                'title' => $banner->title ?? 'Promo Baru!',
                'description' => $banner->description,
                'image_url' => $banner->image_url,
            ]);
        }

        CacheService::clearMenuCache();

        return redirect()->route('banners.index')->with('success', 'Banner berhasil ditambahkan.');
    }

    public function destroy(Banner $banner)
    {
        // Delete file from storage
        $filename = basename($banner->image_path);
        Storage::disk('public')->delete('banners/' . $filename);
        
        $banner->delete();

        CacheService::clearMenuCache();

        return redirect()->route('banners.index')->with('success', 'Banner berhasil dihapus.');
    }

    public function toggle(Banner $banner)
    {
        $banner->is_active = !$banner->is_active;
        $banner->save();

        CacheService::clearMenuCache();
        
        return redirect()->back()->with('success', 'Status banner diperbarui.');
    }
}
