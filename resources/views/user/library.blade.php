@extends('layouts.app')

@section('content')
<div x-data="{ 
    activeTab: new URLSearchParams(window.location.search).get('tab') || 'library',
    cart: [],
    loadingCart: true,
    init() {
        this.cart = JSON.parse(localStorage.getItem(window.CART_KEY) || '[]');
        this.loadingCart = false;
        window.addEventListener('cart-updated', () => {
            this.cart = JSON.parse(localStorage.getItem(window.CART_KEY) || '[]');
        });
    },
    removeItem(id) {
        this.cart = this.cart.filter(i => i.id !== id);
        this.saveCart();
    },
    saveCart() {
        localStorage.setItem(window.CART_KEY, JSON.stringify(this.cart));
        window.dispatchEvent(new CustomEvent('cart-updated'));
    },
    get cartTotal() {
        return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    }
}">
    <!-- Search Bar Section (Sticky) -->
    <div class="sticky top-16 z-40 bg-[#FDF6E3]/95 backdrop-blur-md border-b-2 border-[#D4AF37] shadow-xl transition-all duration-300" id="library-search-bar" x-show="activeTab === 'library'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="relative max-w-4xl mx-auto">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                    <i class="fas fa-search text-[#8B4513] text-xl"></i>
                </div>
                <input type="text" 
                       id="book-search-input"
                       class="block w-full pl-14 pr-4 py-5 bg-[#F8F1E9] border-2 border-[#D4AF37] rounded-full text-[#2C1810] placeholder-[#8B4513]/60 focus:outline-none focus:border-[#2C1810] focus:ring-2 focus:ring-[#D4AF37]/50 text-xl transition-all font-serif shadow-inner hover-quill"
                       placeholder="Search in your library..."
                       autocomplete="off">
                <div class="absolute inset-y-0 right-0 pr-6 flex items-center">
                    <kbd class="hidden md:inline-block px-3 py-1 bg-[#D4AF37]/20 border border-[#D4AF37] rounded-full text-xs text-[#2C1810] font-sans font-bold">
                        /
                    </kbd>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Tab Switcher -->
        <div class="flex gap-2 md:gap-4 mb-10 bg-[#FDF6E3] p-2 rounded-2xl border border-[#D4AF37] shadow-sm max-w-fit mx-auto md:mx-0">
            <button @click="activeTab = 'library'; window.history.replaceState(null, '', '?tab=library')" 
                    :class="activeTab === 'library' ? 'bg-[#2C1810] text-[#D4AF37] shadow-md' : 'text-[#8B4513] hover:bg-[#D4AF37]/10'"
                    class="px-5 md:px-8 py-3 rounded-xl font-bold transition-all flex items-center gap-2 text-sm md:text-base">
                <i class="fas fa-book-open"></i> <span class="hidden sm:inline">My</span> Library
            </button>
            <button @click="activeTab = 'cart'; window.history.replaceState(null, '', '?tab=cart')" 
                    :class="activeTab === 'cart' ? 'bg-[#2C1810] text-[#D4AF37] shadow-md' : 'text-[#8B4513] hover:bg-[#D4AF37]/10'"
                    class="px-5 md:px-8 py-3 rounded-xl font-bold transition-all flex items-center gap-2 relative text-sm md:text-base">
                <i class="fas fa-shopping-cart"></i> Cart
                <span x-show="cart.length > 0" x-text="cart.length" class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] w-5 h-5 flex items-center justify-center rounded-full border-2 border-[#FDF6E3]"></span>
            </button>
        </div>

        <!-- Library Tab Content -->
        <div x-show="activeTab === 'library'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="bg-[#2C1810] p-6 rounded-2xl border border-[#D4AF37] shadow-lg relative overflow-hidden group min-w-[300px]">
            <!-- Shine Effect -->
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#D4AF37]/10 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
            
            <h1 class="text-2xl md:text-2xl font-bold text-[#F8F1E9] font-serif relative z-10 flex items-center gap-2">
                My Library <span class="text-2xl">📚</span>
            </h1>
            <p class="text-[#D4AF37] mt-1 font-serif italic text-sm relative z-10">Your enrolled content and learning materials.</p>
        </div>
        
        <!-- Collection Categories -->
        <div class="flex gap-2 overflow-x-auto no-scrollbar pb-2 scroll-smooth" id="category-filters">
            <button class="px-6 py-2 rounded-full whitespace-nowrap text-sm font-bold shadow-md transition-all active-category bg-[#2C1810] text-[#D4AF37] border border-[#2C1810]" data-category="all">
                All Items
            </button>
            @foreach($products->pluck('category.name')->unique() as $categoryName)
                @if($categoryName)
                    <button class="px-6 py-2 rounded-full whitespace-nowrap text-sm font-bold shadow-sm hover:shadow-md transition-all bg-[#FDF6E3] text-[#2C1810] border border-[#D4AF37] hover:bg-[#D4AF37] hover:text-[#2C1810]" data-category="{{ $categoryName }}">
                        {{ $categoryName }}
                    </button>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Recently Opened / Continue Reading -->
    <section class="max-w-7xl mx-auto mt-8 mb-20" x-data="myLibrary()" x-init="init()" x-cloak id="continue-reading-section">
        <div class="section-cloud-card">
             <div class="flex items-center justify-between bg-[#2C1810] p-4 rounded-xl border border-[#D4AF37] shadow-lg mb-8 relative overflow-hidden group">
                <!-- Shine Effect -->
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#D4AF37]/10 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
                
                <h2 class="text-xl md:text-2xl font-bold text-[#F8F1E9] flex items-center gap-3 font-serif relative z-10">
                    <i class="fas fa-history text-[#D4AF37]"></i> Continue Reading
                </h2>
                 <button x-show="hasBooks" @click="clearHistory()" class="relative z-10 bg-red-900/80 hover:bg-red-800 text-red-100 border border-red-700/50 px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 shadow-inner">
                    <i class="fas fa-trash-alt"></i> Clear History
                </button>
            </div>
            
            <div x-show="!hasBooks" class="bg-gray-50/50 backdrop-blur rounded-xl p-8 text-center border border-dashed border-gray-200">
                 <p class="text-gray-500">You haven't opened any books recently.</p>
            </div>
    
            <div x-show="hasBooks">
                <!-- Mobile Slider (Recently Opened) -->
                <div class="md:hidden">
                    <div class="relative shelf-container mb-12">
                        <div class="swiper recentSwiper w-full !overflow-visible">
                            <div class="swiper-wrapper relative z-10">
                                <template x-for="book in books" :key="book.id">
                                    <div class="swiper-slide flex justify-center items-end pb-4 cont-read-item" :data-title="book.title.toLowerCase()" :data-category="book.category">
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
                    <div class="flex flex-wrap justify-evenly gap-16 md:gap-24 relative z-10 items-end px-4 md:px-8 pl-12 md:pl-16 min-h-[200px]">
                    <template x-for="(book, index) in books" :key="book.id">
                        <div class="book-container mb-6 group relative w-40 h-60 md:w-48 md:h-72 perspective-1000 z-20 cursor-pointer cont-read-item" 
                             @click="window.location.href = book.url"
                             :data-title="book.title.toLowerCase()"
                             :data-category="book.category">
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
        </div>
    </section>

    <!-- Purchased Content Section -->
    <section class="mb-20" id="purchased-content-section">
        <div class="section-cloud-card">
            <div class="flex items-center justify-between bg-[#2C1810] p-4 rounded-xl border border-[#D4AF37] shadow-lg mb-8 relative overflow-hidden group">
                <!-- Shine Effect -->
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#D4AF37]/10 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
                
                <h2 class="text-xl md:text-2xl font-bold text-[#F8F1E9] flex items-center gap-3 font-serif relative z-10">
                     <i class="fas fa-book text-[#D4AF37]"></i> Purchased Content
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
                            <div class="flex flex-wrap justify-evenly gap-16 md:gap-24 relative z-10 items-end px-4 md:px-8 pl-12 md:pl-16 min-h-[200px]">
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
                <div class="bg-gray-50/50 backdrop-blur rounded-3xl p-12 text-center shadow-lg border border-dashed border-gray-200">
                    <div class="w-24 h-24 bg-[#2C1810]/10 text-[#2C1810] rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-[#2C1810] mb-2 font-serif">Your library is empty</h2>
                    <p class="text-[#8B4513] mb-8 font-serif">Purchase some content to see it here.</p>
                    <a href="{{ route('products.index') }}" class="inline-block bg-[#2C1810] text-[#FDF6E3] px-8 py-3 rounded-full font-bold hover:bg-[#1A0D00] shadow-md transition-all border border-[#D4AF37] uppercase tracking-wider text-sm">Explore Content</a>
                </div>
            @endif
        </div>
    </section>
        </div>

        <!-- Cart Tab Content -->
        <div x-show="activeTab === 'cart'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="max-w-3xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-[#2C1810] font-serif">Your Cart 🛒</h1>
                    <p class="text-[#8B4513] mt-2 font-serif italic" x-show="cart.length > 0">Review your items before proceeding.</p>
                </div>

                <!-- Empty State -->
                <template x-if="!loadingCart && cart.length === 0">
                    <div class="bg-[#FDF6E3] rounded-3xl p-12 text-center shadow-lg border border-[#D4AF37]">
                        <div class="w-24 h-24 bg-[#2C1810]/10 text-[#2C1810] rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-[#2C1810] mb-2 font-serif">Your cart is empty</h2>
                        <p class="text-[#8B4513] mb-8 font-serif">Looks like you haven't added anything yet.</p>
                        <a href="{{ route('products.index') }}" class="inline-block bg-[#2C1810] text-[#FDF6E3] px-8 py-3 rounded-full font-bold hover:bg-[#1A0D00] shadow-md transition-all border border-[#D4AF37] uppercase tracking-wider text-sm">Start Shopping</a>
                    </div>
                </template>

                <!-- Cart Items -->
                <div class="space-y-4" x-show="cart.length > 0">
                    <template x-for="item in cart" :key="item.id">
                        <div class="bg-white p-4 rounded-2xl shadow-md border border-[#D4AF37]/20 flex gap-4 items-center">
                            <img :src="item.thumbnail" class="w-20 h-20 rounded-xl object-cover bg-[#F8F1E9] border border-[#D4AF37]/30" :alt="item.title">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-[#2C1810] truncate font-serif" x-text="item.title"></h3>
                                <p class="text-[#D4AF37] font-bold mt-1" x-text="'₹' + item.price"></p>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <button @click="removeItem(item.id)" class="bg-red-50 text-red-500 p-2 rounded-lg hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Checkout Section -->
                <div class="mt-8 mb-8" x-show="cart.length > 0" x-cloak>
                    <div class="bg-[#2C1810] text-[#FDF6E3] p-6 rounded-3xl shadow-2xl flex items-center justify-between border-2 border-[#D4AF37]">
                        <div>
                            <p class="text-[#D4AF37] text-sm font-serif">Total Amount</p>
                            <p class="text-3xl font-black font-serif" x-text="'₹' + cartTotal"></p>
                        </div>
                        <button id="rzp-cart-button" class="bg-[#D4AF37] text-[#2C1810] px-8 py-4 rounded-2xl font-bold hover:bg-[#B89626] transition-all shadow-lg border border-[#2C1810]/10 flex items-center gap-2 uppercase tracking-wider text-sm">
                            <i class="fas fa-bolt text-[#FDF6E3]"></i> Checkout Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

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
    
    // Inject Categories for Continue Reading mapping
    @if(isset($products))
        window.PRODUCT_CATEGORIES = {
            @foreach($products as $p)
                {{ $p->id }}: "{{ $p->category->name }}",
            @endforeach
        };
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
            const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
            
            const activeBtn = document.querySelector('.active-category');
            if(!activeBtn) return; // Safety check
            
            const activeCategory = activeBtn.dataset.category;
            
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
            // Added class 'cont-read-item' to better target these items dynamically
            const continueItems = document.querySelectorAll('.cont-read-item');
            continueItems.forEach(item => {
                const title = item.getAttribute('data-title') || '';
                // Since Alpine renders category into dataset, we can read it. 
                // Note: book.category needs to be populated in app.blade.php logic
                const category = item.getAttribute('data-category') || 'Uncategorized';
                
                const matchesSearch = title.toLowerCase().includes(query);
                const matchesCategory = activeCategory === 'all' || category === activeCategory;
                
                if (matchesSearch && matchesCategory) {
                    item.style.display = 'block';
                    if(item.classList.contains('swiper-slide')) item.classList.add('swiper-slide');
                } else {
                    item.style.display = 'none';
                }
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
                // Remove active class from all and reset to "Inactive" style
                categoryButtons.forEach(btn => {
                    btn.classList.remove('bg-[#2C1810]', 'text-[#D4AF37]', 'active-category', 'shadow-md', 'border-[#2C1810]');
                    btn.classList.add('bg-[#FDF6E3]', 'text-[#2C1810]', 'border', 'border-[#D4AF37]', 'hover:bg-[#D4AF37]', 'hover:text-[#2C1810]');
                });
                
                // Add active class to clicked and set to "Active" style
                this.classList.remove('bg-[#FDF6E3]', 'text-[#2C1810]', 'border', 'border-[#D4AF37]', 'hover:bg-[#D4AF37]', 'hover:text-[#2C1810]');
                this.classList.add('bg-[#2C1810]', 'text-[#D4AF37]', 'active-category', 'shadow-md', 'border-[#2C1810]');
                
                filterLibrary();
            });
        });
        
        // Tab deep linking helper
        window.setTab = (tab) => {
            const el = document.querySelector('[x-data]');
            if (el && el.__x && el.__x.$data) {
                el.__x.$data.activeTab = tab;
            } else {
                // For non-Alpine initialized yet
                const params = new URLSearchParams(window.location.search);
                params.set('tab', tab);
                window.location.search = params.toString();
            }
        };

        // Razorpay Logic (Moved from cart.blade.php)
        const rzpBtn = document.getElementById('rzp-cart-button');
        if (rzpBtn) {
            rzpBtn.onclick = function(e) {
                const cartItems = JSON.parse(localStorage.getItem(window.CART_KEY) || '[]');
                if (cartItems.length === 0) {
                    alert("Your cart is empty.");
                    return;
                }

                fetch("{{ route('razorpay.cart.order') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        items: cartItems.map(item => ({
                            id: item.id,
                            price: item.price
                        }))
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    var options = {
                        "key": data.key,
                        "amount": data.amount,
                        "currency": "INR",
                        "name": "{{ $siteName }}",
                        "description": "Cart Checkout (" + cartItems.length + " items)",
                        "order_id": data.order_id,
                        "handler": function (response) {
                            fetch("{{ route('razorpay.verify') }}", {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Content-Type": "application/json",
                                    "Accept": "application/json"
                                },
                                body: JSON.stringify({
                                    razorpay_order_id: response.razorpay_order_id,
                                    razorpay_payment_id: response.razorpay_payment_id,
                                    razorpay_signature: response.razorpay_signature
                                })
                            })
                            .then(res => res.json())
                            .then(verifyData => {
                                if (verifyData.success) {
                                    localStorage.removeItem(window.CART_KEY);
                                    window.dispatchEvent(new CustomEvent('cart-updated'));
                                    window.location.href = "{{ route('library') }}?success=1";
                                } else {
                                    alert("Payment verification failed. Please contact support.");
                                }
                            });
                        },
                        "prefill": {
                            "name": data.user_name,
                            "email": data.user_email
                        },
                        "theme": {
                            "color": "{{ $brandColor }}"
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                });
                e.preventDefault();
            }
        }
    });
</script>
@endpush
@endsection
