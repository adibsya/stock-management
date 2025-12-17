<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran';

    protected $fillable = [
        'tanggal',
        'jenis_pengeluaran',
        'jumlah_biaya',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah_biaya' => 'decimal:2',
    ];

    /**
     * Scope: Filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    /**
     * Scope: Filter by jenis pengeluaran
     */
    public function scopeJenis($query, string $jenis)
    {
        return $query->where('jenis_pengeluaran', $jenis);
    }
}
