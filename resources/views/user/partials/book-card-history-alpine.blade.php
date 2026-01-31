<div class="book-container group relative w-40 h-60 md:w-48 md:h-72 perspective-1000 z-20 cursor-pointer origin-bottom {{ $marginClass ?? 'mb-1' }}" 
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
        <div class="absolute top-1 bottom-1 right-0 w-8 md:w-10 bg-white transform translate-z-[-2px] translate-x-[2px] shadow-sm rounded-r-sm bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPgo8cmVjdCB3aWR0aD0iNCIgaGVpZ2h0PSI0IiBfiWxsPSIjZmZmIi8+CjxyZWN0IHdpZHRoPSI0IiBoZWlnaHQ9IjEiIGZpbGw9IiNjY2MiLz4KPC9zdmc+')]"></div>

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
