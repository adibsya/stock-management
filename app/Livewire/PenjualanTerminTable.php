<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\PembayaranPenjualan;

class PenjualanTerminTable extends Component
{
    public $termins = [];

    public bool $showModal = false;
    public ?PembayaranPenjualan $selectedTermin = null;

    public $jumlah;
    public $tanggal_bayar;
    public $metode_pembayaran = 'tunai';
    public $catatan;

    public function mount()
    {
        $this->loadTermins();
    }

    public function loadTermins()
    {
        $user = auth()->user();
        
        $this->termins = PembayaranPenjualan::with('penjualan.pelanggan')
            // Filter berdasarkan gudang untuk admin gudang
            ->when($user && $user->role === 'admin' && $user->gudang_id, function ($query) use ($user) {
                $query->whereHas('penjualan', function ($q) use ($user) {
                    $q->where('gudang_id', $user->gudang_id);
                });
            })
            ->orderBy('tanggal_jatuh_tempo')
            ->get();
    }

    public function openModalBayar($id)
    {
        $this->reset(['jumlah', 'tanggal_bayar', 'catatan']);

        $this->selectedTermin = PembayaranPenjualan::with('penjualan.pelanggan')
            ->findOrFail($id);

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedTermin = null;
    }

    public function bayar()
    {
        $this->validate([
            'jumlah' => 'required|numeric|min:1',
            'tanggal_bayar' => 'required|date',
            'metode_pembayaran' => 'required',
        ]);

        // Ambil ulang model fresh dari database
        $termin = \App\Models\PembayaranPenjualan::findOrFail($this->selectedTermin->id);
        $sisa = $termin->jumlah - ($termin->jumlah_bayar ?? 0);


        if ($this->jumlah > $sisa) {
            $this->addError('jumlah', 'Melebihi sisa tagihan');
            return;
        }
        if ($this->jumlah <= 0) {
            $this->addError('jumlah', 'Nominal pembayaran harus lebih dari 0');
            return;
        }

        $total = ($termin->jumlah_bayar ?? 0) + $this->jumlah;

        $termin->update([
            'jumlah_bayar' => $total,
            'pembayaran_terakhir' => $this->jumlah,
            'tanggal_bayar' => $this->tanggal_bayar,
            'metode_pembayaran' => $this->metode_pembayaran,
            'status' => $total >= $termin->jumlah ? 'lunas' : 'belum_lunas',
        ]);

        $this->loadTermins();
        $this->closeModal();

        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Pembayaran berhasil!'
        ]);
    }

    public function render()
    {
        return view('livewire.penjualan-termin-table', [
            'termins' => $this->termins,
            'showModal' => $this->showModal,
            'selectedTermin' => $this->selectedTermin,
        ]);
    }
}
