<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class RecentContentController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        return view('admin.recent.index', compact('products'));
    }

    public function update(Request $request)
    {
        $recentIds = $request->input('recent_ids', []);

        // Reset all recent status
        Product::query()->update(['is_recent' => false]);

        // Set selected as recent
        if (!empty($recentIds)) {
            Product::whereIn('id', $recentIds)->update(['is_recent' => true]);
        }

        return redirect()->back()->with('success', 'Recently added content updated successfully.');
    }
}
