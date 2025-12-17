<?php

namespace App\Livewire;

use App\Models\Penjualan;
use Livewire\Component;
use Livewire\WithPagination;

class PenjualanTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $startDate = '';
    public string $endDate = '';
    public string $sortBy = 'tanggal';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
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
        $penjualans = Penjualan::query()
            ->with(['pelanggan', 'user'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('no_faktur', 'like', '%' . $this->search . '%')
                        ->orWhereHas('pelanggan', function ($q2) {
                            $q2->where('nama', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->startDate, function ($query) {
                $query->whereDate('tanggal', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($query) {
                $query->whereDate('tanggal', '<=', $this->endDate);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $totalPenjualan = Penjualan::query()
            ->when($this->startDate, fn($q) => $q->whereDate('tanggal', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('tanggal', '<=', $this->endDate))
            ->where('status', 'selesai')
            ->sum('total_bayar');

        return view('livewire.penjualan-table', [
            'penjualans' => $penjualans,
            'totalPenjualan' => $totalPenjualan,
        ]);
    }
}
