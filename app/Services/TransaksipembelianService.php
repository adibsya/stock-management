<?php

namespace App\Services;

use App\Models\PosMasterData;
use App\Models\Pembelian;
use App\Models\PembayaranPembelian;
use Illuminate\Support\Facades\DB;

class TransaksiPembelianService
{
    /**
     * JURNAL SAAT PEMBELIAN (AWAL)
     */
    public static function jurnalPembelian(Pembelian $pembelian): void
    {
        $akunPersediaan = PosMasterData::where('kode', '1-01-04')->firstOrFail();
        $akunKas        = PosMasterData::where('kode', '1-01-01')->firstOrFail();
        $akunHutang     = PosMasterData::where('kode', '2-01-01')->firstOrFail();

        $total = $pembelian->total_biaya;

        $entries = [
            [
                'coa_id' => $akunPersediaan->id,
                'debit'  => $total,
                'kredit' => 0,
            ],
        ];

        if ($pembelian->tipe_pembayaran === 'tunai') {
            $entries[] = [
                'coa_id' => $akunKas->id,
                'debit'  => 0,
                'kredit' => $total,
            ];
        } else {
            $entries[] = [
                'coa_id' => $akunHutang->id,
                'debit'  => 0,
                'kredit' => $total,
            ];
        }

        JurnalService::create(
            $pembelian->tanggal,
            'Pembelian ' . $pembelian->no_faktur,
            'pembelian',
            $pembelian->id,
            $entries
        );
    }

    /**
     * JURNAL PEMBAYARAN TERMIN PEMBELIAN
     */
    public static function bayarTerminPembelian(
    int $terminId,
    float $jumlahBayar
): void {
    if ($jumlahBayar <= 0) {
        throw new \Exception('Jumlah bayar tidak valid');
    }

    DB::transaction(function () use ($terminId, $jumlahBayar) {

        $termin = PembayaranPembelian::lockForUpdate()->findOrFail($terminId);

        $totalBayar = (float)$termin->jumlah_bayar + $jumlahBayar;

        $termin->update([
            'jumlah_bayar'  => $totalBayar,
            'tanggal_bayar' => now(),
            'status'        => $totalBayar >= $termin->jumlah ? 'lunas' : 'belum_lunas',
        ]);

        $akunHutang = PosMasterData::where('kode', '2-01-01')->firstOrFail();
        $akunKas    = PosMasterData::where('kode', '1-01-01')->firstOrFail();

        JurnalService::create(
            now(),
            'Pembayaran Hutang Pembelian',
            'pembayaran_pembelian',
            $termin->id,
            [
                [
                    'coa_id' => $akunHutang->id,
                    'debit'  => $jumlahBayar,
                    'kredit' => 0,
                ],
                [
                    'coa_id' => $akunKas->id,
                    'debit'  => 0,
                    'kredit' => $jumlahBayar,
                ],
            ]
        );
    });
}

}
