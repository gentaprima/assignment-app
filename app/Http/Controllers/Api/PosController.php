<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosController extends Controller
{
    public function getProducts()
    {
        // Ambil produk beserta varian cup dan harganya
        $products = Product::with('variants')->where('is_active', true)->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.cup_size' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.qty' => 'required|integer|min:1',
            'total' => 'required|numeric'
        ]);

        DB::beginTransaction();
        try {
            // 1. Ambil transaksi terakhir untuk mendapatkan ID/Nomor Invoice terakhir
            $lastTransaction = Transaction::latest('id')->first();

            // 2. Tentukan nomor urut berikutnya (jika belum ada transaksi, mulai dari 1)
            $nextNumber = $lastTransaction ? $lastTransaction->id + 1 : 1;

            // 3. Format nomor urut menjadi TRX-000001, TRX-000002, dst (6 digit angka)
            $invoiceNumber = 'TRX-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

            $transaction = Transaction::create([
                'invoice_number' => $invoiceNumber,
                'total_amount' => $request->total,
                'user_id' => auth()->id() ?? null
            ]);

            foreach ($request->items as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'cup_size' => $item['cup_size'],
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'subtotal' => $item['price'] * $item['qty']
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil',
                'order_id' => $transaction->invoice_number, // Kirim invoice_number agar dibaca oleh frontend & printer
                'data' => $transaction->load('details')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function history(Request $request)
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
