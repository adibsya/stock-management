<?php

namespace App\Http\Controllers;

use App\Models\BarangMaster;
use App\Models\StokBarang;
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
        
        // Periode sebelumnya untuk comparison
        $previousMonth = Carbon::now()->subMonth();
        $startOfPreviousMonth = $previousMonth->copy()->startOfMonth();
        $endOfPreviousMonth = $previousMonth->copy()->endOfMonth();

        // Statistik hari ini
        $penjualanHariIni = Penjualan::whereDate('tanggal', $today)
            ->where('status', 'selesai')
            ->sum('total_bayar');

        $transaksiHariIni = Penjualan::whereDate('tanggal', $today)
            ->where('status', 'selesai')
            ->count();
            
        // Statistik kemarin untuk trend
        $yesterday = Carbon::yesterday();
        $penjualanKemarin = Penjualan::whereDate('tanggal', $yesterday)
            ->where('status', 'selesai')
            ->sum('total_bayar');

        // Statistik bulan ini
        $penjualanBulanIni = Penjualan::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->where('status', 'selesai')
            ->sum('total_bayar');
            
        // Statistik bulan lalu untuk trend
        $penjualanBulanLalu = Penjualan::whereBetween('tanggal', [$startOfPreviousMonth, $endOfPreviousMonth])
            ->where('status', 'selesai')
            ->sum('total_bayar');

        $pembelianBulanIni = Pembelian::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->sum('total_biaya');
            
        $pembelianBulanLalu = Pembelian::whereBetween('tanggal', [$startOfPreviousMonth, $endOfPreviousMonth])
            ->sum('total_biaya');

        $pengeluaranBulanIni = Pengeluaran::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->sum('jumlah_biaya');

        // Barang hampir habis dan habis (dari relasi stok)
        $barangStok = BarangMaster::withSum('stok', 'jumlah')->get();
        $barangHampirHabis = $barangStok->where('stok_sum', '<=', 5)->where('stok_sum', '>', 0)->count();
        $barangHabis = $barangStok->where('stok_sum', '<=', 0)->count();

        // Total pelanggan
        $totalPelanggan = Pelanggan::count();
        $pelangganBaru = Pelanggan::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        // Total barang
        $totalBarang = BarangMaster::count();

        // Penjualan terbaru
        $penjualanTerbaru = Penjualan::with(['pelanggan', 'user'])
            ->where('status', 'selesai')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Barang terlaris bulan ini
        $barangTerlaris = BarangMaster::withSum(['detailPenjualan as total_terjual' => function ($query) use ($startOfMonth, $endOfMonth) {
            $query->whereHas('penjualan', function ($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                   ->where('status', 'selesai');
            });
        }], 'jumlah')
            ->having('total_terjual', '>', 0)
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        // Grafik penjualan vs pembelian 7 hari terakhir
        $grafikPenjualan = [];
        $grafikPembelian = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            
            $totalPenjualan = Penjualan::whereDate('tanggal', $date)
                ->where('status', 'selesai')
                ->sum('total_bayar');
                
            $totalPembelian = Pembelian::whereDate('tanggal', $date)
                ->sum('total_biaya');
                
            $grafikPenjualan[] = [
                'tanggal' => $date->format('d M'),
                'total' => $totalPenjualan,
            ];
            
            $grafikPembelian[] = [
                'tanggal' => $date->format('d M'),
                'total' => $totalPembelian,
            ];
        }
        
        // Grafik kategori penjualan bulan ini (pie chart)
        $penjualanPerKategori = BarangMaster::join('detail_penjualan', 'barang_master.id', '=', 'detail_penjualan.barang_id')
            ->join('penjualan', 'detail_penjualan.penjualan_id', '=', 'penjualan.id')
            ->whereBetween('penjualan.tanggal', [$startOfMonth, $endOfMonth])
            ->where('penjualan.status', 'selesai')
            ->selectRaw('barang_master.kategori, SUM(detail_penjualan.subtotal) as total')
            ->groupBy('barang_master.kategori')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        
        // Hitung trend
        $trendPenjualan = $penjualanBulanLalu > 0 
            ? (($penjualanBulanIni - $penjualanBulanLalu) / $penjualanBulanLalu) * 100 
            : 0;
            
        $trendPembelian = $pembelianBulanLalu > 0 
            ? (($pembelianBulanIni - $pembelianBulanLalu) / $pembelianBulanLalu) * 100 
            : 0;
            
        $trendHarian = $penjualanKemarin > 0 
            ? (($penjualanHariIni - $penjualanKemarin) / $penjualanKemarin) * 100 
            : 0;

        // Total stok sesuai role
        $user = auth()->user();
        if ($user && $user->isAdmin() && $user->gudang_id) {
            $totalStokSemuaGudang = StokBarang::where('gudang_id', $user->gudang_id)->sum('jumlah');
        } else {
            $totalStokSemuaGudang = StokBarang::sum('jumlah');
        }

        return view('dashboard', compact(
            'penjualanHariIni',
            'transaksiHariIni',
            'penjualanBulanIni',
            'pembelianBulanIni',
            'pengeluaranBulanIni',
            'barangHampirHabis',
            'barangHabis',
            'totalPelanggan',
            'pelangganBaru',
            'totalBarang',
            'penjualanTerbaru',
            'barangTerlaris',
            'grafikPenjualan',
            'grafikPembelian',
            'penjualanPerKategori',
            'trendPenjualan',
            'trendPembelian',
            'trendHarian',
            'totalStokSemuaGudang'
        ));
    }
}
