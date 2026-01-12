<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    protected $table = 'gudang';

    protected $fillable = [
        'nama_gudang',
        'lokasi',
    ];

    public function stokBarangs()
    {
        return $this->hasMany(\App\Models\StokBarang::class, 'gudang_id');
    }
}
