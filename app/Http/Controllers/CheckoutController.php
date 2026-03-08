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
use App\Events\DashboardUpdated;
use App\Models\Product;
use App\Models\User;
use App\Models\Address;

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
                // Resolve shipping address: either selected address_id, provided address data, or user's main address
                $addressIdToSave = null;
                $shippingAddress = null;

                if ($request->filled('address_id')) {
                    $addr = Address::where('id', $request->input('address_id'))->where('user_id', $user->id)->firstOrFail();
                    $addressIdToSave = $addr->id;
                    $shippingAddress = [
                        'label' => $addr->label,
                        'address' => $addr->address,
                        'city' => $addr->city,
                        'postal_code' => $addr->postal_code,
                        'phone' => $addr->phone,
                        'lat' => $addr->lat,
                        'lng' => $addr->lng,
                    ];
                } elseif ($request->filled('address')) {
                    $count = $user->addresses()->count();
                    if ($count >= 3) {
                        throw new \Exception('Anda sudah memiliki 3 alamat. Hapus salah satu atau pilih alamat lain.');
                    }

                    $data = $request->validate([
                        'label' => 'nullable|string|max:100',
                        'address' => 'required|string',
                        'city' => 'nullable|string',
                        'postal_code' => 'nullable|string',
                        'phone' => 'nullable|string',
                        'lat' => 'nullable|numeric',
                        'lng' => 'nullable|numeric',
                    ]);

                    $created = $user->addresses()->create($data);
                    $addressIdToSave = $created->id;
                    $shippingAddress = [
                        'label' => $created->label,
                        'address' => $created->address,
                        'city' => $created->city,
                        'postal_code' => $created->postal_code,
                        'phone' => $created->phone,
                        'lat' => $created->lat,
                        'lng' => $created->lng,
                    ];
                } else {
                    // Fallback: use user's primary saved address if any
                    $primary = $user->addresses()->orderByDesc('is_primary')->first();
                    if ($primary) {
                        $addressIdToSave = $primary->id;
                        $shippingAddress = [
                            'label' => $primary->label,
                            'address' => $primary->address,
                            'city' => $primary->city,
                            'postal_code' => $primary->postal_code,
                            'phone' => $primary->phone,
                            'lat' => $primary->lat,
                            'lng' => $primary->lng,
                        ];
                    } else {
                        throw new \Exception('Silakan pilih atau masukkan alamat pengiriman.');
                    }
                }

                $order = Order::create([
                    'user_id' => $user->id,
                    'order_number' => $orderNumber,
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                    'fulfillment_status' => 'dikemas',
                    'address_id' => $addressIdToSave,
                    'shipping_address' => $shippingAddress,
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

            // Broadcast updated dashboard stats
            try {
                $totalRevenue = Order::where('status', 'paid')->sum('total_amount');
                $totalOrders = Order::count();
                $totalProducts = Product::count();
                $totalUsers = User::count();

                // simple 7-day series
                $series = Order::selectRaw("DATE(created_at) as day, SUM(total_amount) as revenue")
                    ->where('status', 'paid')
                    ->where('created_at', '>=', now()->subDays(6)->startOfDay())
                    ->groupBy('day')
                    ->orderBy('day')
                    ->get()
                    ->mapWithKeys(function ($row) {
                        return [ $row->day => (int) $row->revenue ];
                    })->toArray();

                $days = [];
                $values = [];
                for ($i = 6; $i >= 0; $i--) {
                    $d = now()->subDays($i)->format('Y-m-d');
                    $days[] = now()->subDays($i)->format('d M');
                    $values[] = isset($series[$d]) ? (int) $series[$d] : 0;
                }

                $stats = [
                    'total_revenue' => (int) $totalRevenue,
                    'total_orders' => (int) $totalOrders,
                    'total_products' => (int) $totalProducts,
                    'total_users' => (int) $totalUsers,
                    'chart' => ['labels' => $days, 'data' => $values],
                ];

                event(new DashboardUpdated($stats));
            } catch (\Throwable $e) {
                // non-fatal: continue
            }

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

    /**
     * Return JSON status for an order (used by polling fallback).
     */
    public function status($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json([
            'order_number' => $order->order_number,
            'status' => $order->status,
            'total_amount' => $order->total_amount,
        ]);
    }
}
