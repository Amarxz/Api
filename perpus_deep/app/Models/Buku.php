<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'bukus';
    protected $fillable = [
        'judul',
        'penulis',
        'kategori_id',
        'stok_total',
        'stok_saat_ini',
        'cover', // <-- Tambahkan
        'publisher', // <-- Tambahkan
        'tanggal_publikasi' // <-- Tambahkan
    ];

    // Relasi ke kategori buku
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // Relasi peminjaman buku
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    // Relasi pemesanan buku
    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class);
    }
}
