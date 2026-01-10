<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';

    protected $fillable = [
        'kode_pelanggan',
        'nama_pelanggan',
        'no_hp',
        'alamat',
    ];

    /**
     * Get all penjualan from this pelanggan
     */
    public function penjualan(): HasMany
    {
        return $this->hasMany(Penjualan::class, 'pelanggan_id');
    }

    /**
     * Check if pelanggan is grosir
     */
    public function isGrosir(): bool
    {
        // Jenis pelanggan dihapus
    }

    /**
     * Check if pelanggan is eceran
     */
    public function isEceran(): bool
    {
        // Jenis pelanggan dihapus
    }
}
