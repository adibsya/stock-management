<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function labaRugi(): View
    {
        return view('laporan.laba-rugi');
    }

    public function stok(): View
    {
        return view('laporan.stok');
    }
}
