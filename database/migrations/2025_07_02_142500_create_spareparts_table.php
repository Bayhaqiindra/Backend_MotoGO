<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('spareparts', function (Blueprint $table) {
            $table->id('sparepart_id'); // ID unik sparepart
            $table->string('name', 255); // Nama sparepart
            $table->text('description'); // Deskripsi sparepart
            $table->decimal('price', 10, 2); // Harga sparepart
            $table->integer('stock_quantity'); // Jumlah stok sparepart
            $table->timestamps(); // Kolom created_at dan updated_at
            $table->string('image_url', 255)->nullable(); // URL atau path file gambar sparepart
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spareparts'); // Menghapus tabel spareparts jika rollback
    }
};
