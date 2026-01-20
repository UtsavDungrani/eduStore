@extends('layouts.app')

@section('content')
<!-- Search Bar Section (Sticky) -->
<div class="sticky top-16 z-40 bg-gray-50/95 backdrop-blur-md border-b border-gray-200 shadow-sm transition-all duration-300" id="library-search-bar">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="relative max-w-3xl mx-auto">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400 text-lg"></i>
            </div>
            <input type="text" 
                   id="book-search-input"
                   class="block w-full pl-12 pr-4 py-4 bg-white border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-primary focus:ring-0 text-lg transition-all font-serif shadow-inner"
                   placeholder="Search in your library..."
                   autocomplete="off">
            <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                <kbd class="hidden md:inline-block px-2 py-1 bg-gray-100 border border-gray-300 rounded text-xs text-gray-500 font-sans">
                    /
                </kbd>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Library 📚</h1>
            <p class="text-gray-600 mt-2">Your enrolled content and learning materials.</p>
        </div>
        
        <!-- Collection Categories -->
        <div class="flex gap-2 overflow-x-auto no-scrollbar pb-2 scroll-smooth" id="category-filters">
            <button class="bg-primary text-white px-6 py-2 rounded-full text-sm font-bold shadow-md whitespace-nowrap active-category" data-category="all">All Items</button>
            @foreach($products->pluck('category.name')->unique() as $categoryName)
                @if($categoryName)
                    <button class="bg-white text-gray-600 border border-gray-200 px-6 py-2 rounded-full text-sm font-bold shadow-sm whitespace-nowrap hover:bg-gray-50 transition-all" data-category="{{ $categoryName }}">
                        {{ $categoryName }}
                    </button>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Recently Opened / Continue Reading -->
    <section class="mb-20" x-data="myLibrary()" x-init="init()" x-cloak id="continue-reading-section">
         <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-history text-amber-600"></i> Continue Reading
            </h2>
             <button x-show="hasBooks" @click="clearHistory()" class="text-sm text-red-500 hover:underline">Clear History</button>
        </div>
        
        <div x-show="!hasBooks" class="bg-gray-50 rounded-xl p-8 text-center border border-dashed border-gray-200">
             <p class="text-gray-500">You haven't opened any books recently.</p>
        </div>

        <div x-show="hasBooks">
            <!-- Mobile Slider (Recently Opened) -->
            <div class="md:hidden">
                <div class="relative shelf-container mb-12">
                    <div class="swiper recentSwiper w-full !overflow-visible">
                        <div class="swiper-wrapper relative z-10">
                            <template x-for="book in books" :key="book.id">
                                <div class="swiper-slide flex justify-center items-end pb-4">
                                     <div class="book-container group relative w-40 h-60 perspective-1000 z-20 cursor-pointer transform scale-90 origin-bottom" 
                                         @click="window.location.href = book.url"
                                         :data-title="book.title.toLowerCase()">
                                        <div class="book relative w-full h-full transform-style-3d transition-transform duration-500 group-hover:rotate-y-[-20deg] shadow-xl">
                                            
                                            <!-- Front Cover -->
                                            <div class="absolute inset-0 bg-cover bg-center rounded-r-md shadow-inner origin-left z-10" 
                                                 :style="`background-image: url('${book.image}'); background-size: cover;`">
                                                
                                                <div class="absolute inset-0 bg-gradient-to-r from-black/40 to-transparent opacity-50 rounded-r-md"></div>
                                    
                                                <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/90 to-transparent text-white rounded-br-md">
                                                    <h3 class="font-serif font-bold text-sm leading-tight line-clamp-2 shadow-sm" x-text="book.title"></h3>
                                                    <p class="text-[10px] text-gray-300 mt-1 font-sans">Continue Reading</p>
                                                </div>
                                                
                                                <!-- Progress Bar Overlay -->
                                                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gray-700/50 backdrop-blur-sm z-20">
                                                     <div class="h-full bg-emerald-500 transition-all duration-300" :style="`width: ${(book.page / 20) * 100 > 100 ? 100 : ((book.page / 20) * 100)}%`"></div> 
                                                </div>
                                            </div>
                                    
                                            <div class="absolute top-0 bottom-0 left-0 w-8 bg-gray-800 transform -translate-x-full origin-right rotate-y-[-90deg] flex flex-col justify-center items-center shadow-inner" style="background-color: #1a202c;">
                                                 <span class="text-white text-[10px] font-bold tracking-widest writing-vertical-rl rotate-180 line-clamp-1 py-4 opacity-80" x-text="book.title"></span>
                                            </div>
                                    
                                            <!-- Pages -->
                                            <div class="absolute top-1 bottom-1 right-0 w-8 bg-white transform translate-z-[-2px] translate-x-[2px] shadow-sm rounded-r-sm bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPgo8cmVjdCB3aWR0aD0iNCIgaGVpZ2h0PSI0IiBmaWxsPSIjZmZmIi8+CjxyZWN0IHdpZHRoPSI0IiBoZWlnaHQ9IjEiIGZpbGw9IiNjY2MiLz4KPC9zdmc+')]"></div>
                                    
                                            <!-- Back Cover -->
                                            <div class="absolute inset-0 bg-gray-900 transform translate-z-[-25px] rounded-l-md shadow-xl"></div>
                                        </div>
                                        
                                        <!-- Shelf Shadow -->
                                        <div class="absolute -bottom-4 left-2 right-2 h-4 bg-black/20 blur-md rounded-full transform scale-x-90 group-hover:scale-x-100 transition-transform duration-500"></div>
                                    
                                        <!-- Description & Actions Panel -->
                                        <div class="absolute -bottom-8 left-[-10px] right-[-10px] bg-white/95 backdrop-blur-sm p-4 pt-6 rounded-xl shadow-xl opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-[-10px] transition-all duration-500 z-50 pointer-events-none group-hover:pointer-events-auto border border-gray-100">
                                            <div class="mb-4 text-center">
                                                <p class="text-[10px] text-gray-500 italic font-serif leading-relaxed">
                                                    Resume from Page <span x-text="book.page || 1" class="font-bold text-primary"></span>
                                                </p>
                                            </div>
                                    
                                            <div class="flex justify-center gap-2">
                                                <button @click.stop="window.location.href = book.url" 
                                                   class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-1.5 rounded-full text-[10px] font-bold shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all flex items-center gap-2 uppercase tracking-wider">
                                                    <i class="fas fa-book-open"></i> Resume
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="swiper-button-prev !text-primary !w-8 !h-8 bg-white/80 backdrop-blur shadow-md rounded-full after:!text-sm hover:bg-white transition-all transform -translate-x-2"><i class="fas fa-chevron-left"></i></div>
                        <div class="swiper-button-next !text-primary !w-8 !h-8 bg-white/80 backdrop-blur shadow-md rounded-full after:!text-sm hover:bg-white transition-all transform translate-x-2"><i class="fas fa-chevron-right"></i></div>
                    </div>
                    <!-- Shelf Board -->
                    <div class="absolute bottom-0 left-0 right-0 h-8 bg-[#5d4037] shadow-lg rounded-sm transform translate-y-1/2 flex items-center justify-center overflow-hidden z-0">
                        <div class="absolute top-0 w-full h-2 bg-[#8d6e63] opacity-50"></div>
                    </div>
                    <!-- Shelf Shadow/Depth -->
                    <div class="absolute bottom-[-20px] left-2 right-2 h-4 bg-black/20 blur-xl rounded-full"></div>
                </div>
            </div>

            <!-- Desktop Shelf (Recently Opened) -->
            <div class="hidden md:block relative shelf-container">
                <!-- Books Row -->
                <div class="flex flex-wrap justify-start gap-8 md:gap-20 relative z-10 items-end px-8 min-h-[200px]">
                <template x-for="(book, index) in books" :key="book.id">
                    <div class="book-container mb-6 group relative w-40 h-60 md:w-48 md:h-72 perspective-1000 z-20 cursor-pointer" 
                         :class="{'ml-8 md:ml-20': index === 0}"
                         @click="window.location.href = book.url"
                         :data-title="book.title.toLowerCase()">
                        <div class="book relative w-full h-full transform-style-3d transition-transform duration-500 group-hover:rotate-y-[-20deg] shadow-xl">
                            
                            <!-- Front Cover -->
                            <div class="absolute inset-0 bg-cover bg-center rounded-r-md shadow-inner origin-left z-10" 
                                 :style="`background-image: url('${book.image}'); background-size: cover;`">
                                
                                <div class="absolute inset-0 bg-gradient-to-r from-black/40 to-transparent opacity-50 rounded-r-md"></div>
                    
                                <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/90 to-transparent text-white rounded-br-md">
                                    <h3 class="font-serif font-bold text-lg leading-tight line-clamp-2 shadow-sm" x-text="book.title"></h3>
                                    <p class="text-xs text-gray-300 mt-1 font-sans">Continue Reading</p>
                                </div>
                                
                                <!-- Progress Bar Overlay -->
                                <div class="absolute bottom-0 left-0 right-0 h-1.5 bg-gray-700/50 backdrop-blur-sm z-20">
                                     <div class="h-full bg-emerald-500 transition-all duration-300" :style="`width: ${(book.page / 20) * 100 > 100 ? 100 : ((book.page / 20) * 100)}%`"></div> 
                                </div>
                            </div>
                    
                            <div class="absolute top-0 bottom-0 left-0 w-8 md:w-12 bg-gray-800 transform -translate-x-full origin-right rotate-y-[-90deg] flex flex-col justify-center items-center shadow-inner" style="background-color: #1a202c;">
                                 <span class="text-white text-[10px] md:text-xs font-bold tracking-widest writing-vertical-rl rotate-180 line-clamp-1 py-4 opacity-80" x-text="book.title"></span>
                            </div>
                    
                            <!-- Pages -->
                            <div class="absolute top-1 bottom-1 right-0 w-8 md:w-10 bg-white transform translate-z-[-2px] translate-x-[2px] shadow-sm rounded-r-sm bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPgo8cmVjdCB3aWR0aD0iNCIgaGVpZ2h0PSI0IiBmaWxsPSIjZmZmIi8+CjxyZWN0IHdpZHRoPSI0IiBoZWlnaHQ9IjEiIGZpbGw9IiNjY2MiLz4KPC9zdmc+')]"></div>
                    
                            <!-- Back Cover -->
                            <div class="absolute inset-0 bg-gray-900 transform translate-z-[-25px] rounded-l-md shadow-xl"></div>
                        </div>
                        
                        <!-- Shelf Shadow -->
                        <div class="absolute -bottom-4 left-2 right-2 h-4 bg-black/20 blur-md rounded-full transform scale-x-90 group-hover:scale-x-100 transition-transform duration-500"></div>
                    
                        <!-- Description & Actions Panel -->
                        <div class="absolute -bottom-8 left-[-10px] right-[-10px] bg-white/95 backdrop-blur-sm p-4 pt-6 rounded-xl shadow-xl opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-[-10px] transition-all duration-500 z-50 pointer-events-none group-hover:pointer-events-auto border border-gray-100">
                            <div class="mb-4 text-center">
                                <p class="text-xs text-gray-500 italic font-serif leading-relaxed">
                                    Resume from Page <span x-text="book.page || 1" class="font-bold text-primary"></span>
                                </p>
                            </div>
                    
                            <div class="flex justify-center gap-2">
                                <button @click.stop="window.location.href = book.url" 
                                   class="bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-2 rounded-full text-xs font-bold shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all flex items-center gap-2 uppercase tracking-wider">
                                    <i class="fas fa-book-open"></i> Resume
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                </div>
                
                <!-- Shelf Board -->
                <div class="absolute bottom-0 left-0 right-0 h-8 md:h-12 bg-[#5d4037] shadow-lg rounded-sm transform translate-y-1/2 flex items-center justify-center overflow-hidden">
                    <div class="absolute top-0 w-full h-2 bg-[#8d6e63] opacity-50"></div>
                </div>
                <!-- Shelf Shadow/Depth -->
                <div class="absolute bottom-[-20px] left-2 right-2 h-4 bg-black/20 blur-xl rounded-full"></div>
            </div>
        </div>
    </section>

    <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-4">
         <h2 class="text-3xl font-serif font-bold text-gray-900 flex items-center gap-3">
             <i class="fas fa-book text-primary"></i> Purchased Content
         </h2>
    </div>

    @if($products->count() > 0)
        <!-- Mobile Slider (Purchased Content) -->
        <div class="md:hidden">
            <div class="relative shelf-container mb-12">
                <div class="swiper purchasedSwiper w-full !overflow-visible">
                    <div class="swiper-wrapper relative z-10">
                        @foreach($products as $product)
                            <div class="swiper-slide flex justify-center items-end pb-4" 
                                 data-category="{{ $product->category->name ?? 'Uncategorized' }}" 
                                 data-title="{{ strtolower($product->title) }}">
                                <div class="transform scale-90 origin-bottom">
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
                <!-- Shelf Shadow/Depth -->
                <div class="absolute bottom-[-20px] left-2 right-2 h-4 bg-black/20 blur-xl rounded-full"></div>
            </div>
        </div>

        <!-- Desktop Shelf (Purchased Content) -->
        <div class="hidden md:block space-y-20 mt-12" id="purchased-content-desktop">
            @foreach($products->chunk(4) as $chunk)
                <div class="relative shelf-container">
                    <!-- Books Row -->
                    <div class="flex flex-wrap justify-between gap-4 relative z-10 items-end px-12 md:px-20">
                        @foreach($chunk as $product)
                            <div class="purchased-item" 
                                 data-category="{{ $product->category->name ?? 'Uncategorized' }}" 
                                 data-title="{{ strtolower($product->title) }}">
                                @include('user.partials.book-card', ['product' => $product, 'marginClass' => 'mb-6'])
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Shelf Board -->
                    <div class="absolute bottom-0 left-0 right-0 h-8 md:h-12 bg-[#5d4037] shadow-lg rounded-sm transform translate-y-1/2 flex items-center justify-center overflow-hidden">
                        <div class="absolute top-0 w-full h-2 bg-[#8d6e63] opacity-50"></div>
                    </div>
                    <!-- Shelf Shadow/Depth -->
                    <div class="absolute bottom-[-20px] left-2 right-2 h-4 bg-black/20 blur-xl rounded-full"></div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-3xl p-12 text-center shadow-sm border border-gray-100">
            <div class="w-24 h-24 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-2">Your library is empty</h2>
            <p class="text-gray-500 mb-8">Purchase some content to see it here.</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-primary text-white px-8 py-3 rounded-full font-bold hover:opacity-90 transition-all">Explore Content</a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    
    /* Centering Override for Slider */
    .recentSwiper .book-container, 
    .purchasedSwiper .book-container {
        margin-left: auto !important;
        margin-right: auto !important;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        transform: translateX(22px); /* Compensation for spine */
    }
    
    /* 3D Book Effects */
    .perspective-1000 { perspective: 1000px; }
    .transform-style-3d { transform-style: preserve-3d; }
    
    .book-container:hover .book {
        transform: rotateY(-20deg) rotateX(5deg) scale(1.05);
    }

    /* Shelf Wooden Texture (Simple CSS Pattern) */
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
    
    .writing-vertical-rl {
        writing-mode: vertical-rl;
    }
