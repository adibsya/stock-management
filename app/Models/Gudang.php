<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gudang extends Model
{
    protected $table = 'gudang';

    protected $fillable = [
        'nama_gudang',
        'lokasi',
    ];

    /**
     * Get all barang in this gudang
     */
    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class, 'gudang_id');
    }
}
