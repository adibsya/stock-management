<?php
namespace App\Livewire;
use Illuminate\Support\Facades\Auth;

use App\Models\Gudang;
use App\Models\Barang;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Services\TransaksiService;
use Livewire\Component;

class PointOfSale extends Component
{
    public array $cart = [];
    public string $searchBarang = '';
    public ?int $pelanggan_id = null;
    public string $metode_pembayaran = 'tunai';
    public string $diskon_transaksi = '0';
    public string $bayar = '0';
    public ?int $gudang_id = null;
    public int $jumlah_cicilan = 1;
    public array $termin_cicilan = [];

    protected $listeners = ['refreshCart' => '$refresh'];

    public function updatedJumlahCicilan()
    {
        // Initialize termin_cicilan array based on jumlah_cicilan
        $this->termin_cicilan = [];
        $jumlahPerCicilan = $this->total > 0 ? round($this->total / $this->jumlah_cicilan, 2) : 0;
        
        for ($i = 1; $i <= $this->jumlah_cicilan; $i++) {
            $this->termin_cicilan[$i] = [
                'jumlah' => $jumlahPerCicilan,
                'tanggal_jatuh_tempo' => null,
            ];
        }
    }

    public function addToCart(int $barangId): void
    {
        // Load barang dengan master untuk mendapatkan info lengkap
        $barang = Barang::with('master')->find($barangId);
        if (!$barang) {
            $this->dispatch('notify', message: 'Barang tidak ditemukan!');
            return;
        }
        
        // Cek stok real-time
        if ($barang->stok <= 0) {
            $this->dispatch('notify', message: 'Stok barang habis!');
            return;
        }

        // Ambil harga dari BarangMaster
        $harga_jual = $barang->master->harga_jual ?? $barang->harga_jual;

        // Cek apakah barang sudah ada di cart
        $key = array_search($barangId, array_column($this->cart, 'barang_id'));
        if ($key !== false) {
            // Cek stok sebelum menambah
            if ($this->cart[$key]['jumlah'] >= $barang->stok) {
                $this->dispatch('notify', message: 'Stok tidak mencukupi! Stok tersedia: ' . $barang->stok);
                return;
            }
            $this->cart[$key]['jumlah']++;
            $this->cart[$key]['subtotal'] = $this->cart[$key]['jumlah'] * $this->cart[$key]['harga_satuan'];
        } else {
            $this->cart[] = [
                'barang_id' => $barang->id,
                'kode_barang' => $barang->kode_barang,
                'nama_barang' => $barang->nama_barang,
                'satuan' => $barang->satuan,
                'harga_satuan' => $harga_jual,
                'jumlah' => 1,
                'subtotal' => $harga_jual,
                'stok' => $barang->stok,
            ];
        }

        $this->searchBarang = '';
        $this->dispatch('notify', message: 'Berhasil ditambahkan ke keranjang!');
    }

    public function updateQty(int $index, int $qty): void
    {
        if (!isset($this->cart[$index])) return;

        if ($qty <= 0) {
            $this->removeFromCart($index);
            return;
        }

        // Cek stok real-time dari database
        $barang = Barang::find($this->cart[$index]['barang_id']);
        if (!$barang || $qty > $barang->stok) {
            $this->dispatch('notify', message: 'Stok tidak mencukupi! Stok tersedia: ' . ($barang ? $barang->stok : 0));
            return;
        }

        $this->cart[$index]['jumlah'] = $qty;
        $this->cart[$index]['stok'] = $barang->stok; // Update stok di cart
        $this->cart[$index]['subtotal'] = $qty * $this->cart[$index]['harga_satuan'];
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
        $this->diskon_transaksi = '0';
        $this->bayar = '0';
    }

    public function getSubtotalProperty(): float
    {
        return collect($this->cart)->sum('subtotal');
    }

    public function getDiskonProperty(): float
    {
        return (float) $this->diskon_transaksi;
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

    public function prosesTransaksi(): void
    {
        if (empty($this->cart)) {
            $this->dispatch('notify', message: 'Keranjang kosong!');
            return;
        }

        if (!$this->gudang_id) {
            $this->dispatch('notify', message: 'Pilih gudang terlebih dahulu!');
            return;
        }

        // Validasi untuk termin
        if ($this->metode_pembayaran === 'termin') {
            foreach ($this->termin_cicilan as $key => $cicilan) {
                if (empty($cicilan['tanggal_jatuh_tempo'])) {
                    $this->dispatch('notify', message: 'Lengkapi tanggal jatuh tempo untuk semua cicilan!', type: 'error');
                    return;
                }
            }
        } else {
            // Validasi untuk non-termin
            $bayar = (float) $this->bayar;
            if ($bayar < $this->total) {
                $this->dispatch('notify', message: 'Pembayaran kurang!');
                return;
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
                ];
            }, $this->cart);

            $penjualan = $transaksiService->simpanPenjualan([
                'user_id' => $user->id,
                'pelanggan_id' => $this->pelanggan_id,
                'gudang_id' => $this->gudang_id,
                'items' => $items,
                'diskon_transaksi' => $this->diskon,
                'metode_pembayaran' => $this->metode_pembayaran,
            ]);

            // Jika metode pembayaran termin, simpan detail cicilan
            if ($this->metode_pembayaran === 'termin') {
                foreach ($this->termin_cicilan as $key => $cicilan) {
                    \App\Models\PembayaranPenjualan::create([
                        'penjualan_id' => $penjualan->id,
                        'tanggal_bayar' => $cicilan['tanggal_jatuh_tempo'],
                        'jumlah_bayar' => $cicilan['jumlah'],
                        'metode_pembayaran' => 'termin',
                        'catatan' => 'Cicilan ke-' . $key . ' dari ' . $this->jumlah_cicilan . ' cicilan',
                    ]);
                }
            }

            $this->clearCart();
            $this->metode_pembayaran = 'tunai';
            $this->jumlah_cicilan = 1;
            $this->termin_cicilan = [];
            $this->bayar = '0';
            $this->dispatch('notify', message: 'Transaksi berhasil! No Faktur: ' . $penjualan->no_faktur);

        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Error: ' . $e->getMessage());
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
                $this->gudang_id = $user->gudang->id;
            }
        }

        // Query barang dengan stok > 0
        $barangsQuery = Barang::query()
            ->with(['master', 'gudang'])
            ->where('stok', '>', 0)
            ->when($this->gudang_id, function ($query) {
                $query->where('gudang_id', $this->gudang_id);
            })
            ->when($this->searchBarang, function ($query) {
                $query->whereHas('master', function ($q) {
                    $q->where('nama_barang', 'like', '%' . $this->searchBarang . '%')
                        ->orWhere('kode_barang', 'like', '%' . $this->searchBarang . '%');
                });
            });

        // Kelompokkan berdasarkan kategori dari master
        $barangs = $barangsQuery->get()->groupBy(function($barang) {
            return $barang->master->kategori ?? 'Lainnya';
        });

        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();

        return view('livewire.point-of-sale', [
            'barangs' => $barangs,
            'pelanggans' => $pelanggans,
            'gudangs' => $gudangs,
        ]);
    }
}
