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
    public string $sortBy = 'barang_master_id';
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
        $barangsQuery = Barang::query()
            ->with(['pemasok', 'gudang', 'master'])
            ->when($this->search, function ($query) {
                $query->whereHas('master', function ($q) {
                    $q->where('nama_barang', 'like', '%' . $this->search . '%')
                        ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
                });
            });

        // Sorting by relasi master.nama_barang jika sortBy == 'nama_barang'
        if ($this->sortBy === 'nama_barang') {
            $barangsQuery->join('barang_master', 'barang.barang_master_id', '=', 'barang_master.id')
                ->orderBy('barang_master.nama_barang', $this->sortDirection)
                ->select('barang.*');
        } else {
            $barangsQuery->orderBy($this->sortBy, $this->sortDirection);
        }

        $barangs = $barangsQuery->paginate($this->perPage);

        // Kategori diambil dari barang_master
        $kategoris = \App\Models\BarangMaster::distinct()->pluck('kategori')->filter();

        return view('livewire.barang-table', [
            'barangs' => $barangs,
            'kategoris' => $kategoris,
        ]);
    }
}
