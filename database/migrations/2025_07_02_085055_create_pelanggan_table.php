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
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->bigIncrements('id_pelanggan');// ID unik profil pelanggan
            $table->unsignedBigInteger('user_id'); // Kolom user_id sebagai foreign key
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade'); // Relasi dengan kolom user_id di tabel users
            $table->string('name'); // Nama lengkap pelanggan
            $table->string('phone', 15); // Nomor telepon pelanggan
            $table->string('profile_picture')->nullable(); // URL foto profil pelanggan
            $table->timestamps(); // Kolom created_at dan updated_at
            $table->text('address'); // Alamat pelanggan
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
