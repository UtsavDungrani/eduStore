<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageService;
use App\Models\ProductDemoImage;

class ProductController extends Controller
{
    public function index()
    {
        $query = Product::with('category');
        
        if (auth()->user()->hasRole('Instructor')) {
            $query->where('user_id', auth()->id());
        }

        $products = $query->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $badge_options = Product::BADGE_STRIP_OPTIONS;
        $badge_colors = Product::BADGE_STRIP_COLORS;
        return view('admin.products.create', compact('categories', 'badge_options', 'badge_colors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
            'sale_tag' => 'nullable|string|max:50',
            'product_file' => 'required|file|mimes:pdf|max:307200', // 300MB
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // 2MB
            'demo_images' => 'required|array|min:1',
            'demo_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
            'is_downloadable' => 'boolean',
            'is_featured' => 'boolean',
            'is_demo' => 'boolean',
            'is_recent' => 'boolean',
            'sale_percentage' => 'nullable|integer|min:0|max:100',
            'sale_display_mode' => 'nullable|string|in:tag,percentage',
            'badge_strip_text' => 'nullable|string|in:' . implode(',', \App\Models\Product::BADGE_STRIP_OPTIONS),
            'badge_strip_color' => 'nullable|string|in:' . implode(',', array_keys(\App\Models\Product::BADGE_STRIP_COLORS)),
        ]);

        $filePath = $request->file('product_file')->store('products', 'private');
        $imagePath = $request->hasFile('cover_image') 
            ? ImageService::compressAndStore($request->file('cover_image'), 'product_covers', 'public') 
            : null;

        if (auth()->user()->hasRole('Instructor')) {
            $user_id = auth()->id();
        } else {
            $user_id = $request->user_id;
        }

        $product = Product::create([
            'category_id' => $request->category_id,
            'user_id' => $user_id,
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
            'is_featured' => $request->has('is_featured'),
            'is_demo' => $request->has('is_demo'),
            'is_recent' => $request->has('is_recent'),
            'sale_percentage' => $request->sale_percentage,
            'sale_display_mode' => 'percentage',
            'badge_strip_text' => $request->badge_strip_text,
            'badge_strip_color' => $request->badge_strip_color ?? 'golden',
        ]);

        if ($request->hasFile('demo_images')) {
            foreach ($request->file('demo_images') as $index => $image) {
                $path = ImageService::compressAndStore($image, 'product_demos', 'public');
                $product->demoImages()->create([
                    'image_path' => $path,
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        if (auth()->user()->hasRole('Instructor') && $product->user_id !== auth()->id()) {
            abort(403);
        }

        $categories = Category::all();
        $badge_options = Product::BADGE_STRIP_OPTIONS;
        $badge_colors = Product::BADGE_STRIP_COLORS;
        return view('admin.products.edit', compact('product', 'categories', 'badge_options', 'badge_colors'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
            'sale_tag' => 'nullable|string|max:50',
            'product_file' => 'nullable|file|mimes:pdf|max:307200',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'demo_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'remove_demo_images.*' => 'nullable|exists:product_demo_images,id',
            'is_active' => 'boolean',
            'is_downloadable' => 'boolean',
            'is_featured' => 'boolean',
            'is_demo' => 'boolean',
            'is_recent' => 'boolean',
            'sale_percentage' => 'nullable|integer|min:0|max:100',
            'sale_display_mode' => 'nullable|string|in:tag,percentage',
            'badge_strip_text' => 'nullable|string|in:' . implode(',', \App\Models\Product::BADGE_STRIP_OPTIONS),
            'badge_strip_color' => 'nullable|string|in:' . implode(',', array_keys(\App\Models\Product::BADGE_STRIP_COLORS)),
        ]);

        // Custom Validation: Ensure at least one demo image remains
        $currentImagesCount = $product->demoImages()->count();
        $removeImagesCount = $request->has('remove_demo_images') ? count($request->remove_demo_images) : 0;
        $newImagesCount = $request->hasFile('demo_images') ? count($request->file('demo_images')) : 0;

        if (($currentImagesCount - $removeImagesCount + $newImagesCount) < 1) {
            return back()->withErrors(['demo_images' => 'At least one demo image is required.'])->withInput();
        }

        if (auth()->user()->hasRole('Instructor') && $product->user_id !== auth()->id()) {
            abort(403);
        }

        if (auth()->user()->hasRole('Instructor')) {
            $user_id = auth()->id();
        } else {
            $user_id = $request->user_id;
        }

        $data = [
            'category_id' => $request->category_id,
            'user_id' => $user_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . $product->id,
            'description' => $request->description,
            'price' => $request->price ?? 0,
            'original_price' => $request->original_price,
            'offer_price' => $request->offer_price,
            'sale_tag' => $request->sale_tag,
            'is_active' => $request->has('is_active'),
            'is_downloadable' => $request->has('is_downloadable'),
            'is_featured' => $request->has('is_featured'),
            'is_demo' => $request->has('is_demo'),
            'is_recent' => $request->has('is_recent'),
            'sale_percentage' => $request->sale_percentage,
            'sale_display_mode' => 'percentage',
            'badge_strip_text' => $request->badge_strip_text,
            'badge_strip_color' => $request->badge_strip_color ?? 'golden',
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
            $data['image_path'] = ImageService::compressAndStore($request->file('cover_image'), 'product_covers', 'public');
        }

        $product->update($data);

        // Remove selected demo images
        if ($request->has('remove_demo_images')) {
            $imagesToRemove = ProductDemoImage::whereIn('id', $request->remove_demo_images)
                ->where('product_id', $product->id)
                ->get();

            foreach ($imagesToRemove as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

        // Upload new demo images
        if ($request->hasFile('demo_images')) {
            $lastOrder = $product->demoImages()->max('order') ?? -1;
            foreach ($request->file('demo_images') as $index => $image) {
                $path = ImageService::compressAndStore($image, 'product_demos', 'public');
                $product->demoImages()->create([
                    'image_path' => $path,
                    'order' => $lastOrder + $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if (auth()->user()->hasRole('Instructor') && $product->user_id !== auth()->id()) {
            abort(403);
        }

        Storage::disk('private')->delete($product->file_path);
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
