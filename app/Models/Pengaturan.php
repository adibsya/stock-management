<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';

    protected $fillable = [
        'kunci',
        'nilai',
    ];

    /**
     * Get setting value by key
     */
    public static function getValue(string $kunci, $default = null): ?string
    {
        $setting = static::where('kunci', $kunci)->first();
        return $setting?->nilai ?? $default;
    }

    /**
     * Set setting value by key
     */
    public static function setValue(string $kunci, ?string $nilai): void
    {
        static::updateOrCreate(
            ['kunci' => $kunci],
            ['nilai' => $nilai]
        );
    }
}
