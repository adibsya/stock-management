<?php

namespace App\Livewire;

use App\Models\Penjualan;
use App\Models\PembayaranPenjualan;
use Livewire\Component;

class PenjualanTerminUpdate extends Component
{
    public $penjualanId;
    public $penjualan;
    public $pembayarans = [];

    public function mount($penjualan)
    {
        $this->penjualanId = $penjualan;
        $this->penjualan = Penjualan::with(['pembayaranPenjualan', 'pelanggan'])->findOrFail($penjualan);
        
        // Load pembayaran data
        foreach ($this->penjualan->pembayaranPenjualan as $pembayaran) {
            $this->pembayarans[$pembayaran->id] = [
                'id' => $pembayaran->id,
                'jumlah_bayar' => $pembayaran->jumlah_bayar,
                'tanggal_bayar' => $pembayaran->tanggal_bayar,
                'status_bayar' => $pembayaran->status_bayar,
                'metode_pembayaran' => $pembayaran->metode_pembayaran,
                'catatan' => $pembayaran->catatan,
            ];
        }
    }

    public function updateStatus($pembayaranId, $status)
    {
        $pembayaran = PembayaranPenjualan::findOrFail($pembayaranId);
        
        if ($status === 'lunas') {
            $this->validate([
                "pembayarans.{$pembayaranId}.tanggal_bayar" => 'required|date',
            ], [
                "pembayarans.{$pembayaranId}.tanggal_bayar.required" => 'Tanggal bayar harus diisi',
            ]);
        }
        
        $pembayaran->update([
            'status_bayar' => $status,
            'tanggal_bayar' => $this->pembayarans[$pembayaranId]['tanggal_bayar'],
        ]);
        
        // Cek apakah semua cicilan sudah lunas
        $this->checkAndUpdatePenjualanStatus();
        
        $this->dispatch('notify', message: 'Status pembayaran berhasil diupdate!');
        $this->mount($this->penjualanId); // Refresh data
    }

    public function updatePembayaran($pembayaranId)
    {
        $this->validate([
            "pembayarans.{$pembayaranId}.jumlah_bayar" => 'required|numeric|min:0',
            "pembayarans.{$pembayaranId}.tanggal_bayar" => 'required|date',
            "pembayarans.{$pembayaranId}.metode_pembayaran" => 'required|string',
        ]);

        $pembayaran = PembayaranPenjualan::findOrFail($pembayaranId);
        $pembayaran->update([
            'jumlah_bayar' => $this->pembayarans[$pembayaranId]['jumlah_bayar'],
            'tanggal_bayar' => $this->pembayarans[$pembayaranId]['tanggal_bayar'],
            'metode_pembayaran' => $this->pembayarans[$pembayaranId]['metode_pembayaran'],
            'catatan' => $this->pembayarans[$pembayaranId]['catatan'] ?? null,
        ]);

        $this->dispatch('notify', message: 'Data pembayaran berhasil diupdate!');
        $this->mount($this->penjualanId); // Refresh data
    }

    private function checkAndUpdatePenjualanStatus()
    {
        $totalCicilan = $this->penjualan->pembayaranPenjualan->count();
        $cicilanLunas = $this->penjualan->pembayaranPenjualan()->where('status_bayar', 'lunas')->count();
        
        if ($totalCicilan > 0 && $cicilanLunas === $totalCicilan) {
            $this->penjualan->update(['status' => 'selesai']);
        }
    }

    public function render()
    {
        return view('livewire.penjualan-termin-update');
    }
}
