<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $siteName }} - Secure Digital Content</title>

    <!-- Resource Hints for Performance -->
    <link rel="dns-prefetch" href="//cdn.tailwindcss.com">
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="//unpkg.com">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

    <!-- Scripts -->
    <!-- Scripts -->
    <!-- Tailwind Moved to Footer for Performance -->
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,700;1,400&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lora:ital,wght@0,400;0,700;1,400&family=Merriweather:ital,wght@0,400;0,700;1,400&family=Inconsolata:wght@400;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" async></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --primary:
                {{ $brandColor }}
            ;
            --primary-light:
                {{ $brandColor }}
                22;
        }

        [x-cloak] {
            display: none !important;
        }

        .no-select {
            user-select: none;
            -webkit-user-select: none;
        }

        html {
            width: 100%;
            overflow-x: hidden;
        }
        body {
            font-family: 'Lora', 'Merriweather', serif; /* Body Font */
            background-color: #F8F1E9;
            min-height: 100vh;
            width: 100%;
            color: #1A0D00;
            overflow-x: hidden;
            position: relative;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'EB Garamond', 'Playfair Display', serif; /* Heading Font */
        }
        
        pre, code, .font-mono {
            font-family: 'Inconsolata', monospace;
        }

        /* Texture Overlay for that 'Aged' look */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.05'/%3E%3C/svg%3E");
            opacity: 0.4;
            pointer-events: none;
            z-index: -1;
        }
        
        /* Corner Curls Visual Element */
        .corner-curl {
            position: absolute;
            top: 0;
            right: 0;
            border-width: 0 40px 40px 0;
            border-style: solid;
            border-color: #FDF6E3 #f4f4f4;
            box-shadow: -5px 5px 5px rgba(0,0,0,0.05);
            display: none; /* Add to cards individually if needed */
        }

        /* Quill Hover Effect */
        .hover-quill:hover {
            cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%238B4513" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l7-7 3 3-7 7-3-3z"></path><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path><path d="M2 2l7.586 7.586"></path><circle cx="11" cy="11" r="2"></circle></svg>'), auto;
        }


        /* Anti-Gravity Glassmorphism */
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }

        .section-cloud-card {
            background: rgba(255, 255, 255, 0.85); /* More opaque */
            backdrop-filter: blur(20px); /* Deeper blur */
            -webkit-backdrop-filter: blur(20px);
            border: 2px solid rgba(255, 255, 255, 0.8); /* Pronounced white border */
            border-radius: 3rem;
            box-shadow: 
                0 20px 50px rgba(0, 0, 0, 0.1), /* Outer deep shadow */
                inset 0 0 20px rgba(255, 255, 255, 0.5); /* Inner glow */
            padding: 3rem 2rem;
            margin-top: 3rem;
            margin-bottom: 5rem;
            position: relative;
            overflow: hidden;
            transition: all 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
            animation: cloudFloat 8s ease-in-out infinite;
        }

        /* Shine Effect */
        .section-cloud-card::after {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(255, 255, 255, 0.1),
                transparent
            );
            transform: rotate(45deg);
            animation: cardShine 12s linear infinite;
            pointer-events: none;
        }

        @keyframes cloudFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes cardShine {
            0% { transform: translateX(-100%) rotate(45deg); }
            20%, 100% { transform: translateX(100%) rotate(45deg); }
        }

        .section-cloud-card:hover {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px) scale(1.01);
            animation-play-state: paused;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 1.5rem;
        }

        /* M3 Text Fields */
        .input-filled {
            background-color: #E7E0EC;
            /* Surface Variant */
            border-bottom: 2px solid #49454F;
            /* Outline */
            border-radius: 4px 4px 0 0;
            transition: all 0.2s;
        }

        .input-filled:focus {
            background-color: var(--primary-light);
            border-bottom-color: var(--primary);
        }

        /* Security Blackout */
        body.devtools-open {
            display: none !important;
            background: black !important;
        }

        body.devtools-open::after {
            content: "Access Denied";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: black;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            z-index: 999999;
        }

        @media print {
            body {
                display: none !important;
            }
        }

        /* Loading Screen - Intro Style */
        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #F8F1E9; /* Parchment */
            background-image: 
                radial-gradient(#D4AF37 0.5px, transparent 0.5px),
                radial-gradient(#D4AF37 0.5px, #F8F1E9 0.5px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 1s ease-out, visibility 1s;
            cursor: pointer;
            overflow: hidden;
        }

        #loading-screen::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('https://www.transparenttextures.com/patterns/aged-paper.png');
            opacity: 0.5;
            pointer-events: none;
        }

        .loader-content {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 2rem;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInContent 1s ease-out forwards;
        }

        @keyframes fadeInContent {
            to { opacity: 1; transform: translateY(0); }
        }

        .text-glow {
            text-shadow: 0 0 10px rgba(212, 175, 55, 0.3);
        }

        /* Prevent scroll during loading but keep navbar visible */
        body.loading-overflow-hidden {
            overflow-y: hidden;
            overflow-x: hidden;
        }

        /* CRITICAL CSS: Mobile Navbar - MUST RENDER IMMEDIATELY */
        #mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: 72px; /* Fixed height for stability */
            z-index: 2147483647 !important;
            background-color: white !important;
            border-top: 1px solid #e5e7eb !important;
            box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1) !important;
            display: flex !important;
            flex-direction: row !important;
            justify-content: center !important;
            align-items: stretch !important;
            padding: 8px 0 max(20px, env(safe-area-inset-bottom)) 0 !important;
            visibility: visible !important;
            opacity: 1 !important;
            transform: translateZ(0) !important;
        }

        .mobile-nav-inner {
            display: flex !important;
            flex-direction: row !important;
            justify-content: space-around !important;
            align-items: center !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 auto !important;
            padding: 0 1rem !important;
            height: 100% !important;
        }

        .mobile-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
            padding: 0.5rem 0.75rem;
            min-width: 60px;
            text-decoration: none;
            color: inherit;
            border: none;
        }

        .mobile-nav-icon {
            width: 24px;
            height: 24px;
            display: block;
            flex-shrink: 0;
        }

        .mobile-nav-text {
            font-size: 11px;
            font-weight: 500;
            line-height: 1;
            display: block;
        }

        /* Critical Utilities (Tailwind Polyfills for Initial Render) */
        .relative { position: relative; }
        .absolute { position: absolute; }
        .inset-0 { top: 0; right: 0; bottom: 0; left: 0; }
        .flex { display: flex; }
        .flex-col { flex-direction: column; }
        .items-center { align-items: center; }
        .justify-center { justify-content: center; }
        .gap-1 { gap: 0.25rem; }
        .hidden { display: none; }
        .blur-sm { filter: blur(4px); }
        .rounded-full { border-radius: 9999px; }
        .bg-primary\/10 { background-color: rgba(var(--primary-rgb, 59, 130, 246), 0.1); }
        .bg-red-500 { background-color: #ef4444 !important; }
        .text-white { color: #ffffff !important; }
        .font-bold { font-weight: 700 !important; }
        .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }

        /* Critical Colors & Utilities */
        .text-primary {
            color: var(--primary) !important;
        }

        .text-gray-400 {
            color: #9ca3af !important;
        }

        .text-gray-500 {
            color: #6b7280 !important;
        }

        .text-gray-600 {
            color: #4b5563 !important;
        }

        .text-gray-700 {
            color: #374151 !important;
        }

        .bg-white {
            background-color: #ffffff !important;
        }

        .group:hover .group-hover\:text-gray-600 {
            color: #4b5563 !important;
        }

        .group:hover .group-hover\:text-gray-700 {
            color: #374151 !important;
        }

        /* Prevent content from hiding under navbar - CRITICAL for mobile */
        @media (max-width: 767px) {
            main {
                padding-bottom: 100px !important;
                margin-bottom: 0 !important;
            }

            footer {
                margin-bottom: 0 !important;
            }

            body {
                padding-bottom: 0 !important;
            }
        }

        /* Visibility Control */
        @media (min-width: 768px) {
            #mobile-bottom-nav {
                display: none !important;
            }

            main {
                padding-bottom: 0 !important;
            }

            footer {
                margin-bottom: 0 !important;
            }
        }

        /* Hero Section Prevention of Collapse */
        .h-\[300px\] {
            height: 300px;
        }

        @media (min-width: 768px) {
            .md\:h-\[500px\] {
                height: 500px;
            }
        }
    </style>
    <script src="{{ asset('js/security.js') }}"></script>
    <script>
        // Global Cart Key
        window.CART_KEY = 'cart_' + ({{ auth()->id() ?? "'guest'" }});

        // Global My Library Logic
        document.addEventListener('alpine:init', () => {
            Alpine.data('myLibrary', () => ({
                books: [],
                hasBooks: false,
                init() {
                    this.loadBooks();
                    window.addEventListener('book-opened', () => this.loadBooks());
                },
                loadBooks() {
                    try {
                        let stored = JSON.parse(localStorage.getItem('my_library_books') || '[]');
                        
                        // Backend Validation (Active on Library Page)
                        if (window.VALID_PRODUCT_IDS && Array.isArray(window.VALID_PRODUCT_IDS)) {
                            const initialCount = stored.length;
                            stored = stored.filter(book => window.VALID_PRODUCT_IDS.includes(book.id));
                            
                            // If items were removed, update storage immediately
                            if (stored.length !== initialCount) {
                                localStorage.setItem('my_library_books', JSON.stringify(stored));
                            }
                        }

                        // Assign Categories if available
                        if (window.PRODUCT_CATEGORIES) {
                            stored = stored.map(book => {
                                book.category = window.PRODUCT_CATEGORIES[book.id] || 'Uncategorized';
                                return book;
                            });
                        }

                        this.books = stored.reverse();
                        this.hasBooks = this.books.length > 0;
                    } catch (e) {
                        console.error('Error loading library:', e);
                        this.books = [];
                        this.hasBooks = false;
                    }
                },
                clearHistory() {
                    if (confirm('Clear your reading history?')) {
                        localStorage.removeItem('my_library_books');
                        this.books = [];
                        this.hasBooks = false;
                    }
                }
            }));
        });

        // Global Helper to save book
        window.saveBookToLibrary = function (book) {
            try {
                let library = JSON.parse(localStorage.getItem('my_library_books') || '[]');
                // Remove duplicates based on ID
                library = library.filter(b => b.id !== book.id);
                // Add new entry
                library.push({
                    id: book.id,
                    title: book.title,
                    image: book.image,
                    url: book.url,
                    timestamp: new Date().getTime()
                });
                // Limit to 12 items
                if (library.length > 12) library.shift();

                localStorage.setItem('my_library_books', JSON.stringify(library));
                window.dispatchEvent(new CustomEvent('book-opened'));
            } catch (e) {
                console.error('Error saving book:', e);
            }
        }
    </script>
