<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JurnalDetail extends Model
{
    protected $table = 'jurnal_detail';
    protected $fillable = [
        'jurnal_id',
        'coa_id',
        'debit',
        'kredit',
    ];

    public function jurnal(): BelongsTo
    {
        return $this->belongsTo(Jurnal::class, 'jurnal_id');
    }
}
