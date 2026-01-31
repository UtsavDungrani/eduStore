@extends('layouts.app')

@section('content')
@php
    $isDiscounted = ($product->original_price > $product->selling_price);
    $savings = $isDiscounted ? ($product->original_price - $product->selling_price) : 0;
    
    $saleTag = $product->sale_percentage ? $product->sale_percentage . '% OFF' : null;

    if ($saleTag && str_contains($saleTag, '%') && !str_contains(strtoupper($saleTag), 'OFF')) {
        $saleTag .= ' OFF';
    }
@endphp
<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Product Image/Preview -->
        <div class="w-full lg:w-1/2">
            <div class="bg-white rounded-3xl overflow-hidden shadow-xl border border-gray-100 relative">
                @if($saleTag)
                    <div class="absolute top-0 right-0 w-24 h-24 overflow-hidden z-20 pointer-events-none">
                        <div class="absolute top-4 -right-8 w-32 bg-red-600 text-white text-[12px] font-black py-1.5 text-center transform rotate-45 shadow-lg border-b border-white/20 uppercase tracking-tighter">
                            {{ $saleTag }}
                        </div>
                    </div>
                @endif

                <!-- Highlight Badge (Advanced Customizable) -->
                @if($product->highlight_badge)
                    @php
                        $shape = $product->highlight_badge_shape ?? 'pill';
                        $color = $product->highlight_badge_color ?? 'golden';

                        
                        // Color Mapping
                        $colors = [
                            'golden' => 'bg-amber-400 bg-gradient-to-br from-[#FDE68A] via-[#F59E0B] to-[#B45309] text-black border-t border-white/40 border-l border-white/20',
                            'red' => 'bg-red-500 bg-gradient-to-br from-red-400 via-red-600 to-red-800 text-white border-t border-white/30 border-l border-white/10',
                            'blue' => 'bg-blue-500 bg-gradient-to-br from-blue-400 via-blue-600 to-blue-800 text-white border-t border-white/30 border-l border-white/10',
                            'green' => 'bg-emerald-500 bg-gradient-to-br from-emerald-400 via-emerald-600 to-emerald-800 text-white border-t border-white/30 border-l border-white/10',
                            'black' => 'bg-gray-900 bg-gradient-to-br from-gray-700 via-gray-900 to-black text-white border-t border-white/20 border-l border-white/10',
                            'pink' => 'bg-pink-500 bg-gradient-to-br from-pink-400 via-pink-600 to-pink-800 text-white border-t border-white/30 border-l border-white/10',
                            'orange' => 'bg-orange-500 bg-gradient-to-br from-orange-400 via-orange-600 to-orange-800 text-white border-t border-white/30 border-l border-white/10',
                        ];

                        // Shape Mapping
                        $shapeClasses = 'rounded-full px-5 py-2'; // Larger for show page
                        $clipPath = '';

                        switch($shape) {
                            case 'soft_rectangle': $shapeClasses = 'rounded-2xl px-5 py-2'; break;
                            case 'tag': $shapeClasses = 'rounded-r-full rounded-l-none pl-8 pr-6 py-3 -ml-2'; break;
                            case 'circle': $shapeClasses = 'rounded-full h-20 w-20 flex items-center justify-center p-2 text-center leading-tight shrink-0'; break;
                            case 'square': $shapeClasses = 'rounded-none h-20 w-20 flex items-center justify-center p-2 text-center leading-tight shrink-0'; break;

                            case 'banner': $shapeClasses = 'w-20 pt-4 pb-8 px-2 min-h-[7rem] flex items-center justify-center text-center leading-none text-sm'; $clipPath = 'polygon(100% 0, 100% 100%, 50% 85%, 0 100%, 0 0)'; break;
                            case 'flag': $shapeClasses = 'pl-4 pr-5 py-2.5'; $clipPath = 'polygon(0 0, 100% 0, 95% 50%, 100% 100%, 0 100%)'; break;
                            case 'arrow': $shapeClasses = 'pl-5 pr-6 py-2.5'; $clipPath = 'polygon(0 0, 95% 0, 100% 50%, 95% 100%, 0 100%)'; break;
                        }

                        // Position Mapping
                        $posClasses = 'top-6 left-6';
                        if($shape === 'banner') $posClasses = 'top-0 left-6';
                        if($shape === 'tag') $posClasses = 'top-6 left-0';

                    @endphp
                    <div class="absolute {{ $posClasses }} z-20 pointer-events-none w-fit max-w-[calc(100%-3rem)]">
                        <div class="{{ $colors[$color] ?? $colors['golden'] }} text-xs md:text-sm font-black {{ $shapeClasses }} shadow-[0_15px_35px_rgba(0,0,0,0.4)] uppercase tracking-widest flex items-center justify-center gap-3 transition-all duration-300"
                             style="{{ $clipPath ? 'clip-path: ' . $clipPath . ';' : '' }}">
                            @if(!in_array($shape, ['circle', 'square', 'banner']))
                                <i class="fas fa-bolt text-sm {{ $color === 'golden' ? 'text-amber-950' : 'text-white/80' }} shrink-0"></i>
                            @endif
                            <span class="{{ in_array($shape, ['circle', 'square', 'banner']) ? 'whitespace-normal leading-none break-words' : 'whitespace-nowrap' }}">{{ $product->highlight_badge }}</span>
                        </div>
                    </div>
                @endif
                @if($product->image_path)
                    <img src="{{ $product->image_url }}" class="w-full aspect-[4/3] object-cover">
                @else
                    <img src="https://placehold.co/800x600/2C1810/ffffff?text={{ urlencode($product->title) }}" class="w-full aspect-[4/3] object-cover">
                @endif
            </div>
            
            <div class="mt-8 grid grid-cols-3 gap-4">
                <div class="bg-primary/10 p-4 rounded-2xl text-center">
                    <div class="text-primary text-xl mb-1"><i class="fas fa-eye text-sm"></i></div>
                    <div class="text-[10px] uppercase font-bold text-primary/60">Security</div>
                    <div class="text-sm font-bold text-primary">View-Only</div>
                </div>
                <div class="bg-primary/10 p-4 rounded-2xl text-center">
                    <div class="text-primary text-xl mb-1"><i class="fas fa-file-pdf text-sm"></i></div>
                    <div class="text-[10px] uppercase font-bold text-primary/60">Format</div>
                    <div class="text-sm font-bold text-primary">Digital PDF</div>
                </div>
                <div class="bg-primary/10 p-4 rounded-2xl text-center">
                    <div class="text-primary text-xl mb-1"><i class="fas fa-sync text-sm"></i></div>
                    <div class="text-[10px] uppercase font-bold text-primary/60">Access</div>
                    <div class="text-sm font-bold text-primary">Lifetime</div>
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="w-full lg:w-1/2">
            <nav class="flex text-sm font-medium text-gray-500 mb-6">
                <a href="{{ route('home') }}" class="hover:text-primary">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-primary">{{ $product->category->name }}</a>
            </nav>

            <h1 class="text-4xl font-extrabold text-gray-900 mb-4 leading-tight">{{ $product->title }}</h1>
            
            <!-- Pricing & Badges (Refined) -->
            <div class="bg-white border border-gray-100 rounded-3xl p-6 mb-10 shadow-sm relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-2">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Exclusive Access</span>
                            @if($isDiscounted)
                            <span class="bg-red-600 text-white text-[10px] font-black px-2 py-0.5 rounded-sm uppercase transform -skew-x-12">
                                Special Deal
                            </span>
                            @endif
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-baseline gap-4">
                            <span class="text-3xl md:text-5xl font-black text-primary">₹{{ number_format($product->selling_price, 2) }}</span>
                            @if($isDiscounted)
                                <span class="text-lg md:text-xl text-gray-400 line-through font-bold decoration-red-500/50">₹{{ number_format($product->original_price, 2) }}</span>
                            @endif
                        </div>

                        <div class="bg-green-50 text-green-700 border border-green-100 px-3 py-1.5 md:px-5 md:py-3 rounded-2xl flex items-center">
                            <span class="text-[10px] md:text-xs font-bold uppercase tracking-widest flex items-center gap-2 whitespace-nowrap">
                                <i class="fas fa-check-circle"></i> Lifetime Access
                            </span>
                        </div>
                    </div>

                    @if($isDiscounted)
                        <p class="text-emerald-700 font-bold mt-2 flex items-center gap-2 text-sm">
                            <i class="fas fa-circle-check"></i> Instant Savings: ₹{{ number_format($savings, 2) }}
                        </p>
                    @endif
                </div>

                @php
                    $activeMethod = $siteSettings['active_payment_method'] ?? 'razorpay';
                @endphp

                <!-- Buy Now Button -->
                <div class="mt-8 relative z-10">
                    @auth
                        @if(!($product->price == 0 || $product->is_demo || auth()->user()->hasRole('Super Admin') || auth()->user()->hasPurchased($product->id)))
                            @if($activeMethod == 'razorpay' || $activeMethod == 'both')
                                <button id="rzp-button" class="w-full bg-primary text-white py-4 rounded-2xl font-bold hover:opacity-90 transition-all shadow-lg flex items-center justify-center gap-3 relative overflow-hidden group">
                                    <span class="absolute inset-0 bg-white/20 group-hover:translate-x-full transition-transform duration-500 ease-out -skew-x-12"></span>
                                    <span class="relative">Buy Now - ₹{{ number_format($product->selling_price, 2) }}</span>
                                    <i class="fas fa-arrow-right relative"></i>
                                </button>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('products.buy', $product->id) }}" class="w-full bg-primary text-white py-4 rounded-2xl font-bold hover:opacity-90 transition-all shadow-lg flex items-center justify-center gap-3 relative overflow-hidden group">
                            <span class="absolute inset-0 bg-white/20 group-hover:translate-x-full transition-transform duration-500 ease-out -skew-x-12"></span>
                            <span class="relative">Buy Now - ₹{{ number_format($product->selling_price, 2) }}</span>
                            <i class="fas fa-arrow-right relative"></i>
                        </a>
                    @endauth
                </div>
                
                <!-- Background Decoration -->
                <div class="absolute -right-4 -bottom-4 opacity-[0.02] text-8xl transform -rotate-12 group-hover:scale-110 transition-transform duration-700">
                    <i class="fas fa-certificate text-primary"></i>
                </div>
            </div>

            <div class="prose prose-stone max-w-none text-gray-600 mb-10">
                {!! nl2br(e($product->description)) !!}
            </div>

            @auth
                @if($product->price == 0 || $product->is_demo || auth()->user()->hasRole('Super Admin') || auth()->user()->hasPurchased($product->id))
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('content.view', $product->id) }}" class="flex-1 bg-primary text-white text-center py-4 rounded-2xl font-bold text-lg hover:opacity-90 transition-all shadow-lg flex items-center justify-center">
                            <i class="fas fa-book-open mr-3"></i> Open Secure Viewer
                        </a>
                        @if($product->is_downloadable)
                            <a href="{{ route('content.download', $product->id) }}" class="sm:w-auto px-8 bg-gray-100 text-gray-800 text-center py-4 rounded-2xl font-bold hover:bg-gray-200 transition-all flex items-center justify-center">
                                <i class="fas fa-download mr-3"></i>
                            </a>
                        @endif
                    </div>
                @else
                    <!-- Payment Options -->
                    <div class="space-y-6" x-data="{ showManualForm: false }">
                        @if($activeMethod == 'manual' || $activeMethod == 'both')
                            <!-- Manual Payment Manual -->
                            <div class="bg-gray-900 rounded-3xl p-6 text-white overflow-hidden relative">
                                <div class="absolute top-0 right-0 p-4 opacity-10">
                                    <i class="fas fa-qrcode text-9xl"></i>
                                </div>
                                
                                <div x-show="!showManualForm">
                                    <h3 class="text-xl font-bold mb-2 flex items-center gap-2 relative z-10"><i class="fas fa-qrcode text-yellow-400"></i> Manual / QR Pay</h3>
                                    <p class="text-gray-400 mb-6 text-sm relative z-10">Scan QR or Transfer to <strong>{{ $siteSettings['upi_id'] ?? 'N/A' }}</strong></p>
                                    
                                    @if(!empty($siteSettings['qr_code_url']))
                                        <div class="flex justify-center mb-6">
                                            <div class="p-2 bg-white rounded-xl">
                                                <img src="{{ $siteSettings['qr_code_url'] }}" class="w-48 h-48 object-contain">
                                            </div>
                                        </div>
                                    @endif

                                    <button @click="showManualForm = true" class="w-full bg-white/10 text-white py-4 rounded-2xl font-bold hover:bg-white/20 transition-all border border-white/5 relative z-10">
                                        Upload Payment Screenshot
                                    </button>
                                </div>
                                
                                <div x-show="showManualForm" x-cloak>
                                    <h3 class="text-xl font-bold mb-6 flex items-center gap-3">
                                        <button @click="showManualForm = false" class="text-gray-400 hover:text-white"><i class="fas fa-arrow-left text-sm"></i></button>
                                        Upload Proof
                                    </h3>
                                    <form action="{{ route('payments.store', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest">Transaction / UTR ID</label>
                                            <input type="text" name="transaction_id" required class="w-full bg-zinc-800 border-none rounded-xl px-4 py-3 text-white focus:ring-primary" placeholder="Enter Reference Number">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest">Screenshot</label>
                                            <input type="file" name="payment_proof" class="w-full bg-zinc-800 border-none rounded-xl px-4 py-2 text-sm text-gray-400" required>
                                        </div>
                                        <button type="submit" class="w-full bg-green-500 text-white py-4 rounded-2xl font-bold hover:bg-green-600 transition-all shadow-lg mt-4">Submit Verification</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>

                    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                    <script>
                        document.getElementById('rzp-button').onclick = function(e) {
                            fetch("{{ route('razorpay.order', $product->id) }}", {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Content-Type": "application/json",
                                    "Accept": "application/json"
                                }
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
                                    "description": "Product Purchase: " + data.product_name,
                                    "order_id": data.order_id,
                                    "handler": function (response) {
                                        // Verify payment
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
                                                window.location.reload();
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
                    </script>

                    @if(session('trigger_purchase'))
                        <script>
                            window.addEventListener('load', function() {
                                setTimeout(function() {
                                    const buyBtn = document.getElementById('rzp-button');
                                    if (buyBtn) {
                                        buyBtn.click();
                                    }
                                }, 800);
                            });
                        </script>
                    @endif
                @endif
            @endauth

            <div class="mt-12 p-6 bg-red-50 rounded-2xl border border-red-100">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-lock text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-red-800 uppercase tracking-tight">Security Notice</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p>To protect our authors, the following actions are strictly prohibited and monitored:</p>
                            <ul class="list-disc list-inside mt-1 space-y-1 font-medium">
                                <li>Right-click or Copying context</li>
                                <li>Downloading (unless allowed)</li>
                                <li>Printing or Screen-capturing</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
