<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $table = 'spareparts'; // Sesuai dengan nama tabel
    protected $primaryKey = 'sparepart_id'; // Primary key khusus

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'image_url',
    ];
}
