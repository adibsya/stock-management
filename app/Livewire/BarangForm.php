<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\Gudang;
use App\Models\Pemasok;
use Livewire\Component;
use Livewire\WithFileUploads;

class BarangForm extends Component
{
    use WithFileUploads;

    public ?Barang $barang = null;
    
    public string $kode_barang = '';
    public string $nama_barang = '';
    public string $kategori = '';
    public string $harga_beli = '';
    public string $harga_jual = '';
    public string $stok = '0';
    public string $stok_minimum = '0';
    public string $satuan = 'pcs';
    public $foto;
    public ?int $pemasok_id = null;
    public ?int $gudang_id = null;

    public bool $isEdit = false;

    protected function rules(): array
    {
        return [
            'kode_barang' => 'required|string|max:50|unique:barang,kode_barang' . ($this->isEdit ? ',' . $this->barang->id : ''),
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'foto' => 'nullable|image|max:2048',
            'pemasok_id' => 'nullable|exists:pemasok,id',
            'gudang_id' => 'nullable|exists:gudang,id',
        ];
    }

    public function mount(?Barang $barang = null): void
    {
        if ($barang && $barang->exists) {
            $this->isEdit = true;
            $this->barang = $barang;
            $this->kode_barang = $barang->kode_barang;
            $this->nama_barang = $barang->nama_barang;
            $this->kategori = $barang->kategori ?? '';
            $this->harga_beli = $barang->harga_beli;
            $this->harga_jual = $barang->harga_jual;
            $this->stok = $barang->stok;
            $this->stok_minimum = $barang->stok_minimum;
            $this->satuan = $barang->satuan;
            $this->pemasok_id = $barang->pemasok_id;
            $this->gudang_id = $barang->gudang_id;
        } else {
            // Generate kode barang otomatis
            $this->kode_barang = 'BRG-' . str_pad(Barang::count() + 1, 5, '0', STR_PAD_LEFT);
        }
    }

    public function save(): void
    {
        if (!auth()->user()->canModify()) {
            session()->flash('error', 'Anda tidak memiliki akses untuk menyimpan data!');
            return;
        }

        $validated = $this->validate();

        $data = [
            'kode_barang' => $this->kode_barang,
            'nama_barang' => $this->nama_barang,
            'kategori' => $this->kategori ?: null,
            'harga_beli' => $this->harga_beli,
            'harga_jual' => $this->harga_jual,
            'stok' => $this->stok,
            'stok_minimum' => $this->stok_minimum,
            'satuan' => $this->satuan,
            'pemasok_id' => $this->pemasok_id,
            'gudang_id' => $this->gudang_id,
        ];

        if ($this->foto) {
            $data['foto'] = $this->foto->store('barang', 'public');
        }

        if ($this->isEdit) {
            $this->barang->update($data);
            session()->flash('success', 'Barang berhasil diperbarui!');
        } else {
            Barang::create($data);
            session()->flash('success', 'Barang berhasil ditambahkan!');
        }

        $this->redirect(route('barang.index'));
    }

    public function render()
    {
        return view('livewire.barang-form', [
            'gudangs' => Gudang::all(),
            'pemasoks' => Pemasok::all(),
        ]);
    }
}
