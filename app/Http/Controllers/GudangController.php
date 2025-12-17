<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GudangController extends Controller
{
    public function index(): View
    {
        return view('gudang.index');
    }

    public function create(): View
    {
        return view('gudang.create');
    }

    public function edit(Gudang $gudang): View
    {
        return view('gudang.edit', compact('gudang'));
    }
}
