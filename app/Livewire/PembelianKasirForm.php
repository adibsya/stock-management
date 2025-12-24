<?php

namespace App\Livewire;

use App\Models\Pembelian;
use App\Models\Pemasok;
use App\Models\BarangMaster;
use App\Models\PembayaranPembelian;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PembelianKasirForm extends Component
{
    public $pemasok_id = '';
    public $tanggal = '';
    public $items = [];
    public $termin = false;
    public $termins = [];
    public $no_faktur_supplier = '';

    public function mount()
    {
        $this->tanggal = now()->format('Y-m-d');
        $this->items = [
            ['barang_master_id' => '', 'qty' => 1, 'harga' => 0],
        ];
        $this->termins = [
            ['jumlah' => 0, 'tanggal_jatuh_tempo' => ''],
        ];
    }

    public function addItem()
    {
        $this->items[] = ['barang_master_id' => '', 'qty' => 1, 'harga' => 0];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function addTermin()
    {
        $this->termins[] = ['jumlah' => 0, 'tanggal_jatuh_tempo' => ''];
    }

    public function removeTermin($index)
    {
        unset($this->termins[$index]);
        $this->termins = array_values($this->termins);
    }

    public function save()
    {
        $this->validate([
            'pemasok_id' => 'required|exists:pemasok,id',
            'tanggal' => 'required|date',
            'items.*.barang_master_id' => 'required|exists:barang_master,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'no_faktur_supplier' => 'nullable|string|max:100',
            'termin' => 'boolean',
            'termins.*.jumlah' => 'required_if:termin,true|numeric|min:0',
            'termins.*.tanggal_jatuh_tempo' => 'required_if:termin,true|date',
        ]);

        DB::transaction(function () {
            $total = collect($this->items)->sum(function($item) { return $item['qty'] * $item['harga']; });
            $status_bayar = $this->termin ? 'belum_lunas' : 'lunas';
            $jatuh_tempo = $this->termin && count($this->termins) ? $this->termins[0]['tanggal_jatuh_tempo'] : null;
            $pembelian = Pembelian::create([
                'pemasok_id' => $this->pemasok_id,
                'tanggal' => $this->tanggal,
                'no_faktur_supplier' => $this->no_faktur_supplier,
                'total_biaya' => $total,
                'status_bayar' => $status_bayar,
                'jatuh_tempo' => $jatuh_tempo,
            ]);

            foreach ($this->items as $item) {
                $pembelian->detailPembelian()->create([
                    'barang_master_id' => $item['barang_master_id'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                ]);
            }

            if ($this->termin) {
                foreach ($this->termins as $termin) {
                    PembayaranPembelian::create([
                        'pembelian_id' => $pembelian->id,
                        'jumlah' => $termin['jumlah'],
                        'tanggal_jatuh_tempo' => $termin['tanggal_jatuh_tempo'],
                        'status' => 'belum_lunas',
                    ]);
                }
            }
        });

        session()->flash('success', 'Transaksi pembelian berhasil disimpan!');
        return redirect()->route('pembelian.index');
    }

    public function render()
    {
        return view('livewire.pembelian-kasir-form', [
            'pemasoks' => Pemasok::all(),
            'barangs' => BarangMaster::all(),
        ]);
    }
}
