<?php
namespace App\Http\Controllers;

use App\Models\PosMasterData;
use Illuminate\Http\Request;

class PosMasterDataController extends Controller
{
    public function index()
    {
        $data = PosMasterData::all();
        return view('pos-master-data.index', compact('data'));
    }

    public function create()
    {
        $edit = false;
        $pos = null;
        return view('pos-master-data.form', compact('edit', 'pos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:pos_master_data,kode',
            'nama' => 'required',
            'jenis' => 'required|in:aktiva,pasiva',
        ]);
        PosMasterData::create($validated);
        return redirect()->route('pos-master-data.index')->with('success', 'Pos berhasil ditambahkan');
    }

    public function edit($id)
    {
        $edit = true;
        $pos = PosMasterData::findOrFail($id);
        return view('pos-master-data.form', compact('edit', 'pos'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:pos_master_data,kode,' . $id,
            'nama' => 'required',
            'jenis' => 'required|in:aktiva,pasiva',
        ]);
        $pos = PosMasterData::findOrFail($id);
        $pos->update($validated);
        return redirect()->route('pos-master-data.index')->with('success', 'Pos berhasil diupdate');
    }
}
