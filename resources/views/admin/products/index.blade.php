@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Products</h1>
        <p class="text-gray-500">Manage your digital content catalog.</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 transition-all flex items-center gap-2">
        <i class="fas fa-plus"></i> Add Product
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-[10px] uppercase font-bold">
                    <th class="px-6 py-4">Title</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Price</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 text-primary rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">{{ $product->title }}</div>
                                    <div class="text-xs text-gray-400">{{ $product->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg text-xs font-medium">{{ $product->category->name }}</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900">₹{{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4">
                            @if($product->is_active)
                                <span class="flex items-center gap-1.5 text-green-600 font-bold text-xs uppercase">
                                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Active
                                </span>
                            @else
                                <span class="flex items-center gap-1.5 text-gray-400 font-bold text-xs uppercase">
                                    <span class="w-2 h-2 bg-gray-300 rounded-full"></span> Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="p-2 text-gray-400 hover:text-primary transition-colors hover:bg-gray-100 rounded-lg">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors hover:bg-gray-100 rounded-lg">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">No products found. Start by creating one.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
        <div class="p-6 bg-gray-50/50 border-t border-gray-50">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
