<?php

namespace App\Services;

use App\Models\Retur;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\StokBarang;
use App\Models\RiwayatStok;
use App\Models\PosMasterData;
use App\Services\JurnalService;
use Illuminate\Support\Facades\DB;

class ReturService
{
    public function returPenjualan(array $data)
    {
        return DB::transaction(function () use ($data) {

            // =========================
            // 1. AMBIL DATA
            // =========================
            $detail = DetailPenjualan::with('penjualan', 'barang')
                ->findOrFail($data['detail_penjualan_id']);

            $penjualan = $detail->penjualan;
            $qty = (int) $data['jumlah'];

            if ($qty > $detail->jumlah) {
                throw new \Exception('Jumlah retur melebihi jumlah pembelian');
            }

            $nilaiRetur = $qty * $detail->harga_satuan;

            // =========================
            // 2. SIMPAN RETUR
            // =========================
            $retur = Retur::create([
                'tanggal' => $data['tanggal'],
                'referensi_faktur' => $penjualan->no_faktur,
                'penjualan_id' => $penjualan->id,
                'detail_penjualan_id' => $detail->id,
                'barang_id' => $detail->barang_id,
                'jumlah' => $qty,
                'kondisi_barang' => $data['kondisi_barang'],
                'alasan' => $data['alasan'] ?? null,
                'nilai_retur' => $nilaiRetur,
            ]);

            // =========================
            // 3. STOK KELUAR (BARANG PENGGANTI)
            // =========================
            $stok = StokBarang::where('barang_master_id', $detail->barang_id)
                ->where('gudang_id', $detail->gudang_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($stok->jumlah < $qty) {
                throw new \Exception('Stok tidak cukup untuk penggantian');
            }

            $stok->decrement('jumlah', $qty);

            RiwayatStok::create([
                'tanggal' => $retur->tanggal,
                'barang_id' => $detail->barang_id,
                'jenis_transaksi' => 'retur_pengganti',
                'jumlah_masuk' => 0,
                'jumlah_keluar' => $qty,
                'sisa_stok' => $stok->jumlah,
                'referensi_id' => 'RTR-' . $retur->id,
            ]);

            // =========================
            // 4. JURNAL BEBAN RETUR
            // =========================
            $hpp = $detail->barang->harga_beli * $qty;

            $akunBebanRetur = PosMasterData::where('kode', '6-01-02')->firstOrFail();
            $akunPersediaan = PosMasterData::where('kode', '1-01-04')->firstOrFail();

            app(JurnalService::class)->create(
                $retur->tanggal,
                'Retur Penjualan (Barang Pengganti)',
                'retur_penjualan',
                $retur->id,
                [
                    ['coa_id' => $akunBebanRetur->id, 'debit' => $hpp],
                    ['coa_id' => $akunPersediaan->id, 'kredit' => $hpp],
                ]
            );

            return $retur;
        });
    }
}
