<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Retur extends Model
{
    protected $table = 'retur';

    protected $fillable = [
        'tanggal',
        'jenis_retur',
        'referensi_faktur',
        'barang_id',
        'jumlah',
        'alasan',
        'kondisi_barang',
        'aksi_stok',
        'nilai_pengembalian',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'integer',
        'nilai_pengembalian' => 'decimal:2',
    ];

    /**
     * Get barang for this retur
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /**
     * Check if this is retur penjualan
     */
    public function isReturPenjualan(): bool
    {
        return $this->jenis_retur === 'retur_penjualan';
    }

    /**
     * Check if this is retur pembelian
     */
    public function isReturPembelian(): bool
    {
        return $this->jenis_retur === 'retur_pembelian';
    }

    /**
     * Check if barang is in good condition
     */
    public function isBagus(): bool
    {
        return $this->kondisi_barang === 'bagus';
    }

    /**
     * Check if barang is damaged
     */
    public function isRusak(): bool
    {
        return $this->kondisi_barang === 'rusak';
    }

    /**
     * Check if stock should be returned
     */
    public function shouldKembaliKeStok(): bool
    {
        return $this->aksi_stok === 'kembali_ke_stok';
    }

    /**
     * Check if stock should be discarded
     */
    public function shouldBuang(): bool
    {
        return $this->aksi_stok === 'buang';
    }

    /**
     * Scope: Filter retur penjualan
     */
    public function scopeReturPenjualan($query)
    {
        return $query->where('jenis_retur', 'retur_penjualan');
    }

    /**
     * Scope: Filter retur pembelian
     */
    public function scopeReturPembelian($query)
    {
        return $query->where('jenis_retur', 'retur_pembelian');
    }
}
