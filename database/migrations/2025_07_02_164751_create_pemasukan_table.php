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
        Schema::create('pemasukan', function (Blueprint $table) {
            $table->id(); // ID unik pemasukan
            $table->foreignId('transaction_id')->nullable()->constrained('payments_sparepart', 'payment_id'); // Mengacu ke payments_sparepart.payment_id
            $table->foreignId('confirmation_id')->nullable()->constrained('payments_service', 'payment_id'); // Mengacu ke payments_service.payment_id
            $table->decimal('total_income', 15, 2); // Jumlah total pemasukan yang diterima
            $table->string('payment_method', 50); // Metode pembayaran yang digunakan (misalnya: Transfer Bank, COD)
            $table->timestamp('payment_date'); // Tanggal transaksi pembayaran dilakukan
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukan'); // Menghapus tabel pemasukan jika rollback
    }
};
