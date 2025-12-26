<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPembelian extends Model
{
    protected $table = 'detail_pembelian';

    protected $fillable = [
        'pembelian_id',
        'barang_id',
        'jumlah',
        'harga_beli',
        'total',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_beli' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Get pembelian for this detail
     */
    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }

    /**
     * Get barang for this detail
     */
    public function barangmaster(): BelongsTo
    {
        return $this->belongsTo(BarangMaster::class, 'barang_id');
    }

    /**
     * Calculate total
     */
    public function hitungTotal(): float
    {
        return $this->jumlah * $this->harga_beli;
    }
}
