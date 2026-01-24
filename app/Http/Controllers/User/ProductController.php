<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function home()
    {
        $banners = Banner::where('is_active', true)->orderBy('order')->get();
        $categories = Category::all();
        $featuredProducts = Product::where('is_active', true)->where('is_featured', true)->with('category')->latest()->take(8)->get();
        
        $purchasedProducts = collect([]);
        $allProductIds = [];
        $productCategories = [];

        if (auth()->check()) {
            $purchasedProducts = auth()->user()->purchasedProducts ?? collect([]); 
            
            // For Recently Viewed validation
            $allProductIds = Product::where('is_active', true)->pluck('id')->toArray();
            $productCategories = Product::with('category')->get()->mapWithKeys(function ($product) {
                return [$product->id => $product->category->name ?? 'Uncategorized'];
            })->toArray();
        }

        $recentlyAddedProducts = Product::where('is_active', true)->where('is_recent', true)->latest()->take(8)->get();

        return view('user.landing', compact('banners', 'categories', 'featuredProducts', 'purchasedProducts', 'allProductIds', 'productCategories', 'recentlyAddedProducts'));
    }

    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with('category');

        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(12);
        $categories = Category::all();

        return view('user.products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        return view('user.products.show', compact('product'));
    }
}
