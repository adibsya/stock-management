<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\RiwayatStok;
use Livewire\Component;
use Livewire\WithPagination;

class LaporanStok extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $barang_id = null;
    public string $startDate = '';
    public string $endDate = '';

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingBarangId()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Daftar barang untuk dropdown
        $barangs = Barang::orderBy('nama_barang')->get();

        // Kartu stok / riwayat stok
        $riwayatStok = RiwayatStok::query()
            ->with('barang')
            ->when($this->barang_id, function ($query) {
                $query->where('barang_id', $this->barang_id);
            })
            ->when($this->search, function ($query) {
                $query->whereHas('barang', function ($q) {
                    $q->where('nama_barang', 'like', '%' . $this->search . '%')
                        ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->startDate, function ($query) {
                $query->whereDate('tanggal', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($query) {
                $query->whereDate('tanggal', '<=', $this->endDate);
            })
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        // Summary stok per barang
        $summaryStok = Barang::query()
            ->when($this->barang_id, fn($q) => $q->where('id', $this->barang_id))
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_barang', 'like', '%' . $this->search . '%')
                        ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
                });
            })
            ->withSum(['riwayatStok as total_masuk' => function ($query) {
                $query->when($this->startDate, fn($q) => $q->whereDate('tanggal', '>=', $this->startDate))
                    ->when($this->endDate, fn($q) => $q->whereDate('tanggal', '<=', $this->endDate));
            }], 'jumlah_masuk')
            ->withSum(['riwayatStok as total_keluar' => function ($query) {
                $query->when($this->startDate, fn($q) => $q->whereDate('tanggal', '>=', $this->startDate))
                    ->when($this->endDate, fn($q) => $q->whereDate('tanggal', '<=', $this->endDate));
            }], 'jumlah_keluar')
            ->get();

        return view('livewire.laporan-stok', [
            'barangs' => $barangs,
            'riwayatStok' => $riwayatStok,
            'summaryStok' => $summaryStok,
        ]);
    }
}
