<?php

namespace App\Livewire;

use App\Models\Pengeluaran;
use Livewire\Component;
use Livewire\WithPagination;

class PengeluaranTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $kategori = '';
    public string $startDate = '';
    public string $endDate = '';
    public string $sortBy = 'tanggal';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'kategori' => ['except' => ''],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

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
            $this->sortDirection = 'desc';
        }
    }

    public function delete(int $id): void
    {
        if (!auth()->user()->canModify()) {
            $this->dispatch('notify', message: 'Anda tidak memiliki akses untuk menghapus data!');
            return;
        }

        $pengeluaran = Pengeluaran::find($id);
        if ($pengeluaran) {
            $pengeluaran->delete();
            $this->dispatch('notify', message: 'Pengeluaran berhasil dihapus!');
        }
    }

    public function render()
    {
        $pengeluarans = Pengeluaran::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('keterangan', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->kategori, function ($query) {
                $query->where('jenis_pengeluaran', $this->kategori);
            })
            ->when($this->startDate, function ($query) {
                $query->whereDate('tanggal', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($query) {
                $query->whereDate('tanggal', '<=', $this->endDate);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $totalPengeluaran = Pengeluaran::query()
            ->when($this->startDate, fn($q) => $q->whereDate('tanggal', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('tanggal', '<=', $this->endDate))
            ->sum('jumlah_biaya');

        $kategoris = Pengeluaran::distinct()->pluck('jenis_pengeluaran')->filter();

        return view('livewire.pengeluaran-table', [
            'pengeluarans' => $pengeluarans,
            'totalPengeluaran' => $totalPengeluaran,
            'kategoris' => $kategoris,
        ]);
    }
}
