<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjamans'; // <-- PASTIKAN INI SESUAI DENGAN NAMA TABEL DI DATABASE ANDA

    protected $fillable = [
        'user_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status' // <-- Tambahkan baris ini
    ];

    // Relasi ke user (anggota)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke buku yang dipinjam
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
