<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\ProductView;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard.
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'total_sellers' => User::where('role', 'seller')->count(),
            'active_sellers' => User::where('role', 'seller')->where('is_active', true)->count(),
            'total_products' => Product::count(),
            'active_products' => Product::whereNull('deleted_at')->count(),
            'total_orders' => Order::count(),
            'total_views' => ProductView::count(),
        ];

        // Get chart data for last 7 days
        $chartData = $this->getChartData();

        // Get recent activities
        $recentProducts = Product::with('seller')
            ->latest()
            ->take(5)
            ->get();

        $recentSellers = User::where('role', 'seller')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'chartData', 'recentProducts', 'recentSellers'));
    }

    /**
     * Get chart data for last 7 days.
     */
    private function getChartData(): array
    {
        $labels = [];
        $viewsData = [];
        $ordersData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d M');

            $viewsData[] = ProductView::whereDate('created_at', $date->toDateString())->count();
            $ordersData[] = Order::whereDate('created_at', $date->toDateString())->count();
        }

        return [
            'labels' => $labels,
            'views' => $viewsData,
            'orders' => $ordersData,
        ];
    }
}
