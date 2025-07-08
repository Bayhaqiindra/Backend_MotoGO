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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('booking_id'); // ID unik pemesanan servis
            $table->foreignId('id_pelanggan')->constrained('pelanggan', 'id_pelanggan'); // Relasi dengan tabel profile_pelanggan
            $table->foreignId('service_id')->constrained('services', 'service_id'); // Relasi dengan tabel services
            $table->string('status', 50); // Status booking (Menunggu, Diterima, Ditolak)
            $table->text('customer_notes')->nullable(); // Catatan dari pelanggan
            $table->string('pickup_location', 255); // Lokasi penjemputan motor
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings'); // Menghapus tabel bookings jika rollback
    }
};
