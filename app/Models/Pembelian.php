<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembelian extends Model
{
    public function pembayaranPembelian()
    {
        return $this->hasMany(\App\Models\PembayaranPembelian::class, 'pembelian_id');
    }
    protected $table = 'pembelian';

    protected $fillable = [
        'no_faktur_supplier',
        'tanggal',
        'pemasok_id',
        'user_id',
        'total_biaya',
        'jatuh_tempo',
        'status_bayar',
    ];
    /**
     * Get user (kasir) for this pembelian
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    protected $casts = [
        'tanggal' => 'date',
        'jatuh_tempo' => 'date',
        'total_biaya' => 'decimal:2',
    ];

    /**
     * Get pemasok for this pembelian
     */
    public function pemasok(): BelongsTo
    {
        return $this->belongsTo(Pemasok::class, 'pemasok_id');
    }

    /**
     * Get all detail pembelian
     */
    public function detailPembelian(): HasMany
    {
        return $this->hasMany(DetailPembelian::class, 'pembelian_id');
    }

    /**
     * Alias for detailPembelian
     */
    public function items(): HasMany
    {
        return $this->detailPembelian();
    }

    /**
     * Check if pembelian is lunas
     */
    public function isLunas(): bool
    {
        return $this->status_bayar === 'lunas';
    }

    /**
     * Check if pembelian is belum lunas (hutang)
     */
    public function isBelumLunas(): bool
    {
        return $this->status_bayar === 'belum_lunas';
    }

    /**
     * Check if pembelian is overdue
     */
    public function isJatuhTempo(): bool
    {
        if (!$this->jatuh_tempo || $this->isLunas()) {
            return false;
        }
        return $this->jatuh_tempo->isPast();
    }

    /**
     * Scope: Filter pembelian that is belum lunas (hutang)
     */
    public function scopeBelumLunas($query)
    {
        return $query->where('status_bayar', 'belum_lunas');
    }

    /**
     * Scope: Filter pembelian that is overdue
     */
    public function scopeJatuhTempo($query)
    {
        return $query->where('status_bayar', 'belum_lunas')
                     ->whereNotNull('jatuh_tempo')
                     ->where('jatuh_tempo', '<', now());
    }
}
