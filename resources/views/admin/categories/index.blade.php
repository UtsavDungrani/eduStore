@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Categories</h1>
    <p class="text-gray-500">Organize your content into sections.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Add Category Form -->
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 italic">
            <h3 class="font-bold text-gray-900 mb-6">Create New Category</h3>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Category Name</label>
                    <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary" placeholder="e.g. Assignments">
                </div>
                <button type="submit" class="w-full bg-primary text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-md">Add Category</button>
            </form>
        </div>
    </div>

    <!-- Category List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left font-medium">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-[10px] uppercase font-bold">
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Slug</th>
                        <th class="px-6 py-4">Products</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-gray-900 font-bold whitespace-nowrap">{{ $category->name }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $category->slug }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-50 text-primary px-2 py-1 rounded-md text-xs font-bold">{{ $category->products_count }} items</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete category?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-all">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
