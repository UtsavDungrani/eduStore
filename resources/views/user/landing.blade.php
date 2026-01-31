@extends('layouts.app')

@section('content')

<!-- Hero / Banner Carousel -->
<div class="relative bg-transparent overflow-hidden border-b border-[#D4AF37]">
    @if($banners->count() > 0)
        <div x-data="{ activeSlide: 0, slides: {{ $banners->count() }} }" class="relative h-[300px] md:h-[500px]">
            @foreach($banners as $index => $banner)
                <div x-show="activeSlide === {{ $index }}" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform scale-105" x-transition:enter-end="opacity-100 transform scale-100" class="absolute inset-0">
                    <img src="{{ $banner->image_url }}" class="w-full h-full object-cover" alt="{{ $banner->title }}" onerror="console.error('Failed to load banner:', this.src)">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center text-center p-4">
                        <div class="max-w-2xl">
                            <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">{{ $banner->title }}</h1>
                            @if($banner->link)
                                <a href="{{ $banner->link }}" class="inline-block bg-primary text-white px-8 py-3 rounded-full font-bold hover:opacity-90 transition-all uppercase tracking-widest text-sm">Shop Now</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            

        </div>
    @else
        <!-- Default Hero -->
        <div class="bg-primary py-20 px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6">Upgrade Your Learning with {{ $siteName }}</h1>
            <p class="text-white/80 text-xl max-w-2xl mx-auto mb-10">Premium assignments, E-books, and study notes at your fingertips.</p>
            <a href="{{ route('products.index') }}" class="bg-white text-primary px-10 py-4 rounded-full font-bold text-lg hover:bg-gray-100 transition-all shadow-xl">Browse All Content</a>
        </div>
    @endif
</div>

@auth
    <!-- Recently Viewed (Continue Reading) -->
    <section class="max-w-7xl mx-auto px-4 mt-8" x-data="myLibrary()" x-init="init()" x-cloak id="recently-viewed-section">
        <div class="section-cloud-card">
             <div class="flex items-center justify-between bg-[#2C1810] p-4 rounded-xl border border-[#D4AF37] shadow-lg mb-8 relative overflow-hidden group">
                <!-- Shine Effect -->
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#D4AF37]/10 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
                
                <h2 class="text-xl md:text-2xl font-bold text-[#F8F1E9] flex items-center gap-3 font-serif relative z-10">
                    <i class="fas fa-history text-[#D4AF37]"></i> Recently Viewed
                </h2>
                 <button x-show="hasBooks" @click="clearHistory()" class="relative z-10 bg-red-900/80 hover:bg-red-800 text-red-100 border border-red-700/50 px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 shadow-inner">
                    <i class="fas fa-trash-alt"></i> Clear History
                </button>
            </div>
            
            <div x-show="!hasBooks" class="bg-gray-50/50 backdrop-blur rounded-xl p-8 text-center border border-dashed border-gray-200">
                 <p class="text-gray-500">You haven't viewed any books recently.</p>
            </div>
    
            <div x-show="hasBooks">
                <!-- Mobile Carousel View -->
                <div class="md:hidden mb-12">
                    <div class="relative shelf-container">
                        <!-- Custom Navigation Buttons -->
                        <button x-show="books.length > 1" class="recent-next absolute top-1/2 -translate-y-1/2 -right-4 z-50 bg-white text-black w-10 h-10 rounded-full flex items-center justify-center hover:bg-white transition-all shadow-lg active:scale-95">
                            <i class="fas fa-chevron-right text-sm"></i>
                        </button>
                        <button x-show="books.length > 1" class="recent-prev absolute top-1/2 -translate-y-1/2 -left-4 z-50 bg-white text-black w-10 h-10 rounded-full flex items-center justify-center hover:bg-white transition-all shadow-lg active:scale-95">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </button>

                        <div class="swiper recentSwiper w-full !overflow-visible">
                            <div class="swiper-wrapper relative z-10">
                                <template x-for="book in books" :key="book.id">
                                    <div class="swiper-slide flex justify-center items-end pb-4 cont-read-item" :data-title="book.title.toLowerCase()" :data-category="book.category">
                                        <div class="origin-bottom">
                                            @include('user.partials.book-card-history-alpine')
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <!-- Shelf Board -->
                        <div class="absolute bottom-0 left-0 right-0 h-8 bg-[#5d4037] shadow-lg rounded-sm transform translate-y-1/2 flex items-center justify-center overflow-hidden z-0">
                            <div class="absolute top-0 w-full h-2 bg-[#8d6e63] opacity-50"></div>
                        </div>
                    </div>
                </div>

                <!-- Desktop Static Shelf View -->
                <div class="hidden md:block">
                    <template x-for="(chunk, index) in chunkedBooks" :key="index">
                        <div class="relative shelf-container mb-20 last:mb-12">
                            <div class="flex justify-evenly gap-16 md:gap-24 items-end relative z-10 w-full px-4 md:px-8 pl-12 md:pl-16">
                                <template x-for="book in chunk" :key="book.id">
                                    <div class="cont-read-item origin-bottom" :data-title="book.title.toLowerCase()" :data-category="book.category">
                                        @include('user.partials.book-card-history-alpine', ['marginClass' => 'mb-6'])
                                    </div>
                                </template>
                            </div>
                            <!-- Shelf Board -->
                            <div class="absolute bottom-0 left-0 right-0 h-12 bg-[#5d4037] shadow-lg rounded-sm transform translate-y-1/2 flex items-center justify-center overflow-hidden z-0">
                                <div class="absolute top-0 w-full h-2 bg-[#8d6e63] opacity-50"></div>
                            </div>
                            <!-- Shelf Shadow/Depth -->
                            <div class="absolute bottom-[-20px] left-2 right-2 h-4 bg-black/20 blur-xl rounded-full"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>