</head>

<body class="bg-gray-50 font-sans antialiased no-select loading-overflow-hidden" x-data="{ mobileMenuOpen: false }">
    <!-- Loading Screen / Intro -->
    <div id="loading-screen" onclick="hideLoader()">
        <div class="loader-content w-full max-w-4xl mx-auto px-4">
            @if(request()->routeIs('home'))
                <!-- Logic: Default to Simple Loader to prevent flash on return visits. Inline JS switches to Intro if needed. -->
                
                <!-- Intro Content (Hidden by default) -->
                <div id="intro-content" class="hidden">
                    <div class="mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto text-[#2C1810]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-bold text-[#2C1810] mb-4 text-glow font-serif">
                        Upgrade your learning <br/>
                        <span class="text-[#D4AF37] italic">with {{ $siteName }}</span>
                    </h1>
                    <p class="text-[#8B4513] mt-8 text-sm animate-pulse">Click to enter</p>
                </div>

                <!-- Simple Content (Visible by default) -->
                <div id="simple-content" class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#2C1810] mb-4"></div>
                    <div class="text-[#2C1810] font-bold tracking-widest">{{ $siteName }}</div>
                </div>

                <script>
                    // Immediate check to switch content if this is the first visit
                    if (!sessionStorage.getItem('intro_shown')) {
                        document.getElementById('intro-content').classList.remove('hidden');
                        document.getElementById('intro-content').classList.add('block');
                        document.getElementById('simple-content').classList.add('hidden');
                        document.getElementById('simple-content').classList.remove('flex'); // remove flex if used
                        
                        sessionStorage.setItem('intro_shown', 'true');
                        window.showIntroDelay = true; // Flag for the load listener
                    }
                </script>
            @else
                <!-- Standard Loader for other pages -->
                <div class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#2C1810] mb-4"></div>
                    <div class="text-[#2C1810] font-bold tracking-widest">{{ $siteName }}</div>
                </div>
            @endif
        </div>
    </div>

    @include('layouts.partials.bottom-nav')

    <!-- Ensure navbar is visible immediately -->
    <script>
            (function () {
                const nav = document.getElementById('mobile-bottom-nav');
                if (nav) {
                    nav.style.display = 'flex';
                    nav.style.position = 'fixed';
                    nav.style.bottom = '0';
                    nav.style.height = '72px';
                    nav.style.zIndex = '2147483647';
                    nav.style.overflow = 'hidden';
                }
            })();
    </script>

    <!-- Navbar -->
    <nav class="bg-surface/80 backdrop-blur-md border-b border-white/50 fixed top-0 left-0 right-0 w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-primary">{{ $siteName }}</a>
                </div>

                <!-- Mobile Login/Profile Button -->
                <div class="flex items-center md:hidden">
                    @auth
                        <a href="{{ route('profile.edit') }}" class="text-primary p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8">
                                <path fill-rule="evenodd"
                                    d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-primary font-bold px-4 py-1 border border-primary rounded-full text-sm">Login</a>
                    @endauth
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-primary font-medium">Home</a>
                    <a href="{{ route('products.index') }}"
                        class="text-gray-600 hover:text-primary font-medium">Browse</a>
                    @auth
                        <a href="{{ route('library', ['tab' => 'library']) }}" class="text-gray-600 hover:text-primary font-medium">Library</a>
                        @if(auth()->user()->hasRole('Super Admin'))
                            <a href="{{ route('admin.dashboard') }}"
                                class="text-gray-600 hover:text-primary font-medium">Admin</a>
                        @endif

                        <!-- Desktop Cart -->
                        <a href="{{ route('library', ['tab' => 'cart']) }}"
                            class="relative group py-2 text-gray-600 hover:text-primary font-medium transition-colors flex items-center gap-1">
                            <span>Cart</span>
                            <span x-data="{ count: 0 }"
                                x-init="count = JSON.parse(localStorage.getItem(window.CART_KEY) || '[]').length; window.addEventListener('cart-updated', () => count = JSON.parse(localStorage.getItem(window.CART_KEY) || '[]').length)"
                                x-show="count > 0" x-text="count" x-cloak
                                class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white"></span>
                        </a>

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center text-gray-700 font-medium focus:outline-none">
                                <span>{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 focus:outline-none">
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-primary font-medium">Login</a>
                        <a href="{{ route('register') }}"
                            class="bg-primary text-white px-5 py-2 rounded-full font-medium hover:bg-blue-700 transition-all">Sign
                            Up</a>
                    @endauth
                </div>

            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-500">&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
        </div>
    </footer>

    @stack('scripts')

    <!-- Deferred Scripts for Performance -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2C1810', // Deep Academic Brown
                        'primary-container': '#D4AF37', // Gold for accents
                        'on-primary-container': '#1A0D00',
                        secondary: '#D4AF37', // Gold Accent
                        'secondary-container': '#FDF6E3', // Parchment
                        'on-secondary-container': '#1A0D00',
                        surface: '#F8F1E9', // Aged Paper
                        'surface-variant': '#ffffff', // White (Cards)
                        'on-surface': '#1A0D00', // Deep Charcoal
                        'on-surface-variant': '#8B4513', // Saddle Brown (Highlights)
                        outline: '#8B4513',
                        'inverse-surface': '#1A0D00',
                        'inverse-on-surface': '#F8F1E9',
                    },
                    borderRadius: {
                        '3xl': '1.5rem',
                        '4xl': '2rem',
                    },
                    boxShadow: {
                        'elevation-1': '0px 1px 2px rgba(0, 0, 0, 0.3), 0px 1px 3px 1px rgba(0, 0, 0, 0.15)',
                        'elevation-2': '0px 1px 2px rgba(0, 0, 0, 0.3), 0px 2px 6px 2px rgba(0, 0, 0, 0.15)',
                        'elevation-3': '0px 4px 8px 3px rgba(0, 0, 0, 0.15), 0px 1px 3px rgba(0, 0, 0, 0.3)',
                    }
                }
            }
        }
    </script>

    <script>
        // Loading Screen Logic
        let loaderHidden = false;

        function hideLoader() {
            if (loaderHidden) return;
            loaderHidden = true;

            const loader = document.getElementById('loading-screen');
            if (loader) {
                loader.style.opacity = '0';
                loader.style.visibility = 'hidden';
            }
            document.body.classList.remove('loading-overflow-hidden');
            
            // Re-enable nav if needed (removed old critical css hide)
        }

        // Logic for Hide Delay
        window.addEventListener('load', function() {
            if (window.showIntroDelay) {
                // If it was the intro, wait 3 seconds
                setTimeout(hideLoader, 3000);
            } else {
                // Otherwise, hide immediately
                if (document.readyState === 'loading') {
                    // This case is rare inside window load, but good for safety
                    hideLoader();
                } else {
                    hideLoader();
                }
            }
        });

        // Backup for immediate hide on DOMContentLoaded for non-intro pages 
        // (makes navigation snappy for simple loader)
        document.addEventListener('DOMContentLoaded', function() {
            if (!window.showIntroDelay && !loaderHidden) {
                hideLoader();
            }
        });

        // Safety fallback
        setTimeout(hideLoader, 10000);
    </script>
</body>

</html>