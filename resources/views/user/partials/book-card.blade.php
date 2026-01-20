<div class="book-container group relative w-48 h-72 perspective-1000 mx-auto {{ $marginClass ?? 'my-4' }} cursor-pointer" 
     onclick="window.location.href='{{ route('products.show', $product->slug) }}'">
    <div class="book relative w-full h-full transform-style-3d transition-transform duration-500 group-hover:rotate-y-[-20deg] shadow-xl">
        
        <!-- Front Cover -->
        <div class="absolute inset-0 bg-cover bg-center rounded-r-md shadow-inner origin-left z-10" 
             style="background-image: url('{{ $product->image_path ? $product->image_url : "https://placehold.co/400x600/2c3e50/ffffff?text=".urlencode($product->title) }}'); background-size: cover;"
             loading="lazy">
            
            <!-- Overlay Gradient for realism -->
            <div class="absolute inset-0 bg-gradient-to-r from-black/40 to-transparent opacity-50 rounded-r-md"></div>

            <!-- Title Overlay (Refined) -->
            <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/90 to-transparent text-white rounded-br-md">
                <h3 class="font-serif font-bold text-lg leading-tight line-clamp-2 shadow-sm">{{ $product->title }}</h3>
                <p class="text-xs text-gray-300 mt-1 font-sans">{{ $product->category->name }}</p>
            </div>
            
            <!-- Sale Tag -->
            @if($product->sale_tag)
                <div class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 shadow-md z-20">
                    {{ $product->sale_tag }}
                </div>
            @endif
        </div>

        <!-- Spine -->
        <div class="absolute top-0 bottom-0 left-0 w-12 bg-gray-800 transform -translate-x-full origin-right rotate-y-[-90deg] flex flex-col justify-center items-center shadow-inner" style="background-color: #1a202c;">
             <span class="text-white text-xs font-bold tracking-widest writing-vertical-rl rotate-180 line-clamp-1 py-4 opacity-80">{{ $product->title }}</span>
        </div>

        <!-- Pages (Right Side) -->
        <div class="absolute top-1 bottom-1 right-0 w-10 bg-white transform translate-z-[-2px] translate-x-[2px] shadow-sm rounded-r-sm bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPgo8cmVjdCB3aWR0aD0iNCIgaGVpZ2h0PSI0IiBmaWxsPSIjZmZmIi8+CjxyZWN0IHdpZHRoPSI0IiBoZWlnaHQ9IjEiIGZpbGw9IiNjY2MiLz4KPC9zdmc+')]"></div>

        <!-- Back Cover (Visual only for rotation depth) -->
        <div class="absolute inset-0 bg-gray-900 transform translate-z-[-25px] rounded-l-md shadow-xl"></div>
    </div>
    
    <!-- Shelf Shadow -->
    <div class="absolute -bottom-4 left-2 right-2 h-4 bg-black/20 blur-md rounded-full transform scale-x-90 group-hover:scale-x-100 transition-transform duration-500"></div>

    <!-- Description & Actions Panel -->
    <div class="absolute -bottom-8 left-[-10px] right-[-10px] bg-white/95 backdrop-blur-sm p-4 pt-6 rounded-xl shadow-xl opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-[-10px] transition-all duration-500 z-50 pointer-events-none group-hover:pointer-events-auto border border-gray-100">
        <!-- Description -->
        <div class="mb-4 text-center">
            <p class="text-xs text-gray-500 italic font-serif leading-relaxed line-clamp-3">
                {{ $product->description ? Str::limit(strip_tags($product->description), 80) : "Admin can add brief description here to help users understand content before opening" }}
            </p>
        </div>

        <!-- Actions -->
        <div class="flex justify-center gap-2">
             @php
                $isPurchased = auth()->check() && auth()->user()->hasPurchased($product->id);
            @endphp

            @if($product->price == 0 || $product->is_demo || $isPurchased)
                <a href="{{ route('content.view', $product->id) }}" 
                   onclick="event.stopPropagation(); window.saveBookToLibrary({id: {{ $product->id }}, title: '{{ addslashes($product->title) }}', image: '{{ $product->image_path ? $product->image_url : "https://placehold.co/400x600/2c3e50/ffffff?text=".urlencode($product->title) }}', url: '{{ route('content.view', $product->id) }}'})" 
                   class="bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-2 rounded-full text-xs font-bold shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all flex items-center gap-2 uppercase tracking-wider">
                    <i class="fas fa-book-open"></i> Read Now
                </a>
            @else
                 <button class="bg-primary hover:bg-blue-600 text-white px-6 py-2 rounded-full text-xs font-bold shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all flex items-center gap-2 uppercase tracking-wider">
                    <i class="fas fa-info-circle"></i> Details
                </button>
            @endif
        </div>
    </div>
</div>
