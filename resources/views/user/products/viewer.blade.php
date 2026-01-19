<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->title }} - Secure Viewer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <style>
        body {
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            background-color: #0f0f10;
            height: 100vh;
            overflow: hidden;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        #viewer-container {
            width: 100%;
            height: calc(100vh - 64px);
            overflow: auto;
            display: block;
            padding: 10px;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            background-color: #0f0f10;
        }
        #canvas-wrapper {
            position: relative;
            margin: 0 auto;
            display: block;
            width: fit-content;
            transition: none; /* Removed transition to avoid lag during active scaling */
            will-change: transform, width, height;
        }
        #viewer-canvas {
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            background-color: white;
            display: block;
            transform-origin: top left;
        }
        .security-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9999;
            opacity: 0.1;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            align-content: space-around;
        }
        .watermark {
            font-size: 14px;
            color: white;
            transform: rotate(-30deg);
            white-space: nowrap;
            padding: 20px;
        }
        @media print {
            body { display: none !important; }
        }
        /* Custom scrollbar */
        #viewer-container::-webkit-scrollbar {
            width: 6px;
        }
        #viewer-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        #viewer-container::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        /* Page Input Styling */
        #page-num-input {
            appearance: textfield;
            -moz-appearance: textfield;
            background: transparent;
            border: none;
            color: #60a5fa;
            font-weight: 700;
            width: 32px;
            text-align: center;
            padding: 0;
            outline: none;
        }
        #page-num-input::-webkit-outer-spin-button,
        #page-num-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        #page-num-input:focus {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px;
        }
    </style>
