<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatStok extends Model
{
    protected $table = 'riwayat_stok';

    protected $fillable = [
        'tanggal',
        'barang_id',
        'jenis_transaksi',
        'jumlah_masuk',
        'jumlah_keluar',
        'sisa_stok',
        'referensi_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah_masuk' => 'integer',
        'jumlah_keluar' => 'integer',
        'sisa_stok' => 'integer',
    ];

    /**
     * Get barang for this riwayat
     */
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /**
     * Scope: Filter by jenis transaksi
     */
    public function scopeJenisTransaksi($query, string $jenis)
    {
        return $query->where('jenis_transaksi', $jenis);
    }

    /**
     * Scope: Filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    /**
     * Scope: Filter penjualan transactions
     */
    public function scopePenjualan($query)
    {
        return $query->where('jenis_transaksi', 'penjualan');
    }

    /**
     * Scope: Filter pembelian transactions
     */
    public function scopePembelian($query)
    {
        return $query->where('jenis_transaksi', 'pembelian');
    }

    /**
     * Scope: Filter retur masuk transactions
     */
    public function scopeReturMasuk($query)
    {
        return $query->where('jenis_transaksi', 'retur_masuk');
    }

    /**
     * Scope: Filter retur keluar transactions
     */
    public function scopeReturKeluar($query)
    {
        return $query->where('jenis_transaksi', 'retur_keluar');
    }

    /**
     * Scope: Filter opname transactions
     */
    public function scopeOpname($query)
    {
        return $query->where('jenis_transaksi', 'opname');
    }

    /**
     * Check if this is a stock in transaction
     */
    public function isStokMasuk(): bool
    {
        return $this->jumlah_masuk > 0;
    }

    /**
     * Check if this is a stock out transaction
     */
    public function isStokKeluar(): bool
    {
        return $this->jumlah_keluar > 0;
    }
}
