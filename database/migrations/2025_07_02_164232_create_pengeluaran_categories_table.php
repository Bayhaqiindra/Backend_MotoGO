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
        Schema::create('pengeluaran_categories', function (Blueprint $table) {
            $table->id('pengeluaran_category_id'); // ID kategori pengeluaran
            $table->string('name'); // Nama kategori
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_categories'); // Menghapus tabel pengeluaran_categories jika rollback
    }
};
