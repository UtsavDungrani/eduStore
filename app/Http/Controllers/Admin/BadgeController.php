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
            'badge_strip_text' => 'nullable|string|in:' . implode(',', \App\Models\Product::BADGE_STRIP_OPTIONS),
            'badge_strip_color' => 'required_if:badge_strip_text,!null|string|in:' . implode(',', array_keys(\App\Models\Product::BADGE_STRIP_COLORS)),
        ]);

        if (auth()->user()->hasRole('Instructor') && $product->user_id !== auth()->id()) {
            abort(403);
        }

        $product->update([
            'badge_strip_text' => $request->badge_strip_text,
            'badge_strip_color' => $request->badge_strip_color ?? 'golden',
        ]);

        return redirect()->route('admin.badges.index')->with('success', 'Badge updated successfully.');
    }
}
