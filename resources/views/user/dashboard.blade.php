@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-gray-600 mt-2">Ready to continue your learning journey?</p>
    </div>

    <!-- App-Like Shortcuts -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-10 overflow-x-auto pb-4 -mx-1 px-1 snap-x">
        <!-- My Library -->
        <a href="{{ route('library', ['tab' => 'library']) }}" class="flex flex-col items-center p-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg border border-white/10 hover:shadow-xl transition-all duration-300 snap-start shrink-0 min-w-[160px]">
            <div class="bg-white/20 p-3 rounded-xl mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <span class="text-white font-bold text-lg">My Library</span>
            <span class="text-blue-100 text-xs mt-1">Enrolled Content</span>
        </a>

        <!-- Continue Reading -->
        <a href="#" @click.prevent="window.location.href = localStorage.getItem('last_viewed_url') || '{{ route('library', ['tab' => 'library']) }}'" class="flex flex-col items-center p-6 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg border border-white/10 hover:shadow-xl transition-all duration-300 snap-start shrink-0 min-w-[160px]">
            <div class="bg-white/20 p-3 rounded-xl mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" />
                </svg>
            </div>
            <span class="text-white font-bold text-lg">Continue</span>
            <span class="text-emerald-100 text-xs mt-1">Pick up where you left</span>
        </a>

        <!-- New Content -->
        <a href="{{ route('products.index', ['new' => 1]) }}" class="col-span-2 lg:col-span-1 flex lg:flex-col items-center justify-center p-6 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-lg border border-white/10 hover:shadow-xl transition-all duration-300 snap-start gap-4">
            <div class="bg-white/20 p-3 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
                </svg>
            </div>
            <div class="flex flex-col items-center">
                <span class="text-white font-bold text-lg">New Content</span>
                <span class="text-purple-100 text-xs mt-1 text-center">Fresh Uploads</span>
            </div>
        </a>
    </div>

    <!-- Featured Products Section -->
    <div class="flex justify-between items-end mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 line-clamp-1">Explore Courses</h2>
            <p class="text-gray-500 text-sm mt-1">Handpicked for your growth</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-blue-600 font-semibold flex items-center gap-1 hover:gap-2 transition-all">
            See all
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
            @include('user.partials.product-card', ['product' => $product])
        @endforeach
    </div>
</div>
@endsection
