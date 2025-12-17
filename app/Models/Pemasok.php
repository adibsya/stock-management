<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pemasok extends Model
{
    protected $table = 'pemasok';

    protected $fillable = [
        'nama_supplier',
        'kontak',
        'alamat',
        'catatan_termin_pembayaran',
    ];

    /**
     * Get all barang from this pemasok
     */
    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class, 'pemasok_id');
    }

    /**
     * Get all pembelian from this pemasok
     */
    public function pembelian(): HasMany
    {
        return $this->hasMany(Pembelian::class, 'pemasok_id');
    }
}
