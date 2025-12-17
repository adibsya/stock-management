<?php

namespace App\Livewire;

use App\Models\Pembelian;
use Livewire\Component;
use Livewire\WithPagination;

class PembelianTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusBayar = '';
    public string $startDate = '';
    public string $endDate = '';
    public string $sortBy = 'tanggal';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusBayar' => ['except' => ''],
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

    public function render()
    {
        $pembelians = Pembelian::query()
            ->with(['pemasok'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('no_faktur_supplier', 'like', '%' . $this->search . '%')
                        ->orWhereHas('pemasok', function ($q2) {
                            $q2->where('nama', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->statusBayar, function ($query) {
                $query->where('status_bayar', $this->statusBayar);
            })
            ->when($this->startDate, function ($query) {
                $query->whereDate('tanggal', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($query) {
                $query->whereDate('tanggal', '<=', $this->endDate);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $totalPembelian = Pembelian::query()
            ->when($this->startDate, fn($q) => $q->whereDate('tanggal', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('tanggal', '<=', $this->endDate))
            ->sum('total_biaya');

        return view('livewire.pembelian-table', [
            'pembelians' => $pembelians,
            'totalPembelian' => $totalPembelian,
        ]);
    }
}
