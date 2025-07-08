<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    // Menentukan tabel yang digunakan oleh model ini
    protected $table = 'admin';

    // Menentukan primary key tabel
    protected $primaryKey = 'id_admin';

    // Menonaktifkan fitur timestamp jika tabel tidak memiliki created_at dan updated_at
    public $timestamps = true; // Berdasarkan kolom 'created_at' dan 'updated_at'

    // Kolom yang dapat diisi (fillable)
    protected $fillable = [
        'user_id',
        'name',
        'profile_picture',
        'created_at',
        'updated_at',
    ];

    /**
     * Relasi dengan model User
     * Menentukan hubungan antara Admin dan User (Admin belongsTo User)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
