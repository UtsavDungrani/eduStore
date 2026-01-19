<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories']);
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);
        return back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:255|unique:categories,name,' . $category->id]);
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);
        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with products.');
        }
        $category->delete();
        return back()->with('success', 'Category deleted successfully.');
    }
}
