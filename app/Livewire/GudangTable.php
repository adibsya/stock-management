<?php

namespace App\Livewire;

use App\Models\Gudang;
use Livewire\Component;
use Livewire\WithPagination;

class GudangTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'nama_gudang';
    public string $sortDirection = 'asc';
    public int $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

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

    public function delete(int $id): void
    {
        if (!auth()->user()->canModify()) {
            $this->dispatch('notify', message: 'Anda tidak memiliki akses untuk menghapus data!');
            return;
        }

        $gudang = Gudang::find($id);
        if ($gudang) {
            $gudang->delete();
            $this->dispatch('notify', message: 'Gudang berhasil dihapus!');
        }
    }

    public function render()
    {
        $gudangs = Gudang::query()
            ->withCount('barang')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_gudang', 'like', '%' . $this->search . '%')
                        ->orWhere('lokasi', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.gudang-table', [
            'gudangs' => $gudangs,
        ]);
    }
}
