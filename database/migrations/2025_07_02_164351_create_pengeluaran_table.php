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
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id('pengeluaran_id'); // ID unik pengeluaran
            $table->foreignId('pengeluaran_category_id')->constrained('pengeluaran_categories','pengeluaran_category_id'); // Relasi dengan tabel pengeluaran_categories
            $table->decimal('jumlah_pengeluaran', 15, 2); // Total pengeluaran dalam periode tersebut
            $table->text('deskripsi_pengeluaran'); // Deskripsi pengeluaran
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran'); // Menghapus tabel pengeluaran jika rollback
    }
};
