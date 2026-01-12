<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penjualan extends Model
{
    public function pembayaranPenjualan()
    {
        return $this->hasMany(\App\Models\PembayaranPenjualan::class, 'penjualan_id');
    }
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
        'metode_pembayaran',
        'mode_termin',
        'jatuh_tempo',
        'status',
    ];
    /**
     * Get gudang for this penjualan
     */
    public function gudang(): BelongsTo
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    protected $casts = [
        'tanggal' => 'date',
        'total_kotor' => 'decimal:2',
        'diskon_transaksi' => 'decimal:2',
        'pajak' => 'decimal:2',
        'total_bayar' => 'decimal:2',
    ];

    /**
     * Get pelanggan for this penjualan
     */
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    /**
     * Get user (kasir) for this penjualan
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get kasir (alias for user)
     */
    public function kasir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all detail penjualan
     */
    public function detailPenjualan(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id');
    }

    /**
     * Alias for detailPenjualan
     */
    public function items(): HasMany
    {
        return $this->detailPenjualan();
    }

    /**
     * Check if penjualan is completed
     */
    public function isSelesai(): bool
    {
        return $this->status === 'selesai';
    }

    /**
     * Check if penjualan is draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
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

    /**
     * Generate unique no faktur
     */
    public static function generateNoFaktur(): string
    {
        $today = now()->format('Ymd');
        $prefix = "INV-{$today}-";
        
        $lastInvoice = static::where('no_faktur', 'LIKE', $prefix . '%')
            ->orderBy('no_faktur', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->no_faktur, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $newNumber;
    }
}
