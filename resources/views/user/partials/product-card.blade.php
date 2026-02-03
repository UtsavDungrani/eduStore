<div onclick="window.location.href='{{ route('products.show', $product->slug) }}'"
    class="bg-white rounded-2xl overflow-hidden shadow-md border border-[#D4AF37] flex flex-col hover:shadow-2xl transition-all group cursor-pointer relative hover:-translate-y-1 {{ $marginClass ?? '' }}">
    <div class="relative aspect-[4/3] bg-gray-100 overflow-hidden">
        @if($product->image_path)
            <img src="{{ $product->image_url }}"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                alt="{{ $product->title }}">
        @else
            <img src="https://placehold.co/600x400/2C1810/ffffff?text={{ urlencode($product->title) }}"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                alt="{{ $product->title }}">
        @endif
        <!-- Category Badge Moved to Title -->

        <!-- Strips Container (both triangular, side by side) -->
        @php
            $displayTag = $product->sale_percentage ? $product->sale_percentage . '% OFF' : null;
        @endphp
        <div class="absolute top-0 right-0 flex gap-0 z-30 pointer-events-none">
            <!-- Badge Strip (Triangular - Strip 2) -->
            @if($product->badge_strip_text)
                @php
                    $stripColor = $product->badge_strip_color ?? 'golden';
                    $colorMap = [
                        'red' => '#DC2626',
                        'blue' => '#2563EB',
                        'green' => '#16A34A',
                        'golden' => '#D97706',
                        'black' => '#1F2937',
                        'pink' => '#EC4899',
                        'orange' => '#EA580C',
                    ];
                    $bgColor = $colorMap[$stripColor] ?? '#D97706';
                @endphp
                <!-- Badge Strip (Triangular - Strip 2) -->
                <div class="w-20 h-20 relative z-10" style="margin-right: -4rem; margin-top: 1rem;">
                     <div class="absolute top-4 -right-12 w-32 text-white text-xs font-black py-1 text-center transform rotate-45 shadow-md border-b border-white/20 uppercase tracking-tight"
                        style="background-color: {{ $bgColor }}; width: 10rem;">
                        {{ $product->badge_strip_text }}
                    </div>
                </div>
            @endif

            <!-- Sale Strip (Triangular - Strip 1) -->
            @if($displayTag)
                <div class="w-20 h-20 overflow-hidden">
                    <div
                        class="absolute top-4 -right-8 w-28 bg-red-600 text-white text-xs font-black py-1 text-center transform rotate-45 shadow-md border-b border-white/20 uppercase tracking-tight">
                        {{ $displayTag }}
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="p-6 flex-1 flex flex-col">
        <div class="flex justify-between items-start gap-2 mb-2">
            <h3
                class="font-bold text-lg text-[#2C1810] line-clamp-2 font-serif group-hover:text-[#8B4513] transition-colors leading-tight">
                {{ $product->title }}
            </h3>
            <span
                class="shrink-0 bg-[#FDF6E3] border border-[#D4AF37] rounded px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-[#8B4513] whitespace-nowrap mt-1">{{ $product->category->name }}</span>
        </div>
        <p class="text-[#8B4513] text-sm mb-4 line-clamp-3 font-serif italic leading-relaxed">
            @php echo Str::limit(strip_tags($product->description), 100) @endphp</p>
        <div class="mt-auto flex items-center justify-between">
            @if($product->on_sale)
                <div class="flex items-baseline gap-2 leading-none">
                    <span class="text-2xl font-black text-red-600">₹{{ number_format($product->selling_price, 0) }}</span>
                    @if($product->original_price > $product->selling_price)
                        <span
                            class="text-sm text-gray-500 line-through">₹{{ number_format($product->original_price, 0) }}</span>
                    @endif
                </div>
            @elseif($product->original_price && $product->original_price > $product->price)
                <div class="flex items-baseline gap-2 leading-none">
                    <span
                        class="text-2xl font-black text-[#2C1810] font-serif">₹{{ number_format($product->price, 0) }}</span>
                    <span
                        class="text-sm text-[#8B4513] line-through decoration-[#D4AF37]">₹{{ number_format($product->original_price, 0) }}</span>
                </div>
            @else
                <span class="text-2xl font-black text-[#2C1810] font-serif">₹{{ number_format($product->price, 0) }}</span>
            @endif

            @php
                $isPurchased = auth()->check() && auth()->user()->hasPurchased($product->id);
            @endphp

            @if($product->price == 0 || $product->is_demo || $isPurchased)
                <a href="{{ route('content.view', $product->id) }}" onclick="event.stopPropagation()"
                    class="{{ $isPurchased ? 'bg-emerald-600' : 'bg-emerald-500' }} hover:bg-emerald-600 text-white p-3 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg active:scale-95 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                </a>
            @else
                <div x-data="{ 
                            adding: false,
                            inCart: false,
                            checkCart() {
                                let cart = JSON.parse(localStorage.getItem(window.CART_KEY) || '[]');
                                this.inCart = cart.some(i => i.id === {{ $product->id }});
                            },
                            init() {
                                this.checkCart();
                                window.addEventListener('cart-updated', () => this.checkCart());
                            },
                            addToCart(e) {
                                 // Stop propagation if passed manually, just in case
                                 if(e) e.stopPropagation();

                                @auth
                                    if (this.inCart) return;
                                    this.adding = true;
                                    let cart = JSON.parse(localStorage.getItem(window.CART_KEY) || '[]');
                                    cart.push({
                                        id: {{ $product->id }},
                                        title: '{{ addslashes($product->title) }}',
                                        price: {{ $product->selling_price }},
                                        thumbnail: '{{ $product->image_path ? $product->image_url : "https://placehold.co/100x100/2C1810/ffffff?text=" . urlencode(substr($product->title, 0, 2)) }}',
                                        qty: 1
                                    });
                                    localStorage.setItem(window.CART_KEY, JSON.stringify(cart));
                                    window.dispatchEvent(new CustomEvent('cart-updated'));
                                    setTimeout(() => { this.adding = false; }, 1000);
                                @else
                                    window.location.href = '{{ route('login') }}';
                                @endauth
                            }
                        }" @click.stop>
                    <button @click="addToCart($event)"
                        :class="inCart ? 'bg-emerald-600 cursor-default' : (adding ? 'bg-emerald-600 scale-95' : 'bg-[#2C1810] hover:bg-[#1A0D00]')"
                        class="text-white p-3 rounded-xl transition-all duration-300 relative overflow-hidden group/btn shadow-md hover:shadow-lg active:scale-95 border border-[#D4AF37]">
                        <template x-if="!adding && !inCart">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </template>
                        <template x-if="adding || inCart">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </template>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>