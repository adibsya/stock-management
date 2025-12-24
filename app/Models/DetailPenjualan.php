<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualan';

    protected $fillable = [
        'penjualan_id',
        'barang_id',
        'gudang_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];
    /**
     * Get gudang for this detail penjualan
     */
    public function gudang(): BelongsTo
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Get penjualan for this detail
     */
    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    /**
     * Get barang for this detail
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /**
     * Calculate subtotal
     */
    public function hitungSubtotal(): float
    {
        return $this->jumlah * $this->harga_satuan;
    }
}
