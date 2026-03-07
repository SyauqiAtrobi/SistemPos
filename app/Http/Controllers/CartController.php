<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Menampilkan halaman keranjang
    public function index()
    {
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        $total = $carts->sum(fn($cart) => $cart->product->price * $cart->qty);

        return view('cart.index', compact('carts', 'total'));
    }

    // Menambah produk ke keranjang
    public function add(Request $request, Product $product)
    {
        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cart) {
            // Jika sudah ada, tambah kuantitasnya
            $cart->increment('qty', $request->input('qty', 1));
        } else {
            // Jika belum ada, buat baru
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'qty' => $request->input('qty', 1)
            ]);
        }

        return redirect()->back()->with('success', 'Parfum berhasil ditambahkan ke keranjang!');
    }

    // Menghapus item dari keranjang
    public function remove(Cart $cart)
    {
        if ($cart->user_id === Auth::id()) {
            $cart->delete();
        }
        return redirect()->back()->with('success', 'Item dihapus dari keranjang.');
    }
}
