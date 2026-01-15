<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosMasterData extends Model
{
    protected $table = 'pos_master_data';

    protected $fillable = [
        'parent_id',
        'kode',
        'nama',
        'kategori',
        'sub_kategori',
        'normal_saldo',
        'level',
        'urutan',
        'is_active',
    ];

    // =========================
    // RELATION
    // =========================

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->orderBy('urutan');
    }
}
