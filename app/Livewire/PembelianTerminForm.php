<?php

namespace App\Livewire;

use App\Models\Pembelian;
use App\Models\PembayaranPembelian;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PembelianTerminForm extends Component
{
    public Pembelian $pembelian;
    public $tanggal_bayar;
    public $jumlah_bayar;
    public $metode_pembayaran = 'tunai';
    public $catatan;

    public function mount(Pembelian $pembelian)
    {
        $this->pembelian = $pembelian;
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
            PembayaranPembelian::create([
                'pembelian_id' => $this->pembelian->id,
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
        $pembayaranList = $this->pembelian->pembayaranPembelian()->orderBy('tanggal_bayar')->get();
        $totalDibayar = $pembayaranList->sum('jumlah_bayar');
        $sisa = $this->pembelian->total_biaya - $totalDibayar;
        return view('livewire.pembelian-termin-form', compact('pembayaranList', 'totalDibayar', 'sisa'));
    }
}
