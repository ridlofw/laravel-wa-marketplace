<x-seller-layout>
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 font-serif">Produk Saya</h1>
                <p class="text-purple-600 mt-1">Kelola katalog produk Anda</p>
            </div>
            <a href="{{ route('seller.products.create') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all shadow-sm hover:shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Produk
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative mb-8 flex items-center" role="alert">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="block sm:inline font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col">
                        <a href="{{ route('seller.products.edit', $product) }}" class="block flex-1">
                            <div class="relative aspect-w-4 aspect-h-3 bg-gray-100 overflow-hidden">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-50 text-gray-300">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-3 right-3">
                                    <span class="px-3 py-1.5 bg-white/95 backdrop-blur-sm rounded-lg text-sm font-bold text-purple-600 shadow-sm border border-purple-50">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="p-5">
                                <h3 class="font-bold text-lg text-gray-900 mb-2 line-clamp-1 group-hover:text-purple-600 transition-colors">{{ $product->name }}</h3>
                                <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $product->description }}</p>
                            </div>
                        </a>
                        
                        <div class="px-5 pb-5 mt-auto">
                            <div class="flex justify-between items-center pt-4 border-t border-gray-50 gap-3">
                                <a href="{{ route('seller.products.edit', $product) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-purple-50 text-purple-700 text-sm font-medium rounded-lg hover:bg-purple-100 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <form id="delete-form-{{ $product->id }}" action="{{ route('seller.products.destroy', $product) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(event, 'delete-form-{{ $product->id }}')" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-50 text-red-700 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="bg-white rounded-3xl p-6 sm:p-12 text-center border border-gray-100 shadow-sm">
                <div class="w-20 h-20 bg-purple-50 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce-slow">
                    <svg class="w-10 h-10 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada produk</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">Mulai perjalanan jualan Anda dengan menambahkan produk pertama. Produk Anda akan muncul di sini.</p>
                <a href="{{ route('seller.products.create') }}" class="inline-flex items-center px-8 py-4 bg-purple-600 border border-transparent rounded-xl font-bold text-white hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all shadow-lg hover:shadow-purple-200 transform hover:-translate-y-1">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Produk Sekarang
                </a>
            </div>
        @endif
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(event, formId) {
            event.preventDefault();
            
            Swal.fire({
                title: 'Hapus Produk?',
                text: "Produk yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-6 py-2.5 rounded-xl font-bold',
                    cancelButton: 'px-6 py-2.5 rounded-xl font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
</x-seller-layout>
