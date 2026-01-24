@php
    $isLibrary = request()->routeIs('library');
    $activeTab = request()->query('tab', 'library');
@endphp

<!-- Mobile Bottom Navigation - Sticky Design -->
<nav id="mobile-bottom-nav"
    style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 2147483647; width: 100%; height: 72px; display: flex; justify-content: center; align-items: stretch; background-color: white; border-top: 1px solid #e5e7eb; box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1); transform: translateZ(0); -webkit-transform: translateZ(0);">
    <div class="mobile-nav-inner"
        style="display: flex; justify-content: space-around; align-items: center; width: 100%; height: 100%; padding: 0 1rem;">

        <!-- Home -->
        <a href="{{ route('home') }}" class="mobile-nav-item group">
            <div class="relative">
                @if(request()->routeIs('home'))
                    <div class="absolute inset-0 bg-primary/10 rounded-full blur-sm"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="mobile-nav-icon text-primary relative">
                        <path
                            d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
                        <path
                            d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="mobile-nav-icon text-gray-400 group-hover:text-gray-600 transition-colors">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                @endif
            </div>
            <span class="mobile-nav-text {{ request()->routeIs('home') ? 'text-primary' : 'text-gray-500 group-hover:text-gray-700' }} transition-colors">Home</span>
        </a>

        <!-- Browse -->
        <a href="{{ route('products.index') }}" class="mobile-nav-item group">
            <div class="relative">
                @if(request()->routeIs('products.index'))
                    <div class="absolute inset-0 bg-primary/10 rounded-full blur-sm"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="mobile-nav-icon text-primary relative">
                        <path fill-rule="evenodd"
                            d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z"
                            clip-rule="evenodd" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="mobile-nav-icon text-gray-400 group-hover:text-gray-600 transition-colors">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                @endif
            </div>
            <span class="mobile-nav-text {{ request()->routeIs('products.index') ? 'text-primary' : 'text-gray-500 group-hover:text-gray-700' }} transition-colors">Browse</span>
        </a>

        <!-- Cart -->
        <a href="{{ route('library', ['tab' => 'cart']) }}" class="mobile-nav-item group" onclick="if(window.setTab) { window.setTab('cart'); return false; }">
            <div class="relative">
                @if($isLibrary && $activeTab === 'cart')
                    <div class="absolute inset-0 bg-primary/10 rounded-full blur-sm"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="mobile-nav-icon text-primary relative">
                        <path fill-rule="evenodd"
                            d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 0 0 4.25 22.5h15.5a1.875 1.875 0 0 0 1.865-2.071l-1.263-12a1.875 1.875 0 0 0-1.865-1.679H16.5V6a4.5 4.5 0 1 0-9 0ZM12 3a3 3 0 0 0-3 3v.75h6V6a3 3 0 0 0-3-3Zm-3 8.25a3 3 0 1 0 6 0v-.75a.75.75 0 0 1 1.5 0v.75a4.5 4.5 0 1 1-9 0v-.75a.75.75 0 0 1 1.5 0v.75Z"
                            clip-rule="evenodd" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor"
                        class="mobile-nav-icon text-gray-400 group-hover:text-gray-600 transition-colors">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                @endif
                <!-- Cart Badge -->
                <span x-data="{ count: 0 }"
                    x-init="count = JSON.parse(localStorage.getItem(window.CART_KEY) || '[]').length; window.addEventListener('cart-updated', () => count = JSON.parse(localStorage.getItem(window.CART_KEY) || '[]').length)"
                    x-show="count > 0" x-text="count" x-cloak
                    class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center shadow-sm"></span>
            </div>
            <span class="mobile-nav-text {{ ($isLibrary && $activeTab === 'cart') ? 'text-primary' : 'text-gray-500 group-hover:text-gray-700' }} transition-colors">Cart</span>
        </a>

        <!-- Library -->
        <a href="{{ route('library', ['tab' => 'library']) }}" class="mobile-nav-item group" onclick="if(window.setTab) { window.setTab('library'); return false; }">
            <div class="relative">
                @if($isLibrary && $activeTab === 'library')
                    <div class="absolute inset-0 bg-primary/10 rounded-full blur-sm"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="mobile-nav-icon text-primary relative">
                        <path
                            d="M11.25 4.533A9.707 9.707 0 0 0 6 3a9.735 9.735 0 0 0-3.25.555.75.75 0 0 0-.5.707v14.25a.75.75 0 0 0 1 .707A8.237 8.237 0 0 1 6 18.75c1.995 0 3.823.707 5.25 1.886V4.533ZM12.75 20.636A8.214 8.214 0 0 1 18 18.75c.966 0 1.89.166 2.75.47a.75.75 0 0 0 1-.708V4.262a.75.75 0 0 0-.5-.707A9.735 9.735 0 0 0 18 3a9.707 9.707 0 0 0-5.25 1.533v16.103Z" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="mobile-nav-icon text-gray-400 group-hover:text-gray-600 transition-colors">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                @endif
            </div>
            <span class="mobile-nav-text {{ ($isLibrary && $activeTab === 'library') ? 'text-primary' : 'text-gray-500 group-hover:text-gray-700' }} transition-colors">Library</span>
        </a>
    </div>
</nav>
