<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\DetailPembelian;
use App\Models\DetailPenjualan;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use App\Models\RiwayatStok;
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
        return DB::transaction(function () use ($data) {
            // Generate no faktur jika belum ada
            $noFaktur = $data['no_faktur'] ?? Penjualan::generateNoFaktur();
            
            // Hitung total kotor dari items
            $totalKotor = 0;
            foreach ($data['items'] as $item) {
                $totalKotor += $item['jumlah'] * $item['harga_satuan'];
            }
            
            // Hitung pajak dan total bayar
            $diskonTransaksi = $data['diskon_transaksi'] ?? 0;
            $pajak = $data['pajak'] ?? 0;
            $totalBayar = ($totalKotor - $diskonTransaksi) + $pajak;
            
            // Simpan penjualan header
            $penjualan = Penjualan::create([
                'no_faktur' => $noFaktur,
                'tanggal' => $data['tanggal'] ?? now(),
                'pelanggan_id' => $data['pelanggan_id'] ?? null,
                'user_id' => $data['user_id'],
                'total_kotor' => $totalKotor,
                'diskon_transaksi' => $diskonTransaksi,
                'pajak' => $pajak,
                'total_bayar' => $totalBayar,
                'metode_pembayaran' => $data['metode_pembayaran'] ?? 'tunai',
                'status' => $data['status'] ?? 'selesai',
            ]);
            
            // Simpan detail penjualan dan update stok
            foreach ($data['items'] as $item) {
                $subtotal = $item['jumlah'] * $item['harga_satuan'];
                
                // Simpan detail penjualan
                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'barang_id' => $item['barang_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal' => $subtotal,
                ]);
                
                // Update stok barang (kurangi)
                $barang = Barang::findOrFail($item['barang_id']);
                $stokSebelum = $barang->stok;
                $barang->kurangiStok($item['jumlah']);
                
                // Catat ke riwayat stok (kartu stok)
                RiwayatStok::create([
                    'tanggal' => $penjualan->tanggal,
                    'barang_id' => $item['barang_id'],
                    'jenis_transaksi' => 'penjualan',
                    'jumlah_masuk' => 0,
                    'jumlah_keluar' => $item['jumlah'],
                    'sisa_stok' => $barang->fresh()->stok,
                    'referensi_id' => 'PNJ-' . $penjualan->id,
                ]);
            }
            
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
                $barang = Barang::findOrFail($item['barang_id']);
                $stokSebelum = $barang->stok;
                $barang->tambahStok($item['jumlah']);
                
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
            
            return $pembelian->load('detailPembelian.barang', 'pemasok');
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
     * 
     * @param array $data
     * @return \App\Models\Retur
     */
    public function prosesReturPenjualan(array $data)
    {
        return DB::transaction(function () use ($data) {
            $retur = \App\Models\Retur::create([
                'tanggal' => $data['tanggal'] ?? now(),
                'jenis_retur' => 'retur_penjualan',
                'referensi_faktur' => $data['referensi_faktur'],
                'barang_id' => $data['barang_id'],
                'jumlah' => $data['jumlah'],
                'alasan' => $data['alasan'] ?? null,
                'kondisi_barang' => $data['kondisi_barang'] ?? 'bagus',
                'aksi_stok' => $data['aksi_stok'] ?? 'kembali_ke_stok',
                'nilai_pengembalian' => $data['nilai_pengembalian'] ?? 0,
            ]);
            
            // Jika barang dikembalikan ke stok
            if ($retur->shouldKembaliKeStok()) {
                $barang = Barang::findOrFail($data['barang_id']);
                $barang->tambahStok($data['jumlah']);
                
                // Catat ke riwayat stok
                RiwayatStok::create([
                    'tanggal' => $retur->tanggal,
                    'barang_id' => $data['barang_id'],
                    'jenis_transaksi' => 'retur_masuk',
                    'jumlah_masuk' => $data['jumlah'],
                    'jumlah_keluar' => 0,
                    'sisa_stok' => $barang->fresh()->stok,
                    'referensi_id' => 'RTR-' . $retur->id,
                ]);
            }
            
            return $retur;
        });
    }

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
            $barang = Barang::findOrFail($data['barang_id']);
            $barang->kurangiStok($data['jumlah']);
            
            // Catat ke riwayat stok
            RiwayatStok::create([
                'tanggal' => $retur->tanggal,
                'barang_id' => $data['barang_id'],
                'jenis_transaksi' => 'retur_keluar',
                'jumlah_masuk' => 0,
                'jumlah_keluar' => $data['jumlah'],
                'sisa_stok' => $barang->fresh()->stok,
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
            $barang = Barang::findOrFail($barangId);
            $stokSistem = $barang->stok;
            $selisih = $stokFisik - $stokSistem;
            
            // Update stok barang
            $barang->update(['stok' => $stokFisik]);
            
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
            'barang_hampir_habis' => Barang::hampirHabis()->count(),
            'barang_habis' => Barang::habis()->count(),
            'hutang_belum_lunas' => Pembelian::belumLunas()->sum('total_biaya'),
            'hutang_jatuh_tempo' => Pembelian::jatuhTempo()->sum('total_biaya'),
        ];
    }
}
