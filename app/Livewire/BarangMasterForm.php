<?php

namespace App\Livewire;

use App\Models\BarangMaster;
use Livewire\Component;
use Illuminate\Validation\Rule;

class BarangMasterForm extends Component
{
    public $kode_barang = '';
    public $nama_barang = '';
    public $kategori = '';
    public $satuan = 'pcs';
    public $harga_beli = 0;
    public $harga_jual = 0;
    public $keterangan = '';

    public $isEdit = false;
    public $barangMaster = null;

    /**
     * Validation rules
     */
    protected function rules(): array
    {
        return [
            'kode_barang' => [
                'required',
                'string',
                'max:50',
                Rule::unique('barang_master', 'kode_barang')
                    ->ignore($this->barangMaster?->id),
            ],
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'satuan' => 'required|string|max:50',
            'keterangan' => 'nullable|string',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ];
    }

    /**
     * Mount (edit mode)
     */
public function mount(BarangMaster $barangMaster = null)
{
    if ($barangMaster && $barangMaster->exists) {
        $this->isEdit = true;
        $this->barangMaster = $barangMaster;

        $this->kode_barang = $barangMaster->kode_barang;
        $this->nama_barang = $barangMaster->nama_barang;
        $this->kategori = $barangMaster->kategori;
        $this->satuan = $barangMaster->satuan;
        $this->harga_beli = $barangMaster->harga_beli;
        $this->harga_jual = $barangMaster->harga_jual;
        $this->keterangan = $barangMaster->keterangan;
    }
}

    /**
     * Save data
     */
    public function save()
    {
        $validated = $this->validate();

        // Pastikan harga_beli dan harga_jual ikut tersimpan
        $data = $validated;
        $data['harga_beli'] = $this->harga_beli;
        $data['harga_jual'] = $this->harga_jual;

        if ($this->isEdit && $this->barangMaster) {
            $this->barangMaster->update($data);
            session()->flash('success', 'Data master barang berhasil diperbarui');
        } else {
            BarangMaster::create($data);
            session()->flash('success', 'Data master barang berhasil ditambahkan');
        }

        return redirect()->route('barang-master.index');
    }

    public function render()
    {
        return view('livewire.barang-form');
    }
}
