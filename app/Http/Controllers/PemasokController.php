<?php

namespace App\Http\Controllers;

use App\Models\Pemasok;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PemasokController extends Controller
{
    public function index(): View
    {
        return view('pemasok.index');
    }

    public function create(): View
    {
        return view('pemasok.create');
    }

    public function edit(Pemasok $pemasok): View
    {
        return view('pemasok.edit', compact('pemasok'));
    }
}
