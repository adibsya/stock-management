<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\Gudang;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class StokBarangTable extends Component
{
    use WithPagination;

    public $gudangId = '';
    public string $search = '';
    public string $sortBy = 'barang_master_id';
    public string $sortDirection = 'asc';
    public int $perPage = 15;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingGudangId()
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
        $user = Auth::user();
        $gudangs = $user->isSuperAdmin() ? Gudang::all() : Gudang::where('id', $user->gudang_id)->get();
        $query = Barang::with('master', 'gudang')
            ->when($this->search, function ($q) {
                $q->whereHas('master', function ($q2) {
                    $q2->where('nama_barang', 'like', '%' . $this->search . '%')
                        ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->gudangId, function ($q) {
                $q->where('gudang_id', $this->gudangId);
            });
        if ($user->isAdmin()) {
            $query->where('gudang_id', $user->gudang_id);
        }
        // Sorting by relasi master.nama_barang jika sortBy == 'nama_barang'
        if ($this->sortBy === 'nama_barang') {
            $query->join('barang_master', 'barang.barang_master_id', '=', 'barang_master.id')
                ->orderBy('barang_master.nama_barang', $this->sortDirection)
                ->select('barang.*');
        } else {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }
        $barangs = $query->paginate($this->perPage);
        return view('livewire.stok-barang-table', [
            'barangs' => $barangs,
            'gudangs' => $gudangs,
        ]);
    }
}
