@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-primary mb-2 inline-block"><i class="fas fa-arrow-left"></i> Back to Products</a>
    <h1 class="text-3xl font-bold text-gray-900">Edit Product: {{ $product->title }}</h1>
</div>

<div class="max-w-4xl">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PATCH')
        
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-2">Product Title</label>
                <input type="text" name="title" value="{{ old('title', $product->title) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary" placeholder="e.g. Engineering Drawing Assignment">
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
                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Original Price (MRP) <span class="text-xs font-normal text-gray-400">(Optional)</span></label>
                <input type="number" step="0.01" name="original_price" value="{{ old('original_price', $product->original_price) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Offer Price (Ref)</label>
                <input type="number" step="0.01" name="offer_price" value="{{ old('offer_price', $product->offer_price) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Sale Badge Tag</label>
                <input type="text" name="sale_tag" value="{{ old('sale_tag', $product->sale_tag) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary" placeholder="HOT, SALE">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="5" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-primary focus:border-primary" placeholder="Full details about the content...">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-2">Product File (Optional - Leave blank to keep current file)</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-2xl relative group hover:border-primary transition-colors">
                    <div class="space-y-1 text-center">
                        <i class="fas fa-file-alt text-4xl text-gray-300 group-hover:text-primary transition-colors"></i>
                        <div class="flex text-sm text-gray-600">
                            <label for="product_file" class="relative cursor-pointer rounded-md font-bold text-primary hover:text-blue-500 focus-within:outline-none">
                                <span>Upload new file</span>
                                <input id="product_file" name="product_file" type="file" class="sr-only">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PDF, ZIP, DOC up to 300MB</p>
                    </div>
                </div>
                <div id="file-name" class="mt-2 text-sm font-bold text-primary italic">Current: {{ basename($product->file_path) }}</div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-2">Cover Image (Optional - Leave blank to keep current)</label>
                <div class="flex items-start gap-4">
                    @if($product->image_path)
                        <div class="w-32 h-32 rounded-xl overflow-hidden border border-gray-200 flex-shrink-0">
                            <img src="{{ $product->image_url }}" alt="Current image" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <div class="flex-grow">
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-2xl relative group hover:border-primary transition-colors">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-image text-4xl text-gray-300 group-hover:text-primary transition-colors"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="cover_image" class="relative cursor-pointer rounded-md font-bold text-primary hover:text-blue-500 focus-within:outline-none">
                                        <span>Upload new cover</span>
                                        <input id="cover_image" name="cover_image" type="file" accept="image/*" class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">JPG, PNG up to 2MB</p>
                            </div>
                        </div>
                        <div id="image-name" class="mt-2 text-sm font-bold text-primary italic">@if($product->image_path) Keep current image @else No image set @endif</div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                    <span class="text-sm font-bold text-gray-700">Live Status</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_downloadable" value="1" {{ $product->is_downloadable ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                    <span class="text-sm font-bold text-gray-700">Allow Download</span>
                </label>
                 <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_demo" value="1" {{ $product->is_demo ? 'checked' : '' }} class="rounded text-primary focus:ring-primary">
                    <span class="text-sm font-bold text-gray-700">Set as Demo/Free</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.products.index') }}" class="px-8 py-4 rounded-2xl font-bold bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all">Cancel</a>
            <button type="submit" class="bg-primary text-white px-10 py-4 rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg">Update Product</button>
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
</script>
@endsection
