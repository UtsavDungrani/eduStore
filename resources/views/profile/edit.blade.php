@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-4 py-8 pb-8">
    <!-- Floating Profile Header -->
    <div class="glass-panel rounded-[2.5rem] p-6 mb-6 text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-primary-container to-secondary-container opacity-50"></div>
        
        <div class="relative z-10">
            <img class="h-28 w-28 rounded-full object-cover border-4 border-surface mx-auto shadow-elevation-2" 
                 src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=EADDFF&color=21005D&size=256&bold=true" 
                 alt="{{ $user->name }}">
            
            <h1 class="text-2xl font-bold text-on-surface mt-4">{{ $user->name }}</h1>
            <p class="text-on-surface-variant text-sm">{{ $user->email }}</p>

            <div class="mt-6 flex justify-center gap-4">
                <div class="bg-surface/50 rounded-2xl px-5 py-2 backdrop-blur-sm border border-white/40">
                    <span class="block text-xl font-bold text-primary">{{ $purchasedProducts->count() }}</span>
                    <span class="text-xs text-on-surface-variant font-medium">Purchases</span>
                </div>
                <div class="bg-surface/50 rounded-2xl px-5 py-2 backdrop-blur-sm border border-white/40">
                    <span class="block text-xl font-bold text-green-600">Active</span>
                    <span class="text-xs text-on-surface-variant font-medium">Status</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Library Access Section -->
    <div class="mb-6">
        <h2 class="text-lg font-bold text-on-surface mb-4 px-2">My Content</h2>
        <a href="{{ route('library') }}" class="block group">
            <div class="glass-card p-6 flex items-center justify-between hover:bg-surface/50 transition-colors">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                        <i class="fas fa-book-reader text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-on-surface">My Library</h3>
                        <p class="text-sm text-on-surface-variant">Access your purchased content</p>
                    </div>
                </div>
                <div class="h-10 w-10 rounded-full flex items-center justify-center text-on-surface-variant group-hover:text-primary group-hover:bg-primary/10 transition-all">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Account Details Section -->
    <div class="mb-6">
        <h2 class="text-lg font-bold text-on-surface mb-4 px-2">Account Details</h2>
        <div class="glass-card p-6">
            <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf
                @method('patch')

                <div class="space-y-1">
                    <label class="text-xs font-bold text-primary ml-1 uppercase tracking-wider">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                           class="w-full input-filled px-4 py-3 text-on-surface focus:outline-none" 
                           placeholder="Your Name">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1 opacity-60">
                    <label class="text-xs font-bold text-on-surface-variant ml-1 uppercase tracking-wider">Email</label>
                    <div class="relative">
                        <input type="email" value="{{ $user->email }}" readonly 
                               class="w-full input-filled px-4 py-3 text-on-surface-variant cursor-not-allowed focus:outline-none">
                        <i class="fas fa-lock absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm"></i>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <button type="submit" class="bg-primary text-white px-8 py-3 rounded-full font-bold shadow-elevation-1 hover:shadow-elevation-2 transition-all active:scale-95 text-sm">Save Changes</button>
                    
                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-xs font-bold text-green-600 flex items-center gap-1">
                            <i class="fas fa-check-circle"></i> Saved
                        </p>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Security Section -->
    <div class="mb-8">
        <h2 class="text-lg font-bold text-on-surface mb-4 px-2">Security</h2>
        <div class="glass-card p-6">
            <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                @method('put')

                <div class="space-y-1">
                    <label class="text-xs font-bold text-on-surface-variant ml-1 uppercase tracking-wider">Current Password</label>
                    <input type="password" name="current_password" 
                           class="w-full input-filled px-4 py-3 text-on-surface focus:outline-none" 
                           placeholder="••••••••">
                    @error('current_password', 'updatePassword') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-on-surface-variant ml-1 uppercase tracking-wider">New Password</label>
                    <input type="password" name="password" 
                           class="w-full input-filled px-4 py-3 text-on-surface focus:outline-none" 
                           placeholder="Min 8 chars">
                    @error('password', 'updatePassword') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-on-surface-variant ml-1 uppercase tracking-wider">Confirm Password</label>
                    <input type="password" name="password_confirmation" 
                           class="w-full input-filled px-4 py-3 text-on-surface focus:outline-none" 
                           placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between pt-2">
                    <button type="submit" class="bg-secondary text-white px-8 py-3 rounded-full font-bold shadow-elevation-1 hover:shadow-elevation-2 transition-all active:scale-95 text-sm">Update Password</button>
                    
                    @if (session('status') === 'password-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-xs font-bold text-green-600 flex items-center gap-1">
                            <i class="fas fa-check-circle"></i> Secure
                        </p>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Support Section -->
    <div class="mb-8">
        <h2 class="text-lg font-bold text-on-surface mb-4 px-2">Support</h2>
        <div class="glass-card p-6 flex flex-col items-center text-center">
            <div class="h-16 w-16 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center mb-4">
                <i class="fas fa-headset text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-on-surface mb-2">Need Help?</h3>
            <p class="text-on-surface-variant text-sm mb-6 max-w-xs">If you have any issues with your account or purchases, our support team is here to help.</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full">
                <a href="mailto:support@example.com" class="flex items-center justify-center gap-2 text-primary font-bold bg-primary/10 px-6 py-3 rounded-2xl hover:bg-primary/20 transition-colors">
                    <i class="fas fa-envelope"></i>
                    <span>Email Us</span>
                </a>
                <a href="https://wa.me/1234567890" target="_blank" class="flex items-center justify-center gap-2 text-green-600 font-bold bg-green-500/10 px-6 py-3 rounded-2xl hover:bg-green-500/20 transition-colors">
                    <i class="fab fa-whatsapp text-lg"></i>
                    <span>WhatsApp</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Logout Action -->
    <div class="flex justify-center">
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 text-red-600 bg-red-50/50 border border-red-100 px-6 py-4 rounded-3xl font-bold hover:bg-red-100 transition-all active:scale-95 group backdrop-blur-sm">
                <i class="fas fa-power-off group-hover:rotate-12 transition-transform"></i>
                Sign Out
            </button>
        </form>
    </div>
</div>
@endsection