@endauth

<!-- Recently Added Section -->
<section class="max-w-7xl mx-auto px-4 mt-8" id="recently-added-section">
    <div class="section-cloud-card">
        <div class="flex items-center justify-between bg-[#2C1810] p-4 rounded-xl border border-[#D4AF37] shadow-lg mb-8 relative overflow-hidden group">
            <!-- Shine Effect -->
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#D4AF37]/10 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
            
            <h2 class="text-xl md:text-2xl font-bold text-[#F8F1E9] flex items-center gap-3 font-serif relative z-10">
                <i class="fas fa-plus-circle text-[#D4AF37]"></i> Recently Added
            </h2>
            <a href="{{ route('products.index') }}" class="text-[#D4AF37] font-bold hover:text-[#F8F1E9] transition-colors relative z-10 flex items-center gap-2 text-sm uppercase tracking-wider">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    
        @if($recentlyAddedProducts->count() > 0)
            <!-- Mobile Carousel View -->
            <div class="md:hidden mb-12">
                <div class="relative shelf-container">
                    @if($recentlyAddedProducts->count() > 1)
                    <!-- Custom Navigation Buttons -->
                    <button class="added-next absolute top-1/2 -translate-y-1/2 -right-4 z-50 bg-white text-black w-10 h-10 rounded-full flex items-center justify-center hover:bg-white transition-all shadow-lg active:scale-95">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </button>
                    <button class="added-prev absolute top-1/2 -translate-y-1/2 -left-4 z-50 bg-white text-black w-10 h-10 rounded-full flex items-center justify-center hover:bg-white transition-all shadow-lg active:scale-95">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>
                    @endif

                    <div class="swiper recentlyAddedSwiper w-full !overflow-visible">
                        <div class="swiper-wrapper relative z-10">
                            @foreach($recentlyAddedProducts as $product)
                                <div class="swiper-slide flex justify-center items-end pb-4" 
                                     data-category="{{ $product->category->name ?? 'Uncategorized' }}" 
                                     data-title="{{ strtolower($product->title) }}">
                                    <div class="origin-bottom">
                                        @include('user.partials.book-card', ['product' => $product, 'marginClass' => 'mb-1'])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Shelf Board -->
                    <div class="absolute bottom-0 left-0 right-0 h-8 bg-[#5d4037] shadow-lg rounded-sm transform translate-y-1/2 flex items-center justify-center overflow-hidden z-0">
                        <div class="absolute top-0 w-full h-2 bg-[#8d6e63] opacity-50"></div>
                    </div>
                </div>
            </div>

            <!-- Desktop Static Shelf View -->
            <div class="hidden md:block">
                @foreach($recentlyAddedProducts->chunk(4) as $chunk)
                    <div class="relative shelf-container mb-20 last:mb-12">
                        <div class="flex justify-evenly gap-16 md:gap-24 items-end relative z-10 w-full px-4 md:px-8 pl-12 md:pl-16">
                            @foreach($chunk as $product)
                                <div data-category="{{ $product->category->name ?? 'Uncategorized' }}" data-title="{{ strtolower($product->title) }}">
                                    @include('user.partials.book-card', ['product' => $product, 'marginClass' => 'mb-6'])
                                </div>
                            @endforeach
                        </div>
                        <!-- Shelf Board -->
                        <div class="absolute bottom-0 left-0 right-0 h-12 bg-[#5d4037] shadow-lg rounded-sm transform translate-y-1/2 flex items-center justify-center overflow-hidden z-0">
                            <div class="absolute top-0 w-full h-2 bg-[#8d6e63] opacity-50"></div>
                        </div>
                        <!-- Shelf Shadow/Depth -->
                        <div class="absolute bottom-[-20px] left-2 right-2 h-4 bg-black/20 blur-xl rounded-full"></div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50/50 backdrop-blur rounded-xl p-8 text-center border border-dashed border-gray-200">
                <p class="text-gray-500">No new products added recently.</p>
            </div>
        @endif
    </div>
