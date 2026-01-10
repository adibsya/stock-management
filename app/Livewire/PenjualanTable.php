<?php

namespace App\Livewire;

use App\Models\Penjualan;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Gudang;


use App\Models\Pelanggan;

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
    public $gudang_id = '';



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
        // Set gudang_id untuk admin gudang
        $user = auth()->user();
        if ($user && $user->role === 'admin' && $user->gudang_id) {
            $this->gudang_id = $user->gudang_id;
        }
        $this->pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();
    }


    // Fungsi hapus penjualan dan kembalikan stok
    protected $listeners = ['hapusPenjualan'];
    public function hapusPenjualan($id)
    {
        $penjualan = Penjualan::with('detailPenjualan')->findOrFail($id);
        foreach ($penjualan->detailPenjualan as $detail) {
            $stok = \App\Models\StokBarang::where('barang_master_id', $detail->barang_id)
                ->where('gudang_id', $detail->gudang_id)
                ->first();
            if ($stok) {
                $stok->jumlah += $detail->jumlah;
                $stok->save();
            }
        }
        $penjualan->delete();
        $this->dispatch('penjualan-dihapus');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

        public function updatingStartDate()
        {
            $this->resetPage();
        }

        public function updatingEndDate()
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
        $user = auth()->user();

        // Validasi dan fallback tanggal agar filter selalu aktif
        $startDate = $this->startDate ?: now()->startOfMonth()->format('Y-m-d');
        $endDate = $this->endDate ?: now()->format('Y-m-d');

        $penjualans = Penjualan::query()
            ->with(['pelanggan', 'user', 'gudang'])
            // Filter berdasarkan gudang untuk admin gudang
            ->when($user && $user->role === 'admin' && $user->gudang_id, function ($query) use ($user) {
                $query->where('gudang_id', $user->gudang_id);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('no_faktur', 'like', '%' . $this->search . '%')
                        ->orWhereHas('pelanggan', function ($q2) {
                            $q2->where('nama', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->gudang_id, function ($query) {
                $query->where('gudang_id', $this->gudang_id);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            // Filter tanggal selalu aktif
            ->whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate)
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $totalPenjualan = Penjualan::query()
            // Filter berdasarkan gudang untuk admin gudang
            ->when($user && $user->role === 'admin' && $user->gudang_id, function ($query) use ($user) {
                $query->where('gudang_id', $user->gudang_id);
            })
            ->when($this->gudang_id, fn($q) => $q->where('gudang_id', $this->gudang_id))
            ->where('status', 'selesai')
            ->whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate)
            ->sum('total_bayar');

        $gudangs = Gudang::orderBy('nama_gudang')->get();
        return view('livewire.penjualan-table', [
            'penjualans' => $penjualans,
            'totalPenjualan' => $totalPenjualan,
            'gudangs' => $gudangs,
        ]);
    }
}
