<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
            'sale_tag' => 'nullable|string|max:50',
            'product_file' => 'required|file|mimes:pdf,zip,jpg,png,doc,docx|max:20480', // 20MB
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB
            'is_active' => 'boolean',
            'is_downloadable' => 'boolean',
            'is_demo' => 'boolean',
        ]);

        $filePath = $request->file('product_file')->store('products', 'private');
        $imagePath = $request->hasFile('cover_image') ? $request->file('cover_image')->store('product_covers', 'public') : null;

        Product::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . rand(1000, 9999),
            'description' => $request->description,
            'price' => $request->price ?? 0,
            'original_price' => $request->original_price,
            'offer_price' => $request->offer_price,
            'sale_tag' => $request->sale_tag,
            'file_path' => $filePath,
            'image_path' => $imagePath,
            'is_active' => $request->has('is_active'),
            'is_downloadable' => $request->has('is_downloadable'),
            'is_demo' => $request->has('is_demo'),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
            'sale_tag' => 'nullable|string|max:50',
            'product_file' => 'nullable|file|mimes:pdf,zip,jpg,png,doc,docx|max:20480',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
            'is_downloadable' => 'boolean',
            'is_demo' => 'boolean',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . $product->id,
            'description' => $request->description,
            'price' => $request->price ?? 0,
            'original_price' => $request->original_price,
            'offer_price' => $request->offer_price,
            'sale_tag' => $request->sale_tag,
            'is_active' => $request->has('is_active'),
            'is_downloadable' => $request->has('is_downloadable'),
            'is_demo' => $request->has('is_demo'),
        ];

        if ($request->hasFile('product_file')) {
            // Delete old file
            Storage::disk('private')->delete($product->file_path);
            $data['file_path'] = $request->file('product_file')->store('products', 'private');
        }

        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('cover_image')->store('product_covers', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        Storage::disk('private')->delete($product->file_path);
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
