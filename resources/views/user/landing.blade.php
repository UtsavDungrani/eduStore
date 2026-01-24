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
                <!-- Mobile Slider (Recently Viewed) -->
                <div class="md:hidden">
                    <div class="relative shelf-container mb-12">
                        <div class="swiper recentSwiper w-full !overflow-visible">
                            <div class="swiper-wrapper relative z-10">
                                <template x-for="book in books" :key="book.id">
                                    <div class="swiper-slide flex justify-center items-end pb-4 cont-read-item" :data-title="book.title.toLowerCase()" :data-category="book.category">
                                         <div class="book-container group relative w-40 h-60 md:w-48 md:h-72 perspective-1000 z-20 cursor-pointer origin-bottom" 
                                              @click="window.location.href = book.url"
                                              :data-title="book.title.toLowerCase()">
                                            <div class="book relative w-full h-full transform-style-3d transition-transform duration-500 group-hover:rotate-y-[-20deg] shadow-xl">
                                                
                                                <!-- Front Cover -->
                                                <div class="absolute inset-0 bg-cover bg-center rounded-r-md shadow-inner origin-left z-10" 
                                                     :style="`background-image: url('${book.image}'); background-size: cover;`">
                                                    
                                                    <div class="absolute inset-0 bg-gradient-to-r from-black/40 to-transparent opacity-50 rounded-r-md"></div>
                                        
                                                    <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/90 to-transparent text-white rounded-br-md">
                                                        <h3 class="font-serif font-bold text-sm md:text-lg leading-tight line-clamp-2 shadow-sm" x-text="book.title"></h3>
                                                        <p class="text-[10px] md:text-xs text-gray-300 mt-1 font-sans">Continue Reading</p>
                                                    </div>
                                                    
                                                    <!-- Progress Bar Overlay -->
                                                    <div class="absolute bottom-0 left-0 right-0 h-1 md:h-1.5 bg-gray-700/50 backdrop-blur-sm z-20">
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
                                        
                                            <!-- Actions Panel -->
                                            <div class="absolute -bottom-8 left-[-10px] right-[-10px] bg-white/95 backdrop-blur-sm p-4 pt-6 rounded-xl shadow-xl opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-[-10px] transition-all duration-500 z-50 pointer-events-none group-hover:pointer-events-auto border border-gray-100">
                                                <div class="mb-4 text-center">
                                                    <p class="text-[10px] md:text-xs text-gray-500 italic font-serif leading-relaxed">
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
                                    </div>
                                </template>
                            </div>

                            <!-- Navigation Buttons -->
                            <div x-show="books.length > 1" class="swiper-button-prev !text-primary !w-10 !h-10 bg-white/90 backdrop-blur shadow-lg rounded-full after:!text-lg hover:bg-white transition-all transform -translate-x-4 border border-gray-100 flex items-center justify-center z-30"></div>
                            <div x-show="books.length > 1" class="swiper-button-next !text-primary !w-10 !h-10 bg-white/90 backdrop-blur shadow-lg rounded-full after:!text-lg hover:bg-white transition-all transform translate-x-4 border border-gray-100 flex items-center justify-center z-30"></div>
                        </div>
                        <!-- Shelf Board -->
                        <div class="absolute bottom-0 left-0 right-0 h-8 md:h-12 bg-[#5d4037] shadow-lg rounded-sm transform translate-y-1/2 flex items-center justify-center overflow-hidden z-0">
                            <div class="absolute top-0 w-full h-2 bg-[#8d6e63] opacity-50"></div>
                        </div>
                        <!-- Shelf Shadow/Depth -->
                        <div class="absolute bottom-[-20px] left-2 right-2 h-4 bg-black/20 blur-xl rounded-full"></div>
                    </div>
                </div>

                <!-- Desktop Shelf (Recently Viewed) -->
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
                        
                            <!-- Actions Panel -->
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
            <!-- Mobile Slider (Recently Added) -->
            <div class="md:hidden">
                <div class="relative shelf-container mb-12">
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
                        <!-- Navigation Buttons -->
                        @if($recentlyAddedProducts->count() > 1)
                            <div class="swiper-button-prev md:hidden !text-primary !w-10 !h-10 bg-white/90 backdrop-blur shadow-lg rounded-full after:!text-lg hover:bg-white transition-all transform -translate-x-4 border border-gray-100 flex items-center justify-center z-30"></div>
                            <div class="swiper-button-next md:hidden !text-primary !w-10 !h-10 bg-white/90 backdrop-blur shadow-lg rounded-full after:!text-lg hover:bg-white transition-all transform translate-x-4 border border-gray-100 flex items-center justify-center z-30"></div>
                        @endif
                    </div>
                    <!-- Shelf Board -->
                    <div class="absolute bottom-0 left-0 right-0 h-8 md:h-12 bg-[#5d4037] shadow-lg rounded-sm transform translate-y-1/2 flex items-center justify-center overflow-hidden z-0">
                        <div class="absolute top-0 w-full h-2 bg-[#8d6e63] opacity-50"></div>
                    </div>
                    <!-- Shelf Shadow/Depth -->
                    <div class="absolute bottom-[-20px] left-2 right-2 h-4 bg-black/20 blur-xl rounded-full"></div>
                </div>
            </div>

            <!-- Desktop Shelf (Recently Added) -->
            <div class="hidden md:block space-y-20 mt-12">
                @foreach($recentlyAddedProducts->chunk(4) as $chunk)
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
                            <!-- Mobile Slider (Featured Content) -->
            <div class="md:hidden">
                <div class="relative shelf-container mb-12">
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
                        <!-- Navigation Buttons -->
                        @if($featuredProducts->count() > 1)
                            <div class="swiper-button-prev md:hidden !text-primary !w-10 !h-10 bg-white/90 backdrop-blur shadow-lg rounded-full after:!text-lg hover:bg-white transition-all transform -translate-x-4 border border-gray-100 flex items-center justify-center z-30"></div>
                            <div class="swiper-button-next md:hidden !text-primary !w-10 !h-10 bg-white/90 backdrop-blur shadow-lg rounded-full after:!text-lg hover:bg-white transition-all transform translate-x-4 border border-gray-100 flex items-center justify-center z-30"></div>
                        @endif
                    </div>
                    <!-- Shelf Board -->
                    <div class="absolute bottom-0 left-0 right-0 h-8 md:h-12 bg-[#5d4037] shadow-lg rounded-sm transform translate-y-1/2 flex items-center justify-center overflow-hidden z-0">
                        <div class="absolute top-0 w-full h-2 bg-[#8d6e63] opacity-50"></div>
                    </div>
                    <!-- Shelf Shadow/Depth -->
                    <div class="absolute bottom-[-20px] left-2 right-2 h-4 bg-black/20 blur-xl rounded-full"></div>
                </div>
            </div>

            <!-- Desktop Shelf (Featured Content) -->
            <div class="hidden md:block space-y-20 mt-12">
                @foreach($featuredProducts->chunk(4) as $chunk)
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

    /* Centering Override for Sliders */
    .featuredSwiper .book-container,
    .recentSwiper .book-container,
    .recentlyAddedSwiper .book-container {
        margin-left: auto !important;
        margin-right: auto !important;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        transform: translateX(20px); /* Compensation for spine */
    }
    
    @media (max-width: 768px) {
        .featuredSwiper .book-container,
        .recentSwiper .book-container,
        .recentlyAddedSwiper .book-container {
            transform: translateX(20px);
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
    
    /* Ensure Swiper arrows are visible and properly styled */
    .swiper-button-next::after, .swiper-button-prev::after {
        font-family: "Font Awesome 6 Free" !important;
        font-weight: 900 !important;
        font-size: 1.25rem !important;
    }
    .swiper-button-next::after { content: "\f054" !important; }
    .swiper-button-prev::after { content: "\f053" !important; }
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
                spaceBetween: 30,
                centeredSlides: true,
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: { slidesPerView: 2, centeredSlides: false },
                    1024: { slidesPerView: 4, centeredSlides: false },
                    1280: { slidesPerView: 5, centeredSlides: false }
                }
            };

            var featuredSwiper = new Swiper(".featuredSwiper", {
                ...commonConfig,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                }
            });

            var recentlyAddedSwiper = new Swiper(".recentlyAddedSwiper", {
                ...commonConfig,
                autoplay: {
                    delay: 3500,
                    disableOnInteraction: false,
                }
            });

            @auth
            var recentSwiper = new Swiper(".recentSwiper", {
                ...commonConfig,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                }
            });

            window.addEventListener('book-opened', () => {
                if(recentSwiper) recentSwiper.update();
            });
            @endauth
        }, 50); // Small 50ms delay for paint

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
