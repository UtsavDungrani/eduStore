@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-4 py-8 pb-8">
    <!-- Floating Profile Header -->
    <!-- Floating Profile Header -->
    <div class="bg-[#FDF6E3] rounded-[2.5rem] p-6 mb-8 text-center relative overflow-hidden border border-[#D4AF37] shadow-lg">
        <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-[#D4AF37]/20 to-[#8B4513]/20 opacity-50"></div>
        
        <div class="relative z-10">
            <img class="h-28 w-28 rounded-full object-cover border-4 border-[#2C1810] mx-auto shadow-xl" 
                 src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2C1810&color=D4AF37&size=256&bold=true" 
                 alt="{{ $user->name }}">
            
            <h1 class="text-2xl font-bold text-[#2C1810] mt-4 font-serif">{{ $user->name }}</h1>
            <p class="text-[#8B4513] text-sm font-serif italic">{{ $user->email }}</p>

            <div class="mt-6 flex justify-center gap-4">
                <div class="bg-[#2C1810]/5 rounded-2xl px-5 py-2 border border-[#D4AF37]/30">
                    <span class="block text-xl font-bold text-[#2C1810]">{{ $purchasedProducts->count() }}</span>
                    <span class="text-xs text-[#8B4513] font-medium uppercase tracking-wider">Purchases</span>
                </div>
                <div class="bg-[#2C1810]/5 rounded-2xl px-5 py-2 border border-[#D4AF37]/30">
                    <span class="block text-xl font-bold text-emerald-700">Active</span>
                    <span class="text-xs text-[#8B4513] font-medium uppercase tracking-wider">Status</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Library Access Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between bg-[#2C1810] p-4 rounded-xl border border-[#D4AF37] shadow-lg mb-6 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#D4AF37]/10 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
            <h2 class="text-xl font-bold text-[#F8F1E9] flex items-center gap-3 font-serif relative z-10">
                <i class="fas fa-book-reader text-[#D4AF37]"></i> My Content
            </h2>
        </div>

        <a href="{{ route('library', ['tab' => 'library']) }}" class="block group">
            <div class="bg-[#FDF6E3] rounded-2xl p-6 flex items-center justify-between hover:bg-[#F8F1E9] transition-all border border-[#D4AF37] shadow-md group-hover:shadow-lg">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-full bg-[#2C1810]/10 flex items-center justify-center text-[#2C1810]">
                        <i class="fas fa-book text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-[#2C1810] font-serif text-lg">My Library</h3>
                        <p class="text-sm text-[#8B4513]">Access your purchased content</p>
                    </div>
                </div>
                <div class="h-10 w-10 rounded-full flex items-center justify-center text-[#D4AF37] group-hover:text-[#2C1810] group-hover:bg-[#D4AF37] transition-all">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Account Details Section -->
    <!-- Account Details Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between bg-[#2C1810] p-4 rounded-xl border border-[#D4AF37] shadow-lg mb-6 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#D4AF37]/10 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
            <h2 class="text-xl font-bold text-[#F8F1E9] flex items-center gap-3 font-serif relative z-10">
                <i class="fas fa-user-cog text-[#D4AF37]"></i> Account Details
            </h2>
        </div>

        <div class="bg-[#FDF6E3] rounded-2xl p-6 border border-[#D4AF37] shadow-md">
            <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf
                @method('patch')

                <div class="space-y-1">
                    <label class="text-xs font-bold text-[#2C1810] ml-1 uppercase tracking-wider">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                           class="w-full bg-white border border-[#D4AF37]/30 rounded-lg px-4 py-3 text-[#2C1810] focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37]" 
                           placeholder="Your Name">
                    @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1 opacity-80">
                    <label class="text-xs font-bold text-[#8B4513] ml-1 uppercase tracking-wider">Email</label>
                    <div class="relative">
                        <input type="email" value="{{ $user->email }}" readonly 
                               class="w-full bg-[#2C1810]/5 border border-transparent rounded-lg px-4 py-3 text-[#8B4513] cursor-not-allowed focus:outline-none">
                        <i class="fas fa-lock absolute right-4 top-1/2 -translate-y-1/2 text-[#8B4513] text-sm"></i>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <button type="submit" class="bg-[#2C1810] text-[#D4AF37] px-8 py-3 rounded-full font-bold shadow-md hover:bg-[#1A0D00] transition-all active:scale-95 text-sm border border-[#D4AF37]">Save Changes</button>
                    
                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-xs font-bold text-emerald-700 flex items-center gap-1">
                            <i class="fas fa-check-circle"></i> Saved
                        </p>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Security Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between bg-[#2C1810] p-4 rounded-xl border border-[#D4AF37] shadow-lg mb-6 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#D4AF37]/10 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
            <h2 class="text-xl font-bold text-[#F8F1E9] flex items-center gap-3 font-serif relative z-10">
                <i class="fas fa-shield-alt text-[#D4AF37]"></i> Security
            </h2>
        </div>

        <div class="bg-[#FDF6E3] rounded-2xl p-6 border border-[#D4AF37] shadow-md">
            <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                @method('put')

                <div class="space-y-1">
                    <label class="text-xs font-bold text-[#8B4513] ml-1 uppercase tracking-wider">Current Password</label>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="current_password" 
                               class="w-full bg-white border border-[#D4AF37]/30 rounded-lg px-4 py-3 text-[#2C1810] focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] pr-12" 
                               placeholder="••••••••">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-[#D4AF37] hover:text-[#8B4513] transition-colors focus:outline-none">
                             <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    @error('current_password', 'updatePassword') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-[#8B4513] ml-1 uppercase tracking-wider">New Password</label>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="password" 
                               class="w-full bg-white border border-[#D4AF37]/30 rounded-lg px-4 py-3 text-[#2C1810] focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] pr-12" 
                               placeholder="Min 8 chars">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-[#D4AF37] hover:text-[#8B4513] transition-colors focus:outline-none">
                             <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    @error('password', 'updatePassword') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-[#8B4513] ml-1 uppercase tracking-wider">Confirm Password</label>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" 
                               class="w-full bg-white border border-[#D4AF37]/30 rounded-lg px-4 py-3 text-[#2C1810] focus:outline-none focus:border-[#D4AF37] focus:ring-1 focus:ring-[#D4AF37] pr-12" 
                               placeholder="••••••••">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-[#D4AF37] hover:text-[#8B4513] transition-colors focus:outline-none">
                             <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <button type="submit" class="bg-[#D4AF37] text-[#2C1810] px-8 py-3 rounded-full font-bold shadow-md hover:bg-[#B59530] transition-all active:scale-95 text-sm border border-[#2C1810]/20">Update Password</button>
                    
                    @if (session('status') === 'password-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-xs font-bold text-emerald-700 flex items-center gap-1">
                            <i class="fas fa-check-circle"></i> Secure
                        </p>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Support Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between bg-[#2C1810] p-4 rounded-xl border border-[#D4AF37] shadow-lg mb-6 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[#D4AF37]/10 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
            <h2 class="text-xl font-bold text-[#F8F1E9] flex items-center gap-3 font-serif relative z-10">
                <i class="fas fa-life-ring text-[#D4AF37]"></i> Support
            </h2>
        </div>

        <div class="bg-[#FDF6E3] rounded-2xl p-6 flex flex-col items-center text-center border border-[#D4AF37] shadow-md">
            <div class="h-16 w-16 rounded-full bg-[#D4AF37]/20 text-[#2C1810] flex items-center justify-center mb-4 border border-[#D4AF37]">
                <i class="fas fa-headset text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-[#2C1810] mb-2 font-serif">Need Help?</h3>
            <p class="text-[#8B4513] text-sm mb-6 max-w-xs font-serif italic">If you have any issues with your account or purchases, our support team is here to help.</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full">
                <a href="mailto:support@example.com" class="flex items-center justify-center gap-2 text-[#2C1810] font-bold bg-[#2C1810]/10 px-6 py-3 rounded-2xl hover:bg-[#2C1810]/20 transition-colors border border-[#2C1810]/10">
                    <i class="fas fa-envelope"></i>
                    <span>Email Us</span>
                </a>
                <a href="https://wa.me/1234567890" target="_blank" class="flex items-center justify-center gap-2 text-emerald-800 font-bold bg-emerald-100 px-6 py-3 rounded-2xl hover:bg-emerald-200 transition-colors border border-emerald-200">
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
            <button type="submit" class="w-full flex items-center justify-center gap-2 text-red-800 bg-red-50 border border-red-200 px-6 py-4 rounded-3xl font-bold hover:bg-red-100 transition-all active:scale-95 group shadow-sm">
                <i class="fas fa-power-off group-hover:rotate-12 transition-transform"></i>
                Sign Out
            </button>
        </form>
    </div>
</div>
@endsection
