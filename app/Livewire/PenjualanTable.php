<?php

namespace App\Livewire;

use App\Models\Penjualan;
use App\Models\StokBarang;
use App\Models\Gudang;
use App\Models\Pelanggan;
use App\Models\BarangMaster;
use Livewire\Component;
use Livewire\WithPagination;

class PenjualanTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $startDate = '';
    public string $endDate = '';
    public string $sortColumn = 'tanggal';
    public string $sortDirection = 'desc';
    public int $perPage = 10;
    public $gudang_id = '';
    public string $kategoriProduk = '';



    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
        'kategoriProduk' => ['except' => ''],
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
        $user = auth()->user();
        $penjualan = Penjualan::with('detailPenjualan')->findOrFail($id);

        // Authorization: Only super_admin can delete, or admin for their own gudang
        if ($user->role !== 'super_admin') {
            if ($user->role !== 'admin' || $penjualan->gudang_id !== $user->gudang_id) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Anda tidak memiliki izin untuk menghapus data ini.'
                ]);
                return;
            }
        }

        foreach ($penjualan->detailPenjualan as $detail) {
            $stok = StokBarang::where('barang_master_id', $detail->barang_id)
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

        public function updatingKategoriProduk()
        {
            $this->resetPage();
        }

    public function sortBy(string $column): void
    {
        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Base query with common filters - DRY principle
     */
    private function baseQuery()
    {
        $user = auth()->user();
        $startDate = $this->startDate ?: now()->startOfMonth()->format('Y-m-d');
        $endDate = $this->endDate ?: now()->format('Y-m-d');

        return Penjualan::query()
            // Filter berdasarkan gudang untuk admin gudang
            ->when($user && $user->role === 'admin' && $user->gudang_id, fn($q) => $q->where('gudang_id', $user->gudang_id))
            ->when($this->gudang_id, fn($q) => $q->where('gudang_id', $this->gudang_id))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->kategoriProduk, fn($q) => $q->whereHas('detailPenjualan.barang', fn($q2) => $q2->where('kategori', $this->kategoriProduk)))
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('no_faktur', 'like', '%' . $this->search . '%')
                        ->orWhereHas('pelanggan', fn($q2) => $q2->where('nama_pelanggan', 'like', '%' . $this->search . '%'));
                });
            })
            ->whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate);
    }

    public function render()
    {
        // Use baseQuery for main list with eager loading (fixes N+1)
        $penjualans = $this->baseQuery()
            ->with(['pelanggan', 'user', 'gudang', 'pembayaranPenjualan'])
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->perPage);

        // Use baseQuery for total calculation
        $totalPenjualan = $this->baseQuery()
            ->where('status', 'selesai')
            ->sum('total_bayar');

        // Count statistics for summary cards
        $jumlahTransaksi = $this->baseQuery()->count();
        $jumlahTerminPending = $this->baseQuery()
            ->where('mode_termin', 'termin')
            ->whereHas('pembayaranPenjualan', fn($q) => $q->where('status', '!=', 'lunas'))
            ->count();

        $kategoris = BarangMaster::select('kategori')->distinct()->whereNotNull('kategori')->pluck('kategori');
        $gudangs = Gudang::orderBy('nama_gudang')->get();

        return view('livewire.penjualan-table', [
            'penjualans' => $penjualans,
            'totalPenjualan' => $totalPenjualan,
            'jumlahTransaksi' => $jumlahTransaksi,
            'jumlahTerminPending' => $jumlahTerminPending,
            'gudangs' => $gudangs,
            'kategoris' => $kategoris,
        ]);
    }
}
