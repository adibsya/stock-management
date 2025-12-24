<?php

namespace App\Http\Controllers;

use App\Models\BarangMaster;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BarangMasterController extends Controller
{
    /**
     * List barang master
     */
    public function index()
    {
        return view('barang.index');
    }

    /**
     * Form create
     */
    public function create()
    {
        return view('barang.create');
    }

    /**
     * Simpan barang baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => ['required', 'string', 'max:50', 'unique:barang_master,kode_barang'],
            'nama_barang' => ['required', 'string', 'max:255'],
            'kategori'    => ['nullable', 'string', 'max:100'],
            'satuan'      => ['nullable', 'string', 'max:50'],
            'keterangan'  => ['nullable', 'string'],
            'harga_beli'  => ['required', 'numeric', 'min:0'],
            'harga_jual'  => ['required', 'numeric', 'gte:harga_beli'],
        ]);

        BarangMaster::create($validated);

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Form edit
     */
    public function edit(BarangMaster $barangMaster)
    {
        return view('barang.edit', compact('barangMaster'));
    }

    /**
     * Update barang
     */
    public function update(Request $request, BarangMaster $barangMaster)
    {
        $validated = $request->validate([
            'kode_barang' => [
                'required',
                'string',
                'max:50',
                Rule::unique('barang_master', 'kode_barang')->ignore($barangMaster->id),
            ],
            'nama_barang' => ['required', 'string', 'max:255'],
            'kategori'    => ['nullable', 'string', 'max:100'],
            'satuan'      => ['nullable', 'string', 'max:50'],
            'keterangan'  => ['nullable', 'string'],
            'harga_beli'  => ['required', 'numeric', 'min:0'],
            'harga_jual'  => ['required', 'numeric', 'gte:harga_beli'],
        ]);

        $barangMaster->update($validated);

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang berhasil diupdate');
    }

    /**
     * Hapus barang
     */
    public function destroy(BarangMaster $barangMaster)
    {
        // nanti bisa ditambah cek: kalau sudah ada stok, jangan dihapus
        $barangMaster->delete();

        return redirect()
            ->route('barang.index')
            ->with('success', 'Barang berhasil dihapus');
    }
}
