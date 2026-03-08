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
        Route::put('/manajemen-pesanan/{order}/status', [OrderController::class, 'updateStatus'])->name('manajemen-pesanan.updateStatus')->middleware('auth');
    
    // User management (admin)
    Route::get('/manajemen-pengguna', [App\Http\Controllers\UserController::class, 'manage'])->name('user.manage');
    Route::post('/manajemen-pengguna', [App\Http\Controllers\UserController::class, 'store'])->name('user.store');
    Route::put('/manajemen-pengguna/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('user.update');
    Route::delete('/manajemen-pengguna/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('user.destroy');

    // Admin profile
    Route::get('/admin/profile', [App\Http\Controllers\AdminProfileController::class, 'show'])->name('admin.profile.show');
    Route::put('/admin/profile', [App\Http\Controllers\AdminProfileController::class, 'update'])->name('admin.profile.update');
    
    // Settings management (.env) - admin only
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    Route::delete('/settings/logo', [App\Http\Controllers\SettingsController::class, 'deleteLogo'])->name('settings.logo.destroy');
});

// 3. Rute yang membutuhkan Login
Route::middleware(['auth'])->group(function () {
    // Dashboard (opsional, jika ingin tetap dipakai)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Dashboard stats API (for realtime dashboard)
    Route::get('/dashboard/stats', [App\Http\Controllers\DashboardController::class, 'stats'])->name('dashboard.stats');

    // Profile bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Halaman Pesanan Saya untuk customer
    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');

    // Fitur Keranjang Belanja (Cart)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'remove'])->name('cart.remove');

    // Alamat pengguna (maks 3)
    Route::get('/addresses', [App\Http\Controllers\AddressesController::class, 'index'])->name('addresses.index');
    Route::post('/addresses', [App\Http\Controllers\AddressesController::class, 'store'])->name('addresses.store');
    Route::patch('/addresses/{address}', [App\Http\Controllers\AddressesController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [App\Http\Controllers\AddressesController::class, 'destroy'])->name('addresses.destroy');

    // Fitur Checkout & Invoice
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/order/{order_number}', [CheckoutController::class, 'show'])->name('order.show');
    Route::get('/order/{order_number}/status', [CheckoutController::class, 'status'])->name('order.status');
});

require __DIR__ . '/auth.php';
