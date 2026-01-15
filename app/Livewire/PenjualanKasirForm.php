<?php

namespace App\Livewire;

use App\Models\Penjualan;
use App\Models\PembayaranPenjualan;
use App\Models\PosMasterData;
use App\Services\JurnalService;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PenjualanKasirForm extends Component
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

            /** ===============================
             * 1️⃣ SIMPAN PEMBAYARAN
             * =============================== */
            $pembayaran = PembayaranPenjualan::create([
                'penjualan_id' => $this->penjualan->id,
                'tanggal_bayar' => $this->tanggal_bayar,
                'jumlah_bayar' => $this->jumlah_bayar,
                'metode_pembayaran' => $this->metode_pembayaran,
                'catatan' => $this->catatan,
            ]);

            /** ===============================
             * 2️⃣ AMBIL COA
             * =============================== */
            $kas = $this->metode_pembayaran === 'transfer'
                ? PosMasterData::where('kode', '1-02')->first() // Bank
                : PosMasterData::where('kode', '1-01')->first(); // Kas

            $piutang = PosMasterData::where('kode', '1-03')->first(); // Piutang Usaha

            if (!$kas || !$piutang) {
                throw new \Exception('COA Kas / Piutang belum disetting');
            }

            /** ===============================
             * 3️⃣ JURNAL OTOMATIS
             * =============================== */
            JurnalService::create(
                $this->tanggal_bayar,
                'Pembayaran ' . $this->penjualan->no_faktur,
                'pembayaran_penjualan',
                $pembayaran->id,
                [
                    ['coa_id' => $kas->id, 'debit' => $this->jumlah_bayar],
                    ['coa_id' => $piutang->id, 'kredit' => $this->jumlah_bayar],
                ]
            );

            /** ===============================
             * 4️⃣ UPDATE STATUS PENJUALAN
             * =============================== */
            $this->penjualan->updateStatus();
        });

        /** ===============================
         * 5️⃣ UI FEEDBACK
         * =============================== */
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => 'Pembayaran berhasil & jurnal tercatat'
        ]);

        $this->reset(['jumlah_bayar', 'catatan']);
        $this->dispatch('pembayaran-updated');
    }

    public function render()
    {
        $pembayaranList = $this->penjualan
            ->pembayaranPenjualan()
            ->orderBy('tanggal_bayar')
            ->get();

        $totalDibayar = $pembayaranList->sum('jumlah_bayar');
        $sisa = max(0, $this->penjualan->total_bayar - $totalDibayar);

        return view('livewire.penjualan-kasir-form', compact(
            'pembayaranList',
            'totalDibayar',
            'sisa'
        ));
    }
}
