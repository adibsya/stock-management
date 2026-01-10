<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MutasiStok extends Model
{
    protected $table = 'mutasi_stok';
    protected $fillable = [
        'barang_id', 'jumlah', 'gudang_asal_id', 'gudang_tujuan_id', 'user_id', 'catatan'
    ];
        public function barang()
        {
            return $this->belongsTo(\App\Models\BarangMaster::class, 'barang_id');
        }
        public function gudangAsal()
        {
            return $this->belongsTo(\App\Models\Gudang::class, 'gudang_asal_id');
        }
        public function gudangTujuan()
        {
            return $this->belongsTo(\App\Models\Gudang::class, 'gudang_tujuan_id');
        }
        public function user()
        {
            return $this->belongsTo(\App\Models\User::class, 'user_id');
        }
}
