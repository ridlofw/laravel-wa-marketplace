<x-seller-layout>
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 font-serif">Dashboard</h1>
            <p class="text-purple-600 mt-1">Ringkasan performa toko Anda hari ini</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Total Products -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-purple-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-50 rounded-xl">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Total Produk</h3>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $productsCount }}</p>
            </div>

            <!-- Total Views -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-purple-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-50 rounded-xl">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Total Dilihat</h3>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalViews }}</p>
            </div>

            <!-- Estimated Revenue -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-purple-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-50 rounded-xl">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Estimasi Omzet</h3>
                <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($estimatedRevenue, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-purple-100 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Statistik Pengunjung (7 Hari Terakhir)</h3>
            </div>
            <div class="relative h-80 w-full">
                <canvas id="visitorChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('visitorChart').getContext('2d');
        const visitorChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Pengunjung',
                    data: @json($chartData),
                    borderColor: '#9333ea', // purple-600
                    backgroundColor: 'rgba(147, 51, 234, 0.1)', // purple-600 with opacity
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#9333ea',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleColor: '#f3f4f6',
                        bodyColor: '#f3f4f6',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f4f6',
                            drawBorder: false
                        },
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 11
                            },
                            color: '#9ca3af'
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#9ca3af'
                        }
                    }
                }
            }
        });
    </script>
</x-seller-layout>
