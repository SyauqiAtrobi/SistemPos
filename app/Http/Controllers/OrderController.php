<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman Manajemen Pesanan untuk Admin
     */
    public function manage()
    {
        // Ambil semua pesanan, muat relasi pelanggan dan produk di dalamnya.
        // Diurutkan dari yang terbaru (latest).
        $orders = Order::with(['user', 'items.product'])->latest()->get();
        
        return view('ordermanagement', compact('orders'));
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

            return back()->with('success', "Pesanan {$order->order_number} berhasil dibatalkan.");
        }

        return back()->with('error', 'Pesanan yang sudah dibayar tidak dapat dibatalkan.');
    }
}