<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'barang_master_id',
        'gudang_id',
        'pemasok_id',
        'harga_beli',
        'harga_jual',
        'stok',
        'stok_minimum',
        'foto',
        'keterangan',
    ];

    /**
     * Cast harga ke number
     */
    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'stok' => 'integer',
        'stok_minimum' => 'integer',
    ];

    /**
     * Accessor untuk mendapatkan kode_barang dari master
     */
    public function getKodeBarangAttribute()
    {
        return $this->master?->kode_barang;
    }

    /**
     * Accessor untuk mendapatkan nama_barang dari master
     */
    public function getNamaBarangAttribute()
    {
        return $this->master?->nama_barang;
    }

    /**
     * Accessor untuk mendapatkan kategori dari master
     */
    public function getKategoriAttribute()
    {
        return $this->master?->kategori;
    }

    /**
     * Accessor untuk mendapatkan satuan dari master
     */
    public function getSatuanAttribute()
    {
        return $this->master?->satuan;
    }

    /**
     * Relasi ke BarangMaster
     */
    public function master()
    {
        return $this->belongsTo(BarangMaster::class, 'barang_master_id');
    }

    /**
     * Alias untuk relasi master
     */
    public function barangMaster()
    {
        return $this->belongsTo(BarangMaster::class, 'barang_master_id');
    }

    /**
     * Relasi ke Gudang
     */
    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    /**
     * Relasi ke Pemasok
     */
    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class);
    }

    /**
     * Relasi ke DetailPembelian
     */
    public function detailPembelian()
    {
        return $this->hasMany(DetailPembelian::class);
    }

    /**
     * Relasi ke DetailPenjualan
     */
    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class);
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $term)
    {
        return $query->whereHas('master', function ($q) use ($term) {
            $q->where('nama_barang', 'like', "%{$term}%")
              ->orWhere('kode_barang', 'like', "%{$term}%");
        });
    }

    /**
     * Scope untuk filter gudang
     */
    public function scopeGudang($query, $gudangId)
    {
        return $query->where('gudang_id', $gudangId);
    }

    /**
     * Scope barang dengan stok tersedia
     */
    public function scopeStokTersedia($query)
    {
        return $query->where('stok', '>', 0);
    }

    /**
     * Scope barang hampir habis
     */
    public function scopeHampirHabis($query)
    {
        return $query->whereColumn('stok', '<=', 'stok_minimum')
                     ->where('stok', '>', 0);
    }

    /**
     * Scope barang habis
     */
    public function scopeHabis($query)
    {
        return $query->where('stok', '<=', 0);
    }

    /**
     * Tambah stok barang
     */
    public function tambahStok(int $jumlah): void
    {
        $this->increment('stok', $jumlah);
    }

    /**
     * Kurangi stok barang
     */
    public function kurangiStok(int $jumlah): void
    {
        $this->decrement('stok', $jumlah);
    }
}
