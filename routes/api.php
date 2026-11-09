<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PosController;

// Group Route untuk API Kasir POS
Route::prefix('pos')->group(function () {
    // URL untuk mengambil daftar produk & varian cup size
    // Method: GET -> http://domain-laravel-anda.com/api/pos/products
    Route::get('/products', [PosController::class, 'getProducts']);

    // URL untuk mengirim & menyimpan data transaksi checkout
    // Method: POST -> http://domain-laravel-anda.com/api/pos/checkout
    Route::post('/checkout', [PosController::class, 'checkout']);

    Route::get('/pos/transactions', [TransactionController::class, 'index']);
});