@extends('layouts.app')

@section('content')
<!-- Hero / Banner Carousel -->
<div class="relative bg-white overflow-hidden">
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
            
            <!-- Controls -->
            <button @click="activeSlide = activeSlide === 0 ? slides - 1 : activeSlide - 1" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-3xl opacity-50 hover:opacity-100 transition-opacity">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button @click="activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-3xl opacity-50 hover:opacity-100 transition-opacity">
                <i class="fas fa-chevron-right"></i>
            </button>
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

<!-- Search Bar Section -->
<div class="sticky top-16 z-40 bg-gray-50/95 backdrop-blur-md border-b border-gray-200 shadow-sm transition-all duration-300" id="library-search-bar">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="relative max-w-3xl mx-auto">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400 text-lg"></i>
            </div>
            <input type="text" 
                   id="book-search-input"
                   class="block w-full pl-12 pr-4 py-4 bg-white border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-primary focus:ring-0 text-lg transition-all font-serif shadow-inner"
                   placeholder="Search by book name, subject, or topic..."
                   autocomplete="off">
            <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                <kbd class="hidden md:inline-block px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs text-gray-500 font-sans">
                    /
                </kbd>
            </div>
        </div>
    </div>
</div>



<!-- Featured Content Grid -->
<section class="max-w-7xl mx-auto px-4 mt-12 mb-20" id="featured-content">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            Featured Content
        </h2>
        <a href="{{ route('products.index') }}" class="text-primary font-bold hover:underline">View All</a>
    </div>

    @if($featuredProducts->count() > 0)
        <!-- Auto-Sliding Bookshelf (All Devices) -->
        <div class="relative shelf-container mt-12 mb-12 w-full max-w-[100vw]">
            <div class="swiper featuredSwiper w-full !overflow-visible" style="padding-bottom: 20px;">
                <div class="swiper-wrapper relative z-10">
                    @foreach($featuredProducts as $product)
                        <div class="swiper-slide flex justify-center items-end" style="height: auto;">
                            <!-- Scale wrapper to ensure book fits well on shelf -->
                            <div class="transform scale-90 origin-bottom transition-transform duration-300 hover:scale-100 hover:z-20">
                                @include('user.partials.book-card', ['product' => $product])
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Shelf Board -->
            <div class="absolute bottom-0 left-0 right-0 h-8 md:h-12 bg-[#5d4037] shadow-lg rounded-sm transform translate-y-1/2 flex items-center justify-center overflow-hidden z-0">
                <div class="absolute top-0 w-full h-2 bg-[#8d6e63] opacity-50"></div>
            </div>
            <!-- Shelf Shadow/Depth -->
            <div class="absolute bottom-[-20px] left-2 right-2 h-4 bg-black/20 blur-xl rounded-full"></div>
        </div>
    @else
        <div class="bg-gray-50 rounded-xl p-12 text-center border border-dashed border-gray-200">
            <p class="text-gray-500">No featured content available at the moment.</p>
        </div>
    @endif
    
    <div class="mt-20 text-center">
        <a href="{{ route('products.index') }}" class="inline-block bg-primary text-white px-8 py-3 rounded-full font-bold hover:bg-gray-800 transition-all shadow-lg hover:shadow-xl">
            Browse Full Library
        </a>
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
    
    /* Ensure book cards center nicely */
    .book-container {
        margin-left: auto;
        margin-right: auto;
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
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Optimization: Defer heavy Swiper initialization to allow Critical CSS (Navbar) to paint first
        setTimeout(() => {
            var featuredSwiper = new Swiper(".featuredSwiper", {
                slidesPerView: 1,
                spaceBetween: 10,
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 40,
                    },
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 50,
                    },
                },
                // Performance: disable observer if not needed, but keep watchSlidesProgress for 3D
                watchSlidesProgress: true,
                observer: true,
                observeParents: true,
            });
        }, 50); // Small 50ms delay for paint

        // Search Functionality
        const searchInput = document.getElementById('book-search-input');
        
        if(searchInput) {
            // Focus on slash
            document.addEventListener('keydown', (e) => {
                if (e.key === '/' && document.activeElement !== searchInput) {
                    e.preventDefault();
                    searchInput.focus();
                }
            });

            // Redirect on Enter
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    window.location.href = "{{ route('products.index') }}?search=" + encodeURIComponent(this.value);
                }
            });
        }
    });
</script>
@endpush

<!-- Features Info -->
<section class="bg-slate-900 py-8 md:py-16">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-3 md:grid-cols-3 gap-4 md:gap-12 text-center text-white">
        <div>
            <div class="text-primary text-2xl md:text-4xl mb-2 md:mb-4"><i class="fas fa-shield-alt"></i></div>
            <h4 class="text-xs md:text-xl font-bold mb-1 md:mb-2">Secure Viewing</h4>
            <p class="hidden md:block text-slate-400">Advanced protection prevents unauthorized copying or downloading of content.</p>
        </div>
        <div>
            <div class="text-primary text-2xl md:text-4xl mb-2 md:mb-4"><i class="fas fa-bolt"></i></div>
            <h4 class="text-xs md:text-xl font-bold mb-1 md:mb-2">Instant Access</h4>
            <p class="hidden md:block text-slate-400">Get access to your digital products immediately after payment approval.</p>
        </div>
        <div>
            <div class="text-primary text-2xl md:text-4xl mb-2 md:mb-4"><i class="fas fa-mobile-alt"></i></div>
            <h4 class="text-xs md:text-xl font-bold mb-1 md:mb-2">Mobile Ready</h4>
            <p class="hidden md:block text-slate-400">Study on the go with our fully responsive and touch-friendly interface.</p>
        </div>
    </div>
</section>
@endsection
