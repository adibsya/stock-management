<?php

namespace App\Services;

use App\Models\BarangMaster;
use App\Models\DetailPembelian;
use App\Models\DetailPenjualan;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use App\Models\RiwayatStok;
use App\Models\PosMasterData;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiService
{
    /**
     * Simpan transaksi penjualan dengan detail items
     * 
     * @param array $data
     * @return Penjualan
     * @throws \Exception
     */
    public function simpanPenjualan(array $data): Penjualan
{
    \Log::debug('MASUK simpanPenjualan');

    if (empty($data['items']) || !is_array($data['items'])) {
        throw new \Exception('Item penjualan kosong');
    }

    return DB::transaction(function () use ($data) {

        // =========================
        // 1. VALIDASI STOK
        // =========================
        foreach ($data['items'] as $item) {
            $bonus = $item['bonus'] ?? 0;
            $totalKeluar = $item['jumlah'] + $bonus;

            $stok = \App\Models\StokBarang::where('barang_master_id', $item['barang_id'])
                ->where('gudang_id', $data['gudang_id'])
                ->first();

            if (!$stok || $stok->jumlah < $totalKeluar) {
                throw new \Exception('Stok tidak mencukupi');
            }
        }

        // =========================
        // 2. HITUNG TOTAL
        // =========================
        $totalKotor = collect($data['items'])->sum(function ($i) {
            return $i['subtotal'] ?? ($i['jumlah'] * $i['harga_satuan']);
        });

        $diskon = $data['diskon_transaksi'] ?? 0;
        $pajak = $data['pajak'] ?? 0;
        $totalBayar = ($totalKotor - $diskon) + $pajak;

        // =========================
        // 3. SIMPAN PENJUALAN
        // =========================
        $penjualan = Penjualan::create([
            'no_faktur' => $data['no_faktur'] ?? Penjualan::generateNoFaktur(),
            'tanggal' => $data['tanggal'] ?? now(),
            'pelanggan_id' => $data['pelanggan_id'] ?? null,
            'user_id' => $data['user_id'],
            'gudang_id' => $data['gudang_id'],
            'total_kotor' => $totalKotor,
            'diskon_transaksi' => $diskon,
            'pajak' => $pajak,
            'total_bayar' => $totalBayar,
            'mode_termin' => $data['mode_termin'] ?? 'cash',
            'status' => 'selesai',
        ]);

        // =========================
        // 4. DETAIL + STOK + KARTU
        // =========================
        $hpp = 0;

        foreach ($data['items'] as $item) {

            $barang = BarangMaster::findOrFail($item['barang_id']);
            $bonus = $item['bonus'] ?? 0;
            $totalKeluar = $item['jumlah'] + $bonus;

            DetailPenjualan::create([
                'penjualan_id' => $penjualan->id,
                'barang_id' => $barang->id,
                'gudang_id' => $penjualan->gudang_id,
                'jumlah' => $item['jumlah'],
                'bonus' => $bonus,
                'harga_satuan' => $item['harga_satuan'],
                'subtotal' => $item['subtotal'] ?? ($item['jumlah'] * $item['harga_satuan']),
            ]);

            // stok
            $stok = \App\Models\StokBarang::where('barang_master_id', $barang->id)
                ->where('gudang_id', $penjualan->gudang_id)
                ->first();

            $stok->decrement('jumlah', $totalKeluar);

            RiwayatStok::create([
                'tanggal' => $penjualan->tanggal,
                'barang_id' => $barang->id,
                'jenis_transaksi' => 'penjualan',
                'jumlah_masuk' => 0,
                'jumlah_keluar' => $totalKeluar,
                'sisa_stok' => $stok->jumlah,
                'referensi_id' => 'PNJ-' . $penjualan->id,
            ]);

            // HPP
            $hpp += $totalKeluar * $barang->harga_beli;
        }

        // =========================
        // 5. JURNAL PENJUALAN (optional - skip if COA not found)
        // =========================
        $akunPendapatan = PosMasterData::where('kode', '4-01-01')->first();
        $akunKas = PosMasterData::where('kode', '1-01-01')->first();
        $akunPiutang = PosMasterData::where('kode', '1-01-02')->first();

        if ($penjualan->mode_termin === 'termin') {
            $debitAkun = $akunPiutang;
        } else {
            $debitAkun = $akunKas;
        }

        // Only create jurnal if all COA accounts exist
        if ($debitAkun && $akunPendapatan) {
            app(\App\Services\JurnalService::class)->create(
                $penjualan->tanggal,
                'Penjualan ' . $penjualan->no_faktur,
                'penjualan',
                $penjualan->id,
                [
                    ['coa_id' => $debitAkun->id, 'debit' => $totalBayar],
                    ['coa_id' => $akunPendapatan->id, 'kredit' => $totalBayar],
                ]
            );
        }

        // =========================
        // 6. JURNAL HPP (optional - skip if COA not found)
        // =========================
        $akunHpp = PosMasterData::where('kode', '5-01-01')->first();
        $akunPersediaan = PosMasterData::where('kode', '1-01-04')->first();

<<<<<<< HEAD
        app(\App\Services\JurnalService::class)->create(
            $penjualan->tanggal,
            'HPP' . $penjualan->no_faktur,
            'penjualan',
            $penjualan->id,
            [
                ['coa_id' => $akunHpp->id, 'debit' => $hpp],
                ['coa_id' => $akunPersediaan->id, 'kredit' => $hpp],
            ]
        );
=======
        // Only create HPP jurnal if all COA accounts exist
        if ($akunHpp && $akunPersediaan) {
            app(\App\Services\JurnalService::class)->create(
                $penjualan->tanggal,
                'HPP Penjualan ' . $penjualan->no_faktur,
                'penjualan',
                $penjualan->id,
                [
                    ['coa_id' => $akunHpp->id, 'debit' => $hpp],
                    ['coa_id' => $akunPersediaan->id, 'kredit' => $hpp],
                ]
            );
        }
>>>>>>> e97b32de2a5bbb8ee4d4f821673fb41ae8e466f1

        return $penjualan->load('detailPenjualan.barang');
    });
}


    /**
     * Simpan transaksi pembelian/restock dengan detail items
     * 
     * @param array $data
     * @return Pembelian
     * @throws \Exception
     */
    public function simpanPembelian(array $data): Pembelian
    {
        \Log::debug('TransaksiService::simpanPembelian dipanggil', $data);
        return DB::transaction(function () use ($data) {
            // Hitung total biaya dari items
            $totalBiaya = 0;
            foreach ($data['items'] as $item) {
                $totalBiaya += $item['jumlah'] * $item['harga_beli'];
            }
            
            // Tentukan jatuh tempo jika belum lunas
            $jatuhTempo = null;
            $statusBayar = $data['status_bayar'] ?? 'lunas';
            
            if ($statusBayar === 'belum_lunas') {
                // Jika belum lunas, wajib ada jatuh tempo
                $jatuhTempo = $data['jatuh_tempo'] ?? now()->addDays(30);
            }
            
            // Simpan pembelian header
            $pembelian = Pembelian::create([
                'no_faktur_supplier' => $data['no_faktur_supplier'],
                'tanggal' => $data['tanggal'] ?? now(),
                'pemasok_id' => $data['pemasok_id'],
                'total_biaya' => $totalBiaya,
                'jatuh_tempo' => $jatuhTempo,
                'status_bayar' => $statusBayar,
            ]);
            
            // Simpan detail pembelian dan update stok
            foreach ($data['items'] as $item) {
                $total = $item['jumlah'] * $item['harga_beli'];
                
                // Simpan detail pembelian
                DetailPembelian::create([
                    'pembelian_id' => $pembelian->id,
                    'barang_id' => $item['barang_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_beli' => $item['harga_beli'],
                    'total' => $total,
                ]);
                
                // Update stok barang (tambah)
                $barang = BarangMaster::findOrFail($item['barang_id']);
                // Update stok barang di tabel stok_barang
                $stok = \App\Models\StokBarang::where('barang_master_id', $item['barang_id'])
                    ->where('gudang_id', $item['gudang_id'] ?? $pembelian->gudang_id)
                    ->first();
                $stokSebelum = $stok ? $stok->jumlah : 0;
                if ($stok) {
                    $stok->jumlah += $item['jumlah'];
                    $stok->save();
                }
                
                // Update harga beli barang (optional: mengikuti harga beli terakhir)
                if (isset($item['update_harga_beli']) && $item['update_harga_beli']) {
                    $barang->update(['harga_beli' => $item['harga_beli']]);
                }
                
                // Catat ke riwayat stok (kartu stok)
                RiwayatStok::create([
                    'tanggal' => $pembelian->tanggal,
                    'barang_id' => $item['barang_id'],
                    'jenis_transaksi' => 'pembelian',
                    'jumlah_masuk' => $item['jumlah'],
                    'jumlah_keluar' => 0,
                    'sisa_stok' => $barang->fresh()->stok,
                    'referensi_id' => 'PBL-' . $pembelian->id,
                ]);
            }
            
            // JURNAL OTOMATIS: Pembelian
            if (($data['mode_termin'] ?? 'cash') === 'termin') {
                // Pembelian Kredit (Termin): Persediaan & Hutang Usaha
                $hutang = PosMasterData::where('kode', '2-01-01')->where('level', 2)->first(); // Hutang Usaha
                $persediaan = PosMasterData::where('kode', '1-01-04')->where('level', 2)->first(); // Persediaan Barang
                \Log::debug('Cek jurnal pembelian termin', [
                    'hutang' => $hutang,
                    'persediaan' => $persediaan,
                    'pembelian_id' => $pembelian->id,
                    'total_biaya' => $pembelian->total_biaya,
                ]);
                if ($hutang && $persediaan) {
                    \Log::debug('Memanggil JurnalService::create (pembelian termin)', [
                        'tanggal' => $pembelian->tanggal,
                        'no_faktur' => $pembelian->no_faktur_supplier,
                        'pembelian_id' => $pembelian->id,
                        'hutang_id' => $hutang->id,
                        'persediaan_id' => $persediaan->id,
                        'total_biaya' => $pembelian->total_biaya,
                    ]);
                    \App\Services\JurnalService::create(
                        $pembelian->tanggal,
                        'Pembelian Termin ' . $pembelian->no_faktur_supplier,
                        'pembelian',
                        $pembelian->id,
                        [
                            ['coa_id' => $persediaan->id, 'debit' => $pembelian->total_biaya],
                            ['coa_id' => $hutang->id, 'kredit' => $pembelian->total_biaya],
                        ]
                    );
                } else {
                    \Log::error('GAGAL JURNAL PEMBELIAN TERMIN: COA tidak ditemukan', [
                        'hutang' => $hutang,
                        'persediaan' => $persediaan,
                        'pembelian_id' => $pembelian->id,
                    ]);
                }
            } else {
                // Pembelian Tunai: Persediaan & Kas/Bank
                $kasbank = PosMasterData::where('kode', '1-01-01')->where('level', 2)->first(); // Kas dan Bank
                $persediaan = PosMasterData::where('kode', '1-01-04')->where('level', 2)->first(); // Persediaan Barang
                \Log::debug('Cek jurnal pembelian tunai', [
                    'kasbank' => $kasbank,
                    'persediaan' => $persediaan,
                    'pembelian_id' => $pembelian->id,
                    'total_biaya' => $pembelian->total_biaya,
                ]);
                if ($kasbank && $persediaan) {
                    \Log::debug('Memanggil JurnalService::create (pembelian tunai)', [
                        'tanggal' => $pembelian->tanggal,
                        'no_faktur' => $pembelian->no_faktur_supplier,
                        'pembelian_id' => $pembelian->id,
                        'kasbank_id' => $kasbank->id,
                        'persediaan_id' => $persediaan->id,
                        'total_biaya' => $pembelian->total_biaya,
                    ]);
                    \App\Services\JurnalService::create(
                        $pembelian->tanggal,
                        'Pembelian Tunai ' . $pembelian->no_faktur_supplier,
                        'pembelian',
                        $pembelian->id,
                        [
                            ['coa_id' => $persediaan->id, 'debit' => $pembelian->total_biaya],
                            ['coa_id' => $kasbank->id, 'kredit' => $pembelian->total_biaya],
                        ]
                    );
                } else {
                    \Log::error('GAGAL JURNAL PEMBELIAN TUNAI: COA tidak ditemukan', [
                        'kasbank' => $kasbank,
                        'persediaan' => $persediaan,
                        'pembelian_id' => $pembelian->id,
                    ]);
                }
            }
            return $pembelian->load('detailPembelian.barang', 'pemasok');
        });
    }

  public function bayarTerminPenjualan(int $terminId, float $jumlahBayar)
{
    return DB::transaction(function () use ($terminId, $jumlahBayar) {

        // 🔒 Lock termin
        $termin = \App\Models\PembayaranPenjualan::lockForUpdate()
            ->with('penjualan')
            ->findOrFail($terminId);

        $penjualan = $termin->penjualan;

        // =========================
        // 1. UPDATE TERMIN
        // =========================
        $totalBayar = ($termin->jumlah_bayar ?? 0) + $jumlahBayar;

        $termin->update([
            'jumlah_bayar' => $totalBayar,
            'pembayaran_terakhir' => $jumlahBayar,
            'tanggal_bayar' => now(),
            'status' => $totalBayar >= $termin->jumlah ? 'lunas' : 'belum_lunas',
        ]);

        // =========================
        // 2. UPDATE STATUS PENJUALAN
        // =========================
        $totalTerbayar = \App\Models\PembayaranPenjualan::where('penjualan_id', $penjualan->id)
            ->sum('jumlah_bayar');

        if ($totalTerbayar >= $penjualan->total_bayar) {
            $penjualan->update(['status_bayar' => 'lunas']);
        }

        // =========================
        // 3. JURNAL PEMBAYARAN TERMIN
        // =========================
        $kas = PosMasterData::where('kode', '1-01-01')->first();
        $piutang = PosMasterData::where('kode', '1-01-02')->first();

        if (!$kas || !$piutang) {
            throw new \Exception('COA Kas / Piutang belum diset');
        }

        \App\Services\JurnalService::create(
            now(),
            'Pembayaran Termin Penjualan ' . $penjualan->no_faktur,
            'pembayaran_penjualan',
            $termin->id,
            [
                ['coa_id' => $kas->id, 'debit' => $jumlahBayar],
                ['coa_id' => $piutang->id, 'kredit' => $jumlahBayar],
            ]
        );

        return $termin;
    });
}

public function pengeluaranOperasional(array $data): Pengeluaran
{
    return DB::transaction(function () use ($data) {

        // =========================
        // 1. SIMPAN PENGELUARAN
        // =========================
        $pengeluaran = Pengeluaran::create([
            'tanggal' => $data['tanggal'],
            'jenis_pengeluaran' => $data['jenis_pengeluaran'],
            'jumlah_biaya' => $data['jumlah_biaya'],
            'keterangan' => $data['keterangan'] ?? null,
            'gudang_id' => $data['gudang_id'],
            'user_id' => $data['user_id'],
        ]);

        // =========================
        // 2. AMBIL AKUN COA
        // =========================
        $beban = PosMasterData::where('kode', '6-01-01')->first(); // Beban Operasional
        $kas   = PosMasterData::where('kode', '1-01-01')->first(); // Kas / Bank

        if (!$beban || !$kas) {
            throw new \Exception('COA Beban Operasional / Kas belum disetting');
        }

        // =========================
        // 3. JURNAL OTOMATIS
        // =========================
        app(\App\Services\JurnalService::class)->create(
            $pengeluaran->tanggal,
            'Pengeluaran Operasional - ' . $pengeluaran->jenis_pengeluaran,
            'pengeluaran',
            $pengeluaran->id,
            [
                ['coa_id' => $beban->id, 'debit' => $pengeluaran->jumlah_biaya],
                ['coa_id' => $kas->id, 'kredit' => $pengeluaran->jumlah_biaya],
            ]
        );

        return $pengeluaran;
    });
}


    /**
     * Generate laporan laba rugi
     * 
     * @param string|Carbon $startDate
     * @param string|Carbon $endDate
     * @return array
     */
    public function laporanLabaRugi($startDate, $endDate): array
    {
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();
        
        // 1. Hitung Total Penjualan (Omzet)
        $totalOmzet = Penjualan::where('status', 'selesai')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->sum('total_bayar');
        
        // 2. Hitung HPP (Harga Pokok Penjualan)
        // HPP = Jumlah barang terjual × Harga beli masing-masing barang
        $hpp = $this->hitungHPP($startDate, $endDate);
        
        // 3. Hitung Laba Kotor
        $labaKotor = $totalOmzet - $hpp;
        
        // 4. Hitung Total Pengeluaran Operasional
        $totalPengeluaran = Pengeluaran::whereBetween('tanggal', [$startDate, $endDate])
            ->sum('jumlah_biaya');
        
        // 5. Hitung Laba Bersih
        $labaBersih = $labaKotor - $totalPengeluaran;
        
        // 6. Data tambahan untuk detail laporan
        $totalDiskon = Penjualan::where('status', 'selesai')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->sum('diskon_transaksi');
        
        $totalPajak = Penjualan::where('status', 'selesai')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->sum('pajak');
        
        $jumlahTransaksi = Penjualan::where('status', 'selesai')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->count();
        
        // Detail pengeluaran per jenis
        $pengeluaranPerJenis = Pengeluaran::whereBetween('tanggal', [$startDate, $endDate])
            ->selectRaw('jenis_pengeluaran, SUM(jumlah_biaya) as total')
            ->groupBy('jenis_pengeluaran')
            ->pluck('total', 'jenis_pengeluaran')
            ->toArray();
        
        return [
            'periode' => [
                'dari' => $startDate->format('Y-m-d'),
                'sampai' => $endDate->format('Y-m-d'),
            ],
            'penjualan' => [
                'total_omzet' => $totalOmzet,
                'total_diskon' => $totalDiskon,
                'total_pajak' => $totalPajak,
                'jumlah_transaksi' => $jumlahTransaksi,
            ],
            'hpp' => $hpp,
            'laba_kotor' => $labaKotor,
            'pengeluaran' => [
                'total' => $totalPengeluaran,
                'per_jenis' => $pengeluaranPerJenis,
            ],
            'laba_bersih' => $labaBersih,
            'margin_laba_kotor' => $totalOmzet > 0 ? round(($labaKotor / $totalOmzet) * 100, 2) : 0,
            'margin_laba_bersih' => $totalOmzet > 0 ? round(($labaBersih / $totalOmzet) * 100, 2) : 0,
        ];
    }

    /**
     * Hitung Harga Pokok Penjualan (HPP)
     * 
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return float
     */
    protected function hitungHPP(Carbon $startDate, Carbon $endDate): float
    {
        // Ambil semua detail penjualan dalam periode
        $detailPenjualan = DetailPenjualan::whereHas('penjualan', function ($query) use ($startDate, $endDate) {
            $query->where('status', 'selesai')
                  ->whereBetween('tanggal', [$startDate, $endDate]);
        })
        ->with('barang')
        ->get();
        
        $hpp = 0;
        
        foreach ($detailPenjualan as $detail) {
            // HPP = jumlah terjual × harga beli
            $hpp += $detail->jumlah * $detail->barang->harga_beli;
        }
        
        return $hpp;
    }

    /**
     * Proses retur penjualan
     * Barang rusak tidak dikembalikan ke stok, customer ambil barang pengganti dari stok
     * 
     * @param array $data
     * @return array
     */
<<<<<<< HEAD
=======
    public function prosesReturPenjualan(array $data): array
    {
        // Cek stok terlebih dahulu sebelum membuat retur
        $stok = \App\Models\StokBarang::where('barang_master_id', $data['barang_id'])
            ->where('gudang_id', $data['gudang_id'])
            ->first();
        
        if (!$stok) {
            return [
                'success' => false,
                'message' => 'Stok barang tidak ditemukan di gudang ini!'
            ];
        }
        
        if ($stok->jumlah < $data['jumlah']) {
            return [
                'success' => false,
                'message' => 'Stok barang pengganti tidak mencukupi! Tersedia: ' . $stok->jumlah
            ];
        }

        return DB::transaction(function () use ($data, $stok) {
            $retur = \App\Models\Retur::create([
                'tanggal' => $data['tanggal'] ?? now(),
                'jenis_retur' => 'retur_penjualan',
                'referensi_faktur' => $data['referensi_faktur'],
                'barang_id' => $data['barang_id'],
                'jumlah' => $data['jumlah'],
                'alasan' => $data['alasan'] ?? null,
                'kondisi_barang' => $data['kondisi_barang'] ?? 'rusak',
                'aksi_stok' => 'buang', // Barang rusak dibuang, tidak kembali ke stok
                'nilai_pengembalian' => 0, // Tidak ada pengembalian uang, ambil barang pengganti
            ]);
            
            $stok->jumlah -= $data['jumlah']; // Kurangi stok untuk barang pengganti
            $stok->save();
            
            // Catat ke riwayat stok (pengambilan barang pengganti)
            RiwayatStok::create([
                'tanggal' => $retur->tanggal,
                'barang_id' => $data['barang_id'],
                'jenis_transaksi' => 'retur_keluar', // Keluar karena ambil barang pengganti
                'jumlah_masuk' => 0,
                'jumlah_keluar' => $data['jumlah'],
                'sisa_stok' => $stok->jumlah,
                'referensi_id' => 'RTR-' . $retur->id,
            ]);
            
            return [
                'success' => true,
                'data' => $retur,
                'message' => 'Retur berhasil diproses'
            ];
        });
    }
>>>>>>> e97b32de2a5bbb8ee4d4f821673fb41ae8e466f1

    /**
     * Proses retur pembelian
     * 
     * @param array $data
     * @return \App\Models\Retur
     */
    public function prosesReturPembelian(array $data)
    {
        return DB::transaction(function () use ($data) {
            $retur = \App\Models\Retur::create([
                'tanggal' => $data['tanggal'] ?? now(),
                'jenis_retur' => 'retur_pembelian',
                'referensi_faktur' => $data['referensi_faktur'],
                'barang_id' => $data['barang_id'],
                'jumlah' => $data['jumlah'],
                'alasan' => $data['alasan'] ?? null,
                'kondisi_barang' => $data['kondisi_barang'] ?? 'rusak',
                'aksi_stok' => $data['aksi_stok'] ?? 'buang',
                'nilai_pengembalian' => $data['nilai_pengembalian'] ?? 0,
            ]);
            
            // Kurangi stok karena barang dikembalikan ke supplier
            $barang = BarangMaster::findOrFail($data['barang_id']);
            $stok = \App\Models\StokBarang::where('barang_master_id', $data['barang_id'])
                ->where('gudang_id', $data['gudang_id'] ?? 1)
                ->first();
            if ($stok) {
                $stok->decrement('jumlah', $data['jumlah']);
            }
            
            // Catat ke riwayat stok
            RiwayatStok::create([
                'tanggal' => $retur->tanggal,
                'barang_id' => $data['barang_id'],
                'jenis_transaksi' => 'retur_keluar',
                'jumlah_masuk' => 0,
                'jumlah_keluar' => $data['jumlah'],
                'sisa_stok' => $stok ? $stok->jumlah : 0,
                'referensi_id' => 'RTR-' . $retur->id,
            ]);
            
            return $retur;
        });
    }


    /**
     * Stock Opname - Sesuaikan stok dengan stok fisik
     * 
     * @param int $barangId
     * @param int $stokFisik
     * @param string|null $keterangan
     * @return RiwayatStok
     */
    public function stockOpname(int $barangId, int $stokFisik, ?string $keterangan = null): RiwayatStok
    {
        return DB::transaction(function () use ($barangId, $stokFisik, $keterangan) {
            $barang = BarangMaster::findOrFail($barangId);
            $stok = \App\Models\StokBarang::where('barang_master_id', $barangId)->first();
            $stokSistem = $stok ? $stok->jumlah : 0;
            $selisih = $stokFisik - $stokSistem;
            
            // Update stok barang
            if ($stok) {
                $stok->update(['jumlah' => $stokFisik]);
            }
            
            // Catat ke riwayat stok
            return RiwayatStok::create([
                'tanggal' => now(),
                'barang_id' => $barangId,
                'jenis_transaksi' => 'opname',
                'jumlah_masuk' => $selisih > 0 ? $selisih : 0,
                'jumlah_keluar' => $selisih < 0 ? abs($selisih) : 0,
                'sisa_stok' => $stokFisik,
                'referensi_id' => 'OPN-' . now()->format('YmdHis'),
            ]);
        });
    }

    /**
     * Get hutang supplier yang belum lunas
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getHutangSupplier()
    {
        return Pembelian::with('pemasok')
            ->belumLunas()
            ->orderBy('jatuh_tempo', 'asc')
            ->get();
    }

    /**
     * Get hutang supplier yang sudah jatuh tempo
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getHutangJatuhTempo()
    {
        return Pembelian::with('pemasok')
            ->jatuhTempo()
            ->orderBy('jatuh_tempo', 'asc')
            ->get();
    }

    /**
     * Lunasi hutang pembelian
     * 
     * @param int $pembelianId
     * @return Pembelian
     */
    public function lunasiHutang(int $pembelianId): Pembelian
    {
        $pembelian = Pembelian::findOrFail($pembelianId);
        $pembelian->update([
            'status_bayar' => 'lunas',
            'jatuh_tempo' => null,
        ]);
        
        return $pembelian;
    }

    /**
     * Get ringkasan dashboard
     * 
     * @return array
     */ 
    public function getDashboardSummary(): array
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();
        
        return [
            'penjualan_hari_ini' => Penjualan::where('status', 'selesai')
                ->whereDate('tanggal', $today)
                ->sum('total_bayar'),
            'penjualan_bulan_ini' => Penjualan::where('status', 'selesai')
                ->whereBetween('tanggal', [$thisMonth, now()])
                ->sum('total_bayar'),
            'jumlah_transaksi_hari_ini' => Penjualan::where('status', 'selesai')
                ->whereDate('tanggal', $today)
                ->count(),
            'barang_hampir_habis' => \App\Models\StokBarang::where('jumlah', '<=', 10)->where('jumlah', '>', 0)->count(),
            'barang_habis' => \App\Models\StokBarang::where('jumlah', '<=', 0)->count(),
            'hutang_belum_lunas' => Pembelian::belumLunas()->sum('total_biaya'),
            'hutang_jatuh_tempo' => Pembelian::jatuhTempo()->sum('total_biaya'),
        ];
    }
}
