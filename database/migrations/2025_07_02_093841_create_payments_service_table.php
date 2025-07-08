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
        Schema::create('payments_service', function (Blueprint $table) {
            $table->id('payment_id'); // ID unik pembayaran
            $table->foreignId('confirmation_id')->constrained('service_confirmation', 'confirmation_id'); // Relasi dengan tabel service_confirmation
            $table->decimal('total_cost', 10, 2); // Jumlah total biaya yang harus dibayar sesuai konfirmasi admin
            $table->string('payment_status', 50); // Status pembayaran (Berhasil, Gagal, Pending)
            $table->string('payment_method', 50); // Metode pembayaran (misalnya: Transfer Bank, Kartu Kredit, COD)
            $table->string('proof_of_payment', 255); // Bukti pembayaran yang diunggah oleh pelanggan (file path atau URL)
            $table->timestamp('payment_date'); // Tanggal pembayaran
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments_service'); // Menghapus tabel payments_service jika rollback
    }
};
