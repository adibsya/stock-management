<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranPenjualan extends Model
{
    // Total pembayaran yang sudah dibayar untuk termin ini
    public function getJumlahBayarAttribute()
    {
        // Jika field jumlah_bayar ada di tabel, gunakan langsung
        if (array_key_exists('jumlah_bayar', $this->attributes)) {
            return $this->attributes['jumlah_bayar'];
        }
        // Jika tidak, fallback ke 0
        return 0;
    }
    protected $table = 'pembayaran_penjualan';
    protected $fillable = [
        'penjualan_id',
        'jumlah',
        'jumlah_bayar',
        'tanggal_jatuh_tempo',
        'tanggal_bayar',
        'metode_pembayaran',
        'catatan',
        'status',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }
}
