<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Permata Klepu - Marketplace</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-white min-h-screen text-gray-800">
        <div class="relative min-h-screen flex flex-col" x-data="productSearch()">
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
                                    <!-- Loading Spinner -->
                                    <svg x-show="loading" class="animate-spin h-5 w-5 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <!-- Search Icon -->
                                    <svg x-show="!loading" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-focus-within:text-purple-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="search" 
                                    x-model="searchQuery"
                                    @input.debounce.300ms="performSearch()"
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

            <main class="flex-grow">
                <!-- Hero Section (Purple Background) -->
                <div class="bg-purple-100 py-10 md:py-20">
                    <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white text-purple-700 text-sm font-semibold mb-8 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd" />
                            </svg>
                            Marketplace Lokal Desa Klepu
                        </div>
                        
                        <h1 class="text-5xl md:text-7xl font-extrabold mb-6 tracking-tight text-gray-900">
                            Belanja Produk Lokal <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-500">Langsung dari Desa</span>
                        </h1>
                        
                        <p class="text-lg md:text-xl text-gray-500 max-w-3xl mx-auto leading-relaxed">
                            Dukung UMKM lokal dengan berbelanja produk pertanian organik, kerajinan tangan, dan kuliner khas dari 6 dusun di Desa Klepu
                        </p>
                    </div>
                </div>
                
                <!-- Product Grid Section -->
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <!-- Product Header -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900">Daftar Produk</h2>
                        <p class="text-gray-500 mt-1">Menampilkan <span x-text="products.length">{{ $products->count() }}</span> produk</p>
                    </div>

                    <!-- Products Grid with Alpine.js -->
                    <div x-show="products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                        <template x-for="product in products" :key="product.id">
                            <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 border border-purple-100 group flex flex-col h-full">
                                <a :href="product.url" class="flex-grow flex flex-col">
                                    <!-- Image Container -->
                                    <div class="relative overflow-hidden w-full pt-[100%] bg-gray-100">
                                        <img x-show="product.image" :src="product.image" :alt="product.name" class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                                        <div x-show="!product.image" class="absolute inset-0 w-full h-full flex items-center justify-center text-gray-300 bg-gray-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        
                                        <!-- Overlay -->
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition duration-300"></div>
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="p-5 flex flex-col flex-grow bg-purple-50">
                                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1 group-hover:text-purple-600 transition-colors" x-text="product.name"></h3>
                                        
                                        <!-- Seller / Location -->
                                        <div class="flex items-center gap-1 mb-6 text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="text-sm" x-text="product.shop_name + ' - ' + product.shop_location"></span>
                                        </div>

                                        <div class="mt-auto pt-4 border-t border-purple-100">
                                            <span class="text-2xl font-bold text-purple-500" x-text="product.price_formatted"></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </template>
                    </div>

                    <!-- Empty State -->
                    <div x-show="products.length === 0" x-cloak
                    class="text-center py-20">
                        <div class="inline-block p-6 rounded-full bg-purple-100 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Produk tidak ditemukan</h2>
                        <p class="text-gray-500">Coba kata kunci lain atau lihat semua produk.</p>
                        <button @click="searchQuery = ''; performSearch()" class="inline-block mt-6 px-6 py-2 bg-purple-600 hover:bg-purple-700 rounded-full text-white font-semibold transition duration-300 shadow-md">
                            Lihat Semua Produk
                        </button>
                    </div>
                </div>
            </main>

            <footer class="bg-purple-100 border-t border-purple-200 py-10 mt-12">
                <div class="container mx-auto px-6 text-center">
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Permata Klepu Logo" class="h-12 w-auto object-contain">
                        <span class="text-lg font-bold text-gray-900">Permata Klepu</span>
                    </div>
                    <p class="text-gray-500 text-sm">
                        &copy; {{ date('Y') }} Permata Klepu Marketplace. All rights reserved.
                    </p>
                </div>
            </footer>
        </div>

        <script>
            function productSearch() {
                return {
                    searchQuery: '{{ request("search") }}',
                    products: @json($productsData),
                    loading: false,

                    performSearch() {
                        this.loading = true;

                        // If search is empty, reload all products
                        if (this.searchQuery === '') {
                            axios.get('{{ route("api.products.search") }}')
                                .then(response => {
                                    this.products = response.data.products;
                                    this.loading = false;
                                })
                                .catch(error => {
                                    console.error('Search error:', error);
                                    this.loading = false;
                                });
                            return;
                        }

                        // Perform search
                        axios.get('{{ route("api.products.search") }}', {
                            params: {
                                search: this.searchQuery
                            }
                        })
                        .then(response => {
                            this.products = response.data.products;
                            this.loading = false;
                        })
                        .catch(error => {
                            console.error('Search error:', error);
                            this.loading = false;
                        });
                    }
                }
            }
        </script>

        <style>
            [x-cloak] { display: none !important; }
        </style>
    </body>
</html>
