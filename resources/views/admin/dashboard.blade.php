<x-admin-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600 mt-1">Selamat datang, {{ Auth::guard('admin')->user()->name }}</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Sellers -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-50 rounded-xl">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">
                        {{ $stats['active_sellers'] }} aktif
                    </span>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Total Seller</h3>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_sellers'] }}</p>
            </div>

            <!-- Total Products -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-50 rounded-xl">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Total Produk</h3>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_products'] }}</p>
            </div>

            <!-- Total Orders -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-green-50 rounded-xl">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Total Order</h3>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_orders'] }}</p>
            </div>

            <!-- Total Views -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-amber-50 rounded-xl">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Total Views</h3>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_views']) }}</p>
            </div>
        </div>

        <!-- Chart & Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Chart -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Aktivitas 7 Hari Terakhir</h3>
                <div class="relative h-64">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>

            <!-- Recent Products -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Produk Terbaru</h3>
                    <a href="{{ route('admin.products.index') }}" class="text-sm text-purple-600 hover:text-purple-800">Lihat semua →</a>
                </div>
                <div class="space-y-3">
                    @forelse($recentProducts as $product)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
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
                            <div class="ml-3 flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500">{{ $product->seller->shop_name ?? $product->seller->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-900">{{ $product->formatted_price }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm text-center py-4">Belum ada produk</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Sellers -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Seller Terbaru</h3>
                <a href="{{ route('admin.sellers.index') }}" class="text-sm text-purple-600 hover:text-purple-800">Lihat semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <th class="pb-3">Nama Toko</th>
                            <th class="pb-3">Email</th>
                            <th class="pb-3">WhatsApp</th>
                            <th class="pb-3">Bergabung</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentSellers as $seller)
                            <tr>
                                <td class="py-3">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold text-sm">
                                            {{ strtoupper(substr($seller->shop_name ?? $seller->name, 0, 1)) }}
                                        </div>
                                        <span class="ml-3 font-medium text-gray-900">{{ $seller->shop_name ?? $seller->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 text-sm text-gray-600">{{ $seller->email }}</td>
                                <td class="py-3 text-sm text-gray-600">{{ $seller->shop_whatsapp ?? '-' }}</td>
                                <td class="py-3 text-sm text-gray-500">{{ $seller->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">Belum ada seller</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('activityChart').getContext('2d');
        const activityChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartData['labels']),
                datasets: [
                    {
                        label: 'Views',
                        data: @json($chartData['views']),
                        borderColor: '#9333ea',
                        backgroundColor: 'rgba(147, 51, 234, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                    },
                    {
                        label: 'Orders',
                        data: @json($chartData['orders']),
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22, 163, 74, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6',
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                        }
                    }
                }
            }
        });
    </script>
</x-admin-layout>
