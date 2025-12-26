<?php

namespace App\Http\Controllers;

use App\Models\BarangMaster;
use App\Models\Pelanggan;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Statistik hari ini
        $penjualanHariIni = Penjualan::whereDate('tanggal', $today)
            ->where('status', 'selesai')
            ->sum('total_bayar');

        $transaksiHariIni = Penjualan::whereDate('tanggal', $today)
            ->where('status', 'selesai')
            ->count();

        // Statistik bulan ini
        $penjualanBulanIni = Penjualan::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->where('status', 'selesai')
            ->sum('total_bayar');

        $pembelianBulanIni = Pembelian::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->sum('total_biaya');

        $pengeluaranBulanIni = Pengeluaran::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->sum('jumlah_biaya');

        // Barang hampir habis
        //$barangHampirHabis = BarangMaster::hampirHabis()->count();
        
        // Barang habis
        //$barangHabis = BarangMaster::habis()->count();

        // Total pelanggan
        $totalPelanggan = Pelanggan::count();

        // Total barang
        $totalBarang = BarangMaster::count();

        // Penjualan terbaru
        $penjualanTerbaru = Penjualan::with(['pelanggan', 'user'])
            ->where('status', 'selesai')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Barang terlaris bulan ini
        //$barangTerlaris = BarangMaster::withSum(['detailPenjualan as total_terjual' => function ($query) use ($startOfMonth, $endOfMonth) {
            //$query->whereHas('penjualan', function ($q) use ($startOfMonth, $endOfMonth) {
                //$q->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                   // ->where('status', 'selesai');
            //});
        //}], 'jumlah')
            //->orderByDesc('total_terjual')
            //->limit(5)
            //->get();

        // Grafik penjualan 7 hari terakhir
        $grafikPenjualan = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $total = Penjualan::whereDate('tanggal', $date)
                ->where('status', 'selesai')
                ->sum('total_bayar');
            $grafikPenjualan[] = [
                'tanggal' => $date->format('d M'),
                'total' => $total,
            ];
        }

        return view('dashboard', compact(
            'penjualanHariIni',
            'transaksiHariIni',
            'penjualanBulanIni',
            'pembelianBulanIni',
            'pengeluaranBulanIni',
            //'barangHampirHabis',
            //'barangHabis',
            'totalPelanggan',
            'totalBarang',
            'penjualanTerbaru',
            //'barangTerlaris',
            'grafikPenjualan'
        ));
    }
}
