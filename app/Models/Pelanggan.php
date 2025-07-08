<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';
    public $timestamps = true; // Based on 'created_at' and 'updated_at' columns
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'profile_picture',
        'address',
    ];


    // Menentukan relasi dengan model User (jika diperlukan)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Relasi dengan tabel 'users' berdasarkan 'user_id'
    }
}