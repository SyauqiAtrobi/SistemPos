<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Events\OrderStatusChanged;
use App\Events\DashboardUpdated;
use App\Models\Product;
use App\Models\User;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman Manajemen Pesanan untuk Admin
     */
    public function manage(Request $request)
    {
        $perPage = (int) $request->query('perPage', 10);
        if (!in_array($perPage, [10,25,50,100])) $perPage = 10;

        // Ambil semua pesanan, muat relasi pelanggan dan produk di dalamnya.
        // Diurutkan dari yang terbaru (latest).
        $orders = Order::with(['user', 'items.product'])->latest()->paginate($perPage)->withQueryString();
        
        return view('ordermanagement', compact('orders'));
    }

    /**
     * Menampilkan riwayat pesanan untuk user yang sedang login.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Mapping nama tab ke status database.
        $map = [
            'semua' => null,
            'belum_bayar' => 'pending',
            'dikemas' => 'processing',
            'dikirim' => 'shipped',
            'selesai' => 'paid',
            'pengembalian' => 'returned',
            'dibatalkan' => 'cancelled',
        ];

        $tab = $request->query('tab', 'semua');
        $status = $map[$tab] ?? null;

        $query = Order::with(['items', 'payment'])->where('user_id', $user->id)->latest();
        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->get();

        return view('orders', compact('orders', 'tab'));
    }

    /**
     * Membatalkan pesanan secara manual (opsional untuk Admin)
     */
    public function cancelManual(Order $order)
    {
        // Pastikan hanya pesanan pending yang bisa dibatalkan
        if ($order->status === 'pending') {
            $order->update(['status' => 'cancelled']);
            
            // Opsional: Anda bisa menambahkan logika menembak API Pakasir
            // untuk membatalkan QRIS di sisi Pakasir juga menggunakan 
            // endpoint /api/transactioncancel (berdasarkan dokumentasi)

            // Broadcast status change and dashboard update
            event(new OrderStatusChanged($order->order_number, $order->status, $order->fulfillment_status ?? null));

            try {
                $totalRevenue = Order::where('status', 'paid')->sum('total_amount');
                $totalOrders = Order::count();
                $totalProducts = Product::count();
                $totalUsers = User::count();
                $stats = [
                    'total_revenue' => (int) $totalRevenue,
                    'total_orders' => (int) $totalOrders,
                    'total_products' => (int) $totalProducts,
                    'total_users' => (int) $totalUsers,
                    'chart' => ['labels' => [], 'data' => []],
                ];
                event(new DashboardUpdated($stats));
            } catch (\Throwable $e) {}

            return back()->with('success', "Pesanan {$order->order_number} berhasil dibatalkan.");
        }

        return back()->with('error', 'Pesanan yang sudah dibayar tidak dapat dibatalkan.');
    }

    /**
     * Update fulfillment status from admin management UI (AJAX)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'fulfillment_status' => 'required|string|in:dikemas,dikirim,selesai,pengembalian,dibatalkan',
        ]);

        $order->fulfillment_status = $data['fulfillment_status'];
        $order->save();

        // Broadcast order status changed and update dashboard
        try {
            event(new OrderStatusChanged($order->order_number, $order->status, $order->fulfillment_status ?? null));

            $totalRevenue = Order::where('status', 'paid')->sum('total_amount');
            $totalOrders = Order::count();
            $totalProducts = Product::count();
            $totalUsers = User::count();
            $stats = [
                'total_revenue' => (int) $totalRevenue,
                'total_orders' => (int) $totalOrders,
                'total_products' => (int) $totalProducts,
                'total_users' => (int) $totalUsers,
                'chart' => ['labels' => [], 'data' => []],
            ];
            event(new DashboardUpdated($stats));
        } catch (\Throwable $e) {
            // swallow broadcast errors to avoid breaking admin flow
        }

        return response()->json(['success' => true, 'fulfillment_status' => $order->fulfillment_status]);
    }
}