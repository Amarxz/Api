<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanans';

    protected $fillable = [
        'user_id',
        'buku_id',
        'tanggal_pesan'
    ];

    // Relasi ke user (anggota)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke buku yang dipesan
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
