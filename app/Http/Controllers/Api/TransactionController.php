<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Ambil riwayat transaksi terbaru beserta detail itemnya
public function index()
{
    try {
        $transactions = Transaction::with('details')
            ->latest('id')
            ->take(20) // Ambil 20 transaksi terbaru
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transactions
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil riwayat: ' . $e->getMessage()
        ], 500);
    }
}
}
