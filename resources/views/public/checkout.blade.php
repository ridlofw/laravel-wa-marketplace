<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Checkout - {{ $product->name }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-[#F8F7FF] min-h-screen text-gray-800">
        <div class="relative min-h-screen flex flex-col">
            <!-- Navbar -->
            <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-20">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <img src="{{ asset('images/logo.png') }}" alt="Permata Klepu Logo" class="h-16 w-auto object-contain group-hover:scale-105 transition-transform duration-300">
                        <div>
                            <h1 class="font-bold text-xl text-purple-600 leading-tight">Permata Klepu</h1>
                            <p class="text-xs text-gray-500">Marketplace</p>
                        </div>
                    </a>
                    
                    <div class="hidden sm:block text-sm text-gray-500">
                        Produk dari <span class="font-bold text-purple-600">{{ $product->seller->shop_name ?? 'Seller' }}</span>
                    </div>
                    </div>
                </div>
            </nav>

            <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="max-w-6xl mx-auto">
                    <!-- Back Link -->
                    <div class="mb-8">
                        <a href="{{ route('public.product.show', $product) }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-purple-600 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke Detail Produk
                        </a>
                    </div>

                    <!-- Header Section -->
                    <div class="text-center mb-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-500 rounded-full mb-6 shadow-xl shadow-purple-200 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">Checkout</h2>
                        <p class="text-gray-500">Lengkapi data Anda untuk melanjutkan pemesanan</p>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                        <!-- Left Column: Order Summary -->
                        <div class="order-1">
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8 sticky top-28">
                                <h3 class="text-lg font-bold mb-6 text-gray-900">Ringkasan Pesanan</h3>
                                
                                <div class="flex items-start mb-6">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-24 h-24 object-cover rounded-xl shadow-sm mr-5">
                                    @else
                                        <div class="w-24 h-24 bg-gray-100 rounded-xl mr-5 flex items-center justify-center text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="font-bold text-gray-900 mb-1 line-clamp-2 text-lg">{{ $product->name }}</h4>
                                        <p class="text-gray-500 text-sm mb-1">{{ $product->seller->shop_name ?? 'Seller' }}</p>
                                    </div>
                                </div>

                                <div class="space-y-4 text-sm">
                                    <div class="flex justify-between text-gray-600">
                                        <span>Harga Satuan</span>
                                        <span class="font-medium">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-gray-600">
                                        <span>Jumlah</span>
                                        <span class="font-medium" id="summary-quantity">1 pcs</span>
                                    </div>
                                    <div class="flex justify-between items-center font-bold text-purple-600 text-2xl pt-6 border-t border-gray-100 mt-6">
                                        <span class="text-lg text-gray-900">Total</span>
                                        <span id="total-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                
                                <div class="mt-8 bg-purple-50 rounded-xl p-5 text-sm text-purple-700 leading-relaxed flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p>Setelah klik tombol beli, Anda akan diarahkan ke WhatsApp penjual dengan pesan yang sudah terformat otomatis.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Form -->
                        <div class="order-2">
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                                <h3 class="text-lg font-bold mb-6 text-gray-900">Informasi Pembeli</h3>
                                
                                <form action="{{ route('public.checkout.process', $product) }}" method="POST">
                                    @csrf

                                    <div class="mb-6">
                                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" id="name" class="w-full px-4 py-3.5 rounded-xl bg-white border border-gray-200 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200" placeholder="Masukkan nama lengkap Anda" required>
                                        @error('name') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="mb-6">
                                        <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                                        <textarea name="address" id="address" class="w-full px-4 py-3.5 rounded-xl bg-white border border-gray-200 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200" rows="3" placeholder="Masukkan alamat lengkap Anda" required></textarea>
                                        @error('address') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="mb-6">
                                        <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Jumlah (pcs) <span class="text-red-500">*</span></label>
                                        <input type="number" name="quantity" id="quantity" value="1" min="1" class="w-full px-4 py-3.5 rounded-xl bg-white border border-gray-200 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200" required oninput="updateTotal(this.value)">
                                        @error('quantity') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="mb-8">
                                        <label for="note" class="block text-gray-700 text-sm font-bold mb-2">Catatan Tambahan (Opsional)</label>
                                        <textarea name="note" id="note" class="w-full px-4 py-3.5 rounded-xl bg-white border border-gray-200 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200" rows="2" placeholder="Tambahkan catatan jika diperlukan"></textarea>
                                    </div>

                                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-4 px-6 rounded-xl transition duration-300 shadow-lg shadow-purple-200 transform hover:-translate-y-0.5 flex items-center justify-center text-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 transform rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                        Beli Sekarang via WhatsApp
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="bg-white border-t border-gray-200 py-8 mt-12">
                <div class="container mx-auto px-6 text-center text-gray-500 text-sm">
                    &copy; {{ date('Y') }} Permata Klepu Marketplace. All rights reserved.
                </div>
            </footer>
        </div>

        <script>
            function updateTotal(quantity) {
                const price = {{ $product->price }};
                const total = price * quantity;
                document.getElementById('total-price').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                document.getElementById('summary-quantity').innerText = quantity + ' pcs';
            }
        </script>
    </body>
</html>
