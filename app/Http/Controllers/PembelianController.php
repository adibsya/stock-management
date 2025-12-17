<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Pemasok;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PembelianController extends Controller
{
    public function index(): View
    {
        return view('pembelian.index');
    }

    public function create(): View
    {
        $pemasoks = Pemasok::all();
        return view('pembelian.create', compact('pemasoks'));
    }

    public function show(Pembelian $pembelian): View
    {
        $pembelian->load(['pemasok', 'detailPembelian.barang']);
        return view('pembelian.show', compact('pembelian'));
    }
}
