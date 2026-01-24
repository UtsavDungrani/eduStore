<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <h2 class="text-2xl font-bold text-[#2C1810] text-center mb-6 font-serif">Welcome Back</h2>

        <!-- Email Address -->
        <div>
            <label for="email" class="block font-bold text-xs text-[#2C1810] uppercase tracking-wider ml-1 mb-1" style="color: #2C1810;">{{ __('Email') }}</label>
            <input id="email" class="block mt-1 w-full rounded-xl px-4 py-3 text-[#2C1810] shadow-inner transition-all text-lg font-serif" 
                   style="background-color: #FFF8E1; border: 2px solid rgba(139, 69, 19, 0.4); color: #2C1810;"
                   type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="your@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-6">
            <label for="password" class="block font-bold text-xs text-[#2C1810] uppercase tracking-wider ml-1 mb-1" style="color: #2C1810;">{{ __('Password') }}</label>

            <div class="relative" x-data="{ show: false }">
                <input :type="show ? 'text' : 'password'" id="password" class="block mt-1 w-full rounded-xl px-4 py-3 text-[#2C1810] shadow-inner transition-all text-lg font-serif pr-12"
                                style="background-color: #FFF8E1; border: 2px solid rgba(139, 69, 19, 0.4); color: #2C1810;"
                                name="password"
                                required autocomplete="current-password" placeholder="••••••••" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-[#8B4513] hover:text-[#D4AF37] transition-colors focus:outline-none z-10">
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <svg x-show="show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-6">
            <style>
                #remember_me:checked {
                    background-color: #2C1810 !important;
                    background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
                    background-size: 100% 100%;
                    border-color: #2C1810 !important;
                }
            </style>
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded shadow-sm h-6 w-6 cursor-pointer appearance-none transition-all" 
                       style="background-color: #FFF8E1; border: 2px solid rgba(139, 69, 19, 0.5);"
                       name="remember">
                <span class="ms-3 text-base font-bold tracking-wide transition-colors" style="color: #2C1810;">{{ __('Remember My Device') }}</span>
            </label>
        </div>

        <div class="flex flex-col gap-4 mt-8">
            <button type="submit" class="w-full py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-2xl hover:scale-[1.02] transform transition-all border relative overflow-hidden group"
                    style="background: linear-gradient(to right, #2C1810, #3E2723); color: #D4AF37; border-color: #D4AF37;">
                <span class="relative z-10">{{ __('Access Library') }}</span>
                <div class="absolute inset-0 bg-white/10 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-500"></div>
            </button>

            <div class="flex items-center justify-between text-sm px-2 font-serif">
                @if (Route::has('register'))
                    <a class="font-bold transition-colors decoration-2 hover:underline underline-offset-4 flex items-center gap-1" style="color: #8B4513;" href="{{ route('register') }}">
                        <span>Create Account</span> <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                @endif

                @if (Route::has('password.request'))
                    <a class="font-medium transition-colors italic" style="color: rgba(139, 69, 19, 0.8);" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
        </div>
    </form>
</x-guest-layout>
