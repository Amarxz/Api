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
        Schema::create('vaksinasi', function (Blueprint $table) {
            $table->bigIncrements('id_vaksinasi');
            $table->string('nama_hewan'); // Nama Hewan yang akan divaksinasi
            $table->string('nama_vaksin'); // Nama Vaksin yang diberikan
            $table->date('jadwal_vaksinasi'); // Jadwal vaksinasi
            $table->enum('status', ['Belum', 'Selesai']); // Status vaksinasi
            $table->text('catatan')->nullable(); // Catatan tambahan tentang vaksinasi
            $table->timestamps(); // Waktu pembuatan dan pembaruan data
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaksinasi');
    }
};
