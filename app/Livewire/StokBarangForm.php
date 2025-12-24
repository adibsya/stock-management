<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\BarangMaster;
use App\Models\Gudang;
use App\Models\Pemasok;
use Livewire\Component;

class StokBarangForm extends Component
{
    public $barangMasterId;
    public $gudangId;
    public $pemasokId;
    public $jumlah = 1;
    public $harga_beli = 0;
    public $stok_minimum = 0;
    public $keterangan = '';

    public function mount($barangMasterId)
    {
        $this->barangMasterId = $barangMasterId;
    }

    public function save()
    {
        $this->validate([
            'barangMasterId' => 'required|exists:barang_master,id',
            'gudangId' => 'required|exists:gudang,id',
            'pemasokId' => 'nullable|exists:pemasok,id',
            'jumlah' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
            'stok_minimum' => 'nullable|integer|min:0',
        ]);

        $barang = Barang::create([
            'barang_master_id' => $this->barangMasterId,
            'gudang_id' => $this->gudangId,
            'pemasok_id' => $this->pemasokId,
            'stok' => $this->jumlah,
            'harga_beli' => $this->harga_beli,
            'stok_minimum' => $this->stok_minimum,
            'keterangan' => $this->keterangan,
        ]);

        return redirect()->route('stok-barang.index')->with('success', 'Stok barang berhasil ditambah!');
    }

    public function render()
    {
        return view('livewire.stok-barang-form', [
            'gudangs' => Gudang::all(),
            'pemasoks' => Pemasok::all(),
            'barangMaster' => BarangMaster::find($this->barangMasterId),
        ]);
    }
}
