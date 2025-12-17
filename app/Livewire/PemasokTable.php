<?php

namespace App\Livewire;

use App\Models\Pemasok;
use Livewire\Component;
use Livewire\WithPagination;

class PemasokTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'nama_supplier';
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

        $pemasok = Pemasok::find($id);
        if ($pemasok) {
            $pemasok->delete();
            $this->dispatch('notify', message: 'Pemasok berhasil dihapus!');
        }
    }

    public function render()
    {
        $pemasoks = Pemasok::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_supplier', 'like', '%' . $this->search . '%')
                        ->orWhere('kontak', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.pemasok-table', [
            'pemasoks' => $pemasoks,
        ]);
    }
}