</head>
<body oncontextmenu="return false;">
    <!-- Watermark Overlay -->
    <div class="security-overlay">
        @for($i=0; $i<40; $i++)
            <div class="watermark">SECURE - {{ auth()->user()->email }}</div>
        @endfor
    </div>

    <!-- Toolbar -->
    <div class="h-16 bg-zinc-900/90 backdrop-blur-md border-b border-white/5 flex items-center justify-between px-4 md:px-6 text-white sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <a href="{{ route('products.show', $product->slug) }}" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10 transition-colors">
                <i class="fas fa-arrow-left text-zinc-400"></i>
            </a>
            <h1 class="font-semibold text-sm md:text-base truncate max-w-[120px] sm:max-w-[200px] md:max-w-md">{{ $product->title }}</h1>
        </div>
        
        <div class="flex items-center gap-2 md:gap-6">
            <div class="flex items-center gap-2 bg-white/5 border border-white/10 px-2 md:px-3 py-1 rounded-lg">
                <button id="prev-page" class="w-8 h-8 flex items-center justify-center rounded hover:bg-white/10 transition-colors"><i class="fas fa-chevron-left text-xs"></i></button>
                <div class="flex items-center text-[10px] md:text-sm border-l border-white/10 pl-2 md:pl-3 whitespace-nowrap">
                    <input type="number" id="page-num-input" value="1" min="1" step="1" class="transition-all">
                    <span class="mx-1 text-zinc-500">/</span>
                    <span id="page-count" class="text-zinc-400">1</span>
                </div>
                <button id="next-page" class="w-8 h-8 flex items-center justify-center rounded hover:bg-white/10 transition-colors"><i class="fas fa-chevron-right text-xs"></i></button>
            </div>
            
            <div class="flex items-center gap-1 md:gap-4 md:border-l border-white/10 md:pl-6">
                <button id="zoom-out" class="w-8 h-8 flex items-center justify-center rounded hover:bg-white/10 text-zinc-400 transition-colors"><i class="fas fa-minus text-xs"></i></button>
                <span id="zoom-percent" class="text-[10px] md:text-xs font-mono text-zinc-500 min-w-[35px] text-center">100%</span>
                <button id="zoom-in" class="w-8 h-8 flex items-center justify-center rounded hover:bg-white/10 text-zinc-400 transition-colors"><i class="fas fa-plus text-xs"></i></button>
            </div>
        </div>

        <div class="hidden sm:block">
            @if($product->is_downloadable)
                <a href="{{ route('content.download', $product->id) }}" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-1.5 rounded-lg text-sm font-bold flex items-center gap-2 shadow-lg shadow-blue-600/20 transition-all active:scale-95">
                    <i class="fas fa-download"></i> <span class="hidden md:inline">Download</span>
                </a>
            @else
                <div class="bg-white/5 border border-white/10 px-3 py-1.5 rounded-lg text-zinc-400 flex items-center gap-2">
                    <i class="fas fa-shield-halved text-blue-400"></i> <span class="hidden md:inline text-[10px] uppercase tracking-widest font-bold">Secure View</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Viewer -->
    <div id="viewer-container">
        <div id="canvas-wrapper">
            <canvas id="viewer-canvas"></canvas>
        </div>
    </div>

    <script>
        const url = '{!! $signedUrl !!}';
        let pdfDoc = null,
            pageNum = 1,
            pageRendering = false,
            pageNumPending = null,
            renderScale = 1.3,
            visualScale = 1.0,
            canvas = document.getElementById('viewer-canvas'),
            ctx = canvas.getContext('2d'),
            wrapper = document.getElementById('canvas-wrapper');

        // PDF.js worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

        /**
         * Render the page with high quality.
         */
        async function renderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
                return;
            }
            pageRendering = true;

            try {
                const page = await pdfDoc.getPage(num);
                const viewport = page.getViewport({scale: renderScale});
                
                // Adjust for high DPI screens
                const outputScale = window.devicePixelRatio || 1;
                canvas.width = Math.floor(viewport.width * outputScale);
                canvas.height = Math.floor(viewport.height * outputScale);
                canvas.style.width = Math.floor(viewport.width) + "px";
                canvas.style.height = Math.floor(viewport.height) + "px";

                const transform = outputScale !== 1 
                    ? [outputScale, 0, 0, outputScale, 0, 0] 
                    : null;

                const renderContext = {
                    canvasContext: ctx,
                    transform: transform,
                    viewport: viewport
                };

                // Apply current visual scale to the wrapper to prevent jump
                // Before we render, we are at renderScale * visualScale
                // After render, we are at the new renderScale, so visualScale should be 1.0
                await page.render(renderContext).promise;
                
                visualScale = 1.0;
                updateVisualTransform();
                
                pageRendering = false;
                
                if (pageNumPending !== null) {
                    const next = pageNumPending;
                    pageNumPending = null;
                    renderPage(next);
                }
            } catch (err) {
                console.error('Render error:', err);
                pageRendering = false;
            }

            document.getElementById('page-num-input').value = num;
            document.getElementById('zoom-percent').textContent = Math.round(renderScale * visualScale * 100) + '%';
            
            // Save Progress
            saveProgress(num);
        }

        /**
         * Debounced high-quality render
         */
        let renderTimeout;
        function debouncedRender() {
            clearTimeout(renderTimeout);
            renderTimeout = setTimeout(() => {
                // Combine visual scale into render scale
                renderScale = renderScale * visualScale;
                renderPage(pageNum);
            }, 400); // Slightly longer debounce for better stability on mobile
        }

        /**
         * Update visual scale for instant feedback
         */
        function updateVisualTransform() {
            const baseWidth = parseFloat(canvas.style.width) || canvas.offsetWidth;
            const baseHeight = parseFloat(canvas.style.height) || canvas.offsetHeight;
            
            // Visual scale applied to canvas
            canvas.style.transform = `scale(${visualScale})`;
            
            // Update wrapper to be the scrollable bounds
            const newWidth = baseWidth * visualScale;
            const newHeight = baseHeight * visualScale;
            
            wrapper.style.width = newWidth + 'px';
            wrapper.style.height = newHeight + 'px';
            
            // Center horizontally if smaller than container, otherwise align left
            if (newWidth < container.clientWidth) {
                wrapper.style.marginLeft = 'auto';
                wrapper.style.marginRight = 'auto';
            } else {
                wrapper.style.marginLeft = '0';
                wrapper.style.marginRight = '0';
            }

            document.getElementById('zoom-percent').textContent = Math.round(renderScale * visualScale * 100) + '%';
        }

        function changeZoom(delta) {
            const currentTotalScale = renderScale * visualScale;
            let nextTotalScale = currentTotalScale + delta;
            
            if (nextTotalScale < 0.5) nextTotalScale = 0.5;
            if (nextTotalScale > 4) nextTotalScale = 4;
            
            visualScale = nextTotalScale / renderScale;
            updateVisualTransform();
            debouncedRender();
        }

        function onPrevPage() {
            if (pageNum <= 1 || pageRendering) return;
            pageNum--;
            renderPage(pageNum);
        }

        function onNextPage() {
            if (!pdfDoc || pageNum >= pdfDoc.numPages || pageRendering) return;
            pageNum++;
            renderPage(pageNum);
        }

        // Initialize PDF
        pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
            pdfDoc = pdfDoc_;
            document.getElementById('page-count').textContent = pdfDoc.numPages;
            
            // Auto-fit to width on small screens
            if (window.innerWidth < 768) {
                pdfDoc.getPage(1).then(page => {
                    const viewport = page.getViewport({scale: 1});
                    const containerWidth = document.getElementById('viewer-container').clientWidth - 20;
                    renderScale = containerWidth / viewport.width;
                    
                    // Resume from saved page
                    const savedPage = getSavedPage();
                    if (savedPage && savedPage > 1 && savedPage <= pdfDoc.numPages) {
                        pageNum = parseInt(savedPage);
                    }
                    renderPage(pageNum);
                });
            } else {
                // Resume from saved page
                const savedPage = getSavedPage();
                if (savedPage && savedPage > 1 && savedPage <= pdfDoc.numPages) {
                    pageNum = parseInt(savedPage);
                }
                renderPage(pageNum);
            }
        }).catch(err => {
            console.error('Error loading PDF:', err);
            document.getElementById('viewer-container').innerHTML = `
                <div class="max-w-md mx-auto mt-20 p-8 bg-zinc-900 border border-red-500/20 rounded-2xl text-center">
                    <i class="fas fa-exclamation-circle text-red-500 text-4xl mb-4"></i>
                    <h2 class="text-white font-bold text-xl mb-2">Failed to load document</h2>
                    <p class="text-zinc-400 mb-6">This could be due to a connection issue or document expiration.</p>
                    <button onclick="window.location.reload()" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-lg font-bold transition-colors">
                        Try Again
                    </button>
                </div>
            `;
        });
        
        // Progress Saving Logic
        const productId = {{ $product->id }};
        const productTitle = "{{ addslashes($product->title) }}";
        const productImage = "{{ $product->image_path ? $product->image_url : 'https://placehold.co/400x600/2c3e50/ffffff?text='.urlencode($product->title) }}";
        const viewerUrl = "{{ route('content.view', $product->id) }}";

        function getSavedPage() {
            try {
                const library = JSON.parse(localStorage.getItem('my_library_books') || '[]');
                const book = library.find(b => b.id === productId);
                return book ? book.page : 1;
            } catch(e) { return 1; }
        }

        function saveProgress(num) {
            try {
                let library = JSON.parse(localStorage.getItem('my_library_books') || '[]');
                // Remove existing to push to top
                library = library.filter(b => b.id !== productId);
                
                library.push({
                    id: productId,
                    title: productTitle,
                    image: productImage,
                    url: viewerUrl, // We could append #page=num but we are storing page separately for cleaner URL
                    page: num,
                    timestamp: new Date().getTime()
                });
                
                if (library.length > 20) library.shift();
                localStorage.setItem('my_library_books', JSON.stringify(library));
            } catch(e) { console.error('Error saving progress', e); }
        }

        // Event Listeners
        document.getElementById('prev-page').addEventListener('click', onPrevPage);
        document.getElementById('next-page').addEventListener('click', onNextPage);
        document.getElementById('zoom-in').addEventListener('click', () => changeZoom(0.3));
        document.getElementById('zoom-out').addEventListener('click', () => changeZoom(-0.3));

        /**
         * Page navigation from input
         */
        const pageInput = document.getElementById('page-num-input');
        
        function navigateToPage() {
            if (!pdfDoc) return;
            const val = parseInt(pageInput.value);
            if (val >= 1 && val <= pdfDoc.numPages && val !== pageNum) {
                pageNum = val;
                renderPage(pageNum);
            } else {
                // Reset input to current page if invalid or unchanged
                pageInput.value = pageNum;
            }
        }

        pageInput.addEventListener('change', navigateToPage);
        pageInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                pageInput.blur(); // Triggers change event
            }
        });
        
        // Prevent typing non-numbers and handle width adjustment
        pageInput.addEventListener('input', () => {
            // Basic auto-width based on content
            const length = pageInput.value.length || 1;
            pageInput.style.width = (length * 8 + 16) + 'px';
        });

        // Pinch to Zoom Implementation
        let startDist = 0;
        let startScale = 1;
        const container = document.getElementById('viewer-container');

        container.addEventListener('touchstart', (e) => {
            if (e.touches.length === 2) {
                startDist = Math.hypot(
                    e.touches[0].pageX - e.touches[1].pageX,
                    e.touches[0].pageY - e.touches[1].pageY
                );
                startScale = visualScale;
            }
        }, { passive: true });

        container.addEventListener('touchmove', (e) => {
            if (e.touches.length === 2 && startDist > 0) {
                e.preventDefault();
                const currentDist = Math.hypot(
                    e.touches[0].pageX - e.touches[1].pageX,
                    e.touches[0].pageY - e.touches[1].pageY
                );
                
                const ratio = currentDist / startDist;
                let nextVisualScale = startScale * ratio;
                
                // Constraints
                const totalNextScale = renderScale * nextVisualScale;
                if (totalNextScale < 0.5) nextVisualScale = 0.5 / renderScale;
                if (totalNextScale > 4) nextVisualScale = 4 / renderScale;
                
                visualScale = nextVisualScale;
                updateVisualTransform();
                debouncedRender();
            }
        }, { passive: false });

        container.addEventListener('touchend', () => {
            startDist = 0;
        }, { passive: true });

        // Security: Keyboard & Mouse
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && (e.key === 'p' || e.key === 's')) e.preventDefault();
            if (e.key === 'F12') e.preventDefault();
        });

        // Prevention of screenshots (best effort for web)
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                document.body.style.filter = 'blur(10px)';
            } else {
                document.body.style.filter = 'none';
            }
        });
    </script>
</body>
</html>
