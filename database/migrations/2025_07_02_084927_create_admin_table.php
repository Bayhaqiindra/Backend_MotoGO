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
        Schema::create('admin', function (Blueprint $table) {
        $table->bigIncrements('id_admin'); // Kolom primary key dengan nama admin_id
        $table->unsignedBigInteger('user_id'); // Menambahkan kolom user_id dengan tipe data yang sesuai
        $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade'); // Relasi ke kolom 'id' di tabel 'users'
        $table->string('name'); // Nama lengkap admin
        $table->string('profile_picture')->nullable(); // URL foto profil admin
        $table->timestamps(); // Kolom created_at dan updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
