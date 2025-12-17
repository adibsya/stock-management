<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'harga_beli',
        'harga_jual',
        'stok',
        'stok_minimum',
        'satuan',
        'foto',
        'pemasok_id',
        'gudang_id',
        'keterangan',
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'stok' => 'integer',
        'stok_minimum' => 'integer',
    ];

    /**
     * Get pemasok for this barang
     */
    public function pemasok(): BelongsTo
    {
        return $this->belongsTo(Pemasok::class, 'pemasok_id');
    }

    /**
     * Get gudang for this barang
     */
    public function gudang(): BelongsTo
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    /**
     * Get all detail penjualan for this barang
     */
    public function detailPenjualan(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class, 'barang_id');
    }

    /**
     * Get all detail pembelian for this barang
     */
    public function detailPembelian(): HasMany
    {
        return $this->hasMany(DetailPembelian::class, 'barang_id');
    }

    /**
     * Get all riwayat stok for this barang
     */
    public function riwayatStok(): HasMany
    {
        return $this->hasMany(RiwayatStok::class, 'barang_id');
    }

    /**
     * Get all retur for this barang
     */
    public function retur(): HasMany
    {
        return $this->hasMany(Retur::class, 'barang_id');
    }

    /**
     * Scope: Filter barang with stok below stok_minimum (almost out of stock)
     */
    public function scopeHampirHabis(Builder $query): Builder
    {
        return $query->whereColumn('stok', '<', 'stok_minimum');
    }

    /**
     * Scope: Filter barang that is out of stock
     */
    public function scopeHabis(Builder $query): Builder
    {
        return $query->where('stok', '<=', 0);
    }

    /**
     * Scope: Filter by kategori
     */
    public function scopeKategori(Builder $query, string $kategori): Builder
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Check if barang stock is low
     */
    public function isHampirHabis(): bool
    {
        return $this->stok < $this->stok_minimum;
    }

    /**
     * Increase stock
     */
    public function tambahStok(int $jumlah): void
    {
        $this->increment('stok', $jumlah);
    }

    /**
     * Decrease stock
     */
    public function kurangiStok(int $jumlah): void
    {
        $this->decrement('stok', $jumlah);
    }
}
