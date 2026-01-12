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
