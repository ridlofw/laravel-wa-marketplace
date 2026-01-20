<x-admin-layout>
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.products.index') }}" class="text-purple-600 hover:text-purple-800 text-sm flex items-center mb-4">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Daftar Produk
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                    <p class="text-gray-600 mt-1">Detail produk</p>
                </div>
                @if(!$product->trashed())
                    <form method="POST" 
                          action="{{ route('admin.products.destroy', $product) }}" 
                          x-data
                          @submit.prevent="confirmModal.title = 'Hapus Produk?'; confirmModal.message = 'Apakah Anda yakin ingin menghapus produk ini?'; confirmModal.action = $el; confirmModal.open = true;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus Produk
                        </button>
                    </form>
                @else
                    <span class="px-4 py-2 bg-red-100 text-red-800 rounded-lg font-medium">
                        Produk Dihapus
                    </span>
                @endif
            </div>
        </div>

        <!-- Product Gallery -->
        <div class="mb-6" x-data="{ 
            lightboxOpen: false, 
            activeImage: '{{ $product->images->count() > 0 ? asset('storage/' . $product->images->first()->image_path) : ($product->image ? asset('storage/' . $product->image) : '') }}',
            activeSlide: 0, 
            slides: {{ $product->images->count() }} 
        }">
            <!-- Left: Image Section -->
            <div class="flex items-start justify-center">
                @if($product->images->count() > 0)
                    <div class="relative group w-full">
                        <div class="overflow-hidden rounded-3xl shadow-lg relative w-full pt-[56.25%] bg-white cursor-pointer" @click="lightboxOpen = true">
                            @foreach($product->images as $index => $image)
                                <div x-show="activeSlide === {{ $index }}" class="absolute inset-0 transition-opacity duration-500" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-contain bg-gray-50" @click="activeImage = '{{ asset('storage/' . $image->image_path) }}'">
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
                    <div class="w-full relative pt-[56.25%] bg-white rounded-3xl shadow-lg overflow-hidden cursor-pointer" @click="lightboxOpen = true; activeImage = '{{ asset('storage/' . $product->image) }}'">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-contain bg-gray-50">
                    </div>
                @else
                    <div class="w-full relative pt-[56.25%] bg-gray-200 rounded-3xl shadow-lg">
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

                <div class="relative max-w-7xl max-h-full w-full h-full flex items-center justify-center" @click.outside="lightboxOpen = false">
                    <img :src="activeImage" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Produk</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Harga</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $product->formatted_price }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Kategori</p>
                    <p class="font-medium text-gray-900">{{ $product->category ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Views</p>
                    <p class="font-medium text-gray-900">{{ number_format($product->views_count) }} views</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Dibuat</p>
                    <p class="font-medium text-gray-900">{{ $product->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
            @if($product->description)
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <p class="text-sm text-gray-500 mb-2">Deskripsi</p>
                    <p class="text-gray-900 whitespace-pre-line">{{ $product->description }}</p>
                </div>
            @endif
        </div>

        <!-- Seller Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Seller</h2>
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold flex-shrink-0">
                    {{ strtoupper(substr($product->seller->shop_name ?? $product->seller->name, 0, 1)) }}
                </div>
                <div class="ml-4 flex-1">
                    <p class="font-medium text-gray-900">{{ $product->seller->shop_name ?? $product->seller->name }}</p>
                    <p class="text-sm text-gray-500">{{ $product->seller->email }}</p>
                </div>
                <a href="{{ route('admin.sellers.show', $product->seller) }}" 
                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm">
                    Lihat Seller
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>
