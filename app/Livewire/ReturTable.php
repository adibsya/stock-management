<?php

namespace App\Livewire;

use App\Models\Retur;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ReturTable extends Component
{
    use WithPagination;

    public $search = '';
    public $jenis_retur = '';
    public $tanggal_dari = '';
    public $tanggal_sampai = '';
    public $perPage = 15;

    protected $listeners = ['retur-saved' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();
        
        $query = Retur::with(['barang', 'penjualan.gudang'])
            ->when($this->search, function ($q) {
                $q->where(function ($q2) {
                    $q2->where('referensi_faktur', 'like', '%' . $this->search . '%')
                        ->orWhere('alasan', 'like', '%' . $this->search . '%')
                        ->orWhereHas('barang', function ($q3) {
                            $q3->where('nama_barang', 'like', '%' . $this->search . '%')
                                ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->jenis_retur, function ($q) {
                $q->where('jenis_retur', $this->jenis_retur);
            })
            ->when($this->tanggal_dari, function ($q) {
                $q->where('tanggal', '>=', $this->tanggal_dari);
            })
            ->when($this->tanggal_sampai, function ($q) {
                $q->where('tanggal', '<=', $this->tanggal_sampai);
            });

        // Filter untuk admin gudang - hanya tampilkan retur dari penjualan gudangnya
        if ($user->isAdmin()) {
            $query->whereHas('penjualan', function ($q) use ($user) {
                $q->where('gudang_id', $user->gudang_id);
            });
        }

        $returs = $query->orderByDesc('tanggal')->paginate($this->perPage);

        return view('livewire.retur-table', [
            'returs' => $returs,
        ]);
    }
}
