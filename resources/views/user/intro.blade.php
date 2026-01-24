<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'EduStore') }} - Loading...</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        academic: {
                            base: '#2C1810',
                            gold: '#D4AF37',
                            paper: '#F8F1E9',
                            text: '#1A0D00'
                        }
                    },
                    fontFamily: {
                        serif: ['"EB Garamond"', 'Georgia', 'serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #F8F1E9;
            background-image: 
                radial-gradient(#D4AF37 0.5px, transparent 0.5px),
                radial-gradient(#D4AF37 0.5px, #F8F1E9 0.5px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            background-blend-mode: multiply;
            font-family: 'EB Garamond', serif;
            overflow: hidden;
            cursor: pointer;
        }
        
        .parchment-texture {
            position: absolute;
            inset: 0;
            background: url('https://www.transparenttextures.com/patterns/aged-paper.png');
            opacity: 0.5;
            pointer-events: none;
        }
        
        .fade-out {
            animation: fadeOut 1s ease-in-out forwards;
        }
        
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        
        .text-glow {
            text-shadow: 0 0 10px rgba(212, 175, 55, 0.3);
        }
    </style>
</head>
<body onclick="skipIntro()" class="h-screen w-screen flex flex-col items-center justify-center relative">

    <!-- Texture Overlay -->
    <div class="parchment-texture"></div>

    <!-- Content -->
    <div class="relative z-10 text-center p-6 md:p-12 transition-opacity duration-1000" id="intro-content">
        <!-- Logo/Icon (Open Book) -->
        <div class="mb-8 opacity-0 animate-[fadeIn_1.2s_ease-out_forwards]">
            <div class="relative inline-block">
                <div class="absolute inset-0 bg-academic-gold blur-3xl opacity-20 animate-pulse"></div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-28 w-28 md:h-36 md:w-36 mx-auto text-academic-base relative z-10 drop-shadow-2xl" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
        </div>

        <div class="space-y-6 opacity-0 animate-[fadeIn_1.2s_ease-out_0.6s_forwards]">
            <h1 class="text-5xl md:text-8xl font-bold text-academic-base tracking-tight leading-[1.1] text-glow font-serif">
                Upgrade your learning <br/>
                <span class="text-academic-gold italic block mt-2 md:mt-4">with {{ config('app.name', 'EduStore') }}</span>
            </h1>
            
            <div class="h-1 w-24 md:w-32 bg-academic-gold mx-auto rounded-full opacity-60"></div>
            
            <p class="text-academic-base/70 text-xl md:text-2xl font-medium tracking-wide max-w-2xl mx-auto">
                Premium assignments, E-books, and study notes at your fingertips.
            </p>
        </div>
        
        <div class="mt-16 opacity-0 animate-[fadeIn_1.2s_ease-out_1.2s_forwards]">
            <p class="text-[#8B4513] text-sm md:text-base font-bold uppercase tracking-[0.3em] animate-pulse">
                Click anywhere to enter
            </p>
        </div>
    </div>

    <script>
        let skipped = false;
        
        function skipIntro() {
            if (skipped) return;
            skipped = true;
            
            const content = document.getElementById('intro-content');
            content.classList.add('opacity-0');
            
            setTimeout(() => {
                window.location.href = "{{ route('home') }}";
            }, 500);
        }

        // Auto advance after 3 seconds
        setTimeout(() => {
            skipIntro();
        }, 3000);
    </script>
    
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</body>
</html>
