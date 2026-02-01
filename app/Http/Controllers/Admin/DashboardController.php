<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {
    }

    /**
     * Show the admin dashboard with comprehensive statistics
     */
    public function index()
    {
        try {
            $dashboardData = $this->dashboardService->getDashboardStats();

            return view('admin.dashboard', compact('dashboardData'));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Dashboard data loading failed: ' . $e->getMessage());

            // Return view with empty data structure
            $dashboardData = [
                'overview' => [
                    'total_revenue' => 0,
                    'total_orders' => 0,
                    'delivered_orders' => 0,
                    'cancelled_orders' => 0,
                    'this_month_revenue' => 0,
                    'revenue_growth' => 0,
                ],
                'revenue' => [
                    'monthly_goal' => 25000,
                    'current_revenue' => 0,
                    'goal_percentage' => 0,
                ],
                'orders' => [
                    'delivered' => 0,
                    'processing' => 0,
                    'delivered_percentage' => 0,
                    'processing_percentage' => 0,
                ],
                'recent_orders' => [],
                'top_customers' => [],
                'top_products' => [],
                'monthly_sales' => [
                    'months' => [],
                    'sales' => [],
                    'profits' => [],
                ],
                'order_status_chart' => [
                    'labels' => [],
                    'data' => [],
                ],
                'revenue_chart' => [
                    'months' => [],
                    'revenue' => [],
                ],
            ];

            return view('admin.dashboard', compact('dashboardData'));
        }
    }

    /**
     * Get dashboard data as JSON for AJAX requests
     */
    public function getData(Request $request)
    {
        try {
            $dashboardData = $this->dashboardService->getDashboardStats();

            return response()->json([
                'success' => true,
                'data' => $dashboardData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}