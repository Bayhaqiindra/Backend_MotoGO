<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    // Menentukan tabel yang digunakan oleh model ini
    protected $table = 'services';

    // Menentukan primary key tabel
    protected $primaryKey = 'service_id';

    // Menonaktifkan fitur timestamp jika tabel tidak memiliki created_at dan updated_at
    public $timestamps = true; // Berdasarkan kolom 'created_at' dan 'updated_at'

    // Kolom yang dapat diisi (fillable)
    protected $fillable = [
        'service_name',
        'service_cost',
        'created_at',
        'updated_at',
    ];

    /**
     * Relasi dengan Booking
     * Setiap service dapat dimiliki oleh banyak booking
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'service_id', 'service_id');
    }

    /**
     * Relasi dengan ConfirmationService
     * Setiap service dapat dimiliki oleh banyak konfirmasi layanan
     */
    public function confirmationServices()
    {
        return $this->hasMany(ConfirmationService::class, 'service_id', 'service_id');
    }
}
