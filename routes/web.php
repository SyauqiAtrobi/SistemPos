<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// 1. Rute Publik (Tidak perlu login)
Route::get('/', function () {
    return redirect('/katalog'); // Arahkan halaman utama langsung ke katalog
});

// Rute untuk menampilkan View Katalog (Bisa diakses siapa saja)
Route::get('/katalog', [ProductController::class, 'index'])->name('katalog.index');

// Rute Webhook Pakasir (Wajib di luar Auth)
Route::post('/webhook/pakasir', [WebhookController::class, 'handlePakasir'])->name('webhook.pakasir');


// 2. Rute khusus Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/manajemen-produk', [ProductController::class, 'manage'])->name('product.manage');
    Route::post('/manajemen-produk', [ProductController::class, 'store'])->name('product.store');
    Route::put('/manajemen-produk/{product}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/manajemen-produk/{product}', [ProductController::class, 'destroy'])->name('product.destroy');

    Route::get('/manajemen-kategori', [CategoryController::class, 'manage'])->name('category.manage');
    Route::post('/manajemen-kategori', [CategoryController::class, 'store'])->name('category.store');
    Route::put('/manajemen-kategori/{category}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/manajemen-kategori/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');

    Route::get('/manajemen-pesanan', [OrderController::class, 'manage'])->name('order.manage');
    Route::put('/manajemen-pesanan/{order}/cancel', [OrderController::class, 'cancelManual'])->name('order.cancel');
});

// 3. Rute yang membutuhkan Login
Route::middleware(['auth'])->group(function () {
    // Dashboard (opsional, jika ingin tetap dipakai)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Fitur Keranjang Belanja (Cart)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'remove'])->name('cart.remove');

    // Fitur Checkout & Invoice
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/order/{order_number}', [CheckoutController::class, 'show'])->name('order.show');
});

require __DIR__ . '/auth.php';
