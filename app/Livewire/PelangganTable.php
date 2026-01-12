<?php

namespace App\Livewire;

use App\Models\Pelanggan;
use Livewire\Component;
use Livewire\WithPagination;

class PelangganTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'nama_pelanggan';
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

        $pelanggan = Pelanggan::find($id);
        if ($pelanggan) {
            $pelanggan->delete();
            $this->dispatch('pelanggan-deleted');
        }
    }

    public function render()
    {
        $pelanggans = Pelanggan::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_pelanggan', 'like', '%' . $this->search . '%')
                        ->orWhere('no_hp', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.pelanggan-table', [
            'pelanggans' => $pelanggans,
        ]);
    }
}
