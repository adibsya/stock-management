<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengeluaranController extends Controller
{
    public function index(): View
    {
        return view('pengeluaran.index');
    }

    public function create(): View
    {
        return view('pengeluaran.create');
    }

    public function edit(Pengeluaran $pengeluaran): View
    {
        return view('pengeluaran.edit', compact('pengeluaran'));
    }
}
