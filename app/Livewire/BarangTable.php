<?php

namespace App\Livewire;

use App\Models\Barang;
use Livewire\Component;
use Livewire\WithPagination;

class BarangTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $kategori = '';
    public string $sortBy = 'nama_barang';
    public string $sortDirection = 'asc';
    public int $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'kategori' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingKategori()
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

    public function delete(int $id): void
    {
        if (!auth()->user()->canModify()) {
            $this->dispatch('notify', message: 'Anda tidak memiliki akses untuk menghapus data!');
            return;
        }

        $barang = Barang::find($id);
        if ($barang) {
            $barang->delete();
            $this->dispatch('notify', message: 'Barang berhasil dihapus!');
        }
    }

    public function render()
    {
        $barangs = Barang::query()
            ->with(['pemasok', 'gudang'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_barang', 'like', '%' . $this->search . '%')
                        ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->kategori, function ($query) {
                $query->where('kategori', $this->kategori);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $kategoris = Barang::distinct()->pluck('kategori')->filter();

        return view('livewire.barang-table', [
            'barangs' => $barangs,
            'kategoris' => $kategoris,
        ]);
    }
}
