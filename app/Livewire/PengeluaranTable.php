<?php

namespace App\Livewire;

use App\Models\Pengeluaran;
use App\Models\Gudang;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class PengeluaranTable extends Component
{
    use WithPagination;

    public $gudang_id;
    public $gudangs = [];
    public string $search = '';
    public string $kategori = '';
    public string $startDate = '';
    public string $endDate = '';
    public string $sortBy = 'tanggal';
    public string $sortDirection = 'desc';
    public int $perPage = 10;
    public bool $isSuperadmin = false;

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->format('Y-m-d');

        $this->isSuperadmin = Auth::user()->isSuperAdmin();
        $this->gudangs      = Gudang::all();

        if (!$this->isSuperadmin) {
            $this->gudang_id = Auth::user()->gudang_id;
        }
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

    /* ===========================
     | DELETE FLOW
     =========================== */

    public function confirmDelete(int $id): void
    {
        \Log::info('ID yang dikirim ke JS:', ['id' => $id]);
        $this->dispatch('swal:confirm-delete', id: $id, message: 'Yakin ingin menghapus pengeluaran ini?');
    }

    #[On('deleteConfirmed')]
    public function deleteConfirmed($id = null): void
    {
        // Kompatibel Livewire v3: parameter bisa berupa array/object
        if (is_array($id)) {
            $id = $id['id'] ?? null;
        } elseif (is_object($id)) {
            $id = $id->id ?? null;
        }
        if (!$id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'ID pengeluaran tidak ditemukan!'
            ]);
            return;
        }
        if (!auth()->user()->canModify()) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Anda tidak memiliki akses!'
            ]);
            return;
        }

        $pengeluaran = Pengeluaran::find($id);

        if ($pengeluaran) {
            $pengeluaran->delete();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Pengeluaran berhasil dihapus'
            ]);
        }
    }


    public function render()
    {
        $pengeluarans = Pengeluaran::query()
            ->when($this->gudang_id, fn ($q) => $q->where('gudang_id', $this->gudang_id))
            ->when($this->search, fn ($q) => $q->where('keterangan', 'like', "%{$this->search}%"))
            ->when($this->kategori, fn ($q) => $q->where('jenis_pengeluaran', $this->kategori))
            ->when($this->startDate, fn ($q) => $q->whereDate('tanggal', '>=', $this->startDate))
            ->when($this->endDate, fn ($q) => $q->whereDate('tanggal', '<=', $this->endDate))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $kategoris = Pengeluaran::query()
            ->distinct()
            ->pluck('jenis_pengeluaran')
            ->filter();

        $totalPengeluaran = Pengeluaran::query()
            ->when($this->gudang_id, fn ($q) => $q->where('gudang_id', $this->gudang_id))
            ->when($this->startDate, fn ($q) => $q->whereDate('tanggal', '>=', $this->startDate))
            ->when($this->endDate, fn ($q) => $q->whereDate('tanggal', '<=', $this->endDate))
            ->sum('jumlah_biaya');

        return view('livewire.pengeluaran-table', compact(
            'pengeluarans',
            'kategoris',
            'totalPengeluaran'
        ));
    }
}
