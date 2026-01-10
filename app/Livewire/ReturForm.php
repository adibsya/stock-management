<?php

namespace App\Livewire;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Services\TransaksiService;
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
            'kondisi_barang' => 'required|in:bagus,rusak',
        ]);

        $detail = DetailPenjualan::findOrFail($this->detail_penjualan_id);

        if ($this->jumlah_retur > $detail->jumlah) {
            session()->flash('error', 'Jumlah retur melebihi jumlah pembelian!');
            return;
        }

        $transaksiService = app(TransaksiService::class);
        
        $result = $transaksiService->prosesReturPenjualan([
            'tanggal' => $this->tanggal,
            'referensi_faktur' => $this->penjualan->no_faktur,
            'barang_id' => $detail->barang_id,
            'gudang_id' => $detail->gudang_id,
            'jumlah' => $this->jumlah_retur,
            'alasan' => $this->alasan,
            'kondisi_barang' => $this->kondisi_barang,
        ]);

        if (!$result['success']) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => $result['message']
            ]);
            return;
        }

        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Retur berhasil diproses! Barang pengganti telah diambil dari stok.'
        ]);
        $this->dispatch('retur-saved');
        $this->dispatch('close-modal');
    }

    public function render()
    {
        return view('livewire.retur-form');
    }
}
