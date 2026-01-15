<?php

namespace App\Livewire;

use App\Models\Pelanggan;
use Livewire\Component;
use Livewire\WithPagination;

class PelangganTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $kota = '';
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

    public function updatingKota()
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
        // Get unique cities from alamat field
        $kotas = Pelanggan::query()
            ->whereNotNull('alamat')
            ->where('alamat', '!=', '')
            ->pluck('alamat')
            ->map(function ($alamat) {
                // Extract city name (last word or after comma)
                $parts = preg_split('/[,\/]/', $alamat);
                return trim(end($parts));
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $pelanggans = Pelanggan::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_pelanggan', 'like', '%' . $this->search . '%')
                        ->orWhere('no_hp', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->kota, function ($query) {
                $query->where('alamat', 'like', '%' . $this->kota . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.pelanggan-table', [
            'pelanggans' => $pelanggans,
            'kotas' => $kotas,
        ]);
    }
}

