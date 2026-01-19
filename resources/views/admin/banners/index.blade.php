@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Banners</h1>
    <p class="text-gray-500">Manage home page slides and promotions.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Add Banner Form -->
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 italic">
            <h3 class="font-bold text-gray-900 mb-6">Add New Banner</h3>
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Banner Image (1200x400 recommended)</label>
                    <input type="file" name="image" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Title (Optional)</label>
                    <input type="text" name="title" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary" placeholder="e.g. Flash Sale!">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Link (Optional)</label>
                    <input type="text" name="link" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary" placeholder="e.g. /products?category=notes">
                </div>
                <button type="submit" class="w-full bg-primary text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-md">Upload Banner</button>
            </form>
        </div>
    </div>

    <!-- Banner List -->
    <div class="lg:col-span-2 space-y-4">
        @forelse($banners as $banner)
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-6 items-center">
                <div class="w-full md:w-32 h-20 rounded-xl overflow-hidden flex-shrink-0">
                    <img src="{{ $banner->image_url }}" class="w-full h-full object-cover" alt="{{ $banner->title }}">
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h4 class="font-bold text-gray-900">{{ $banner->title ?? 'Untitled Banner' }}</h4>
                    <p class="text-xs text-gray-400 truncate max-w-xs">{{ $banner->link }}</p>
                    <p class="text-xs text-gray-300 mt-1" title="{{ $banner->image_url }}">Path: {{ Str::limit($banner->image_path, 40) }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" name="is_active" value="{{ $banner->is_active ? '0' : '1' }}" class="text-xs font-bold uppercase {{ $banner->is_active ? 'text-green-500' : 'text-gray-300' }}">
                            {{ $banner->is_active ? 'Active' : 'Hidden' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Delete banner?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-500 p-2 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-12 text-center text-gray-400 italic">
                No banners added yet.
            </div>
        @endforelse
    </div>
</div>
@endsection
