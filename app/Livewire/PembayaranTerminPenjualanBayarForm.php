<?php

namespace App\Livewire;

use App\Models\PembayaranPenjualan;
use Livewire\Component;

class PembayaranTerminPenjualanBayarForm extends Component
{
    public $termin;
    public $jumlah;
    public $tanggal_bayar;
    public $metode_pembayaran = 'tunai';
    public $catatan;

    public function mount($termin)
    {
        $terminObj = PembayaranPenjualan::findOrFail($termin);
        $this->termin = $terminObj->id;
        $this->jumlah = '';
        $this->tanggal_bayar = now()->format('Y-m-d');
    }

    public function bayar()
    {
        $this->validate([
            'jumlah' => 'required|numeric|min:1',
            'tanggal_bayar' => 'required|date',
            'metode_pembayaran' => 'required|string',
        ]);
        $termin = PembayaranPenjualan::findOrFail($this->termin);
        $sisa = (float)$termin->jumlah - (float)$termin->jumlah_bayar;
        if ((float)$this->jumlah > $sisa) {
            session()->flash('error', 'Pembayaran melebihi sisa tagihan termin!');
            return null;
        }
        $totalBayarSekarang = (float)$termin->jumlah_bayar + (float)$this->jumlah;
        $isLunas = $totalBayarSekarang >= (float)$termin->jumlah;
        $termin->update([
            'jumlah_bayar' => $totalBayarSekarang,
            'tanggal_bayar' => $this->tanggal_bayar,
            'metode_pembayaran' => $this->metode_pembayaran,
            'catatan' => $this->catatan,
            'status' => $isLunas ? 'lunas' : 'belum_lunas',
        ]);
        session()->flash('success', 'Pembayaran termin penjualan berhasil!');
        $this->dispatch('closeModalBayar');
    }

    public function render()
    {
        $terminObj = PembayaranPenjualan::find($this->termin);
        return view('livewire.pembayaran-penjualan-termin-bayar-form', [
            'terminObj' => $terminObj
        ]);
    }
}
