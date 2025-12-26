<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMaster extends Model
{
    use HasFactory;

    protected $table = 'barang_master';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'satuan',
        'harga_beli',
        'harga_jual',
        'keterangan',
    ];

    /**
     * Cast harga ke number
     */
    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
    ];

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('nama_barang', 'like', "%{$term}%")
            ->orWhere('kode_barang', 'like', "%{$term}%")
            ->orWhere('kategori', 'like', "%{$term}%");
    }

    /**
     * Relasi ke stok per gudang (next step)
     */
    public function stok()
    {
        return $this->hasMany(StokBarang::class, 'barang_master_id');
    }

    /**
     * Scope barang hampir habis (stok <= 5 dan > 0)
     */

    public function scopeHampirHabis($query)
    {
        return $query->whereHas('stok', function ($q) {
            $q->selectRaw('barang_master_id, SUM(jumlah) as total_stok')
              ->groupBy('barang_master_id')
              ->havingRaw('SUM(jumlah) <= 5 AND SUM(jumlah) > 0');
        });
    }

    /**
     * Scope barang habis (stok <= 0)
     */

    public function scopeHabis($query)
    {
        return $query->whereHas('stok', function ($q) {
            $q->selectRaw('barang_master_id, SUM(jumlah) as total_stok')
              ->groupBy('barang_master_id')
              ->havingRaw('SUM(jumlah) <= 0');
        });
    }
}
