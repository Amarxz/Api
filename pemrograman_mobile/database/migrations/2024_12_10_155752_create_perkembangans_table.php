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
        Schema::create('perkembangan', function (Blueprint $table) {
            $table->bigIncrements('id_perkembangan');  // ID Perkembangan
            $table->string('nama_hewan');   // Nama Hewan
            $table->date('tanggal');        // Tanggal Perkembangan
            $table->float('berat_badan');   // Berat Badan
            $table->float('tinggi');        // Tinggi Hewan
            $table->string('foto')->nullable(); // Foto (bisa kosong jika tidak ada)
            $table->text('catatan')->nullable(); // Catatan Perkembangan
            $table->timestamps();  // Menyimpan waktu pembuatan dan pembaruan data
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perkembangan');
    }
};
