<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranPenjualan extends Model
{
    protected $table = 'pembayaran_penjualan';
    protected $fillable = [
        'penjualan_id',
        'tanggal_bayar',
        'jumlah_bayar',
        'metode_pembayaran',
        'catatan',
    ];

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }
}
