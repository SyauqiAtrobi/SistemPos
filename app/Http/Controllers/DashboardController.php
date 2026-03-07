<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Return aggregate stats and simple revenue series (last 7 days).
     */
    public function stats(Request $request)
    {
        $totalRevenue = Order::where('status', 'paid')->sum('total_amount');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::count();

        // Revenue by day for last 7 days
        $series = Order::selectRaw("DATE(created_at) as day, SUM(total_amount) as revenue")
            ->where('status', 'paid')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->mapWithKeys(function ($row) {
                return [
                    $row->day => (int) $row->revenue,
                ];
            })->toArray();

        // Build 7-day array
        $days = [];
        $values = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i)->format('Y-m-d');
            $days[] = now()->subDays($i)->format('d M');
            $values[] = isset($series[$d]) ? (int) $series[$d] : 0;
        }

        return response()->json([
            'total_revenue' => (int) $totalRevenue,
            'total_orders' => (int) $totalOrders,
            'total_products' => (int) $totalProducts,
            'total_users' => (int) $totalUsers,
            'chart' => [ 'labels' => $days, 'data' => $values ],
        ]);
    }
}
