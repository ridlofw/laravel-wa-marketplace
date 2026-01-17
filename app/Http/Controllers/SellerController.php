<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateSellerSettingsRequest;
use App\Services\DashboardService;

class SellerController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function dashboard()
    {
        $user = auth()->user();
        
        // Get seller statistics
        $stats = $this->dashboardService->getSellerStats($user);
        
        // Get visitor chart data for last 7 days
        $chartData = $this->dashboardService->getVisitorChartData($user);
        
        // Get recent orders
        $recentOrders = $this->dashboardService->getRecentOrders($user);

        return view('seller.dashboard', [
            'productsCount' => $stats['products_count'],
            'totalViews' => $stats['total_views'],
            'estimatedRevenue' => $stats['estimated_revenue'],
            'chartLabels' => $chartData['labels'],
            'chartData' => $chartData['data'],
            'recentOrders' => $recentOrders,
        ]);
    }

    public function settings()
    {
        return view('seller.settings', ['user' => auth()->user()]);
    }

    public function updateSettings(UpdateSellerSettingsRequest $request)
    {
        $user = auth()->user();
        $data = $request->only(['shop_name', 'shop_address', 'shop_whatsapp']);

        if ($request->hasFile('shop_logo')) {
            $path = $request->file('shop_logo')->store('logos', 'public');
            $data['shop_logo'] = $path;
        }

        $user->update($data);

        return redirect()->route('seller.settings')
            ->with('success', 'Settings updated successfully.');
    }
}
