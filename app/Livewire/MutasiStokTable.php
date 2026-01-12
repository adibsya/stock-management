<?php
namespace App\Livewire;
use Livewire\Component;
use App\Models\MutasiStok;
use App\Models\BarangMaster;
use App\Models\Gudang;
use Illuminate\Support\Facades\Auth;
class MutasiStokTable extends Component
{
    public function render()
    {
        $riwayat = MutasiStok::with(['barang','gudangAsal','gudangTujuan','user'])
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();
        return view('livewire.mutasi-stok-table', compact('riwayat'));
    }
}
