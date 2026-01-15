<?php

namespace App\Services;

use App\Models\Jurnal;
use App\Models\JurnalDetail;
use App\Models\PosMasterData;
use Illuminate\Support\Facades\DB;


class JurnalService
{
    public static function create($tanggal, $keterangan, $sumber, $refId, $entries)
    {
        DB::transaction(function () use ($tanggal, $keterangan, $sumber, $refId, $entries) {
            \Log::debug('JurnalService::create dipanggil', compact('tanggal', 'keterangan', 'sumber', 'refId', 'entries'));

            $jurnal = Jurnal::create([
                'tanggal'    => $tanggal,
                'kode'       => self::generateKode(),
                'keterangan' => $keterangan,
                'sumber'     => $sumber,
                'ref_id'     => $refId,
            ]);
            \Log::debug('Jurnal berhasil dibuat', ['jurnal' => $jurnal]);

            foreach ($entries as $row) {
                try {
                    $detail = JurnalDetail::create([
                        'jurnal_id' => $jurnal->id,
                        'coa_id'    => $row['coa_id'],
                        'debit'     => $row['debit'] ?? 0,
                        'kredit'    => $row['kredit'] ?? 0,
                    ]);
                    \Log::debug('JurnalDetail berhasil dibuat', ['detail' => $detail]);
                } catch (\Exception $e) {
                    \Log::error('Gagal membuat JurnalDetail', [
                        'error' => $e->getMessage(),
                        'data' => $row,
                        'jurnal_id' => $jurnal->id
                    ]);
                }
            }
        });
    }

    private static function generateKode()
    {
        $prefix = 'JR-' . now()->format('Ym');
        $last = Jurnal::where('kode','like',"$prefix%")->count() + 1;
        return $prefix . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }
}
