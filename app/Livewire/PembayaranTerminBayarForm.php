<?php

namespace App\Livewire;

use App\Models\PembayaranPembelian;
use Livewire\Component;

class PembayaranTerminBayarForm extends Component
{
    public $terminId;
    public $jumlah;
    public $tanggal_bayar;
    public $metode_pembayaran = 'tunai';
    public $catatan;

    public function mount($terminId)
    {
        $termin = PembayaranPembelian::findOrFail($terminId);
        $this->terminId = $termin->id;
        $this->jumlah = $termin->jumlah;
        $this->tanggal_bayar = now()->format('Y-m-d');
    }

    public function bayar()
    {
        $this->validate([
            'jumlah' => 'required|numeric|min:1',
            'tanggal_bayar' => 'required|date',
            'metode_pembayaran' => 'required|string',
        ]);
        $termin = PembayaranPembelian::findOrFail($this->terminId);
        $termin->update([
            'jumlah_bayar' => $this->jumlah,
            'tanggal_bayar' => $this->tanggal_bayar,
            'metode_pembayaran' => $this->metode_pembayaran,
            'catatan' => $this->catatan,
            'status' => 'lunas',
        ]);
        session()->flash('success', 'Pembayaran termin berhasil!');
        return redirect()->route('pembelian.termin');
    }

    public function render()
    {
        return view('livewire.pembayaran-termin-bayar-form');
    }
}
