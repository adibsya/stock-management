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

        // Superadmin: tampilkan semua stok, Admin: hanya stok di gudang miliknya
        if ($user && method_exists($user, 'isSuperAdmin') && !$user->isSuperAdmin()) {
            if ($user->gudang_id) {
                $query->where('gudang_id', $user->gudang_id);
            } else {
                // Tidak tampilkan apapun jika admin tidak punya gudang
                $query->whereRaw('1=0');
            }
        }

        $stoks = $query->orderByDesc('id')->paginate($this->perPage);

        return view('livewire.stok-table', [
            'stoks' => $stoks,
        ]);
    }
}
