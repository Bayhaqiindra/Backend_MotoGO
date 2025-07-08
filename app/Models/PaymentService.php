<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentService extends Model
{
    use HasFactory;

    // Tentukan nama tabel yang digunakan jika bukan nama default
    protected $table = 'payments_service';

    // Tentukan primary key
    protected $primaryKey = 'payment_id';

    // Tentukan kolom yang dapat diisi
    protected $fillable = [
        'confirmation_id',     // ID konfirmasi
        'total_amount',         // Total biaya yang harus dibayar
        'payment_status',       // Status pembayaran (Lunas, Belum Lunas, dll.)
        'metode_pembayaran',    // Metode pembayaran (Transfer, Tunai, dll.)
        'bukti_pembayaran',     // Bukti pembayaran (file atau link)
        'payment_date',         // Tanggal pembayaran
    ];

    // Relasi dengan model ServiceConfirmation
    public function serviceConfirmation()
    {
        return $this->belongsTo(ServiceConfirmation::class, 'confirmation_id');
    }

    // Relasi dengan model Booking (Jika dibutuhkan untuk melihat booking terkait)
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
