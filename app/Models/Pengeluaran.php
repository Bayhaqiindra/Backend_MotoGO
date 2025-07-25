<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';
    protected $primaryKey = 'pengeluaran_id';
    protected $fillable = [
        'category_pengeluaran',
        'jumlah_pengeluaran',
        'deskripsi_pengeluaran',
    ];
}
