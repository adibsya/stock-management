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
        'gudang_id',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah_biaya' => 'integer',
    ];

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
