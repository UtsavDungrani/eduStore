<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $siteName }} - Admin Panel</title>
    
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '{{ $brandColor }}',
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: {{ $brandColor }};
        }
        [x-cloak] { display: none !important; }
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

        /* Loading Screen */
        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #ffffff;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease-out, visibility 0.5s;
        }

        .loader {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            color: #374151;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        body.loading-overflow-hidden {
            overflow: hidden;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="{{ asset('js/security.js') }}"></script>
    <script>
        // Performance optimization: Show loading screen on form submit and page leave
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('loading-screen');
            
            // Show loader when a form is submitted
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    loader.style.opacity = '1';
                    loader.style.visibility = 'visible';
                    document.body.classList.add('loading-overflow-hidden');
                });
            });

            // Show loader before page unload for clicks on links
            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href && href !== '#' && !href.startsWith('javascript:') && !this.hasAttribute('target')) {
                        loader.style.opacity = '1';
                        loader.style.visibility = 'visible';
                        document.body.classList.add('loading-overflow-hidden');
                    }
                });
            });
        });

        window.addEventListener('load', function() {
            const loader = document.getElementById('loading-screen');
            loader.style.opacity = '0';
            loader.style.visibility = 'hidden';
            document.body.classList.remove('loading-overflow-hidden');
        });
    </script>