</style>
<script>
    @if(isset($allProductIds))
        window.VALID_PRODUCT_IDS = @json($allProductIds);
    @endif

    document.addEventListener('DOMContentLoaded', function() {
        const swiperOptions = {
            slidesPerView: 1,
            centeredSlides: true,
            loop: true,
            @if(($siteSettings['products_auto_scroll'] ?? '1') == '1')
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            @endif
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        };

        // Initialize Recent Swiper with Alpine integration
        let recentSwiper = null;
        let purchasedSwiper = null;

        window.addEventListener('book-opened', () => {
            if(recentSwiper) recentSwiper.update();
        });

        // Small delay to let Alpine render templates
        setTimeout(() => {
            recentSwiper = new Swiper(".recentSwiper", swiperOptions);
            
            // Purchased Swiper without navigation unless requested
            const purchasedOptions = { ...swiperOptions };
            delete purchasedOptions.navigation;
            purchasedSwiper = new Swiper(".purchasedSwiper", purchasedOptions);
        }, 100);

        // Search and Filtering Logic
        const searchInput = document.getElementById('book-search-input');
        const categoryButtons = document.querySelectorAll('#category-filters button');
        
        function filterLibrary() {
            const query = searchInput.value.toLowerCase().trim();
            const activeCategory = document.querySelector('.active-category').dataset.category;
            
            // Filter Purchased Content
            const items = document.querySelectorAll('.purchased-item, .purchasedSwiper .swiper-slide');
            items.forEach(item => {
                const title = item.dataset.title;
                const category = item.dataset.category;
                
                const matchesSearch = title.includes(query);
                const matchesCategory = activeCategory === 'all' || category === activeCategory;
                
                if (matchesSearch && matchesCategory) {
                    item.style.display = 'block';
                    if(item.classList.contains('swiper-slide')) item.classList.add('swiper-slide'); // Swiper needs the class
                } else {
                    item.style.display = 'none';
                }
            });

            // Swiper needs update after visibility change
            if(purchasedSwiper) {
                purchasedSwiper.update();
                purchasedSwiper.slideToLoop(0);
            }

            // Hide empty shelves on Desktop
            const shelves = document.querySelectorAll('#purchased-content-desktop .shelf-container');
            shelves.forEach(shelf => {
                const visibleItems = Array.from(shelf.querySelectorAll('.purchased-item')).filter(i => i.style.display !== 'none');
                shelf.style.display = visibleItems.length === 0 ? 'none' : 'block';
            });

            // Filter Continue Reading (Alpine/Local Storage based items)
            // Note: Since these are rendered by Alpine Template, we filter by DOM after Alpine renders or simply filter the visible elements
            const continueItems = document.querySelectorAll('#continue-reading-section .book-container, #continue-reading-section .swiper-slide');
            continueItems.forEach(item => {
                const title = item.getAttribute('data-title') || '';
                const matchesSearch = title.includes(query);
                
                // Categories don't apply to Continue Reading usually as they are recently opened
                item.style.display = matchesSearch ? 'block' : 'none';
            });

            if(recentSwiper) {
                recentSwiper.update();
            }
        }

        if(searchInput) {
            searchInput.addEventListener('input', filterLibrary);
            
            // Focus key '/'
            document.addEventListener('keydown', (e) => {
                if (e.key === '/' && document.activeElement !== searchInput) {
                    e.preventDefault();
                    searchInput.focus();
                }
            });
        }

        categoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                categoryButtons.forEach(btn => btn.classList.remove('bg-primary', 'text-white', 'active-category'));
                categoryButtons.forEach(btn => btn.classList.add('bg-white', 'text-gray-600'));
                
                this.classList.remove('bg-white', 'text-gray-600');
                this.classList.add('bg-primary', 'text-white', 'active-category');
                
                filterLibrary();
            });
        });
    });
</script>
@endpush
