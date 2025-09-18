<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRememberTokenToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom remember_token jika belum ada
            // Laravel akan secara otomatis menambahkan ini di migrasi create_users_table defaultnya
            // Jika tidak ada, kemungkinan Anda menghapusnya atau menggunakan migrasi kustom.
            $table->rememberToken()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom remember_token saat rollback
            $table->dropColumn('remember_token');
        });
    }
}
