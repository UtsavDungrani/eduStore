@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="bg-[#2C1810] p-8 rounded-2xl border border-[#D4AF37] shadow-lg relative overflow-hidden group text-center md:text-left">
        <!-- Shine Effect -->
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#D4AF37]/10 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
        
        <h1 class="text-3xl md:text-4xl font-bold text-[#F8F1E9] font-serif relative z-10 mb-2">Browse Content</h1>
        <p class="text-[#D4AF37] text-lg font-serif italic relative z-10">Find the best assignments, e-books, and notes.</p>
    </div>

    <!-- Featured Promotional Cloud (Advertisement Cloud) -->
    @if($featuredProducts->count() > 0)
    <section class="mt-12 mb-8" id="browse-promotion-cloud">
        <div class="section-cloud-card !mt-0 !mb-0">
            <div class="flex items-center justify-between bg-[#2C1810] p-4 rounded-xl border border-[#D4AF37] shadow-lg mb-8 relative overflow-hidden group">
                <!-- Shine Effect -->
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#D4AF37]/10 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
                
                <h2 class="text-xl md:text-2xl font-bold text-[#F8F1E9] flex items-center gap-3 font-serif relative z-10">
                    <i class="fas fa-bullhorn text-[#D4AF37]"></i> Featured Promotions
                </h2>
            </div>

            <!-- Mobile Slider (Featured Promotions) -->
            <div class="md:hidden">
                <div class="relative mb-6">
                    <div class="swiper promoSwiper w-full">
                        @if($featuredProducts->count() > 1)
                        <!-- Custom Navigation Buttons -->
                        <button class="promo-next absolute top-1/2 -translate-y-1/2 -right-4 z-50 bg-white text-black w-10 h-10 rounded-full flex items-center justify-center hover:bg-white transition-all shadow-lg active:scale-95">
                            <i class="fas fa-chevron-right text-sm"></i>
                        </button>
                        <button class="promo-prev absolute top-1/2 -translate-y-1/2 -left-4 z-50 bg-white text-black w-10 h-10 rounded-full flex items-center justify-center hover:bg-white transition-all shadow-lg active:scale-95">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </button>
                        @endif

                        <div class="swiper-wrapper relative z-10">
                                @foreach($featuredProducts as $product)
                                    <div class="swiper-slide h-auto">
                                        @include('user.partials.product-card', ['product' => $product])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    
                    </div>
                </div>


            <!-- Desktop Shelf (Featured Promotions) -->
            <div class="hidden md:block">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch relative z-10 w-full">
                    @foreach($featuredProducts->take(4) as $product)
                        <div class="flex">
                            @include('user.partials.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif
</div>

@push('scripts')
<style>
    /* Swiper custom styles */
    .promoSwiper {
        padding-bottom: 2rem !important;
    }

    /* Ensure Swiper arrows are visible and properly styled like home page */
    .promo-next, .promo-prev {
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    }
    .promo-next:hover, .promo-prev:hover {
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.8), 0 0 40px rgba(255, 255, 255, 0.4);
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // MATCHING HOME PAGE OPTIMIZATION: Defer Swiper initialization
        setTimeout(() => {
            const commonConfig = {
                slidesPerView: 1,
                spaceBetween: 30,
                centeredSlides: true,
                navigation: {
                    nextEl: '.promo-next',
                    prevEl: '.promo-prev',
                },
                breakpoints: {
                    640: { slidesPerView: 2 },
                    1024: { slidesPerView: 4 },
                    1280: { slidesPerView: 5 }
                }
            };

            // Helper function to check if loop should be enabled
            function shouldEnableLoop(swiperElement) {
                const slides = swiperElement.querySelectorAll('.swiper-slide');
                return slides.length > 1; // Only enable loop if more than 1 slide
            }

            const promoEl = document.querySelector(".promoSwiper");
            if (promoEl && shouldEnableLoop(promoEl)) {
                new Swiper(".promoSwiper", {
                    ...commonConfig,
                    loop: true,
                    autoplay: {
                        delay: 3500,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: true,
                    },
                    navigation: {
                        nextEl: '.promo-next',
                        prevEl: '.promo-prev',
                    },
                    // Responsive overrides for promo context
                    breakpoints: {
                        640: { slidesPerView: 2, centeredSlides: false },
                        // On larger screens, the container is hidden, but Swiper still initializes
                    }
                });
            } else if (promoEl) {
                new Swiper(".promoSwiper", {
                    ...commonConfig,
                    loop: false,
                    autoplay: {
                        delay: 3500,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: true,
                    },
                    navigation: {
                        nextEl: '.promo-next',
                        prevEl: '.promo-prev',
                    },
                    // Responsive overrides for promo context
                    breakpoints: {
                        640: { slidesPerView: 2, centeredSlides: false },
                        // On larger screens, the container is hidden, but Swiper still initializes
                    }
                });
            }
        }, 50); // Same 50ms delay as landing.blade.php
    });
</script>
@endpush

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
                <div class="md:hidden relative mb-12">


                    <div class="swiper myProductSwiper !pb-12">
                        <div class="swiper-wrapper">
                            @foreach($products as $product)
                                <div class="swiper-slide h-auto">
                                    @include('user.partials.product-card', ['product' => $product])
                                </div>
                            @endforeach
                        </div>
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
