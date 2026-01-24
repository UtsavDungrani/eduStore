@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Product Image/Preview -->
        <div class="w-full lg:w-1/2">
            <div class="bg-white rounded-3xl overflow-hidden shadow-xl border border-gray-100">
                @if($product->image_path)
                    <img src="{{ $product->image_url }}" class="w-full aspect-[4/3] object-cover">
                @else
                    <img src="https://placehold.co/800x600/1e40af/ffffff?text={{ urlencode($product->title) }}" class="w-full aspect-[4/3] object-cover">
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
            @php
                $isDiscounted = ($product->original_price > $product->selling_price);
                $savings = $isDiscounted ? ($product->original_price - $product->selling_price) : 0;
                $saleTag = $product->sale_tag;
                if ($saleTag && str_contains($saleTag, '%') && !str_contains(strtoupper($saleTag), 'OFF')) {
                    $saleTag .= ' OFF';
                }
            @endphp

            <div class="bg-white border border-gray-100 rounded-3xl p-6 mb-10 shadow-sm relative overflow-hidden group">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 items-start relative z-10">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                             <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Exclusive Access</span>
                             @if($isDiscounted)
                                <span class="bg-red-600 text-white text-[10px] font-black px-2 py-0.5 rounded-sm uppercase transform -skew-x-12">
                                    Special Deal
                                </span>
                             @endif
                        </div>

                        <div class="flex items-baseline gap-4">
                            <span class="text-5xl font-black text-primary">₹{{ number_format($product->selling_price, 2) }}</span>
                            @if($isDiscounted)
                                <span class="text-xl text-gray-400 line-through font-bold decoration-red-500/50">₹{{ number_format($product->original_price, 2) }}</span>
                            @endif
                        </div>

                        @if($isDiscounted)
                            <p class="text-emerald-700 font-bold mt-2 flex items-center gap-2 text-sm">
                                <i class="fas fa-circle-check"></i> Instant Savings: ₹{{ number_format($savings, 2) }}
                            </p>
                        @endif
                    </div>

                    @if($saleTag)
                        <div class="flex flex-col items-center gap-1 bg-red-50 border border-red-100 px-6 py-3 rounded-2xl shadow-sm animate-bounce">
                            <span class="text-2xl font-black text-red-600 leading-none">{{ $saleTag }}</span>
                            <span class="text-[10px] uppercase font-bold text-red-700 tracking-tighter opacity-70 italic whitespace-nowrap">Offer Applied</span>
                        </div>
                    @else
                        <div class="bg-green-50 text-green-700 border border-green-100 px-5 py-3 rounded-2xl">
                            <span class="text-xs font-bold uppercase tracking-widest flex items-center gap-2 whitespace-nowrap">
                                <i class="fas fa-check-circle"></i> Lifetime Access
                            </span>
                        </div>
                    @endif
                </div>
                
                <!-- Background Decoration -->
                <div class="absolute -right-4 -bottom-4 opacity-[0.02] text-8xl transform -rotate-12 group-hover:scale-110 transition-transform duration-700">
                    <i class="fas fa-certificate text-primary"></i>
                </div>
            </div>

            <div class="prose prose-blue max-w-none text-gray-600 mb-10">
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
                        @php
                            $activeMethod = $siteSettings['active_payment_method'] ?? 'razorpay';
                            $showUpiApps = $siteSettings['upi_apps_visibility'] ?? 'show';
                        @endphp
                        
                        @if($activeMethod == 'razorpay' || $activeMethod == 'both')
                            <!-- Razorpay Section -->
                                <button id="rzp-button" class="w-full bg-primary text-white py-4 rounded-2xl font-bold hover:opacity-90 transition-all shadow-lg flex items-center justify-center gap-3 relative overflow-hidden group">
                                    <span class="absolute inset-0 bg-white/20 group-hover:translate-x-full transition-transform duration-500 ease-out -skew-x-12"></span>
                                    <span class="relative">Pay Now - ₹{{ number_format($product->selling_price, 2) }}</span>
                                    <i class="fas fa-arrow-right relative"></i>
                                </button>

                                @if($showUpiApps == 'show')
                                    <div class="mt-4 pt-4 border-t border-indigo-100">
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 text-center">Pay via UPI App</p>
                                        <div class="flex justify-center gap-4 grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all">
                                            <!-- UPI App Icons (Simulated with text/FA for now as requested plan didn't provide assets) -->
                                            <button class="flex flex-col items-center gap-1 group" onclick="document.getElementById('rzp-button').click()">
                                                <div class="w-12 h-12 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center group-hover:border-primary transition-colors">
                                                    <i class="fab fa-google-pay text-2xl text-gray-600 group-hover:text-primary"></i>
                                                </div>
                                                <span class="text-[10px] font-bold text-gray-400">GPay</span>
                                            </button>
                                            <button class="flex flex-col items-center gap-1 group" onclick="document.getElementById('rzp-button').click()">
                                                <div class="w-12 h-12 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center group-hover:border-purple-500 transition-colors">
                                                    <i class="fas fa-mobile-alt text-2xl text-gray-600 group-hover:text-purple-500"></i>
                                                </div>
                                                <span class="text-[10px] font-bold text-gray-400">PhonePe</span>
                                            </button>
                                            <button class="flex flex-col items-center gap-1 group" onclick="document.getElementById('rzp-button').click()">
                                                <div class="w-12 h-12 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center group-hover:border-blue-400 transition-colors">
                                                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/24/Paytm_Logo_%28standalone%29.svg" class="h-4 opacity-60 group-hover:opacity-100"> 
                                                    <!-- Fallback if img breaks or blocked -->
                                                </div>
                                                <span class="text-[10px] font-bold text-gray-400">Paytm</span>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

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
                @endif
            @else
                <div class="bg-gray-100 rounded-3xl p-8 text-center border-2 border-dashed border-gray-300">
                    <p class="text-gray-600 mb-6 font-medium">Please login to access this content.</p>
                    <a href="{{ route('login') }}" class="inline-block bg-primary text-white px-10 py-4 rounded-2xl font-bold hover:opacity-90 transition-all">Login / Register</a>
                </div>
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
