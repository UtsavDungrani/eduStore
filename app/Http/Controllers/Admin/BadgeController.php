<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function index()
    {
        $query = Product::with('category');
        
        if (auth()->user()->hasRole('Instructor')) {
            $query->where('user_id', auth()->id());
        }

        $products = $query->latest()->paginate(15);
        return view('admin.badges.index', compact('products'));
    }

    public function edit(Product $product)
    {
        if (auth()->user()->hasRole('Instructor') && $product->user_id !== auth()->id()) {
            abort(403);
        }

        return view('admin.badges.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'highlight_badge' => 'nullable|string|in:' . implode(',', \App\Models\Product::HIGHLIGHT_BADGE_OPTIONS),
            'highlight_badge_shape' => 'required|string|in:' . implode(',', array_keys(\App\Models\Product::HIGHLIGHT_BADGE_SHAPES)),
            'highlight_badge_color' => 'required|string|in:' . implode(',', array_keys(\App\Models\Product::HIGHLIGHT_BADGE_COLORS)),
        ]);

        if (auth()->user()->hasRole('Instructor') && $product->user_id !== auth()->id()) {
            abort(403);
        }

        $product->update([
            'highlight_badge' => $request->highlight_badge,
            'highlight_badge_shape' => $request->highlight_badge_shape ?? 'pill',
            'highlight_badge_color' => $request->highlight_badge_color ?? 'golden',
        ]);

        return redirect()->route('admin.badges.index')->with('success', 'Badge updated successfully.');
    }
}
