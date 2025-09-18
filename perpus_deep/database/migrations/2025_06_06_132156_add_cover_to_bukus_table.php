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
        Schema::table('bukus', function (Blueprint $table) {
            // Menambahkan kolom 'cover'
            $table->string('cover')->nullable()->after('stok_saat_ini');

            // Menambahkan kolom 'publisher'
            $table->string('publisher')->nullable()->after('cover'); // Atau setelah 'stok_saat_ini' jika diinginkan

            // Menambahkan kolom 'tanggal_publikasi'
            $table->date('tanggal_publikasi')->nullable()->after('publisher'); // Atau setelah 'publisher'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bukus', function (Blueprint $table) {
            // Menghapus semua kolom yang ditambahkan jika migration di-rollback
            $table->dropColumn(['cover', 'publisher', 'tanggal_publikasi']);
        });
    }
};
