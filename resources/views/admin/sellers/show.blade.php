<x-admin-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.sellers.index') }}" class="text-purple-600 hover:text-purple-800 text-sm flex items-center mb-4">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Daftar Seller
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $user->shop_name ?? $user->name }}</h1>
                    <p class="text-gray-600 mt-1">Detail seller dan produk</p>
                </div>
                @if($user->is_active)
                    <form method="POST" 
                          action="{{ route('admin.sellers.destroy', $user) }}" 
                          x-data
                          @submit.prevent="confirmModal.title = 'Nonaktifkan Seller?'; confirmModal.message = 'Apakah Anda yakin ingin menonaktifkan seller ini?'; confirmModal.action = $el; confirmModal.open = true;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                            Nonaktifkan Seller
                        </button>
                    </form>
                @else
                    <span class="px-4 py-2 bg-red-100 text-red-800 rounded-lg font-medium">
                        Seller Nonaktif
                    </span>
                @endif
            </div>
        </div>

        <!-- Seller Info Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nama Pemilik</p>
                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Email</p>
                    <p class="font-medium text-gray-900">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">WhatsApp</p>
                    <p class="font-medium text-gray-900">{{ $user->shop_whatsapp ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Bergabung</p>
                    <p class="font-medium text-gray-900">{{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>
            @if($user->shop_address)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Alamat Toko</p>
                    <p class="font-medium text-gray-900">{{ $user->shop_address }}</p>
                </div>
            @endif
        </div>

        <!-- Products -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Produk ({{ $products->total() }})</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Produk</th>
                            <th class="px-6 py-4">Harga</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50 {{ $product->trashed() ? 'bg-red-50' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $product->formatted_price }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $product->category ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    @if($product->trashed())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Dihapus
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.products.show', $product) }}" 
                                           class="p-1 text-purple-600 hover:text-purple-800 hover:bg-purple-100 rounded-lg transition-colors" title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        @if(!$product->trashed())
                                            <form method="POST" 
                                                  action="{{ route('admin.products.destroy', $product) }}"
                                                  class="flex"
                                                  x-data
                                                  @submit.prevent="confirmModal.title = 'Hapus Produk?'; confirmModal.message = 'Apakah Anda yakin ingin menghapus produk ini?'; confirmModal.action = $el; confirmModal.open = true;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-1 text-red-600 hover:text-red-800 hover:bg-red-100 rounded-lg transition-colors" title="Hapus">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    Seller ini belum memiliki produk
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        Scanning {{ $products->firstItem() }} - {{ $products->lastItem() }} of {{ $products->total() }} results
                    </div>
                    <div>
                        {{ $products->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
