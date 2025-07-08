<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceConfirmation extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika bukan nama default 'service_confirmations'
    protected $table = 'service_confirmation';

    // Tentukan primary key
    protected $primaryKey = 'confirmation_id';

    public $timestamps = true;
    
    // Tentukan kolom yang dapat diisi
    protected $fillable = [
        'booking_id',
        'service_id',
        'service_status',
        'total_cost',
        'customer_agreed',
        'admin_notes',
        'confirmed_at',
    ];

    // Relasi dengan model Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    // Relasi dengan model Service
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
