@extends('layouts.app')

@section('content')
<div class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900">Browse Content</h1>
        <p class="text-gray-500 mt-2">Find the best assignments, e-books, and notes.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <div class="w-full lg:w-64 flex-shrink-0">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-24">
                <form action="{{ route('products.index') }}" method="GET">
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-tight">Search</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-primary focus:border-primary" placeholder="Search...">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-tight">Category</label>
                        <div class="space-y-2">
                            <a href="{{ route('products.index') }}" class="block px-3 py-2 rounded-lg text-sm {{ !request('category') ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50' }}">All Categories</a>
                            @foreach($categories as $category)
                                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="block px-3 py-2 rounded-lg text-sm {{ request('category') === $category->slug ? 'bg-primary text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gray-900 text-white py-3 rounded-xl font-bold hover:bg-primary transition-colors">Apply Filters</button>
                    @if(request()->anyFilled(['search', 'category']))
                        <a href="{{ route('products.index') }}" class="block text-center mt-4 text-sm text-gray-500 hover:text-red-500">Clear All</a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="flex-1">
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        @include('user.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            @else
                <div class="bg-white rounded-2xl p-12 text-center border border-dashed border-gray-300">
                    <div class="text-gray-300 text-6xl mb-4"><i class="fas fa-search"></i></div>
                    <h3 class="text-xl font-bold text-gray-900">No products found</h3>
                    <p class="text-gray-500 mt-2">Try adjusting your filters or search terms.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
