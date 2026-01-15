<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
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
    public string $kategoriProduk = '';
    protected $listeners = ['delete'];

    public string $gudangId = '';
    protected $queryString = [
        'search' => ['except' => ''],
        'statusBayar' => ['except' => ''],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
        'gudangId' => ['except' => ''],
        'kategoriProduk' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');

        $user = Auth::user();
        if ($user && $user->isAdmin() && $user->gudang_id) {
            $this->gudangId = $user->gudang_id;
        }
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
    public function delete($payload)
    {
        $id = is_array($payload) ? $payload[0] : $payload;
        $pembelian = \App\Models\Pembelian::with('detailPembelian')->findOrFail($id);
        foreach ($pembelian->detailPembelian as $detail) {
            $stok = \App\Models\StokBarang::where('barang_master_id', $detail->barang_master_id)
                ->where('gudang_id', $pembelian->gudang_id)
                ->first();
            if ($stok) {
                $stok->jumlah -= $detail->jumlah;
                if ($stok->jumlah < 0) $stok->jumlah = 0;
                $stok->save();
            }
        }
        $pembelian->delete();
        $this->dispatch('pembelian-dihapus');
    }


    public function updatingGudangId()
    {
        $this->resetPage();
    }

    public function updatingKategoriProduk()
    {
        $this->resetPage();
    }


    public function render()
    {
        $pembelians = Pembelian::query()
            ->with(['pemasok', 'gudang'])
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
            ->when($this->gudangId, function ($query) {
                $query->where('gudang_id', $this->gudangId);
            })
            ->when($this->kategoriProduk, function ($query) {
                $query->whereHas('detailPembelian.barangmaster', function ($q) {
                    $q->where('kategori', $this->kategoriProduk);
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $totalPembelian = Pembelian::query()
            ->when($this->startDate, fn($q) => $q->whereDate('tanggal', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('tanggal', '<=', $this->endDate))
            ->when($this->gudangId, fn($q) => $q->where('gudang_id', $this->gudangId))
            ->when($this->kategoriProduk, function ($query) {
                $query->whereHas('detailPembelian.barangmaster', function ($q) {
                    $q->where('kategori', $this->kategoriProduk);
                });
            })
            ->sum('total_biaya');

        $kategoris = \App\Models\BarangMaster::select('kategori')->distinct()->whereNotNull('kategori')->pluck('kategori');

        $user = Auth::user();
        $gudangs = [];
        $showGudangFilter = true;
        if ($user && $user->isAdmin()) {
            $showGudangFilter = false;
        } else {
            $gudangs = \App\Models\Gudang::all();
        }

        return view('livewire.pembelian-table', [
            'pembelians' => $pembelians,
            'totalPembelian' => $totalPembelian,
            'gudangs' => $gudangs,
            'gudangId' => $this->gudangId,
            'showGudangFilter' => $showGudangFilter,
            'kategoris' => $kategoris,
        ]);
    }
}