</section>



<!-- Featured Content Slider -->
<section class="max-w-7xl mx-auto px-4 mt-8 mb-20" id="featured-content">
    <div class="section-cloud-card">
        <!-- Featured Content Header -->
        <div class="flex items-center justify-between bg-[#2C1810] p-4 rounded-xl border border-[#D4AF37] shadow-lg mb-8 relative overflow-hidden group">
            <!-- Shine Effect -->
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#D4AF37]/10 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
            
            <h2 class="text-xl md:text-2xl font-bold text-[#F8F1E9] flex items-center gap-3 font-serif relative z-10">
                <i class="fas fa-star text-[#D4AF37]"></i> Featured Content
            </h2>
            <a href="{{ route('products.index') }}" class="text-[#D4AF37] font-bold hover:text-[#F8F1E9] transition-colors relative z-10 flex items-center gap-2">
                View All <i class="fas fa-arrow-right text-sm"></i>
            </a>
        </div>
    
        @if($featuredProducts->count() > 0)
            <!-- Mobile Carousel View -->
            <div class="md:hidden mb-12">
                <div class="relative shelf-container">
                    @if($featuredProducts->count() > 1)
                    <!-- Custom Navigation Buttons -->
                    <button class="featured-next absolute top-1/2 -translate-y-1/2 -right-4 z-50 bg-white text-black w-10 h-10 rounded-full flex items-center justify-center hover:bg-white transition-all shadow-lg active:scale-95">
                        <i class="fas fa-chevron-right text-sm"></i>
                    </button>
                    <button class="featured-prev absolute top-1/2 -translate-y-1/2 -left-4 z-50 bg-white text-black w-10 h-10 rounded-full flex items-center justify-center hover:bg-white transition-all shadow-lg active:scale-95">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>
                    @endif

                    <div class="swiper featuredSwiper w-full !overflow-visible">
                        <div class="swiper-wrapper relative z-10">
                            @foreach($featuredProducts as $product)
                                <div class="swiper-slide flex justify-center items-end pb-4" 
                                     data-category="{{ $product->category->name ?? 'Uncategorized' }}" 
                                     data-title="{{ strtolower($product->title) }}">
                                    <div class="origin-bottom">
                                        @include('user.partials.book-card', ['product' => $product, 'marginClass' => 'mb-1'])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Shelf Board -->
                    <div class="absolute bottom-0 left-0 right-0 h-8 bg-[#5d4037] shadow-lg rounded-sm transform translate-y-1/2 flex items-center justify-center overflow-hidden z-0">
                        <div class="absolute top-0 w-full h-2 bg-[#8d6e63] opacity-50"></div>
                    </div>
                </div>
            </div>

            <!-- Desktop Static Shelf View -->
            <div class="hidden md:block">
                @foreach($featuredProducts->chunk(4) as $chunk)
                    <div class="relative shelf-container mb-20 last:mb-12">
                        <div class="flex justify-evenly gap-16 md:gap-24 items-end relative z-10 w-full px-4 md:px-8 pl-12 md:pl-16">
                            @foreach($chunk as $product)
                                <div data-category="{{ $product->category->name ?? 'Uncategorized' }}" data-title="{{ strtolower($product->title) }}">
                                    @include('user.partials.book-card', ['product' => $product, 'marginClass' => 'mb-6'])
                                </div>
                            @endforeach
                        </div>
                        <!-- Shelf Board -->
                        <div class="absolute bottom-0 left-0 right-0 h-12 bg-[#5d4037] shadow-lg rounded-sm transform translate-y-1/2 flex items-center justify-center overflow-hidden z-0">
                            <div class="absolute top-0 w-full h-2 bg-[#8d6e63] opacity-50"></div>
                        </div>
                        <!-- Shelf Shadow/Depth -->
                        <div class="absolute bottom-[-20px] left-2 right-2 h-4 bg-black/20 blur-xl rounded-full"></div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50/50 backdrop-blur rounded-xl p-12 text-center border border-dashed border-gray-200">
                <p class="text-gray-500">No featured content available at the moment.</p>
            </div>
        @endif
        
        <div class="mt-20 text-center">
            <a href="{{ route('products.index') }}" class="inline-block bg-primary text-white px-8 py-3 rounded-full font-bold hover:bg-gray-800 transition-all shadow-lg hover:shadow-xl">
                Browse Full Library
            </a>
        </div>
    </div>
