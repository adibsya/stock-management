<?php
namespace App\Livewire;
use Livewire\Component;
use App\Models\BarangMaster;
use App\Models\Gudang;
use App\Models\MutasiStok;
use Illuminate\Support\Facades\Auth;
class MutasiStokForm extends Component
{
    public $barang_id = '';
    public $jumlah = '';
    public $gudang_asal_id = '';
    public $gudang_tujuan_id = '';
    public $catatan = '';
    public function render()
    {
        $barangs = BarangMaster::orderBy('nama_barang')->get();
        $gudangs = Gudang::orderBy('nama_gudang')->get();
        return view('livewire.mutasi-stok-form', compact('barangs', 'gudangs'));
    }
    public function simpan()
    {
        $this->validate([
            'barang_id' => 'required|exists:barang_master,id',
            'jumlah' => 'required|integer|min:1',
            'gudang_asal_id' => 'required|different:gudang_tujuan_id|exists:gudang,id',
            'gudang_tujuan_id' => 'required|exists:gudang,id',
        ]);
        // Update stok di gudang asal
        $stokAsal = \App\Models\StokBarang::where('barang_master_id', $this->barang_id)
            ->where('gudang_id', $this->gudang_asal_id)
            ->first();
        $stokTersedia = $stokAsal ? $stokAsal->jumlah : 0;
        if ($stokTersedia < $this->jumlah || $stokTersedia == 0) {
            session()->flash('error', 'Stok di gudang asal tidak cukup! Stok tersedia: ' . $stokTersedia);
            return;
        }
        $stokAsal->decrement('jumlah', $this->jumlah);

        // Update/tambah stok di gudang tujuan
        $stokTujuan = \App\Models\StokBarang::firstOrCreate([
            'barang_master_id' => $this->barang_id,
            'gudang_id' => $this->gudang_tujuan_id,
        ], ['jumlah' => 0]);
        $stokTujuan->increment('jumlah', $this->jumlah);

        MutasiStok::create([
            'barang_id' => $this->barang_id,
            'jumlah' => $this->jumlah,
            'gudang_asal_id' => $this->gudang_asal_id,
            'gudang_tujuan_id' => $this->gudang_tujuan_id,
            'user_id' => Auth::id(),
            'catatan' => $this->catatan,
        ]);
        $this->reset(['barang_id','jumlah','gudang_asal_id','gudang_tujuan_id','catatan']);
        session()->flash('success', 'Mutasi stok berhasil disimpan!');
    }
}
