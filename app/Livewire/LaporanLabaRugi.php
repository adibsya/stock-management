<?php

namespace App\Livewire;

use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use Livewire\Component;
use Carbon\Carbon;

class LaporanLabaRugi extends Component
{
    public string $startDate = '';
    public string $endDate = '';
    public string $periode = 'bulan_ini';

    public function mount(): void
    {
        $this->setPeriode('bulan_ini');
    }

    public function setPeriode(string $periode): void
    {
        $this->periode = $periode;

        switch ($periode) {
            case 'hari_ini':
                $this->startDate = now()->format('Y-m-d');
                $this->endDate = now()->format('Y-m-d');
                break;
            case 'minggu_ini':
                $this->startDate = now()->startOfWeek()->format('Y-m-d');
                $this->endDate = now()->endOfWeek()->format('Y-m-d');
                break;
            case 'bulan_ini':
                $this->startDate = now()->startOfMonth()->format('Y-m-d');
                $this->endDate = now()->endOfMonth()->format('Y-m-d');
                break;
            case 'tahun_ini':
                $this->startDate = now()->startOfYear()->format('Y-m-d');
                $this->endDate = now()->endOfYear()->format('Y-m-d');
                break;
        }
    }

    public function render()
    {
        // Pendapatan dari penjualan
        $penjualanQuery = Penjualan::query()
            ->where('status', 'selesai')
            ->whereDate('tanggal', '>=', $this->startDate)
            ->whereDate('tanggal', '<=', $this->endDate);

        $totalPenjualan = $penjualanQuery->sum('total_bayar');
        $totalDiskon = $penjualanQuery->sum('diskon_transaksi');
        $pendapatanKotor = $penjualanQuery->sum('total_kotor');

        // HPP dari pembelian (perkiraan berdasarkan barang terjual)
        $hpp = Penjualan::query()
            ->where('status', 'selesai')
            ->whereDate('tanggal', '>=', $this->startDate)
            ->whereDate('tanggal', '<=', $this->endDate)
            ->with('detailPenjualan.barang')
            ->get()
            ->flatMap->detailPenjualan
            ->sum(function ($detail) {
                return $detail->jumlah * ($detail->barang->harga_beli ?? 0);
            });

        // Pengeluaran operasional
        $totalPengeluaran = Pengeluaran::query()
            ->whereDate('tanggal', '>=', $this->startDate)
            ->whereDate('tanggal', '<=', $this->endDate)
            ->sum('jumlah_biaya');

        // Pembelian barang
        $totalPembelian = Pembelian::query()
            ->whereDate('tanggal', '>=', $this->startDate)
            ->whereDate('tanggal', '<=', $this->endDate)
            ->sum('total_biaya');

        // Kalkulasi
        $labaKotor = $totalPenjualan - $hpp;
        $labaBersih = $labaKotor - $totalPengeluaran;

        // Detail pengeluaran per kategori
        $pengeluaranPerKategori = Pengeluaran::query()
            ->whereDate('tanggal', '>=', $this->startDate)
            ->whereDate('tanggal', '<=', $this->endDate)
            ->selectRaw('jenis_pengeluaran as kategori, SUM(jumlah_biaya) as total')
            ->groupBy('jenis_pengeluaran')
            ->get();

        return view('livewire.laporan-laba-rugi', [
            'totalPenjualan' => $totalPenjualan,
            'totalDiskon' => $totalDiskon,
            'pendapatanKotor' => $pendapatanKotor,
            'hpp' => $hpp,
            'labaKotor' => $labaKotor,
            'totalPengeluaran' => $totalPengeluaran,
            'totalPembelian' => $totalPembelian,
            'labaBersih' => $labaBersih,
            'pengeluaranPerKategori' => $pengeluaranPerKategori,
        ]);
    }
}
