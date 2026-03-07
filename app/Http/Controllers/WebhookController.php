<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Events\OrderPaid; // Import Event Reverb yang sudah dibuat
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handlePakasir(Request $request)
    {
        // Tangkap payload dari Webhook Pakasir
        $payload = $request->all();
        
        // Catat ke log sistem Laravel untuk keperluan pemantauan
        Log::info('Pakasir Webhook Received:', $payload);

        // Pastikan status dari webhook adalah 'completed' (pembayaran berhasil)
        if (isset($payload['status']) && $payload['status'] === 'completed') {

            // Cari data order berdasarkan order_id yang dikirim Pakasir
            $order = Order::where('order_number', $payload['order_id'])->first();

            // Validasi: Order harus ada, statusnya masih pending, dan nominalnya persis sama
            if ($order && $order->status === 'pending' && $order->total_amount == $payload['amount']) {

                // 1. Update Status Order menjadi lunas (paid)
                $order->update(['status' => 'paid']);

                // 2. Simpan raw data webhook ke tabel payments sebagai riwayat
                if ($order->payment) {
                    $order->payment->update(['webhook_payload' => $payload]);
                }

                // 3. Kurangi Stok Produk secara otomatis berdasarkan qty yang dibeli
                foreach ($order->items as $item) {
                    $item->product->decrement('stock', $item->qty);
                }

                // 4. Pancarkan (Trigger) Event Reverb untuk update UI Frontend pelanggan secara instan!
                event(new OrderPaid($order));
                
            } else {
                // Catat peringatan jika ada ketidaksesuaian data untuk keamanan
                Log::warning('Pakasir Webhook Validation Failed:', [
                    'order_found' => (bool) $order,
                    'status_match' => $order ? ($order->status === 'pending') : false,
                    'amount_match' => $order ? ($order->total_amount == $payload['amount']) : false,
                ]);
            }
        }

        // Kirim response HTTP 200 OK agar server Pakasir tahu webhook berhasil diterima
        return response()->json(['message' => 'Webhook Processed successfully']);
    }
}