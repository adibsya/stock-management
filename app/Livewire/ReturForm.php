<?php

namespace App\Livewire;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Services\ReturService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ReturForm extends Component
{
    public $penjualan;
    public $tanggal;
    public $detail_penjualan_id;
    public $jumlah_retur;
    public $alasan;
    public $kondisi_barang = 'rusak';

    protected $listeners = ['openReturModal' => 'openModal'];

    public function mount()
    {
        $this->tanggal = now()->toDateString();
    }

    public function openModal($penjualanId)
    {
        $this->penjualan = Penjualan::with('detailPenjualan.barang')->findOrFail($penjualanId);
        $this->reset(['detail_penjualan_id', 'jumlah_retur', 'alasan']);
        $this->tanggal = now()->toDateString();
        $this->kondisi_barang = 'rusak';
    }

    public function simpanRetur()
{
    $this->validate([
        'detail_penjualan_id' => 'required|exists:detail_penjualan,id',
        'jumlah_retur' => 'required|integer|min:1',
        'alasan' => 'required|string|max:500',
        'kondisi_barang' => 'required|in:baik,rusak',
    ]);

    if (!$this->penjualan) {
        $this->addError('penjualan', 'Penjualan tidak ditemukan');
        return;
    }

    try {
        app(ReturService::class)->returPenjualan([
            'tanggal' => $this->tanggal,
            'penjualan_id' => $this->penjualan->id,
            'detail_penjualan_id' => $this->detail_penjualan_id,
            'jumlah' => $this->jumlah_retur,
            'alasan' => $this->alasan,
            'kondisi_barang' => $this->kondisi_barang,
        ]);

        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Retur berhasil diproses',
        ]);

        $this->dispatch('retur-saved');
        $this->dispatch('close-modal');

        $this->reset(['detail_penjualan_id', 'jumlah_retur', 'alasan']);

    } catch (\Throwable $e) {
        $this->dispatch('show-alert', [
            'type' => 'error',
            'message' => $e->getMessage(),
        ]);
    }
}


    public function render()
    {
        return view('livewire.retur-form');
    }
}
