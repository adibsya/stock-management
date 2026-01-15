<?php

namespace App\Livewire;

use App\Models\StokBarang;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class StokTable extends Component
{
    use WithPagination;

    public $perPage = 15;
    public $search = '';
    public $gudangId = '';
    public $statusStok = ''; // 'habis', 'sedikit', 'aman'

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingGudangId()
    {
        $this->resetPage();
    }

    public function updatingStatusStok()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();
        $query = StokBarang::with(['barangMaster', 'gudang']);

        if ($this->search) {
            $query->whereHas('barangMaster', function ($q) {
                $q->where('nama_barang', 'like', "%{$this->search}%")
                  ->orWhere('kode_barang', 'like', "%{$this->search}%");
            });
        }

        if ($this->gudangId) {
            $query->where('gudang_id', $this->gudangId);
        }

        // Filter status stok
        if ($this->statusStok === 'habis') {
            $query->where('jumlah', '<=', 0);
        } elseif ($this->statusStok === 'sedikit') {
            $query->where('jumlah', '>', 0)->where('jumlah', '<=', 10);
        } elseif ($this->statusStok === 'aman') {
            $query->where('jumlah', '>', 10);
        }

        // Superadmin/Viewer: tampilkan semua stok, Admin: hanya stok di gudang miliknya
        if ($user->isAdmin()) {
            if ($user->gudang_id) {
                $query->where('gudang_id', $user->gudang_id);
            } else {
                $query->whereRaw('1=0'); // Tidak tampilkan apapun jika admin tidak punya gudang
            }
        }

        $stoks = $query->orderByDesc('id')->paginate($this->perPage);
        $gudangs = \App\Models\Gudang::all();

        // Hitung total stok sesuai role dan filter
        $totalStok = $query->sum('jumlah');

        return view('livewire.stok-table', [
            'stoks' => $stoks,
            'gudangs' => $gudangs,
            'gudangId' => $this->gudangId,
            'totalStok' => $totalStok,
        ]);
    }
}

