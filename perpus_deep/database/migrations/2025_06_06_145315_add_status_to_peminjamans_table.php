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
        Schema::table('peminjamans', function (Blueprint $table) {
            // Menambahkan kolom 'status' setelah 'tanggal_kembali'
            // Default 'dipinjam' akan disetel saat record baru dibuat jika tidak ditentukan
            $table->string('status')->default('dipinjam')->after('tanggal_kembali');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            // Menghapus kolom 'status' jika migration di-rollback
            $table->dropColumn('status');
        });
    }
};
