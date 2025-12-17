<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PelangganController extends Controller
{
    public function index(): View
    {
        return view('pelanggan.index');
    }

    public function create(): View
    {
        return view('pelanggan.create');
    }

    public function edit(Pelanggan $pelanggan): View
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }
}
