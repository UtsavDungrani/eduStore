@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-primary mb-2 inline-block"><i class="fas fa-arrow-left"></i> Back to Products</a>
    <h1 class="text-3xl font-bold text-gray-900">Edit Product: {{ $product->title }}</h1>
</div>

<div class="max-w-7xl">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-red-800">There were {{ $errors->count() }} errors with your submission</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content Area -->
            <div class="flex-1 space-y-6">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Product Title</label>
                        <input type="text" name="title" value="{{ old('title', $product->title) }}" required class="w-full px-4 py-3 rounded-xl border {{ $errors->has('title') ? 'border-red-500' : 'border-gray-200' }} focus:ring-primary focus:border-primary" placeholder="e.g. Engineering Drawing Assignment">
                        @error('title')<p class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Category</label>
                        <select name="category_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Current Selling Price (INR)</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required class="w-full px-4 py-3 rounded-xl border {{ $errors->has('price') ? 'border-red-500' : 'border-gray-200' }} focus:ring-primary focus:border-primary">
                        @error('price')<p class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Original Price (MRP) <span class="text-xs font-normal text-gray-400">(Optional)</span></label>
                        <input type="number" step="0.01" name="original_price" value="{{ old('original_price', $product->original_price) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Offer Price (Reference)</label>
                        <input type="number" step="0.01" name="offer_price" value="{{ old('offer_price', $product->offer_price) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
                    </div>

                    <div class="md:col-span-2 bg-gray-50/50 p-6 rounded-2xl border border-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <input type="hidden" name="sale_display_mode" value="percentage">
                            <div id="sale_percentage_container">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Sale Percentage (%)</label>
                                <input type="number" name="sale_percentage" value="{{ old('sale_percentage', $product->sale_percentage) }}" min="0" max="100" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary" placeholder="e.g. 20">
                            </div>
                        </div>


                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                        <textarea name="description" required rows="5" class="w-full px-4 py-3 rounded-xl border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-200' }} focus:ring-primary focus:border-primary" placeholder="Full details about the content...">{{ old('description', $product->description) }}</textarea>
                        @error('description')<p class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</p>@enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Product File <span class="text-xs font-normal text-gray-400">(Leave blank to keep current)</span></label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-2xl relative group hover:border-primary transition-colors">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-file-alt text-4xl text-gray-300 group-hover:text-primary transition-colors"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="product_file" class="relative cursor-pointer rounded-md font-bold text-primary hover:text-amber-700 focus-within:outline-none">
                                        <span>Upload new file</span>
                                        <input id="product_file" name="product_file" type="file" accept=".pdf" class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PDF ONLY up to 300MB</p>
                            </div>
                        </div>
                        <div id="file-name" class="mt-2 text-sm font-bold text-primary italic truncate">Current: {{ basename($product->file_path) }}</div>
                        @error('product_file')<p class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</p>@enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Cover Image <span class="text-xs font-normal text-gray-400">(Leave blank to keep current)</span></label>
                        <div class="flex flex-col sm:flex-row items-start gap-4">
                            @if($product->image_path)
                                <div class="w-32 h-40 rounded-xl overflow-hidden border border-gray-200 flex-shrink-0 shadow-sm">
                                    <img src="{{ $product->image_url }}" alt="Current image" class="w-full h-full object-cover">
                                </div>
                            @endif
                            <div class="flex-grow w-full">
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-2xl relative group hover:border-primary transition-colors">
                                    <div class="space-y-1 text-center">
                                        <i class="fas fa-image text-4xl text-gray-300 group-hover:text-primary transition-colors"></i>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="cover_image" class="relative cursor-pointer rounded-md font-bold text-primary hover:text-amber-700 focus-within:outline-none">
                                                <span>Upload new cover</span>
                                                <input id="cover_image" name="cover_image" type="file" accept="image/*" class="sr-only">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">JPG, PNG up to 2MB</p>
                                    </div>
                                </div>
                                <div id="image-name" class="mt-2 text-sm font-bold text-primary italic">@if($product->image_path) Keep current image @else No image set @endif</div>
                                @error('cover_image')<p class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="w-full lg:w-80 space-y-6">
                <!-- Status & Visibility Card -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-900 mb-5 pb-2 border-b border-gray-50 flex items-center gap-2">
                        <i class="fas fa-cog text-primary"></i> settings
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer group">
                            <div class="mt-1">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="w-5 h-5 rounded text-primary focus:ring-primary border-gray-300">
                            </div>
                            <label for="is_active" class="cursor-pointer">
                                <span class="block text-sm font-bold text-gray-700">Live Status</span>
                                <span class="text-[10px] text-gray-400 group-hover:text-gray-500">Make this product visible to everyone</span>
                            </label>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer group">
                            <div class="mt-1">
                                <input type="checkbox" name="is_downloadable" id="is_downloadable" value="1" {{ $product->is_downloadable ? 'checked' : '' }} class="w-5 h-5 rounded text-primary focus:ring-primary border-gray-300">
                            </div>
                            <label for="is_downloadable" class="cursor-pointer">
                                <span class="block text-sm font-bold text-gray-700">Allow Download</span>
                                <span class="text-[10px] text-gray-400 group-hover:text-gray-500">Permit users to download the file</span>
                            </label>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer group">
                            <div class="mt-1">
                                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="w-5 h-5 rounded text-primary focus:ring-primary border-gray-300">
                            </div>
                            <label for="is_featured" class="cursor-pointer">
                                <span class="block text-sm font-bold text-gray-700">Is Featured</span>
                                <span class="text-[10px] text-gray-400 group-hover:text-gray-500">Show in featured suggestions</span>
                            </label>
                        </div>

                        <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer group">
                            <div class="mt-1">
                                <input type="checkbox" name="is_demo" id="is_demo" value="1" {{ $product->is_demo ? 'checked' : '' }} class="w-5 h-5 rounded text-primary focus:ring-primary border-gray-300">
                            </div>
                            <label for="is_demo" class="cursor-pointer">
                                <span class="block text-sm font-bold text-gray-700">Set as Demo/Free</span>
                                <span class="text-[10px] text-gray-400 group-hover:text-gray-500">Allow full access without payment</span>
                            </label>
                        </div>


                        <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer group">
                            <div class="mt-1">
                                <input type="checkbox" name="is_recent" id="is_recent" value="1" {{ $product->is_recent ? 'checked' : '' }} class="w-5 h-5 rounded text-primary focus:ring-primary border-gray-300">
                            </div>
                            <label for="is_recent" class="cursor-pointer">
                                <span class="block text-sm font-bold text-gray-700">Recently Added</span>
                                <span class="text-[10px] text-gray-400 group-hover:text-gray-500">Mark as a fresh new arrival</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-50 space-y-3">
                        <button type="submit" class="w-full bg-primary text-white py-4 rounded-2xl font-bold hover:bg-amber-900 transition-all shadow-lg flex items-center justify-center gap-2">
                            <i class="fas fa-save shadow-sm"></i> Update Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="block w-full text-center py-4 rounded-2xl font-bold bg-gray-50 text-gray-500 hover:bg-gray-100 transition-all border border-gray-100">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('product_file').addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            document.getElementById('file-name').textContent = 'New File: ' + e.target.files[0].name;
        }
    });

    document.getElementById('cover_image').addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            document.getElementById('image-name').textContent = 'New Image: ' + e.target.files[0].name;
        }
    });

    const isDemoCheckbox = document.querySelector('input[name="is_demo"]');
    const priceInput = document.querySelector('input[name="price"]');
    const priceLabel = priceInput.closest('div').querySelector('label');

    function togglePrice() {
        if (isDemoCheckbox.checked) {
            priceInput.removeAttribute('required');
            priceLabel.innerHTML = 'Current Selling Price (INR) <span class="text-xs font-normal text-gray-400">(Optional for Demo)</span>';
        } else {
            priceInput.setAttribute('required', 'required');
            priceLabel.textContent = 'Current Selling Price (INR)';
        }
    }

    isDemoCheckbox.addEventListener('change', togglePrice);
    // Initialize
    togglePrice();

    // No extra JS needed for Sale Display Mode anymore
</script>
@endsection
