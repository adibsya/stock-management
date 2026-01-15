<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Neraca extends Model
{
    protected $table = 'neraca';
    protected $fillable = ['pos_id', 'tanggal', 'jumlah'];

    public function pos()
    {
        return $this->belongsTo(PosMasterData::class, 'pos_id');
    }
}
