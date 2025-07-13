<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika bukan nama default 'bookings'
    protected $table = 'bookings';

    // Tentukan primary key
    protected $primaryKey = 'booking_id';

    // Tentukan kolom yang dapat diisi
    protected $fillable = [
        'id_pelanggan',
        'service_id',
        'status',
        'customer_notes',
        'pickup_location',
        'latitude',  // Tambahkan latitude
        'longitude', // Tambahkan longitude
    ];

    // Relasi dengan model User (pelanggan)
    public function pelanggan()
    {
        return $this->belongsTo(pelanggan::class, 'id_pelanggan');
    }

    // Relasi dengan model Service
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
    
}
