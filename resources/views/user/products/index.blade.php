@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="bg-[#2C1810] p-8 rounded-2xl border border-[#D4AF37] shadow-lg relative overflow-hidden group text-center md:text-left">
        <!-- Shine Effect -->
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#D4AF37]/10 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
        
        <h1 class="text-3xl md:text-4xl font-bold text-[#F8F1E9] font-serif relative z-10 mb-2">Browse Content</h1>
        <p class="text-[#D4AF37] text-lg font-serif italic relative z-10">Find the best assignments, e-books, and notes.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <div class="w-full lg:w-64 flex-shrink-0">
            <div class="bg-white p-6 rounded-2xl shadow-md border border-[#D4AF37] sticky top-24">
                <form action="{{ route('products.index') }}" method="GET">
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-[#2C1810] mb-2 uppercase tracking-tight font-serif">Search</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 bg-[#F8F1E9] border-2 border-[#D4AF37] rounded-xl focus:outline-none focus:border-[#2C1810] text-[#1A0D00] placeholder-[#8B4513]/50 font-serif" placeholder="Search...">
                            <i class="fas fa-search absolute left-3 top-3 text-[#8B4513]"></i>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-[#2C1810] mb-2 uppercase tracking-tight font-serif">Category</label>
                        <div class="space-y-2">
                            <a href="{{ route('products.index') }}" class="block px-3 py-2 rounded-lg text-sm font-bold {{ !request('category') ? 'bg-[#2C1810] text-white' : 'text-[#8B4513] hover:bg-[#D4AF37]/20 transition-colors' }}">All Categories</a>
                            @foreach($categories as $category)
                                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="block px-3 py-2 rounded-lg text-sm font-semibold {{ request('category') === $category->slug ? 'bg-[#2C1810] text-white' : 'text-[#8B4513] hover:bg-[#D4AF37]/20 transition-colors' }}">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#2C1810] text-white py-3 rounded-xl font-bold hover:bg-[#1A0D00] transition-colors border border-[#D4AF37] shadow-sm uppercase tracking-wider text-xs">Apply Filters</button>
                    @if(request()->anyFilled(['search', 'category']))
                        <a href="{{ route('products.index') }}" class="block text-center mt-4 text-sm text-[#8B4513] hover:text-[#2C1810] underline decoration-dotted">Clear All</a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="flex-1">
            @if($products->count() > 0)
                <!-- Mobile Slider -->
                <div class="md:hidden swiper myProductSwiper !pb-12">
                    <div class="swiper-wrapper">
                        @foreach($products as $product)
                            <div class="swiper-slide h-auto">
                                @include('user.partials.product-card', ['product' => $product])
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Desktop Grid -->
                <div class="hidden md:grid grid-cols-2 lg:grid-cols-3 gap-6">
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
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper(".myProductSwiper", {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: {{ $products->count() > 1 ? 'true' : 'false' }}, 
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                },
            },
        });
    });
</script>
@endpush
@endsection
