<div @click="window.location.href = book.url" class="bg-white rounded-2xl overflow-hidden shadow-md border border-[#D4AF37] flex flex-col hover:shadow-2xl transition-all group cursor-pointer relative hover:-translate-y-1 {{ $marginClass ?? '' }}">
    <div class="relative aspect-[4/3] bg-gray-100 overflow-hidden">
        <img :src="book.image" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" :alt="book.title">
        
        <!-- Category Badge -->
        <div class="absolute top-4 left-4 bg-[#FDF6E3]/95 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-[#2C1810] shadow-sm border border-[#D4AF37] z-30" x-text="book.category">
        </div>

        <!-- Progress Bar Overlay -->
        <div class="absolute bottom-0 left-0 right-0 h-1 md:h-2 bg-gray-700/50 backdrop-blur-sm z-30">
             <div class="h-full bg-emerald-500 transition-all duration-300" :style="`width: ${(book.page / 20) * 100 > 100 ? 100 : ((book.page / 20) * 100)}%`"></div> 
        </div>
    </div>
    <div class="p-6 flex-1 flex flex-col">
        <h3 class="font-bold text-lg text-[#2C1810] mb-2 line-clamp-2 font-serif group-hover:text-[#8B4513] transition-colors" x-text="book.title"></h3>
        <p class="text-[#8B4513] text-sm mb-4 line-clamp-2 font-serif italic leading-relaxed">Continue Reading</p>
        
        <div class="mt-auto flex items-center justify-between">
            <div class="text-xs text-gray-500 font-serif lowercase italic">
                Resume from page <span x-text="book.page || 1" class="font-bold text-emerald-600"></span>
            </div>

            <button @click.stop="window.location.href = book.url" 
                    class="bg-emerald-600 hover:bg-emerald-500 text-white p-3 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg active:scale-95 flex items-center justify-center">
                <i class="fas fa-book-open"></i>
            </button>
        </div>
    </div>
</div>
