<?php
namespace App\Livewire;
use Illuminate\Support\Facades\Auth;

use App\Models\Gudang;
use App\Models\BarangMaster;
use App\Models\StokBarang;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Services\TransaksiService;
use Livewire\Component;

class PointOfSale extends Component
{
    public string $bayar = '0';
    public array $cart = [];
    public string $searchBarang = '';
    public ?int $pelanggan_id = null;
    public string $termin = '0'; // '0' = tunai, '1' = termin sekali, '2' = termin bertahap
    public array $termins = [];
    public string $jumlah_termin = '2';
    public string $tanggal_mulai_termin = '';
    public ?int $gudang_id = null;
    public string $diskon = '0';

    public function updatedGudangId($value)
    {
        $this->cart = []; // Clear cart when gudang changes
    }

    public function updatedTermin($value)
    {
        $this->setDefaultTermins();
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

    private function setDefaultTermins()
    {
        $total = $this->total;
        if ($this->termin === '1') {
            $this->termins = [
                [
                    'jumlah' => $total,
                    'tanggal_jatuh_tempo' => $this->tanggal_mulai_termin ?: date('Y-m-d'),
                ]
            ];
        } elseif ($this->termin === '2') {
            $this->generateTerminBertahap();
        } else {
            $this->termins = [];
        }
    }

    private function generateTerminBertahap()
    {
        $total = $this->total;
        $jumlah_termin = max(2, (int) $this->jumlah_termin);
        $tanggal_mulai = $this->tanggal_mulai_termin ?: date('Y-m-d');
        $cicilan = floor($total / $jumlah_termin);
        $sisa = $total - ($cicilan * $jumlah_termin);
        $termins = [];
        for ($i = 0; $i < $jumlah_termin; $i++) {
            $jumlah = $cicilan + ($i == 0 ? $sisa : 0);
            $tanggal = date('Y-m-d', strtotime("$tanggal_mulai +$i month"));
            $termins[] = [
                'jumlah' => $jumlah,
                'tanggal_jatuh_tempo' => $tanggal,
            ];
        }
        $this->termins = $termins;
    }

    protected $listeners = ['refreshCart' => '$refresh'];

    // Untuk polling otomatis Livewire (real-time)
    public $refreshInterval = 5; // detik

    public function addToCart(int $barangMasterId): void
    {
        if (!$this->gudang_id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Pilih gudang terlebih dahulu!'
            ]);
            return;
        }
        $stok = StokBarang::where('barang_master_id', $barangMasterId)
            ->where('gudang_id', $this->gudang_id)
            ->first();
        if (!$stok || $stok->jumlah <= 0) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Stok barang habis di gudang ini!'
            ]);
            return;
        }
        $barang = BarangMaster::find($barangMasterId);
        if (!$barang) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Barang tidak ditemukan!'
            ]);
            return;
        }
        // Logic khusus untuk nukleer/rokok
        $isNukleer = false;
        $nama = strtolower($barang->nama_barang);
        $kode = strtolower($barang->kode_barang);
        if (strpos($nama, 'nuklerr') !== false || strpos($kode, 'rkk') !== false) {
            $isNukleer = true;
        }

        $harga_jual = $barang->harga_jual;
        $bonus = 0;
        if ($isNukleer) {
            $qty = 1;
            $key = array_search($barangMasterId, array_column($this->cart, 'barang_id'));
            if ($key !== false) {
                $qty = $this->cart[$key]['jumlah'] + 1;
            }
            
            // Hitung bonus berdasarkan qty
            if ($qty >= 600) {
                $bonus = floor($qty / 600) * 30; // 600 pcs bonus 30
            } elseif ($qty >= 100) {
                $bonus = floor($qty / 100) * 5; // 100 pcs bonus 5
            } elseif ($qty >= 20) {
                $bonus = floor($qty / 20) * 1; // 20 pcs bonus 1
            }
            
            // Bundling logic
            $bundles = [600 => 5100000, 100 => 870000, 10 => 91000];
            $sisa = $qty;
            $subtotal = 0;
            foreach ($bundles as $bundleQty => $bundlePrice) {
                $bundleCount = intdiv($sisa, $bundleQty);
                $subtotal += $bundleCount * $bundlePrice;
                $sisa -= $bundleCount * $bundleQty;
            }
            $subtotal += $sisa * $harga_jual;
            $harga_jual = $harga_jual; // harga satuan untuk sisa
        }
        // Cari item di cart secara manual untuk memastikan index yang tepat
        $key = null;
        foreach ($this->cart as $index => $item) {
            if ($item['barang_id'] == $barangMasterId) {
                // Pastikan tipe data sama atau bandingkan secara loose
                $key = $index;
                break;
            }
        }

        if ($key !== null) {
            if ($this->cart[$key]['jumlah'] >= $stok->jumlah) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Stok tidak mencukupi!'
                ]);
                return;
            }
            $this->cart[$key]['jumlah']++;
            if ($isNukleer) {
                $qty = $this->cart[$key]['jumlah'];
                
                // Hitung bonus berdasarkan qty
                if ($qty >= 600) {
                    $bonus = floor($qty / 600) * 30;
                } elseif ($qty >= 100) {
                    $bonus = floor($qty / 100) * 5;
                } elseif ($qty >= 20) {
                    $bonus = floor($qty / 20) * 1;
                }
                
                $bundles = [600 => 5100000, 100 => 870000, 10 => 91000];
                $sisa = $qty;
                $subtotal = 0;
                foreach ($bundles as $bundleQty => $bundlePrice) {
                    $bundleCount = intdiv($sisa, $bundleQty);
                    $subtotal += $bundleCount * $bundlePrice;
                    $sisa -= $bundleCount * $bundleQty;
                }
                $subtotal += $sisa * $barang->harga_jual;
                
                $this->cart[$key]['subtotal'] = $subtotal;
                $this->cart[$key]['harga_satuan'] = $barang->harga_jual;
                $this->cart[$key]['bonus'] = $bonus;
            } else {
                $this->cart[$key]['subtotal'] = $this->cart[$key]['jumlah'] * $this->cart[$key]['harga_satuan'];
            }
        } else {
            $subtotal = $harga_jual;
            if ($isNukleer) {
                $bundles = [600 => 5100000, 100 => 870000, 10 => 91000];
                $sisa = 1;
                $subtotal = 0;
                foreach ($bundles as $bundleQty => $bundlePrice) {
                    $bundleCount = intdiv($sisa, $bundleQty);
                    $subtotal += $bundleCount * $bundlePrice;
                    $sisa -= $bundleCount * $bundleQty;
                }
                $subtotal += $sisa * $harga_jual;
            }
            $this->cart[] = [
                'barang_id' => $barang->id,
                'kode_barang' => $barang->kode_barang,
                'nama_barang' => $barang->nama_barang,
                'satuan' => $barang->satuan,
                'harga_satuan' => $harga_jual,
                'jumlah' => 1,
                'subtotal' => $subtotal,
                'stok' => $stok->jumlah,
                'satuan' => $barang->satuan,
                'bonus' => $isNukleer ? $bonus : 0,
            ];
        }
        $this->searchBarang = '';
        $this->dispatch('notify', message: 'Berhasil ditambahkan ke keranjang!');
    }

    public function updateQty(int $index, $qty): void
    {
        $qty = (int) $qty;
        if (!isset($this->cart[$index])) return;
        if ($qty <= 0) {
            $this->removeFromCart($index);
            return;
        }
        // Cek stok terbaru dari database
        $stok = StokBarang::where('barang_master_id', $this->cart[$index]['barang_id'])
            ->where('gudang_id', $this->gudang_id)
            ->first();
        $stokJumlah = $stok ? $stok->jumlah : 0;
        if ($qty > $stokJumlah) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Stok tidak mencukupi!'
            ]);
            return;
        }
        $this->cart[$index]['jumlah'] = $qty;
        // Logic harga & bonus khusus nukleer
        $barang = BarangMaster::find($this->cart[$index]['barang_id']);
        $isNukleer = false;
        $nama = strtolower($barang->nama_barang);
        $kode = strtolower($barang->kode_barang);
        if (strpos($nama, 'nuklerr') !== false || strpos($kode, 'rkk') !== false) {
            $isNukleer = true;
        }
        if ($isNukleer) {
            $bundles = [600 => 5100000, 100 => 870000, 10 => 91000];
            $sisa = $qty;
            $subtotal = 0;
            foreach ($bundles as $bundleQty => $bundlePrice) {
                $bundleCount = intdiv($sisa, $bundleQty);
                $subtotal += $bundleCount * $bundlePrice;
                $sisa -= $bundleCount * $bundleQty;
            }
            $subtotal += $sisa * $barang->harga_jual;
            $this->cart[$index]['subtotal'] = $subtotal;
            $this->cart[$index]['harga_satuan'] = $barang->harga_jual;
            
            // Hitung bonus berdasarkan qty
            $bonus = 0;
            if ($qty >= 600) {
                $bonus = floor($qty / 600) * 30;
            } elseif ($qty >= 100) {
                $bonus = floor($qty / 100) * 5;
            } elseif ($qty >= 20) {
                $bonus = floor($qty / 20) * 1;
            }
            $this->cart[$index]['bonus'] = $bonus;
        } else {
            $this->cart[$index]['subtotal'] = $qty * $this->cart[$index]['harga_satuan'];
        }
        $this->cart[$index]['stok'] = $stokJumlah;
    }

    public function removeFromCart(int $index): void
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    public function clearCart(): void
    {
        $this->cart = [];
        $this->pelanggan_id = null;
        $this->bayar = '0';
        $this->diskon = '0';
    }

    public function getSubtotalProperty(): float
    {
        return collect($this->cart)->sum('subtotal');
    }

    public function getDiskonProperty(): float
    {
        return (float) $this->diskon;
    }

    public function getTotalProperty(): float
    {
        return $this->subtotal - $this->diskon;
    }

    public function getKembalianProperty(): float
    {
        $bayar = (float) $this->bayar;
        return max(0, $bayar - $this->total);
    }

    /**
     * Set nilai bayar berdasarkan opsi quick amount
     * Method ini menghindari issue saat menggunakan $set() langsung di blade
     */
    public function setBayar(string $type): void
    {
        // Validasi cart - hapus item yang barangnya sudah tidak ada di database
        $this->validateCart();

        $total = $this->total;
        
        switch ($type) {
            case 'pas':
                $this->bayar = (string) $total;
                break;
            case '50rb':
                $this->bayar = (string) (ceil($total / 50000) * 50000);
                break;
            case '100rb':
                $this->bayar = (string) (ceil($total / 100000) * 100000);
                break;
            default:
                $this->bayar = (string) $total;
        }
    }

    /**
     * Validasi cart dan hapus item yang barangnya sudah tidak ada di database
     */
    protected function validateCart(): void
    {
        $validCart = [];
        foreach ($this->cart as $item) {
            $barang = BarangMaster::find($item['barang_id']);
            if ($barang) {
                // Update stok terbaru
                $item['stok'] = $barang->stok;
                $validCart[] = $item;
            }
        }
        
        if (count($validCart) !== count($this->cart)) {
            $this->cart = $validCart;
            $this->dispatch('notify', message: 'Beberapa barang telah dihapus dari keranjang karena sudah tidak tersedia.');
        }
    }

    public function prosesTransaksi(): void
    {
        // Validasi semua field wajib
        if (empty($this->cart)) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Keranjang kosong!'
            ]);
            return;
        }
        if (!$this->gudang_id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Pilih gudang terlebih dahulu!'
            ]);
            return;
        }
        if (!$this->pelanggan_id) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => 'Pilih pelanggan terlebih dahulu!'
            ]);
            return;
        }
        if ($this->termin === '1') {
            if (empty($this->termins) || empty($this->termins[0]['jumlah']) || empty($this->termins[0]['tanggal_jatuh_tempo'])) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Isi data termin (jumlah & tanggal jatuh tempo)!'
                ]);
                return;
            }
        }
        if ($this->termin === '2') {
            if (empty($this->jumlah_termin) || empty($this->tanggal_mulai_termin)) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'message' => 'Isi jumlah termin dan tanggal mulai termin!'
                ]);
                return;
            }
            foreach ($this->termins as $i => $termin) {
                if (empty($termin['jumlah']) || empty($termin['tanggal_jatuh_tempo'])) {
                    $this->dispatch('show-alert', [
                        'type' => 'error',
                        'message' => 'Isi semua jumlah & tanggal jatuh tempo termin!'
                    ]);
                    return;
                }
            }
        }

        try {
            $transaksiService = app(TransaksiService::class);
            $user = Auth::user();

            $items = array_map(function ($item) {
                return [
                    'barang_id' => $item['barang_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal' => $item['subtotal'], // Kirim subtotal yang sudah dihitung dengan bundling
                    'bonus' => $item['bonus'] ?? 0, // Kirim bonus jika ada
                ];
            }, $this->cart);

            $mode_termin = $this->termin === '0' ? 'cash' : 'termin';
            $status = 'selesai';
            if ($mode_termin === 'termin') {
                $status = 'belum_lunas';
            }
            $jatuh_tempo = null;
            if ($this->termin === '1' && isset($this->termins[0]['tanggal_jatuh_tempo'])) {
                $jatuh_tempo = $this->termins[0]['tanggal_jatuh_tempo'];
            }

            $penjualan = $transaksiService->simpanPenjualan([
                'user_id' => $user->id,
                'pelanggan_id' => $this->pelanggan_id,
                'gudang_id' => $this->gudang_id,
                'items' => $items,
                'mode_termin' => $mode_termin,
                'jatuh_tempo' => $jatuh_tempo,
                'status' => $status,
            ]);

            // Simpan termin bertahap jika ada
            if ($this->termin === '2' && is_array($this->termins)) {
                foreach ($this->termins as $termin) {
                    \App\Models\PembayaranPenjualan::create([
                        'penjualan_id' => $penjualan->id,
                        'jumlah' => $termin['jumlah'],
                        'tanggal_jatuh_tempo' => $termin['tanggal_jatuh_tempo'],
                        'status' => 'belum_lunas',
                    ]);
                }
            } elseif ($this->termin === '1' && isset($this->termins[0]['jumlah'])) {
                // Simpan satu termin jika termin sekali
                \App\Models\PembayaranPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'jumlah' => $this->termins[0]['jumlah'],
                    'tanggal_jatuh_tempo' => $this->termins[0]['tanggal_jatuh_tempo'],
                    'status' => 'belum_lunas',
                ]);
            }

            $this->clearCart();
            $this->dispatch('show-alert', [
                'type' => 'success',
                'message' => 'Transaksi berhasil! No Faktur: ' . $penjualan->no_faktur
            ]);

            // Buka halaman print invoice untuk transaksi termin
            if ($this->termin !== '0') {
                $this->dispatch('open-print-invoice', [
                    'url' => route('penjualan.print', $penjualan->id)
                ]);
            }

        } catch (\Exception $e) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        $user = Auth::user();
        $gudangs = collect();
        if ($user->isSuperAdmin()) {
            $gudangs = Gudang::orderBy('nama_gudang')->get();
        } elseif ($user->isAdmin()) {
            if ($user->gudang) {
                $gudangs = collect([$user->gudang]);
                if (!$this->gudang_id) {
                    $this->gudang_id = $user->gudang->id;
                }
            }
        }

        // Ambil semua barang master, stok per gudang (jika tidak ada stok, tampilkan 0)
        $barangs = BarangMaster::query()
            ->with(['stok' => function($q) {
                $q->where('gudang_id', $this->gudang_id);
            }])
            ->when($this->searchBarang, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_barang', 'like', '%' . $this->searchBarang . '%')
                        ->orWhere('kode_barang', 'like', '%' . $this->searchBarang . '%');
                });
            });

// $barangs = $barangs->get()->groupBy(function($barang) {
        //     return $barang->master->kategori ?? 'Lainnya';
        // });
        $barangs = $barangs->get();

        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();

        return view('livewire.point-of-sale', [
            'barangs' => $barangs,
            'pelanggans' => $pelanggans,
            'gudangs' => $gudangs,
        ]);
    }
}
