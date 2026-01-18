<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\ProductView;
use Illuminate\Support\Collection;

class DashboardService
{
    /**
     * Get seller statistics for dashboard.
     */
    public function getSellerStats(User $seller): array
    {
        $productsCount = $seller->products()->count();
        
        // Get all product IDs (including soft deleted for accurate stats)
        $productIds = $seller->products()->withTrashed()->pluck('id');
        
        // Get orders for this seller
        $orders = Order::where('seller_id', $seller->id)
            ->where('status', 'clicked')
            ->get();
        
        $totalClicks = $orders->count();
        $estimatedRevenue = $orders->sum('total_price');
        
        // Total Views
        $totalViews = ProductView::whereIn('product_id', $productIds)->count();

        return [
            'products_count' => $productsCount,
            'total_views' => $totalViews,
            'total_clicks' => $totalClicks,
            'estimated_revenue' => $estimatedRevenue,
        ];
    }

    /**
     * Get visitor chart data for the last 7 days.
     */
    public function getVisitorChartData(User $seller): array
    {
        $productIds = $seller->products()->withTrashed()->pluck('id');
        
        $visitorStats = ProductView::whereIn('product_id', $productIds)
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        // Fill missing days with 0
        $chartLabels = [];
        $chartData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('D, d M');
            $chartData[] = $visitorStats[$date] ?? 0;
        }

        return [
            'labels' => $chartLabels,
            'data' => $chartData,
        ];
    }

    /**
     * Get recent orders for seller.
     */
    public function getRecentOrders(User $seller, int $limit = 5): Collection
    {
        return Order::where('seller_id', $seller->id)
            ->where('status', 'clicked')
            ->with(['product', 'buyer'])
            ->latest()
            ->take($limit)
            ->get();
    }
}
