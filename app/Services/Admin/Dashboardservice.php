<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Get comprehensive dashboard statistics
     */
    public function getDashboardStats(): array
    {
        return [
            'overview' => $this->getOverviewStats(),
            'revenue' => $this->getRevenueStats(),
            'orders' => $this->getOrderStats(),
            'recent_orders' => $this->getRecentOrders(),
            'top_customers' => $this->getTopCustomers(),
            'top_products' => $this->getTopSellingProducts(),
            'monthly_sales' => $this->getMonthlySales(),
            'order_status_chart' => $this->getOrderStatusChart(),
            'revenue_chart' => $this->getRevenueChart(),
        ];
    }

    /**
     * Get overview statistics
     */
    private function getOverviewStats(): array
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Total Revenue (all time)
        $totalRevenue = Payment::where('status', 'succeeded')->sum('amount');

        // Total Orders (all time)
        $totalOrders = Order::where('status', 'paid')->count();

        // Delivered Orders
        $deliveredOrders = Order::where('status', 'paid')
            ->where('delivery_status', 'delivered')
            ->count();

        // Cancelled Orders
        $cancelledOrders = Order::where('delivery_status', 'cancelled')->count();

        // This month revenue
        $thisMonthRevenue = Payment::where('status', 'succeeded')
            ->where('created_at', '>=', $currentMonth)
            ->sum('amount');

        // Last month revenue for comparison
        $lastMonthRevenue = Payment::where('status', 'succeeded')
            ->whereBetween('created_at', [$lastMonth, $currentMonth])
            ->sum('amount');

        // Calculate percentage change
        $revenueGrowth = $lastMonthRevenue > 0
            ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
            : 0;

        return [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'delivered_orders' => $deliveredOrders,
            'cancelled_orders' => $cancelledOrders,
            'this_month_revenue' => $thisMonthRevenue,
            'revenue_growth' => round($revenueGrowth, 1),
        ];
    }

    /**
     * Get revenue statistics
     */
    private function getRevenueStats(): array
    {
        $currentMonth = Carbon::now()->startOfMonth();

        // Monthly goal (you can make this configurable)
        $monthlyGoal = 25000;

        $thisMonthRevenue = Payment::where('status', 'succeeded')
            ->where('created_at', '>=', $currentMonth)
            ->sum('amount');

        $goalPercentage = ($thisMonthRevenue / $monthlyGoal) * 100;

        return [
            'monthly_goal' => $monthlyGoal,
            'current_revenue' => $thisMonthRevenue,
            'goal_percentage' => min(100, round($goalPercentage, 0)),
        ];
    }

    /**
     * Get order statistics for charts
     */
    private function getOrderStats(): array
    {
        $delivered = Order::where('delivery_status', 'delivered')->count();
        $processing = Order::whereIn('delivery_status', ['confirmed', 'processing', 'shipped'])->count();

        $total = $delivered + $processing;

        return [
            'delivered' => $delivered,
            'processing' => $processing,
            'delivered_percentage' => $total > 0 ? round(($delivered / $total) * 100, 1) : 0,
            'processing_percentage' => $total > 0 ? round(($processing / $total) * 100, 1) : 0,
        ];
    }

    /**
     * Get recent orders for table
     */
    private function getRecentOrders(int $limit = 10): array
    {
        return Order::where('status', 'paid')
            ->with(['user', 'payment', 'orderItems'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($order) {
                $totalQuantity = $order->orderItems->sum('quantity');
                $avgPrice = $totalQuantity > 0 ? $order->total_amount / $totalQuantity : 0;

                return [
                    'id' => $order->id,
                    'order_id' => 'ORD' . str_pad((string) $order->id, 6, '0', STR_PAD_LEFT),
                    'customer_name' => $order->user->name ?? 'Guest',
                    'customer_email' => $order->user->email ?? '',
                    'location' => $order->city?->name ?? 'N/A',
                    'order_date' => $order->created_at?->format('d M, Y') ?? '',
                    'payment_method' => $order->payment_method ? ucfirst($order->payment_method) : 'Unknown',
                    'quantity' => $totalQuantity,
                    'avg_price' => $avgPrice,
                    'total_amount' => $order->total_amount,
                    'delivery_status' => $order->delivery_status ? ucfirst($order->delivery_status) : 'Unknown',
                    'status_class' => $this->getStatusClass($order->delivery_status ?? ''),
                ];
            })
            ->toArray();
    }

    /**
     * Get top customers by total spent
     */
    private function getTopCustomers(int $limit = 5): array
    {
        return User::select('users.*')
            ->selectRaw('SUM(orders.total_amount) as total_spent')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->where('orders.status', 'paid')
            ->groupBy('users.id')
            ->orderBy('total_spent', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($user) {
                return [
                    'name' => $user->name,
                    'email' => $user->email,
                    'total_spent' => $user->total_spent,
                    'avatar' => $user->avatar ?? asset('admin/assets/images/avatar-2.png'),
                ];
            })
            ->toArray();
    }

    /**
     * Get top selling products
     */
    private function getTopSellingProducts(int $limit = 6): array
    {
        return Product::select('products.*')
            ->selectRaw('SUM(order_items.quantity) as total_sold')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'paid')
            ->groupBy('products.id')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name_en ?? $product->name_ar,
                    'total_sold' => $product->total_sold,
                    'price' => $product->price,
                    'image' => $product->cover_image_path ?? asset('admin/assets/images/img-02.png'),
                    'rating' => 4.5, // You can implement actual ratings
                ];
            })
            ->toArray();
    }

    /**
     * Get monthly sales data for chart
     */
    private function getMonthlySales(): array
    {
        $months = [];
        $sales = [];
        $profits = [];

        // Get last 6 months data
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthSales = Payment::where('status', 'succeeded')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('amount');

            // Assume 30% profit margin
            $monthProfit = $monthSales * 0.3;

            $months[] = $month->format('M');
            $sales[] = (float) $monthSales;
            $profits[] = (float) $monthProfit;
        }

        return [
            'months' => $months,
            'sales' => $sales,
            'profits' => $profits,
        ];
    }

    /**
     * Get order status distribution for pie chart
     */
    private function getOrderStatusChart(): array
    {
        $statusCounts = Order::where('status', 'paid')
            ->select('delivery_status')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('delivery_status')
            ->get()
            ->pluck('count', 'delivery_status')
            ->toArray();

        return [
            'labels' => array_keys($statusCounts),
            'data' => array_values($statusCounts),
        ];
    }

    /**
     * Get revenue chart data (last 12 months)
     */
    private function getRevenueChart(): array
    {
        $months = [];
        $revenue = [];

        // Get last 12 months data
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthRevenue = Payment::where('status', 'succeeded')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('amount');

            $months[] = $month->format('M Y');
            $revenue[] = (float) $monthRevenue;
        }

        return [
            'months' => $months,
            'revenue' => $revenue,
        ];
    }

    /**
     * Get CSS class for order status
     */
    private function getStatusClass(string $status): string
    {
        return match ($status) {
            'delivered' => 'bg-green-100 border-green-200 text-green-500 dark:bg-green-500/20 dark:border-green-500/20',
            'shipped' => 'bg-purple-100 border-purple-200 text-purple-500 dark:bg-purple-500/20 dark:border-purple-500/20',
            'processing' => 'bg-yellow-100 border-yellow-200 text-yellow-500 dark:bg-yellow-500/20 dark:border-yellow-500/20',
            'confirmed' => 'bg-sky-100 border-sky-200 text-sky-500 dark:bg-sky-500/20 dark:border-sky-500/20',
            'cancelled' => 'bg-red-100 border-red-200 text-red-500 dark:bg-red-500/20 dark:border-red-500/20',
            default => 'bg-gray-100 border-gray-200 text-gray-500 dark:bg-gray-500/20 dark:border-gray-500/20',
        };
    }
}