</head>
<body class="bg-gray-100 font-sans antialiased no-select loading-overflow-hidden" x-data="{ sidebarOpen: false }">
    <!-- Loading Screen -->
    <div id="loading-screen">
        <div class="loader"></div>
        <div class="loading-text">{{ $siteName }}</div>
    </div>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-100 transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0">
            <div class="flex flex-col h-full">
                <!-- Brand -->
                <div class="flex items-center justify-center h-20 bg-white border-b border-gray-100">
                    <span class="text-primary text-xl font-bold uppercase tracking-wider">{{ $siteName }}</span>
                </div>

                <!-- Nav -->
                <nav class="flex-1 px-4 py-4 space-y-2 overflow-y-auto no-scrollbar">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-primary rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-primary' : '' }}">
                        <i class="fas fa-chart-line w-6"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>
                    <a href="{{ route('admin.analytics') }}" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-primary rounded-lg transition-colors {{ request()->routeIs('admin.analytics') ? 'bg-blue-50 text-primary' : '' }}">
                        <i class="fas fa-chart-pie w-6"></i>
                        <span class="ml-3">Analytics</span>
                    </a>
                    <a href="{{ route('admin.instructors.payouts') }}" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-primary rounded-lg transition-colors {{ request()->routeIs('admin.instructors.payouts') ? 'bg-blue-50 text-primary' : '' }}">
                        <i class="fas fa-hand-holding-usd w-6"></i>
                        <span class="ml-3">Payouts</span>
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-primary rounded-lg transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-blue-50 text-primary' : '' }}">
                        <i class="fas fa-box w-6"></i>
                        <span class="ml-3">Products</span>
                    </a>
                    @role('Super Admin')
                    <a href="{{ route('admin.featured.index') }}" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-primary rounded-lg transition-colors {{ request()->routeIs('admin.featured.*') ? 'bg-blue-50 text-primary' : '' }}">
                        <i class="fas fa-star w-6"></i>
                        <span class="ml-3">Featured Content</span>
                    </a>
                    <a href="{{ route('admin.recent.index') }}" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-primary rounded-lg transition-colors {{ request()->routeIs('admin.recent.*') ? 'bg-blue-50 text-primary' : '' }}">
                        <i class="fas fa-plus-circle w-6"></i>
                        <span class="ml-3">Recently Added</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-primary rounded-lg transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-primary' : '' }}">
                        <i class="fas fa-tags w-6"></i>
                        <span class="ml-3">Categories</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-primary rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-primary' : '' }}">
                        <i class="fas fa-users w-6"></i>
                        <span class="ml-3">Users</span>
                    </a>
                    <a href="{{ route('admin.banners.index') }}" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-primary rounded-lg transition-colors {{ request()->routeIs('admin.banners.*') ? 'bg-blue-50 text-primary' : '' }}">
                        <i class="fas fa-image w-6"></i>
                        <span class="ml-3">Banners</span>
                    </a>
                    @endrole

                    <a href="{{ route('admin.orders.index') }}" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-primary rounded-lg transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-blue-50 text-primary' : '' }}">
                        <i class="fas fa-shopping-basket w-6"></i>
                        <span class="ml-3">Cart Orders</span>
                    </a>
                    <a href="{{ route('admin.payment-requests.index') }}" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-primary rounded-lg transition-colors {{ request()->routeIs('admin.payment-requests.*') ? 'bg-blue-50 text-primary' : '' }}">
                        <i class="fas fa-receipt w-6"></i>
                        <span class="ml-3">Single Orders</span>
                    </a>

                    @role('Super Admin')
                    <a href="{{ route('admin.logs') }}" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-primary rounded-lg transition-colors {{ request()->routeIs('admin.logs') ? 'bg-blue-50 text-primary' : '' }}">
                        <i class="fas fa-history w-6"></i>
                        <span class="ml-3">Access Logs</span>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-primary rounded-lg transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-blue-50 text-primary' : '' }}">
                        <i class="fas fa-cog w-6"></i>
                        <span class="ml-3">Settings</span>
                    </a>
                    @endrole
                </nav>

                <!-- Footer -->
                <div class="p-4 bg-white border-t border-gray-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-4 py-2 text-red-400 hover:bg-red-500 hover:text-white rounded-lg transition-all">
                            <i class="fas fa-sign-out-alt w-6"></i>
                            <span class="ml-3">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 w-full overflow-hidden">
            <!-- Header -->
            <header class="flex items-center justify-between h-20 px-6 bg-white border-b lg:justify-end">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 lg:hidden focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
                <div class="flex items-center space-x-4" x-data="{ userDropdownOpen: false, helpOpen: false }">
                    <!-- Need Help Button -->
                    <button @click="helpOpen = true" class="flex items-center text-gray-500 hover:text-primary transition-colors focus:outline-none">
                        <i class="fas fa-question-circle text-xl"></i>
                        <span class="ml-2 font-medium hidden md:block">Need Help?</span>
                    </button>

                    <!-- User Dropdown -->
                    <div class="relative">
                        <button @click="userDropdownOpen = !userDropdownOpen" @click.away="userDropdownOpen = false" class="flex items-center focus:outline-none">
                            <span class="text-gray-700 font-medium mr-4 hidden md:block">{{ Auth::user()->name }}</span>
                            <img class="h-10 w-10 rounded-full object-cover border-2 border-primary" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=1e40af&color=fff" alt="Profile">
                        </button>


                    <!-- Help Modal -->
                    <div x-show="helpOpen" 
                         class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto px-4 py-6 sm:px-0"
                         style="display: none;">
                        
                        <!-- Backdrop -->
                        <div x-show="helpOpen" 
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="fixed inset-0 transform transition-all" 
                             @click="helpOpen = false">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>

                        <!-- Modal Content -->
                        <div x-show="helpOpen" 
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-lg sm:mx-auto">
                            
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <i class="fas fa-headset text-primary text-xl"></i>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                            Contact Support
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500">
                                                Need assistance? Reach out to our support team via Email or WhatsApp.
                                            </p>
                                            
                                            <div class="mt-6 flex flex-col space-y-3">
                                                <a href="mailto:support@edustore.com" class="flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition-colors">
                                                    <i class="fas fa-envelope mr-2"></i> Email Support
                                                </a>
                                                <a href="https://wa.me/919999999999" target="_blank" class="flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-500 hover:bg-green-600 shadow-sm transition-colors">
                                                    <i class="fab fa-whatsapp mr-2"></i> WhatsApp Support
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="helpOpen = false">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
