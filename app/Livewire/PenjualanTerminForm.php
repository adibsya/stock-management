<?php

namespace App\Livewire;

use App\Models\Penjualan;
use App\Models\PembayaranPenjualan;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PenjualanTerminForm extends Component
{
    public function render()
    {
        $termins = PembayaranPenjualan::with(['penjualan.pelanggan'])
            ->orderBy('tanggal_jatuh_tempo')
            ->get();
        return view('livewire.penjualan-termin-table', compact('termins'));
    }
}
