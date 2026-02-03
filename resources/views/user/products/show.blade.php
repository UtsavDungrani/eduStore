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
    <div class="max-w-7xl mx-auto px-4 py-12" x-data="previewModal()" @open-preview.window="openPreview()">
        <!-- Preview Modal (Unified for PDF and Demo Images) -->
        <div x-show="showPreviewModal" style="display: none;"
            class="fixed inset-0 z-[100] flex items-center justify-center px-4" x-cloak>
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="showPreviewModal = false"></div>
            <div
                class="bg-zinc-900 w-full max-w-4xl max-h-[90vh] rounded-3xl overflow-hidden relative shadow-2xl flex flex-col -mt-16 md:mt-0">
                <!-- Header -->
                <div class="p-6 border-b border-white/10 flex items-center justify-between bg-zinc-900/50 backdrop-blur">
                    <div>
                        <h3 class="text-xl font-bold text-white" x-text="hasDemoImages ? 'Quick Preview' : 'Free Preview'">
                        </h3>
                        <p class="text-xs text-zinc-400">Viewing sample pages for {{ Str::limit($product->title, 40) }}</p>
                    </div>
                    <button @click="showPreviewModal = false"
                        class="w-10 h-10 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center text-white transition-all">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Content Area (Scrollable) -->
                <div class="flex-1 overflow-y-auto p-6 pt-0 sm:pt-6 bg-zinc-950/50 text-center" id="preview-container">
                    <div x-show="loading" class="py-20">
                        <i class="fas fa-circle-notch fa-spin text-4xl text-primary"></i>
                        <p class="text-zinc-500 mt-4 text-sm font-medium animate-pulse">Loading preview...</p>
                    </div>

                    <div x-show="!loading" class="space-y-8">
                        <!-- PDF Pages Container -->
                        <template x-if="!hasDemoImages">
                            <div id="pdf-preview-pages" class="space-y-6 flex flex-col items-center"></div>
                        </template>

                        <!-- Demo Images Container -->
                        <template x-if="hasDemoImages">
                            <div class="space-y-6 flex flex-col items-center">
                                @foreach($product->demoImages as $image)
                                    <div
                                        class="relative shadow-[0_10px_30px_rgba(0,0,0,0.5)] transition-transform hover:scale-[1.01] duration-300 bg-white rounded-xl overflow-hidden w-full max-w-2xl">
                                        <img src="{{ $image->image_url }}" alt="Demo Sample" class="w-full h-auto">
                                    </div>
                                @endforeach
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Footer (Buy Action) -->
                <div class="p-6 border-t border-white/10 bg-zinc-900 flex items-center justify-between gap-4">
                    <div class="text-left hidden sm:block">
                        <p class="text-zinc-400 text-xs">Like what you see?</p>
                        <p class="text-white font-bold">Get lifetime access now.</p>
                    </div>
                    <button @click="triggerBuy()"
                        class="flex-1 sm:flex-none bg-primary hover:bg-primary/90 text-white px-8 py-4 rounded-xl font-bold shadow-lg shadow-primary/20 transition-all transform hover:scale-105 flex items-center justify-center gap-3">
                        <span>Unlock Full Book</span>
                        <span
                            class="bg-black/20 px-2 py-0.5 rounded text-sm">₹{{ number_format($product->selling_price, 2) }}</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Product Image/Preview -->
            <div class="w-full lg:w-1/2">
                <div class="bg-white rounded-3xl overflow-hidden shadow-xl border border-gray-100 relative">
                    <!-- Strips Container (both triangular, side by side) -->
                    <div class="absolute top-0 right-0 flex gap-0 z-20 pointer-events-none">
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
                            <div class="w-24 h-24 relative z-10">
                                <div class="absolute top-4 -right-14 w-32 text-white text-[12px] font-black py-1.5 text-center transform rotate-45 shadow-lg border-b border-white/20 uppercase tracking-tighter"
                                    style="background-color: {{ $bgColor }}; width:13rem; margin-right: -5.3125rem; margin-top: 1.6875rem;">
                                    {{ $product->badge_strip_text }}
                                </div>
                            </div>
                        @endif

                        <!-- Sale Strip (Triangular - Strip 1) -->
                        @if($saleTag)
                            <div class="w-24 h-24 overflow-hidden">
                                <div
                                    class="absolute top-4 -right-8 w-32 bg-red-600 text-white text-[12px] font-black py-1.5 text-center transform rotate-45 shadow-lg border-b border-white/20 uppercase tracking-tighter">
                                    {{ $saleTag }}
                                </div>
                            </div>
                        @endif
                    </div>
                    @if($product->image_path)
                        <img src="{{ $product->image_url }}" class="w-full aspect-[4/3] object-cover">
                    @else
                        <img src="https://placehold.co/800x600/2C1810/ffffff?text={{ urlencode($product->title) }}"
                            class="w-full aspect-[4/3] object-cover">
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
                    <a href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                        class="hover:text-primary">{{ $product->category->name }}</a>
                </nav>

                <h1 class="text-4xl font-extrabold text-gray-900 mb-4 leading-tight">{{ $product->title }}</h1>

                <!-- Pricing & Badges (Refined) -->
                <div class="bg-white border border-gray-100 rounded-3xl p-6 mb-10 shadow-sm relative overflow-hidden group">
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Exclusive
                                Access</span>
                            @if($isDiscounted)
                                <span
                                    class="bg-red-600 text-white text-[10px] font-black px-2 py-0.5 rounded-sm uppercase transform -skew-x-12">
                                    Special Deal
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-baseline gap-4">
                                <span
                                    class="text-3xl md:text-5xl font-black text-primary">₹{{ number_format($product->selling_price, 2) }}</span>
                                @if($isDiscounted)
                                    <span
                                        class="text-lg md:text-xl text-gray-400 line-through font-bold decoration-red-500/50">₹{{ number_format($product->original_price, 2) }}</span>
                                @endif
                            </div>

                            <div
                                class="bg-green-50 text-green-700 border border-green-100 px-3 py-1.5 md:px-5 md:py-3 rounded-2xl flex items-center">
                                <span
                                    class="text-[10px] md:text-xs font-bold uppercase tracking-widest flex items-center gap-2 whitespace-nowrap">
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
                            @if($product->price == 0 || $product->is_demo || auth()->user()->hasRole('Super Admin') || auth()->user()->hasPurchased($product->id))
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <a href="{{ route('content.view', $product->id) }}"
                                        class="flex-1 bg-primary text-white text-center py-4 rounded-2xl font-bold text-lg hover:opacity-90 transition-all shadow-lg flex items-center justify-center">
                                        <i class="fas fa-book-open mr-3"></i> Open Secure Viewer
                                    </a>
                                    @if($product->is_downloadable)
                                        <a href="{{ route('content.download', $product->id) }}"
                                            class="sm:w-auto px-8 bg-gray-100 text-gray-800 text-center py-4 rounded-2xl font-bold hover:bg-gray-200 transition-all flex items-center justify-center">
                                            <i class="fas fa-download mr-3"></i>
                                        </a>
                                    @endif
                                </div>
                            @else
                                @if($activeMethod == 'razorpay' || $activeMethod == 'both')
                                    <button @click="openPreview()"
                                        class="w-full bg-primary text-white py-4 rounded-2xl font-bold hover:opacity-90 transition-all shadow-lg flex items-center justify-center gap-3 relative overflow-hidden group">
                                        <span
                                            class="absolute inset-0 bg-white/20 group-hover:translate-x-full transition-transform duration-500 ease-out -skew-x-12"></span>
                                        <span class="relative">Buy Now - ₹{{ number_format($product->selling_price, 2) }}</span>
                                        <i class="fas fa-arrow-right relative"></i>
                                    </button>
                                    <!-- Hidden Real Trigger for Purchase from Viewer return -->
                                    <button id="rzp-button" style="display:none;"></button>
                                @endif
                            @endif
                        @else
                            <button @click="openPreview()"
                                class="w-full bg-primary text-white py-4 rounded-2xl font-bold hover:opacity-90 transition-all shadow-lg flex items-center justify-center gap-3 relative overflow-hidden group">
                                <span
                                    class="absolute inset-0 bg-white/20 group-hover:translate-x-full transition-transform duration-500 ease-out -skew-x-12"></span>
                                <span class="relative">Buy Now - ₹{{ number_format($product->selling_price, 2) }}</span>
                                <i class="fas fa-arrow-right relative"></i>
                            </button>
                        @endauth
                    </div>

                    <!-- Background Decoration -->
                    <div
                        class="absolute -right-4 -bottom-4 opacity-[0.02] text-8xl transform -rotate-12 group-hover:scale-110 transition-transform duration-700">
                        <i class="fas fa-certificate text-primary"></i>
                    </div>
                </div>

                <div class="prose prose-stone max-w-none text-gray-600 mb-10">
                    {!! nl2br(e($product->description)) !!}
                </div>

                @auth
                    @if(!($product->price == 0 || $product->is_demo || auth()->user()->hasRole('Super Admin') || auth()->user()->hasPurchased($product->id)))
                        <!-- Payment Options -->
                        <div class="space-y-6" x-data="{ showManualForm: false }">
                            @if($activeMethod == 'manual' || $activeMethod == 'both')
                                <!-- Manual Payment Manual -->
                                <div class="bg-gray-900 rounded-3xl p-6 text-white overflow-hidden relative">
                                    <div class="absolute top-0 right-0 p-4 opacity-10">
                                        <i class="fas fa-qrcode text-9xl"></i>
                                    </div>

                                    <div x-show="!showManualForm">
                                        <h3 class="text-xl font-bold mb-2 flex items-center gap-2 relative z-10"><i
                                                class="fas fa-qrcode text-yellow-400"></i> Manual / QR Pay</h3>
                                        <p class="text-gray-400 mb-6 text-sm relative z-10">Scan QR or Transfer to
                                            <strong>{{ $siteSettings['upi_id'] ?? 'N/A' }}</strong>
                                        </p>

                                        @if(!empty($siteSettings['qr_code_url']))
                                            <div class="flex justify-center mb-6">
                                                <div class="p-2 bg-white rounded-xl">
                                                    <img src="{{ $siteSettings['qr_code_url'] }}" class="w-48 h-48 object-contain">
                                                </div>
                                            </div>
                                        @endif

                                        <button @click="showManualForm = true"
                                            class="w-full bg-white/10 text-white py-4 rounded-2xl font-bold hover:bg-white/20 transition-all border border-white/5 relative z-10">
                                            Upload Payment Screenshot
                                        </button>
                                    </div>

                                    <div x-show="showManualForm" x-cloak>
                                        <h3 class="text-xl font-bold mb-6 flex items-center gap-3">
                                            <button @click="showManualForm = false" class="text-gray-400 hover:text-white"><i
                                                    class="fas fa-arrow-left text-sm"></i></button>
                                            Upload Proof
                                        </h3>
                                        <form action="{{ route('payments.store', $product->id) }}" method="POST"
                                            enctype="multipart/form-data" class="space-y-4">
                                            @csrf
                                            <div>
                                                <label
                                                    class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest">Transaction
                                                    / UTR ID</label>
                                                <input type="text" name="transaction_id" required
                                                    class="w-full bg-zinc-800 border-none rounded-xl px-4 py-3 text-white focus:ring-primary"
                                                    placeholder="Enter Reference Number">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-widest">Screenshot</label>
                                                <input type="file" name="payment_proof"
                                                    class="w-full bg-zinc-800 border-none rounded-xl px-4 py-2 text-sm text-gray-400"
                                                    required>
                                            </div>
                                            <button type="submit"
                                                class="w-full bg-green-500 text-white py-4 rounded-2xl font-bold hover:bg-green-600 transition-all shadow-lg mt-4">Submit
                                                Verification</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
                        <script>
                            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

                            document.addEventListener('alpine:init', () => {
                                Alpine.data('previewModal', () => {
                                    // Store pdfDoc outside the reactive object to avoid Proxy issues with private fields
                                    let pdfDoc = null;

                                    return {
                                        showPreviewModal: false,
                                        loading: false,
                                        loaded: false,
                                        hasDemoImages: {{ $product->demoImages->count() > 0 ? 'true' : 'false' }},

                                        openPreview() {
                                            this.showPreviewModal = true;
                                            if (!this.loaded && !this.hasDemoImages) {
                                                this.loadPreview();
                                            }
                                        },

                                        triggerBuy() {
                                            @auth
                                                this.showPreviewModal = false;
                                                // Trigger the Razorpay logic
                                                const btn = document.getElementById('rzp-button');
                                                if (btn) btn.click();
                                            @else
                                                window.location.href = "{{ route('products.buy', $product->id) }}";
                                            @endauth
                                                            },

                                        async loadPreview() {
                                            this.loading = true;
                                            try {
                                                const url = '{{ route("content.demo.stream", $product->id) }}';
                                                pdfDoc = await pdfjsLib.getDocument(url).promise;

                                                const totalPages = pdfDoc.numPages;
                                                const container = document.getElementById('pdf-preview-pages');
                                                container.innerHTML = ''; // Clear

                                                // Strategy: Show first 5 pages and last 2 pages
                                                // For small PDFs (<=5 pages), only show first 2 pages
                                                let updatePages = [];
                                                let firstPagesLimit = totalPages <= 5 ? 2 : 5;

                                                // First pages
                                                for (let i = 1; i <= firstPagesLimit && i <= totalPages; i++) {
                                                    updatePages.push(i);
                                                }

                                                // Last 2 pages (avoid duplicates and only for larger PDFs)
                                                if (totalPages > 5) {
                                                    for (let i = Math.max(1, totalPages - 1); i <= totalPages; i++) {
                                                        if (!updatePages.includes(i)) {
                                                            if (updatePages.length > 0 && i === Math.max(1, totalPages - 1)) {
                                                                // Add separator if gap exists
                                                                if (updatePages[updatePages.length - 1] < i - 1) {
                                                                    const sep = document.createElement('div');
                                                                    sep.className = 'text-zinc-500 text-xs uppercase tracking-widest my-4';
                                                                    sep.innerText = '... Skipping ' + (i - 1 - updatePages[updatePages.length - 1]) + ' pages ...';
                                                                    container.appendChild(sep);
                                                                }
                                                            }
                                                            updatePages.push(i);
                                                        }
                                                    }
                                                }

                                                // Render pages
                                                for (const pageNum of updatePages) {
                                                    await this.renderPage(pageNum, container);
                                                }

                                                this.loaded = true;
                                            } catch (err) {
                                                console.error('PDF Preview Error:', err);
                                                // Fallback? Just show error or redirect to buy on error?
                                            } finally {
                                                this.loading = false;
                                            }
                                        },

                                        async renderPage(num, container) {
                                            try {
                                                const page = await pdfDoc.getPage(num);

                                                // Responsive scale
                                                // We want it to fit somewhat nicely
                                                const viewport = page.getViewport({ scale: 1 });
                                                // Max width 800px approx
                                                const desiredWidth = Math.min(container.clientWidth || 800, 800);
                                                const scale = desiredWidth / viewport.width;

                                                const scaledViewport = page.getViewport({ scale: scale }); // High quality scale

                                                // Wrapper for style
                                                const wrapper = document.createElement('div');
                                                wrapper.className = 'relative shadow-[0_10px_30px_rgba(0,0,0,0.5)] transition-transform hover:scale-[1.02] duration-300 bg-white';

                                                const canvas = document.createElement('canvas');
                                                const context = canvas.getContext('2d');
                                                canvas.height = scaledViewport.height;
                                                canvas.width = scaledViewport.width;

                                                // Label
                                                const label = document.createElement('div');
                                                label.className = 'absolute bottom-2 right-2 bg-black/50 text-white text-[10px] px-2 py-1 rounded font-mono';
                                                label.innerText = 'Page ' + num;

                                                wrapper.appendChild(canvas);
                                                wrapper.appendChild(label);
                                                container.appendChild(wrapper);

                                                const renderContext = {
                                                    canvasContext: context,
                                                    viewport: scaledViewport
                                                };
                                                await page.render(renderContext).promise;
                                            } catch (e) {
                                                console.error(e);
                                            }
                                        }
                                    };
                                });
                            });
                        </script>
                        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                        <script>
                            document.getElementById('rzp-button').onclick = function (e) {
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

                        @if(session('trigger_purchase') || request('trigger_purchase'))
                            <script>
                                window.addEventListener('load', function () {
                                    setTimeout(function () {
                                        // Trigger the purchase flow directly
                                        // Ensure Razorpay script is loaded and button is ready
                                        const btn = document.getElementById('rzp-button');
                                        if (btn) btn.click();
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