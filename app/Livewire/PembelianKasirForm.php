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
    public $gudang_id = null;
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
        $user = Auth::user();
        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            $this->gudang_id = null;
        } else {
            $this->gudang_id = $user->gudang_id;
        }
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
                $total = collect($this->items)->sum(function($item) { return (float)$item['qty'] * (float)$item['harga']; });
                $this->termins = [
                    [
                        'jumlah' => $total ?? 0,
                        'tanggal_jatuh_tempo' => $this->tanggal_mulai_termin ?: $this->tanggal,
                    ]
                ];
                // Jika total null atau kosong, tetap set 0
                if (!isset($this->termins[0]['jumlah']) || $this->termins[0]['jumlah'] === null) {
                    $this->termins[0]['jumlah'] = 0;
                }
        } elseif ($this->termin === '2') {
            $this->generateTerminBertahap();
        } else {
            $this->termins = [];
        }
    }

        public function updated($property, $value)
    {
        // Jika ada perubahan pada items (barang/qty/harga), update harga dan termin/cicilan
        if (str_starts_with($property, 'items')) {
            // Update harga otomatis jika barang dipilih
            $parts = explode('.', $property);
            if (isset($parts[2]) && $parts[2] === 'barang_master_id') {
                $index = $parts[1];
                $barangId = $this->items[$index]['barang_master_id'] ?? null;
                if ($barangId) {
                    $barang = \App\Models\BarangMaster::find($barangId);
                    if ($barang) {
                        $this->items[$index]['harga'] = $barang->harga_beli;
                    }
                }
            }
            // Update termin/cicilan setiap perubahan barang/qty/harga
            $total = collect($this->items)->sum(function($item) { return (float)$item['qty'] * (float)$item['harga']; });
            if ($this->termin === '2') {
                $this->generateTerminBertahap();
            } elseif ($this->termin === '1') {
                if (isset($this->termins[0])) {
                    $this->termins[0]['jumlah'] = $total;
                }
            }
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
        $total = collect($this->items)->sum(function($item) { return (float)$item['qty'] * (float)$item['harga']; });
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
        // Validasi manual dengan SweetAlert
        $user = Auth::user();
        
        // Cek gudang untuk superadmin
        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            if (!$this->gudang_id) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Pilih gudang terlebih dahulu!'
                ]);
                return;
            }
        }

        // Cek tanggal
        if (!$this->tanggal) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Tanggal harus diisi!'
            ]);
            return;
        }

        // Cek pemasok
        if (!$this->pemasok_id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Pilih pemasok terlebih dahulu!'
            ]);
            return;
        }


        // Cek items
        if (empty($this->items)) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Tambahkan minimal 1 barang!'
            ]);
            return;
        }

        // Cek setiap item
        foreach ($this->items as $index => $item) {
            if (empty($item['barang_master_id'])) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Pilih barang pada baris ' . ($index + 1) . '!'
                ]);
                return;
            }
            if (!isset($item['qty']) || $item['qty'] < 1) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Qty harus diisi minimal 1 pada baris ' . ($index + 1) . '!'
                ]);
                return;
            }
        }

        // Cek no faktur supplier
        if (empty($this->no_faktur_supplier)) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'No Faktur Supplier wajib diisi!'
            ]);
            return;
        }

        // Validasi termin
        if ($this->termin === '1') {
            if (!isset($this->termins[0]['tanggal_jatuh_tempo']) || empty($this->termins[0]['tanggal_jatuh_tempo'])) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Tanggal jatuh tempo termin harus diisi!'
                ]);
                return;
            }
        } elseif ($this->termin === '2') {
            if ($this->jumlah_termin < 2) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Jumlah termin minimal 2!'
                ]);
                return;
            }
            if (empty($this->tanggal_mulai_termin)) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Tanggal mulai termin harus diisi!'
                ]);
                return;
            }
            foreach ($this->termins as $index => $termin) {
                if (empty($termin['tanggal_jatuh_tempo'])) {
                    $this->dispatch('show-alert', [
                        'type' => 'error',
                        'message' => 'Tanggal jatuh tempo termin ke-' . ($index + 1) . ' harus diisi!'
                    ]);
                    return;
                }
            }
        }

        $rules = [
            'pemasok_id' => 'required|exists:pemasok,id',
            'tanggal' => 'required|date',
            'items.*.barang_master_id' => 'required|exists:barang_master,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'no_faktur_supplier' => 'required|string|max:100',
            'termin' => 'in:0,1,2',
            'termins.*.jumlah' => 'required_if:termin,1,2|numeric|min:0',
            'termins.*.tanggal_jatuh_tempo' => 'required_if:termin,1,2|date',
        ];
        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            $rules['gudang_id'] = 'required|exists:gudang,id';
        }
        $this->validate($rules);

        DB::transaction(function () {
            $total = collect($this->items)->sum(function($item) { return (float)$item['qty'] * (float)$item['harga']; });
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
                'gudang_id' => $this->gudang_id,
            ]);

            foreach ($this->items as $item) {
                $pembelian->detailPembelian()->create([
                    'barang_master_id' => $item['barang_master_id'],
                    'jumlah' => $item['qty'],
                    'harga_beli' => $item['harga'],
                    'total' => (float)$item['qty'] * (float)$item['harga'],
                ]);

                // Update stok barang
                $stok = \App\Models\StokBarang::firstOrNew([
                    'barang_master_id' => $item['barang_master_id'],
                    'gudang_id' => $this->gudang_id,
                ]);
                $stok->jumlah = ($stok->jumlah ?? 0) + $item['qty'];
                $stok->save();
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
        // $key format: "{index}.barang_master_id" atau "{index}.qty" atau "{index}.harga"
        $parts = explode('.', $key);
        $index = $parts[0];
        // Update harga otomatis jika barang dipilih
        if (isset($parts[1]) && $parts[1] === 'barang_master_id') {
            $barangId = $this->items[$index]['barang_master_id'] ?? null;
            if ($barangId) {
                $barang = \App\Models\BarangMaster::find($barangId);
                if ($barang) {
                    $this->items[$index]['harga'] = $barang->harga_beli;
                }
            }
        }
        // Update termin setiap kali ada perubahan barang/qty/harga
        $total = collect($this->items)->sum(function($item) { return (float)$item['qty'] * (float)$item['harga']; });
        if ($this->termin === '2') {
            $this->generateTerminBertahap();
        } elseif ($this->termin === '1') {
            if (isset($this->termins[0])) {
                $this->termins[0]['jumlah'] = $total;
            }
        }
    }

    public function render()
    {
        $user = Auth::user();
        $gudangs = [];
        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            $gudangs = \App\Models\Gudang::all();
        }
        return view('livewire.pembelian-kasir-form', [
            'pemasoks' => Pemasok::all(),
            'barangs' => BarangMaster::all(),
            'gudangs' => $gudangs,
        ]);
    }
}
