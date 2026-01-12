<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\PembayaranPenjualan;

class PenjualanTerminBayarForm extends Component
{
    public $termin;
    public $jumlah;
    public $tanggal_bayar;
    public $metode_pembayaran = 'tunai';
    public $catatan;
    public $show = false;

    #[On('openModalBayar')]
public function openModal($data)
{
    $this->termin = PembayaranPenjualan::findOrFail($data['termin_id']);
    $this->jumlah = '';
    $this->tanggal_bayar = now()->format('Y-m-d');
    $this->catatan = '';
    $this->show = true;
}

 #[On('closeModal')]
    public function closeModal()
    {
        $this->showModal = false;
        $this->modalTerminId = null;
    }

    public function bayar()
    {
        $this->validate([
            'jumlah' => 'required|numeric|min:1',
            'tanggal_bayar' => 'required|date',
            'metode_pembayaran' => 'required',
        ]);

        $sisa = $this->termin->jumlah - ($this->termin->jumlah_bayar ?? 0);

        if ($this->jumlah > $sisa) {
            session()->flash('error', 'Pembayaran melebihi sisa tagihan');
            return;
        }

        $total = ($this->termin->jumlah_bayar ?? 0) + $this->jumlah;

        $this->termin->update([
            'jumlah_bayar' => $total,
            'tanggal_bayar' => $this->tanggal_bayar,
            'metode_pembayaran' => $this->metode_pembayaran,
            'catatan' => $this->catatan,
            'status' => $total >= $this->termin->jumlah ? 'lunas' : 'belum_lunas',
        ]);

        session()->flash('success', 'Pembayaran berhasil');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.modal-kasir-penjualan-termin-bayar');
    }
}