</section>

@push('scripts')
<style>
    /* 3D Book Effects */
    .perspective-1000 { perspective: 1000px; }
    .transform-style-3d { transform-style: preserve-3d; }
    
    /* Book Hover Animation Classes */
    .book-container:hover .book {
        transform: rotateY(-20deg) rotateX(5deg) scale(1.05);
    }
    
    .writing-vertical-rl {
        writing-mode: vertical-rl;
    }
    
    /* Ensure book cards reside at the start by default */
    .book-container {
        margin-left: 0;
        margin-right: 0;
    }

    /* Centering Override for Sliders - Refined for Precise Visual Center */
    .featuredSwiper .book-container,
    .recentSwiper .book-container,
    .recentlyAddedSwiper .book-container {
        margin-left: auto !important;
        margin-right: auto !important;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        transform: translateX(16px) !important; /* Visual center compensation for mobile */
    }
    
    @media (min-width: 1024px) {
        /* Desktop alignment fix - compensation for larger spine */
        .featuredSwiper .book-container,
        .recentSwiper .book-container,
        .recentlyAddedSwiper .book-container {
            transform: translateX(24px) !important;
        }
    }

    /* Shelf Wooden Texture */
    .shelf-container::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 12px;
        background: repeating-linear-gradient(
            90deg,
            #5d4037,
            #5d4037 10px,
            #4e342e 10px,
            #4e342e 20px
        );
        opacity: 0.3;
        pointer-events: none;
        z-index: 5;
    }
    
    /* Custom Button Hover Effect (Cloudy Glow) */
    [class*="-next"]:hover, [class*="-prev"]:hover {
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.6), 0 0 40px rgba(255, 255, 255, 0.4) !important;
        transform: translateY(-50%) scale(1.1) !important;
        background-color: white !important;
        color: black !important;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validation Data for Recently Viewed
        @auth
            window.VALID_PRODUCT_IDS = @json($allProductIds);
            window.PRODUCT_CATEGORIES = @json($productCategories);
        @endauth

        // Optimization: Defer heavy Swiper initialization to allow Critical CSS (Navbar) to paint first
        setTimeout(() => {
            const commonConfig = {
                slidesPerView: 1,
                spaceBetween: 20,
                centeredSlides: true,
                breakpoints: {
                    640: { slidesPerView: 2, centeredSlides: false, spaceBetween: 30 },
                    1024: { slidesPerView: 4, centeredSlides: false, spaceBetween: 50 },
                    1280: { slidesPerView: 4, centeredSlides: false, spaceBetween: 60 }
                }
            };

            // Helper function to check if loop should be enabled
            function shouldEnableLoop(swiperElement) {
                const slides = swiperElement.querySelectorAll('.swiper-slide');
                return slides.length > 1; // Only enable loop if more than 1 slide
            }

            const featuredEl = document.querySelector(".featuredSwiper");
            if (featuredEl && shouldEnableLoop(featuredEl)) {
                var featuredSwiper = new Swiper(".featuredSwiper", {
                    ...commonConfig,
                    loop: true,
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.featured-next',
                        prevEl: '.featured-prev',
                    }
                });
            } else if (featuredEl) {
                var featuredSwiper = new Swiper(".featuredSwiper", {
                    ...commonConfig,
                    loop: false,
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.featured-next',
                        prevEl: '.featured-prev',
                    }
                });
            }

            const recentlyAddedEl = document.querySelector(".recentlyAddedSwiper");
            if (recentlyAddedEl && shouldEnableLoop(recentlyAddedEl)) {
                var recentlyAddedSwiper = new Swiper(".recentlyAddedSwiper", {
                    ...commonConfig,
                    loop: true,
                    autoplay: {
                        delay: 3500,
                        disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.added-next',
                        prevEl: '.added-prev',
                    }
                });
            } else if (recentlyAddedEl) {
                var recentlyAddedSwiper = new Swiper(".recentlyAddedSwiper", {
                    ...commonConfig,
                    loop: false,
                    autoplay: {
                        delay: 3500,
                        disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.added-next',
                        prevEl: '.added-prev',
                    }
                });
            }

            @auth
            const recentEl = document.querySelector(".recentSwiper");
            if (recentEl && shouldEnableLoop(recentEl)) {
                var recentSwiper = new Swiper(".recentSwiper", {
                    ...commonConfig,
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.recent-next',
                        prevEl: '.recent-prev',
                    }
                });

                window.addEventListener('book-opened', () => {
                    if(recentSwiper) recentSwiper.update();
                });
            } else if (recentEl) {
                var recentSwiper = new Swiper(".recentSwiper", {
                    ...commonConfig,
                    loop: false,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.recent-next',
                        prevEl: '.recent-prev',
                    }
                });

                window.addEventListener('book-opened', () => {
                    if(recentSwiper) recentSwiper.update();
                });
            }
            @endauth
        }, 50); // Small 50ms delay for paint

    });
