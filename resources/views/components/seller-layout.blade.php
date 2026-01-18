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
            .sidebar-active {
                background: linear-gradient(to right, rgba(139, 92, 246, 0.2), transparent);
                border-left: 4px solid white;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-50">
            <!-- Sidebar -->
            <aside class="fixed left-0 top-0 w-64 h-screen bg-gradient-to-b from-purple-600 via-purple-700 to-violet-800 text-white overflow-y-auto">
                <div class="flex flex-col min-h-full">
                    <!-- Logo/Brand -->
                    <div class="p-6 border-b border-purple-500/30">
                        <h1 class="text-2xl font-bold text-white">Seller Hub</h1>
                        <p class="text-sm text-purple-200">Marketplace</p>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 px-4 py-6 space-y-2">
                        <a href="{{ route('seller.dashboard') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all hover:bg-white/10 {{ request()->routeIs('seller.dashboard') ? 'sidebar-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                            <span class="font-medium">Dashboard</span>
                        </a>

                        <a href="{{ route('seller.products.index') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all hover:bg-white/10 {{ request()->routeIs('seller.products.*') ? 'sidebar-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="font-medium">Produk</span>
                        </a>

                        <a href="{{ route('seller.settings') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all hover:bg-white/10 {{ request()->routeIs('seller.settings') ? 'sidebar-active' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="font-medium">Pengaturan</span>
                        </a>
                    </nav>

                    <!-- Admin Section -->
                    <div class="p-4 border-t border-purple-500/30">
                        <div class="mb-3">
                            <p class="text-sm font-semibold text-white">Admin</p>
                            <p class="text-xs text-purple-200 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('seller.logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 w-full px-3 py-2 text-sm bg-white/10 hover:bg-white/20 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
