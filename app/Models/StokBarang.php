<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokBarang extends Model
{
    protected $table = 'stok_barangs';

    protected $fillable = [
        'barang_master_id',
        'gudang_id',
        'jumlah',
    ];

    public function barangMaster()
    {
        return $this->belongsTo(BarangMaster::class, 'barang_master_id');
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }
}
