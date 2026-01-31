<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class FeaturedContentController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.featured.index', compact('products'));
    }

    public function update(Request $request)
    {
        $allIds = $request->input('all_ids', []);
        $featuredIds = $request->input('featured_ids', []);

        // Reset featured status ONLY for products on this page
        if (!empty($allIds)) {
            Product::whereIn('id', $allIds)->update(['is_featured' => false]);
        }

        // Set selected as featured
        if (!empty($featuredIds)) {
            Product::whereIn('id', $featuredIds)->update(['is_featured' => true]);
        }

        return redirect()->back()->with('success', 'Featured content updated successfully.');
    }
}
