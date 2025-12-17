<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Pemasok;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BarangController extends Controller
{
    public function index(): View
    {
        return view('barang.index');
    }

    public function create(): View
    {
        $gudangs = Gudang::all();
        $pemasoks = Pemasok::all();
        return view('barang.create', compact('gudangs', 'pemasoks'));
    }

    public function edit(Barang $barang): View
    {
        $gudangs = Gudang::all();
        $pemasoks = Pemasok::all();
        return view('barang.edit', compact('barang', 'gudangs', 'pemasoks'));
    }
}
