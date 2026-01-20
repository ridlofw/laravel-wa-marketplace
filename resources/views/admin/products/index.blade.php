<x-admin-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Kelola Produk</h1>
                <p class="text-gray-600 mt-1">Lihat dan kelola semua produk di marketplace</p>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6">
            <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col sm:flex-row gap-4" x-data="{
                search: '{{ $sellerId }}',
                query: '',
                sellers: @js($sellers),
                filteredSellers: [],
                showDropdown: false,
                selectedSeller: @js($sellers->firstWhere('id', $sellerId)),
                init() {
                    if (this.selectedSeller) {
                        this.query = this.selectedSeller.shop_name || this.selectedSeller.name;
                    }
                },
                filterSellers() {
                    if (this.query.length === 0) {
                        this.filteredSellers = this.sellers.slice(0, 10);
                    } else {
                        this.filteredSellers = this.sellers.filter(s => 
                            (s.shop_name && s.shop_name.toLowerCase().includes(this.query.toLowerCase())) ||
                            (s.name && s.name.toLowerCase().includes(this.query.toLowerCase()))
                        ).slice(0, 10);
                    }
                    this.showDropdown = true;
                },
                selectSeller(seller) {
                    this.search = seller.id;
                    this.query = seller.shop_name || seller.name;
                    this.showDropdown = false;
                },
                clearSeller() {
                    this.search = '';
                    this.query = '';
                }
            }">
                <div class="flex-1">
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}"
                           placeholder="Cari nama produk..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>
                
                <!-- Searchable Seller Input -->
                <div class="sm:w-56 relative">
                    <input type="hidden" name="seller" :value="search">
                    <div class="relative">
                        <input type="text" 
                               x-model="query"
                               @focus="filterSellers()"
                               @input="filterSellers()"
                               @click.away="showDropdown = false"
                               placeholder="Cari seller..."
                               class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <button type="button" 
                                x-show="search" 
                                @click="clearSeller()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Dropdown Suggestions -->
                    <div x-show="showDropdown && filteredSellers.length > 0"
                         x-transition
                         class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        <template x-for="seller in filteredSellers" :key="seller.id">
                            <button type="button"
                                    @click="selectSeller(seller)"
                                    class="w-full px-4 py-2 text-left hover:bg-purple-50 flex items-center gap-2">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 text-xs font-bold flex-shrink-0"
                                     x-text="(seller.shop_name || seller.name).charAt(0).toUpperCase()"></div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate" x-text="seller.shop_name || '-'"></p>
                                    <p class="text-xs text-gray-500 truncate" x-text="seller.name"></p>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>

                <div class="sm:w-32">
                    <select name="per_page" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            onchange="this.form.submit()">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>

                <!-- Preserve Sort Params -->
                <input type="hidden" name="sort" value="{{ $sort }}">
                <input type="hidden" name="direction" value="{{ $direction }}">

                <button type="submit" 
                        class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    Filter
                </button>
                @if($search || $sellerId)
                    <a href="{{ route('admin.products.index', ['per_page' => $perPage, 'sort' => $sort, 'direction' => $direction]) }}" 
                       class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition-colors" onclick="window.location='{{ route('admin.products.index', ['sort' => 'name', 'direction' => $sort === 'name' && $direction === 'asc' ? 'desc' : 'asc'] + request()->except(['sort', 'direction', 'page'])) }}'">
                                <div class="flex items-center gap-1">
                                    Produk
                                    @if($sort === 'name')
                                        <svg class="w-4 h-4 {{ $direction === 'asc' ? 'rotate-180' : '' }} transition-transform text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4">Seller</th>
                            <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition-colors" onclick="window.location='{{ route('admin.products.index', ['sort' => 'price', 'direction' => $sort === 'price' && $direction === 'asc' ? 'desc' : 'asc'] + request()->except(['sort', 'direction', 'page'])) }}'">
                                <div class="flex items-center gap-1">
                                    Harga
                                    @if($sort === 'price')
                                        <svg class="w-4 h-4 {{ $direction === 'asc' ? 'rotate-180' : '' }} transition-transform text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition-colors" onclick="window.location='{{ route('admin.products.index', ['sort' => 'views_count', 'direction' => $sort === 'views_count' && $direction === 'asc' ? 'desc' : 'asc'] + request()->except(['sort', 'direction', 'page'])) }}'">
                                <div class="flex items-center gap-1">
                                    Views
                                    @if($sort === 'views_count')
                                        <svg class="w-4 h-4 {{ $direction === 'asc' ? 'rotate-180' : '' }} transition-transform text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition-colors" onclick="window.location='{{ route('admin.products.index', ['sort' => 'status', 'direction' => $sort === 'status' && $direction === 'asc' ? 'desc' : 'asc'] + request()->except(['sort', 'direction', 'page'])) }}'">
                                <div class="flex items-center gap-1">
                                    Status
                                    @if($sort === 'status')
                                        <svg class="w-4 h-4 {{ $direction === 'asc' ? 'rotate-180' : '' }} transition-transform text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                                    @endif
                                </div>
                            </th>
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
                                            <p class="text-xs text-gray-500">{{ $product->category ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.sellers.show', $product->seller) }}" class="text-purple-600 hover:text-purple-800 text-sm">
                                        {{ $product->seller->shop_name ?? $product->seller->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $product->formatted_price }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ number_format($product->views_count) }}</td>
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
                                                  @submit.prevent="confirmModal.title = 'Hapus Produk?'; confirmModal.message = 'Apakah Anda yakin ingin menghapus produk {{ addslashes($product->name) }}?'; confirmModal.action = $el; confirmModal.open = true;">
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
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    @if($search || $sellerId)
                                        Tidak ada produk yang cocok dengan filter
                                    @else
                                        Belum ada produk
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Enhanced Pagination -->
            @if($products->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk
                    </div>
                    <div class="flex items-center gap-2">
                        @if($products->onFirstPage())
                            <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                ← Previous
                            </span>
                        @else
                            <a href="{{ $products->previousPageUrl() . '&sort=' . $sort . '&direction=' . $direction . '&per_page=' . $perPage }}" 
                               class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                ← Previous
                            </a>
                        @endif

                        <div class="hidden sm:flex items-center gap-1">
                            @foreach($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                                @if($page == $products->currentPage())
                                    <span class="px-4 py-2 bg-purple-600 text-white rounded-lg font-medium">{{ $page }}</span>
                                @else
                                    <a href="{{ $url . '&sort=' . $sort . '&direction=' . $direction . '&per_page=' . $perPage }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">{{ $page }}</a>
                                @endif
                            @endforeach
                        </div>

                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() . '&sort=' . $sort . '&direction=' . $direction . '&per_page=' . $perPage }}" 
                               class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                Next →
                            </a>
                        @else
                            <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                Next →
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