</script>
@endpush

<!-- Features Info -->
<section class="bg-primary py-8 md:py-16">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-3 md:grid-cols-3 gap-4 md:gap-12 text-center text-white">
        <div>
            <div class="text-secondary text-2xl md:text-4xl mb-2 md:mb-4"><i class="fas fa-shield-alt"></i></div>
            <h4 class="text-xs md:text-xl font-bold mb-1 md:mb-2">Secure Viewing</h4>
            <p class="hidden md:block text-slate-100/60">Advanced protection prevents unauthorized copying or downloading of content.</p>
        </div>
        <div>
            <div class="text-secondary text-2xl md:text-4xl mb-2 md:mb-4"><i class="fas fa-bolt"></i></div>
            <h4 class="text-xs md:text-xl font-bold mb-1 md:mb-2">Instant Access</h4>
            <p class="hidden md:block text-slate-100/60">Get access to your digital products immediately after payment approval.</p>
        </div>
        <div>
            <div class="text-secondary text-2xl md:text-4xl mb-2 md:mb-4"><i class="fas fa-mobile-alt"></i></div>
            <h4 class="text-xs md:text-xl font-bold mb-1 md:mb-2">Mobile Ready</h4>
            <p class="hidden md:block text-slate-100/60">Study on the go with our fully responsive and touch-friendly interface.</p>
        </div>
    </div>
</section>
@endsection
