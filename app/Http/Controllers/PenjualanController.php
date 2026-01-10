<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
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
}
