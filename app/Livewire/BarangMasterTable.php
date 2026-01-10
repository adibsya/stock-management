<?php

namespace App\Livewire;

use App\Models\BarangMaster;
use Livewire\Component;
use Livewire\WithPagination;

class BarangMasterTable extends Component
{

    use WithPagination;
    protected $listeners = ['delete'];

    public string $search = '';
    public string $kategori = '';
    public string $sortBy = 'nama_barang';
    public string $sortDirection = 'asc';
    public int $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $barangs = BarangMaster::query()
            ->when($this->search, function ($query) {
                $query->where('nama_barang', 'like', '%' . $this->search . '%')
                      ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
            })
            ->when($this->kategori, function ($query) {
                $query->where('kategori', $this->kategori);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        // Ambil semua kategori unik dari master barang
        $kategoris = BarangMaster::query()->distinct()->pluck('kategori')->filter()->values();

        return view('livewire.barang-master-table', [
            'barangs' => $barangs,
            'kategoris' => $kategoris,
        ]);
    }

    public function delete($id)
    {
        $barang = BarangMaster::findOrFail($id);
        $barang->delete();
        session()->flash('message', 'Barang berhasil dihapus.');
    }
}
