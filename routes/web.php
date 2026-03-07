<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WebhookController;

// Rute yang membutuhkan Login
Route::middleware('auth')->group(function () {
    
    // Fitur Keranjang Belanja (Cart)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'remove'])->name('cart.remove');

    // Fitur Checkout & Invoice
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/order/{order_number}', [CheckoutController::class, 'show'])->name('order.show');

});

// Rute Webhook Pakasir (Tanpa Auth)
Route::post('/webhook/pakasir', [WebhookController::class, 'handlePakasir'])->name('webhook.pakasir');
