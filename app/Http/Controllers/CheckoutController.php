<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Services\PakasirService; // Service yang akan kita buat nanti
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function process(Request $request, PakasirService $pakasir)
    {
        $user = Auth::user();
        $carts = Cart::with('product')->where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        $totalAmount = $carts->sum(fn($cart) => $cart->product->price * $cart->qty);
        $orderNumber = 'INV-' . date('YmdHis') . '-' . $user->id;

        DB::beginTransaction();
        try {
            // 1. Buat Data Order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'total_amount' => $totalAmount,
                'status' => 'pending'
            ]);

            // 2. Pindahkan Cart ke Order Items
            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'price' => $cart->product->price, // Snapshot harga
                    'qty' => $cart->qty
                ]);
            }

            // 3. Tembak API Pakasir
            $qrisData = $pakasir->createQrisTransaction($orderNumber, $totalAmount);

            if (!isset($qrisData['payment']['payment_number'])) {
                throw new \Exception('Gagal mendapatkan QRIS dari Pakasir.');
            }

            // 4. Simpan Data Pembayaran (QRIS String)
            Payment::create([
                'order_id' => $order->id,
                'qris_string' => $qrisData['payment']['payment_number'],
                'payment_method' => 'qris'
            ]);

            // 5. Bersihkan Keranjang
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            // Redirect ke halaman invoice/pembayaran
            return redirect()->route('order.show', $order->order_number);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($orderNumber)
    {
        $order = Order::with(['items.product', 'payment'])
            ->where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('ordershow', compact('order'));
    }
}
