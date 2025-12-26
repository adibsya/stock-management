<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranPembelian extends Model
{
    protected $table = 'pembayaran_pembelian';
    protected $fillable = [
        'pembelian_id',
        'tanggal_bayar',
        'jumlah_bayar',
        'jumlah',
        'tanggal_jatuh_tempo',
        'status',
        'metode_pembayaran',
        'catatan',
    ];

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }
}
