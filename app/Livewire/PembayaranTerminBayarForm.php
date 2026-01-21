<?php

namespace App\Livewire;

use App\Models\PembayaranPembelian;
use Livewire\Component;
use App\Services\TransaksiPembelianService;
use Illuminate\Support\Facades\DB;


class PembayaranTerminBayarForm extends Component
{
    public $termin;
    public $jumlah;
    public $tanggal_bayar;
    public $metode_pembayaran = 'tunai';
    public $catatan;

    public function mount($termin)
    {
        $terminObj = PembayaranPembelian::findOrFail($termin);
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
        $termin = PembayaranPembelian::findOrFail($this->termin);

        // Akumulasi pembayaran termin
        $totalBayarSekarang = (float)$termin->jumlah_bayar + (float)$this->jumlah;
        $isLunas = $totalBayarSekarang >= (float)$termin->jumlah;
        $this->validate([
            'jumlah' => 'required|numeric|min:1',
            'tanggal_bayar' => 'required|date',
            'metode_pembayaran' => 'required|string',
        ]);
        $termin = PembayaranPembelian::findOrFail($this->termin);
        $sisa = (float)$termin->jumlah - (float)$termin->jumlah_bayar;
        if ((float)$this->jumlah > $sisa) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Pembayaran melebihi sisa tagihan termin!'
            ]);
            return null;
        }
        // Akumulasi pembayaran termin
        $totalBayarSekarang = (float)$termin->jumlah_bayar + (float)$this->jumlah;
        $isLunas = $totalBayarSekarang >= (float)$termin->jumlah;
        $termin->update([
            'jumlah_bayar' => $totalBayarSekarang,
            'tanggal_bayar' => $this->tanggal_bayar,
            'metode_pembayaran' => $this->metode_pembayaran,
            'catatan' => $this->catatan,
            'status' => $isLunas ? 'lunas' : 'belum_lunas',
        ]);

        // Cek jika semua termin sudah lunas dan update jatuh_tempo ke termin berikutnya
        $pembelian = $termin->pembelian;
        $unpaidTermins = $pembelian->pembayaranPembelian()->where('status', 'belum_lunas')->orderBy('tanggal_jatuh_tempo')->get();
        if ($unpaidTermins->count() === 0) {
            $pembelian->update([
                'status_bayar' => 'lunas',
                'jatuh_tempo' => null,
            ]);
        } else {
            // Set jatuh_tempo ke tanggal_jatuh_tempo termin berikutnya yang belum lunas
            $nextDue = $unpaidTermins->first();
            $pembelian->update([
                'jatuh_tempo' => $nextDue->tanggal_jatuh_tempo,
                'status_bayar' => 'belum_lunas',
            ]);
        }

        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Pembayaran termin berhasil!'
        ]);
         $this->dispatch('closeModalBayar');
        // Tidak redirect, biarkan modal tertutup dan tabel refresh jika perlu

        TransaksiPembelianService::bayarTerminPembelian(
            $this->termin,   // ← ID
            (float)$this->jumlah
        );
    }

    public function render()
    {
        $terminObj = \App\Models\PembayaranPembelian::find($this->termin);
        return view('livewire.pembayaran-termin-bayar-form', [
            'terminObj' => $terminObj
        ]);
    }
}