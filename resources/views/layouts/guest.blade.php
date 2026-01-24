<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .no-select { user-select: none; -webkit-user-select: none; }
            
            /* Security Blackout */
            body.devtools-open {
                display: none !important;
                background: black !important;
            }
            
            body.devtools-open::after {
                content: "Access Denied: Developer Tools Detected";
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: black;
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 999999;
                font-family: sans-serif;
                font-size: 2rem;
            }

            /* Anti-Screenshot / Recording */
            @media print {
                body { display: none !important; }
            }
        </style>
        <script src="{{ asset('js/security.js') }}"></script>
    </head>
    <body class="font-sans text-[#2C1810] antialiased no-select">
        <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0" style="background: linear-gradient(135deg, #2C1810 0%, #3E2723 50%, #1A0D00 100%);">
            <div>
                <a href="/">
                    <x-application-logo class="w-24 h-24 fill-current text-[#D4AF37] drop-shadow-md" style="color: #D4AF37;" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-8 shadow-2xl overflow-hidden sm:rounded-[2rem] border relative group" 
                 style="background-color: #FDF6E3; border-color: rgba(139, 69, 19, 0.3);">
                 <!-- Shine Effect -->
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#D4AF37]/10 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000 pointer-events-none"></div>
                
                <div class="relative z-10">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
