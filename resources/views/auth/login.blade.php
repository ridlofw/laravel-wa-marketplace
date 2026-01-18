<x-seller-auth-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-8 text-center">
        <h2 class="text-3xl font-bold text-purple-900 font-serif" style="font-family: 'Playfair Display', serif;">
            Selamat Datang
        </h2>
        <p class="mt-2 text-sm text-purple-600">
            Masuk ke akun seller Anda
        </p>
    </div>

    <form method="POST" action="{{ route('seller.login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-purple-900">
                Email
            </label>
            <div class="mt-1">
                <input id="email" name="email" type="email" autocomplete="email" required 
                    class="appearance-none block w-full px-3 py-2.5 border border-purple-100 rounded-lg bg-purple-50 text-purple-900 placeholder-purple-400 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm" 
                    placeholder="nama@email.com"
                    value="{{ old('email') }}">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <label for="password" class="block text-sm font-medium text-purple-900">
                Password
            </label>
            <div class="mt-1 relative">
                <input id="password" name="password" :type="show ? 'text' : 'password'" autocomplete="current-password" required 
                    class="appearance-none block w-full px-3 py-2.5 border border-purple-100 rounded-lg bg-purple-50 text-purple-900 placeholder-purple-400 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm pr-10"
                    placeholder="••••••••">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-purple-400 hover:text-purple-600 focus:outline-none">
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg x-show="show" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-purple-300 rounded">
                <label for="remember_me" class="ml-2 block text-sm text-purple-700">
                    Ingat saya
                </label>
            </div>

            @if (Route::has('seller.password.request'))
                <div class="text-sm">
                    <a href="{{ route('seller.password.request') }}" class="font-medium text-purple-600 hover:text-purple-500">
                        Lupa password?
                    </a>
                </div>
            @endif
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150 ease-in-out">
                Masuk
            </button>
        </div>

        <div class="mt-6 text-center text-sm">
            <span class="text-purple-600">Belum punya akun?</span>
            <a href="{{ route('seller.register') }}" class="font-bold text-purple-800 hover:text-purple-900 ml-1">
                Daftar sekarang
            </a>
        </div>
    </form>
</x-seller-auth-layout>
