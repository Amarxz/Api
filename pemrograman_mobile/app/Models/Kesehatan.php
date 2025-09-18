<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kesehatan extends Model
{
    protected $table = 'kesehatan';

    public function hewan()
    {
        return $this->belongsTo(Hewan::class, 'nama_hewan', 'nama_hewan');
    }
}
