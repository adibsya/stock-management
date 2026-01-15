<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PembayaranPenjualan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenjualanController extends Controller
    
{
    public function index(): View
    {
        return view('penjualan.index');
    }

    public function show(Penjualan $penjualan): View
    {
        $penjualan->load(['pelanggan', 'user', 'detailPenjualan.barang']);
        return view('penjualan.show', compact('penjualan'));
    }
    public function print(Penjualan $penjualan): View
    {
        $penjualan->load(['pelanggan', 'user', 'detailPenjualan.barang', 'pembayaranPenjualan']);
        return view('penjualan.print', compact('penjualan'));
    }

    public function printTermin(PembayaranPenjualan $pembayaran): View
    {
        $pembayaran->load(['penjualan.pelanggan', 'penjualan.user', 'penjualan.gudang']);
        return view('penjualan.print-termin', compact('pembayaran'));
    }

    public function printSuratJalan(Penjualan $penjualan): View
    {
        $penjualan->load(['pelanggan', 'detailPenjualan.barang']);
        return view('penjualan.surat-jalan', compact('penjualan'));
    }
}
