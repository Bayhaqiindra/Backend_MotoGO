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
        Schema::create('service_confirmation', function (Blueprint $table) {
            $table->id('confirmation_id'); // ID unik konfirmasi servis
            $table->foreignId('booking_id')->constrained('bookings', 'booking_id'); // Relasi dengan tabel bookings
            $table->foreignId('service_id')->constrained('services', 'service_id'); // Relasi dengan tabel services
            $table->string('service_status', 50); // Status servis (Menunggu, Diterima, Dalam Perbaikan, Selesai)
            $table->decimal('total_cost', 10, 2); // Biaya total yang harus dibayar oleh pelanggan
            $table->boolean('customer_agreed'); // Apakah pelanggan setuju dengan konfirmasi (Ya/Tidak)
            $table->text('admin_notes')->nullable(); // Catatan dari admin (hasil pemeriksaan dan yang perlu diganti)
            $table->timestamp('confirmed_at')->nullable(); // Waktu konfirmasi yang dilakukan oleh admin
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_confirmation'); // Menghapus tabel service_confirmation jika rollback
    }
};
