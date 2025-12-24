<?php

namespace App\Livewire;

use App\Models\Penjualan;
use App\Models\PembayaranPenjualan;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PenjualanTerminForm extends Component
{
    public Penjualan $penjualan;
    public $tanggal_bayar;
    public $jumlah_bayar;
    public $metode_pembayaran = 'tunai';
    public $catatan;

    public function mount(Penjualan $penjualan)
    {
        $this->penjualan = $penjualan;
        $this->tanggal_bayar = now()->toDateString();
    }

    public function simpanPembayaran()
    {
        $this->validate([
            'tanggal_bayar' => 'required|date',
            'jumlah_bayar' => 'required|numeric|min:1',
            'metode_pembayaran' => 'required|string',
        ]);

        DB::transaction(function () {
            PembayaranPenjualan::create([
                'penjualan_id' => $this->penjualan->id,
                'tanggal_bayar' => $this->tanggal_bayar,
                'jumlah_bayar' => $this->jumlah_bayar,
                'metode_pembayaran' => $this->metode_pembayaran,
                'catatan' => $this->catatan,
            ]);
        });

        session()->flash('success', 'Pembayaran termin berhasil disimpan!');
        $this->reset(['jumlah_bayar', 'catatan']);
        $this->dispatch('pembayaran-updated');
    }

    public function render()
    {
        $pembayaranList = $this->penjualan->pembayaranPenjualan()->orderBy('tanggal_bayar')->get();
        $totalDibayar = $pembayaranList->sum('jumlah_bayar');
        $sisa = $this->penjualan->total_bayar - $totalDibayar;
        return view('livewire.penjualan-termin-form', compact('pembayaranList', 'totalDibayar', 'sisa'));
    }
}
