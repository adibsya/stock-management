<?php

namespace App\Livewire;

use App\Models\Pembelian;
use App\Models\Gudang;
use Livewire\Component;
use Livewire\WithPagination;

class LaporanPembelian extends Component
{
    use WithPagination;

    public $gudang_id = '';
    public $startDate = '';
    public $endDate = '';

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function updatingGudangId()
    {
        $this->resetPage();
    }

    public function render()
    {
        $gudangs = Gudang::all();
        $pembelians = Pembelian::with(['pemasok', 'user'])
            ->when($this->gudang_id, function ($q) {
                $q->where('gudang_id', $this->gudang_id);
            })
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->orderBy('tanggal', 'desc')
            ->paginate(20);

        return view('livewire.laporan-pembelian', [
            'gudangs' => $gudangs,
            'pembelians' => $pembelians,
        ]);
    }
}
