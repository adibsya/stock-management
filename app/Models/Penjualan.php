<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penjualan extends Model
{
    protected $table = 'penjualan';

    protected $fillable = [
        'no_faktur',
        'tanggal',
        'pelanggan_id',
        'user_id',
        'gudang_id',
        'total_kotor',
        'diskon_transaksi',
        'pajak',
        'total_bayar',
        'mode_termin',
        'jatuh_tempo',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jatuh_tempo' => 'date',
        'total_kotor' => 'decimal:2',
        'diskon_transaksi' => 'decimal:2',
        'pajak' => 'decimal:2',
        'total_bayar' => 'decimal:2',
    ];

    /* ================= RELATION ================= */

    public function pembayaranPenjualan(): HasMany
    {
        return $this->hasMany(PembayaranPenjualan::class, 'penjualan_id');
    }

    public function detailPenjualan(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id');
    }

    public function items(): HasMany
    {
        return $this->detailPenjualan();
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function gudang(): BelongsTo
    {
        return $this->belongsTo(Gudang::class);
    }
    
    /**
     * Check if penjualan is termin
     */
    public function isTermin(): bool
    {
        return $this->status === 'termin' || $this->metode_pembayaran === 'termin';
    }
    
    /**
     * Get total pembayaran termin yang sudah lunas
     */
    public function getTotalTerminLunas(): float
    {
        return $this->pembayaranPenjualan()
            ->where('status_bayar', 'lunas')
            ->sum('jumlah_bayar');
    }
    
    /**
     * Get jumlah cicilan yang sudah lunas
     */
    public function getJumlahCicilanLunas(): int
    {
        return $this->pembayaranPenjualan()
            ->where('status_bayar', 'lunas')
            ->count();
    }
    
    /**
     * Get total cicilan
     */
    public function getTotalCicilan(): int
    {
        return $this->pembayaranPenjualan()->count();
    }
    
    /**
     * Get status termin dalam format "a/b"
     */
    public function getStatusTerminLabel(): string
    {
        if (!$this->isTermin()) {
            return ucfirst($this->status);
        }
        
        $lunas = $this->getJumlahCicilanLunas();
        $total = $this->getTotalCicilan();
        
        return "Termin {$lunas}/{$total}";
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kasir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /* ================= HELPER ================= */

    public function getTotalDibayarAttribute(): float
    {
        return (float) $this->pembayaranPenjualan()->sum('jumlah_bayar');
    }

    public function getSisaPiutangAttribute(): float
    {
        return max(0, $this->total_bayar - $this->total_dibayar);
    }

    public function isLunas(): bool
    {
        return $this->sisa_piutang <= 0;
    }

    public function updateStatus(): void
    {
        $this->status = $this->isLunas() ? 'lunas' : 'belum_lunas';
        $this->save();
    }

    /* ================= NO FAKTUR ================= */

    public static function generateNoFaktur(): string
    {
        $today = now()->format('Ymd');
        $prefix = "INV-{$today}-";

        $last = static::where('no_faktur', 'like', $prefix.'%')
            ->orderBy('no_faktur', 'desc')
            ->first();

        $number = $last
            ? str_pad(((int) substr($last->no_faktur, -4)) + 1, 4, '0', STR_PAD_LEFT)
            : '0001';

        return $prefix.$number;
    }
}
