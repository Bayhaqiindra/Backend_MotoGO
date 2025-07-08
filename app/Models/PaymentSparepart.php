<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSparepart extends Model
{
    use HasFactory;

    protected $table = 'payments_sparepart';

    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'transaction_id',
        'total_pembayaran',
        'payment_status',
        'metode_pembayaran',
        'bukti_pembayaran',
        'payment_date',
    ];

    // Relasi ke transaksi (setiap pembayaran punya satu transaksi)
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
