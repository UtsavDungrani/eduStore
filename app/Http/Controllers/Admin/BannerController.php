<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
            'title' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
        ]);

        $path = $request->file('image')->store('banners', 'public');

        Banner::create([
            'image_path' => $path,
            'title' => $request->title,
            'link' => $request->link,
            'order' => Banner::max('order') + 1,
        ]);

        return back()->with('success', 'Banner added successfully.');
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if ($request->has('title')) {
            $banner->title = $request->title;
        }
        if ($request->has('link')) {
            $banner->link = $request->link;
        }
        if ($request->has('is_active')) {
            $banner->is_active = $request->boolean('is_active');
        }
        
        $banner->save();

        return back()->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        Storage::disk('public')->delete($banner->image_path);
        $banner->delete();
        return back()->with('success', 'Banner deleted successfully.');
    }

    public function reorder(Request $request)
    {
        foreach ($request->orders as $id => $order) {
            Banner::where('id', $id)->update(['order' => $order]);
        }
        return response()->json(['success' => true]);
    }
}
