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

        return view('cartindex', compact('carts', 'total'));
    }

    // Menambah produk ke keranjang
    public function add(Request $request, Product $product)
    {
        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        $delta = intval($request->input('qty', 1));
        if ($cart) {
            // If cart exists, increment (delta may be negative)
            $cart->increment('qty', $delta);
            $cart->refresh();
        } else {
            // If creating with a negative delta, clamp to zero => ignore
            $initial = $delta > 0 ? $delta : 1;
            $cart = Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'qty' => $initial
            ]);
        }

        // If qty drops to zero or below, delete the cart item
        $deleted = false;
        if ($cart->qty <= 0) {
            $cartId = $cart->id;
            $cart->delete();
            $deleted = true;
        }

        if ($request->wantsJson() || $request->ajax()) {
            $totalQty = Cart::where('user_id', Auth::id())->sum('qty');
            $totalAmount = Cart::with('product')->where('user_id', Auth::id())->get()->sum(fn($c) => $c->product->price * $c->qty);

            return response()->json([
                'success' => true,
                'cart_id' => $deleted ? $cartId ?? null : $cart->id,
                'qty' => $deleted ? 0 : ($cart->qty ?? 0),
                'deleted' => $deleted,
                'totalQty' => $totalQty,
                'totalAmount' => $totalAmount,
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

        if (request()->wantsJson() || request()->ajax()) {
            $totalQty = Cart::where('user_id', Auth::id())->sum('qty');
            $totalAmount = Cart::with('product')->where('user_id', Auth::id())->get()->sum(fn($c) => $c->product->price * $c->qty);
            return response()->json([
                'success' => true,
                'deleted' => true,
                'totalQty' => $totalQty,
                'totalAmount' => $totalAmount,
            ]);
        }

        return redirect()->back()->with('success', 'Item dihapus dari keranjang.');
    }

    // Return total cart quantity for authenticated user (AJAX)
    public function count(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['totalQty' => 0]);
        }

        $totalQty = Cart::where('user_id', Auth::id())->sum('qty');
        return response()->json(['totalQty' => $totalQty]);
    }
}
