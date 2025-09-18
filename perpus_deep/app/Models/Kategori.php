<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategoris';
    protected $fillable = ['nama'];

    // Relasi ke buku yang termasuk kategori ini
    public function bukus()
    {
        return $this->hasMany(Buku::class);
    }
}
