<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Mengizinkan semua kolom diisi via Transaction::create()
    protected $guarded = [];

    protected $fillable = [
        'invoice_number',
        'total_amount',
        'payment_method',
        'user_id'
    ];

    // Relasi ke detail transaksi
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
