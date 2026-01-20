<x-admin-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Kelola Seller</h1>
                <p class="text-gray-600 mt-1">Lihat dan kelola semua seller di marketplace</p>
            </div>
        </div>

        <!-- Search & Per-Page Filter -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6">
            <form method="GET" action="{{ route('admin.sellers.index') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}"
                           placeholder="Cari nama, email, atau nama toko..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
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
                    Cari
                </button>
                @if($search)
                    <a href="{{ route('admin.sellers.index', ['per_page' => $perPage, 'sort' => $sort, 'direction' => $direction]) }}" 
                       class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Sellers Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition-colors" onclick="window.location='{{ route('admin.sellers.index', ['sort' => 'shop_name', 'direction' => $sort === 'shop_name' && $direction === 'asc' ? 'desc' : 'asc'] + request()->except(['sort', 'direction', 'page'])) }}'">
                                <div class="flex items-center gap-1">
                                    Seller
                                    @if($sort === 'shop_name')
                                        <svg class="w-4 h-4 {{ $direction === 'asc' ? 'rotate-180' : '' }} transition-transform text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition-colors" onclick="window.location='{{ route('admin.sellers.index', ['sort' => 'email', 'direction' => $sort === 'email' && $direction === 'asc' ? 'desc' : 'asc'] + request()->except(['sort', 'direction', 'page'])) }}'">
                                <div class="flex items-center gap-1">
                                    Email
                                    @if($sort === 'email')
                                        <svg class="w-4 h-4 {{ $direction === 'asc' ? 'rotate-180' : '' }} transition-transform text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4">WhatsApp</th>
                            <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition-colors" onclick="window.location='{{ route('admin.sellers.index', ['sort' => 'products_count', 'direction' => $sort === 'products_count' && $direction === 'asc' ? 'desc' : 'asc'] + request()->except(['sort', 'direction', 'page'])) }}'">
                                <div class="flex items-center gap-1">
                                    Produk
                                    @if($sort === 'products_count')
                                        <svg class="w-4 h-4 {{ $direction === 'asc' ? 'rotate-180' : '' }} transition-transform text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 cursor-pointer hover:bg-gray-100 transition-colors" onclick="window.location='{{ route('admin.sellers.index', ['sort' => 'is_active', 'direction' => $sort === 'is_active' && $direction === 'asc' ? 'desc' : 'asc'] + request()->except(['sort', 'direction', 'page'])) }}'">
                                <div class="flex items-center gap-1">
                                    Status
                                    @if($sort === 'is_active')
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
                        @forelse($sellers as $seller)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold flex-shrink-0">
                                            {{ strtoupper(substr($seller->shop_name ?? $seller->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-medium text-gray-900">{{ $seller->shop_name ?? '-' }}</p>
                                            <p class="text-sm text-gray-500">{{ $seller->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $seller->email }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $seller->shop_whatsapp ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $seller->products_count }} produk
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($seller->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.sellers.show', $seller) }}" 
                                           class="p-1 text-purple-600 hover:text-purple-800 hover:bg-purple-100 rounded-lg transition-colors" title="Lihat Detail">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        @if($seller->is_active)
                                            <form method="POST" 
                                                  action="{{ route('admin.sellers.destroy', $seller) }}"
                                                  class="flex"
                                                  x-data
                                                  @submit.prevent="confirmModal.title = 'Nonaktifkan Seller?'; confirmModal.message = 'Apakah Anda yakin ingin menonaktifkan seller {{ $seller->shop_name ?? $seller->name }}? Semua produknya akan ikut dinonaktifkan.'; confirmModal.action = $el; confirmModal.open = true;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-1 text-red-600 hover:text-red-800 hover:bg-red-100 rounded-lg transition-colors" title="Nonaktifkan">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
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
                                    @if($search)
                                        Tidak ada seller yang cocok dengan pencarian "{{ $search }}"
                                    @else
                                        Belum ada seller yang terdaftar
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Enhanced Pagination -->
            @if($sellers->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        Menampilkan {{ $sellers->firstItem() }} - {{ $sellers->lastItem() }} dari {{ $sellers->total() }} seller
                    </div>
                    <div class="flex items-center gap-2">
                        @if($sellers->onFirstPage())
                            <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                ← Previous
                            </span>
                        @else
                            <a href="{{ $sellers->previousPageUrl() . '&sort=' . $sort . '&direction=' . $direction . '&per_page=' . $perPage }}" 
                               class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                ← Previous
                            </a>
                        @endif

                        <div class="hidden sm:flex items-center gap-1">
                            @foreach($sellers->getUrlRange(max(1, $sellers->currentPage() - 2), min($sellers->lastPage(), $sellers->currentPage() + 2)) as $page => $url)
                                @if($page == $sellers->currentPage())
                                    <span class="px-4 py-2 bg-purple-600 text-white rounded-lg font-medium">{{ $page }}</span>
                                @else
                                    <a href="{{ $url . '&sort=' . $sort . '&direction=' . $direction . '&per_page=' . $perPage }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">{{ $page }}</a>
                                @endif
                            @endforeach
                        </div>

                        @if($sellers->hasMorePages())
                            <a href="{{ $sellers->nextPageUrl() . '&sort=' . $sort . '&direction=' . $direction . '&per_page=' . $perPage }}" 
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
