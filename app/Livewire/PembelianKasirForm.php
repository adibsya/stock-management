<?php

namespace App\Livewire;

use App\Models\Pembelian;
use Illuminate\Support\Facades\Auth;
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
    // '0' = tunai, '1' = termin sekali, '2' = termin bertahap
    public $termin = '0';
    public $termins = [];
    public $no_faktur_supplier = '';
    public $jumlah_termin = 2;
    public $tanggal_mulai_termin = '';

    public function mount()
    {
        $this->tanggal = now()->format('Y-m-d');
        $this->items = [
            ['barang_master_id' => '', 'qty' => 1, 'harga' => 0],
        ];
        $this->setDefaultTermins();
    }

    public function updatedTermin($value)
    {
        $this->setDefaultTermins();
    }

    private function setDefaultTermins()
    {
        if ($this->termin === '1') {
            $this->termins = [
                ['jumlah' => 0, 'tanggal_jatuh_tempo' => ''],
            ];
        } elseif ($this->termin === '2') {
            $this->generateTerminBertahap();
        } else {
            $this->termins = [];
        }
    }

    public function updatedJumlahTermin($value)
    {
        if ($this->termin === '2') {
            $this->generateTerminBertahap();
        }
    }

    public function updatedTanggalMulaiTermin($value)
    {
        if ($this->termin === '2') {
            $this->generateTerminBertahap();
        }
    }

    private function generateTerminBertahap()
    {
        $total = collect($this->items)->sum(function($item) { return $item['qty'] * $item['harga']; });
        $jumlah_termin = max(2, (int) $this->jumlah_termin);
        $tanggal_mulai = $this->tanggal_mulai_termin ?: now()->format('Y-m-d');
        $cicilan = floor($total / $jumlah_termin);
        $sisa = $total - ($cicilan * $jumlah_termin);
        $oldTermins = $this->termins;
        $termins = [];
        for ($i = 0; $i < $jumlah_termin; $i++) {
            $jumlah = $cicilan + ($i == $jumlah_termin - 1 ? $sisa : 0);
            // If user has already set a date for this termin, keep it
            $tanggal = $oldTermins[$i]['tanggal_jatuh_tempo'] ?? date('Y-m-d', strtotime("+{$i} month", strtotime($tanggal_mulai)));
            $termins[] = [
                'jumlah' => $jumlah,
                'tanggal_jatuh_tempo' => $tanggal,
            ];
        }
        $this->termins = $termins;
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
            'termin' => 'in:0,1,2',
            'termins.*.jumlah' => 'required_if:termin,1,2|numeric|min:0',
            'termins.*.tanggal_jatuh_tempo' => 'required_if:termin,1,2|date',
        ]);

        DB::transaction(function () {
            $total = collect($this->items)->sum(function($item) { return $item['qty'] * $item['harga']; });
            $mode_termin = 'cash';
            $status_bayar = 'lunas';
            $jatuh_tempo = null;
            if ($this->termin === '1') {
                $mode_termin = 'termin';
                $status_bayar = 'belum_lunas';
                $jatuh_tempo = count($this->termins) ? $this->termins[0]['tanggal_jatuh_tempo'] : null;
            } elseif ($this->termin === '2') {
                $mode_termin = 'termin_bertahap';
                $status_bayar = 'belum_lunas';
                // Ambil tanggal jatuh tempo termin pertama (paling awal)
                $jatuh_tempo = null;
                if (count($this->termins)) {
                    $tanggalList = array_column($this->termins, 'tanggal_jatuh_tempo');
                    sort($tanggalList);
                    $jatuh_tempo = $tanggalList[0] ?? null;
                    // Tambahkan keterangan ke mode_termin
                    $mode_termin .= ' (Jatuh tempo pertama: ' . $jatuh_tempo . ')';
                }
            }
            $pembelian = Pembelian::create([
                'pemasok_id' => $this->pemasok_id,
                'tanggal' => $this->tanggal,
                'no_faktur_supplier' => $this->no_faktur_supplier,
                'total_biaya' => $total,
                'status_bayar' => $status_bayar,
                'jatuh_tempo' => $jatuh_tempo,
                'mode_termin' => $mode_termin,
                'user_id' => Auth::id(),
            ]);

            foreach ($this->items as $item) {
                $pembelian->detailPembelian()->create([
                    'barang_id' => $item['barang_master_id'],
                    'jumlah' => $item['qty'],
                    'harga_beli' => $item['harga'],
                    'total' => $item['qty'] * $item['harga'],
                ]);
            }

            if ($this->termin === '1' || $this->termin === '2') {
                foreach ($this->termins as $termin) {
                    PembayaranPembelian::create([
                        'pembelian_id' => $pembelian->id,
                        'tanggal_bayar' => null, // Belum dibayar
                        'jumlah_bayar' => 0, // Belum dibayar
                        'metode_pembayaran' => 'termin',
                        'catatan' => null,
                        // Simpan info termin tambahan jika perlu
                        'tanggal_jatuh_tempo' => $termin['tanggal_jatuh_tempo'],
                        'jumlah' => $termin['jumlah'],
                        'status' => 'belum_lunas',
                    ]);
                }
            }
        });

        session()->flash('success', 'Transaksi pembelian berhasil disimpan!');
        return redirect()->route('pembelian.index');
    }

        // ...existing code...

        public function updatedItems($value, $key)
        {
            // $key format: "{index}.barang_master_id" jika barang dipilih
            if (str_ends_with($key, 'barang_master_id')) {
                $parts = explode('.', $key);
                $index = $parts[0];
                $barangId = $this->items[$index]['barang_master_id'] ?? null;
                if ($barangId) {
                    $barang = \App\Models\BarangMaster::find($barangId);
                    if ($barang) {
                        $this->items[$index]['harga'] = $barang->harga_beli;
                    }
                }
            }
        }

    public function render()
    {
        return view('livewire.pembelian-kasir-form', [
            'pemasoks' => Pemasok::all(),
            'barangs' => BarangMaster::all(),
        ]);
    }
}
