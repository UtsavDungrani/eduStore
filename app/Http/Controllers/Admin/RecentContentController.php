<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class RecentContentController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.recent.index', compact('products'));
    }

    public function update(Request $request)
    {
        $allIds = $request->input('all_ids', []);
        $recentIds = $request->input('recent_ids', []);

        // Reset recent status ONLY for products on this page
        if (!empty($allIds)) {
            Product::whereIn('id', $allIds)->update(['is_recent' => false]);
        }

        // Set selected as recent
        if (!empty($recentIds)) {
            Product::whereIn('id', $recentIds)->update(['is_recent' => true]);
        }

        return redirect()->back()->with('success', 'Recently added content updated successfully.');
    }
}
