<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jurnal extends Model
{
    protected $table = 'jurnal';
    protected $fillable = [
        'tanggal',
        'kode',
        'keterangan',
        'sumber',
        'ref_id',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(JurnalDetail::class, 'jurnal_id');
    }
}
