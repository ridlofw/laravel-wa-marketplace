<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $product->name }} - Marketplace</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-[#FBF9FF] min-h-screen text-gray-800">
        <div class="relative min-h-screen flex flex-col">
            <!-- Navbar -->
            <nav class="bg-white border-b border-purple-100 sticky top-0 z-50 shadow-sm">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-20 gap-8">
                        <!-- Logo -->
                        <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-3">
                            <img src="{{ asset('images/logo.png') }}" alt="Permata Klepu Logo" class="h-16 w-auto object-contain">
                            <div class="hidden md:block">
                                <h1 class="text-xl font-bold text-purple-900 leading-tight">Permata Klepu</h1>
                                <p class="text-xs text-purple-500 font-medium">Marketplace</p>
                            </div>
                        </a>

                        <!-- Search Bar (Wide) -->
                        <div class="flex-grow max-w-3xl">
                            <form action="{{ route('home') }}" method="GET" class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-focus-within:text-purple-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ request('search') }}"
                                    placeholder="Cari produk lokal..." 
                                    class="w-full py-3 pl-12 pr-4 rounded-full bg-gray-100 border-transparent focus:bg-white focus:border-purple-300 focus:ring-4 focus:ring-purple-100 text-gray-800 placeholder-gray-400 transition duration-300"
                                >
                            </form>
                        </div>

                        <!-- Right Actions -->
                        <div class="flex-shrink-0 flex items-center gap-4">
                            <span class="hidden lg:block text-sm font-medium text-gray-500">Produk dari <span class="text-purple-700 font-bold">UMKM</span></span>
                            <div class="h-6 w-px bg-gray-200 hidden lg:block"></div>
                            @auth
                                <a href="{{ route('seller.dashboard') }}" class="text-sm font-semibold text-purple-700 hover:text-purple-900 transition">Kelola Toko</a>
                            @else
                                <a href="{{ route('seller.login') }}" class="text-sm font-semibold text-gray-600 hover:text-purple-700 transition">Kelola Toko</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Back to Home -->
                <div class="mb-6">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-gray-500 hover:text-purple-600 transition-colors font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Beranda
                    </a>
                </div>
                <!-- Product Detail Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-16" x-data="{ 
                    lightboxOpen: false, 
                    activeImage: '{{ $product->images->count() > 0 ? asset('storage/' . $product->images->first()->image_path) : ($product->image ? asset('storage/' . $product->image) : '') }}',
                    activeSlide: 0, 
                    slides: {{ $product->images->count() }} 
                }">
                    <!-- Left: Image Section -->
                    <div class="flex items-start justify-center">
                        @if($product->images->count() > 0)
                            <div class="relative group w-full">
                                <div class="overflow-hidden rounded-3xl shadow-lg relative w-full pt-[100%] bg-white cursor-pointer" @click="lightboxOpen = true">
                                    @foreach($product->images as $index => $image)
                                        <div x-show="activeSlide === {{ $index }}" class="absolute inset-0 transition-opacity duration-500" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover" @click="activeImage = '{{ asset('storage/' . $image->image_path) }}'">
                                        </div>
                                    @endforeach
                                </div>
                                
                                <!-- Navigation Buttons -->
                                <button @click.stop="activeSlide = activeSlide === 0 ? slides - 1 : activeSlide - 1; activeImage = '{{ asset('storage/') }}/' + '{{ $product->images->pluck('image_path')->join(',') }}'.split(',')[activeSlide === 0 ? slides - 1 : activeSlide - 1]" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 p-2 rounded-full transition-colors shadow-md z-10" x-show="slides > 1">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                </button>
                                <button @click.stop="activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1; activeImage = '{{ asset('storage/') }}/' + '{{ $product->images->pluck('image_path')->join(',') }}'.split(',')[activeSlide === slides - 1 ? 0 : activeSlide + 1]" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 p-2 rounded-full transition-colors shadow-md z-10" x-show="slides > 1">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </button>
                                
                                <!-- Indicators -->
                                <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-10" x-show="slides > 1">
                                    <template x-for="i in slides">
                                        <button @click.stop="activeSlide = i - 1; activeImage = '{{ asset('storage/') }}/' + '{{ $product->images->pluck('image_path')->join(',') }}'.split(',')[i - 1]" :class="{'bg-purple-600': activeSlide === i - 1, 'bg-gray-300': activeSlide !== i - 1}" class="w-2 h-2 rounded-full transition-colors"></button>
                                    </template>
                                </div>
                            </div>
                        @elseif($product->image)
                            <div class="w-full relative pt-[100%] bg-white rounded-3xl shadow-lg overflow-hidden cursor-pointer" @click="lightboxOpen = true; activeImage = '{{ asset('storage/' . $product->image) }}'">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-full relative pt-[100%] bg-gray-200 rounded-3xl shadow-lg">
                                <div class="absolute inset-0 flex items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Lightbox Modal -->
                    <div x-show="lightboxOpen" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4" 
                         style="display: none;">
                        
                        <button @click="lightboxOpen = false" class="absolute top-4 right-4 text-white hover:text-gray-300 z-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <div class="relative max-w-5xl max-h-full w-full h-full flex items-center justify-center" @click.outside="lightboxOpen = false">
                            <img :src="activeImage" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
                        </div>
                    </div>

                    <!-- Right: Details Section -->
                    <div class="flex flex-col">
                        <h1 class="text-3xl md:text-4xl font-bold font-serif text-gray-900 mb-4">{{ $product->name }}</h1>
                        
                        <!-- Seller & Location -->
                        <div class="flex flex-wrap items-center gap-4 mb-8 text-sm text-gray-500">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>{{ $product->seller->shop_name ?? $product->seller->name }}</span>
                                @if($product->seller->shop_address)
                                    <span class="mx-1">-</span>
                                    <span>{{ $product->seller->shop_address }}</span>
                                @endif
                            </div>
                            @if($product->address)
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ $product->address }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Price -->
                        <div class="mb-8 p-6 bg-white rounded-2xl border border-gray-100 shadow-sm">
                            <span class="text-sm text-gray-500 font-medium flex items-center gap-1 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                Harga
                            </span>
                            <p class="text-4xl font-semibold text-purple-700/50 leading-relaxed">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>

                        <!-- Description -->
                        <div class="mb-8">
                            <h3 class="font-bold text-lg mb-3 text-gray-900">Deskripsi Produk</h3>
                            <div class="p-5 bg-white border border-gray-200 rounded-2xl text-gray-600 leading-relaxed text-sm shadow-sm">
                                {{ $product->description }}
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="mb-8">
                            <h3 class="font-bold text-lg mb-3 text-gray-900">Informasi Produk</h3>
                            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                                <div class="p-4 flex justify-between items-center border-b border-gray-100 last:border-0">
                                    <span class="text-gray-500 text-sm">Kategori</span>
                                    <span class="text-gray-900 font-medium text-sm">{{ $product->category ?? '-' }}</span>
                                </div>
                                <div class="p-4 flex justify-between items-center border-b border-gray-100 last:border-0">
                                    <span class="text-gray-500 text-sm">Penjual</span>
                                    <span class="text-gray-900 font-medium text-sm">{{ $product->seller->shop_name ?? $product->seller->name }}</span>
                                </div>
                                <div class="p-4 flex justify-between items-center border-b border-gray-100 last:border-0">
                                    <span class="text-gray-500 text-sm">Lokasi</span>
                                    <span class="text-gray-900 font-medium text-sm">{{ $product->address ?? $product->seller->shop_address }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <div class="mt-auto">
                            <a href="{{ route('public.checkout', $product) }}" class="block w-full bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white text-center font-bold py-4 px-6 rounded-xl transition duration-300 shadow-lg hover:shadow-purple-500/30 flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Checkout Sekarang
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Related Products Section -->
                <div class="mb-12">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Produk Lainnya</h2>
                        <a href="{{ route('home') }}" class="text-purple-600 hover:text-purple-800 text-sm font-semibold flex items-center">
                            Lihat Semua
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                        @forelse($relatedProducts as $related)
                            <a href="{{ route('public.product.show', $related) }}" class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-purple-50 overflow-hidden flex flex-col h-full hover:-translate-y-1">
                                <div class="relative aspect-w-1 aspect-h-1 overflow-hidden bg-gray-100">
                                    @if($related->images->count() > 0)
                                        <img src="{{ asset('storage/' . $related->images->first()->image_path) }}" alt="{{ $related->name }}" class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-500">
                                    @elseif($related->image)
                                        <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}" class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-56 bg-gray-200 flex items-center justify-center text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <!-- Overlay -->
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition duration-300"></div>
                                </div>
                                <div class="p-5 flex flex-col flex-grow">
                                    <h3 class="font-bold text-gray-900 mb-1 group-hover:text-purple-600 transition-colors line-clamp-1 text-lg">{{ $related->name }}</h3>
                                    <div class="flex items-center gap-1 mb-3 text-gray-500 text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span class="line-clamp-1">{{ $related->seller->shop_name ?? $related->seller->name }}</span>
                                    </div>
                                    <div class="mt-auto flex items-center justify-between">
                                        <p class="text-purple-600 font-bold text-xl">Rp {{ number_format($related->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-full text-center py-12 text-gray-500 bg-white rounded-2xl border border-dashed border-gray-300">
                                <p>Belum ada produk lainnya.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </main>

            <footer class="bg-white border-t border-gray-200 py-8 mt-12">
                <div class="container mx-auto px-6 text-center text-gray-500 text-sm">
                    &copy; {{ date('Y') }} Permata Klepu Marketplace. All rights reserved.
                </div>
            </footer>
        </div>
    </body>
</html>
