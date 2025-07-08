<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLatitudeLongitudeToBookingsTable extends Migration
{
    /**
     * Jalankan migration untuk menambahkan kolom latitude dan longitude.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable();  // Menambahkan kolom latitude
            $table->decimal('longitude', 10, 7)->nullable(); // Menambahkan kolom longitude
        });
    }

    /**
     * Membatalkan perubahan yang dibuat oleh migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
}
