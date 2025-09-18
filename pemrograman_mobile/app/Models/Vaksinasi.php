<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaksinasi extends Model
{
    protected $table = 'vaksinasi';

    public function hewan()
    {
        return $this->belongsTo(Hewan::class, 'nama_hewan', 'nama_hewan');
    }
}
