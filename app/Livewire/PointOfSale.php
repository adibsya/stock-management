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

    /**
     * Hook saat diskon diubah - validasi cart terlebih dahulu
     */
    public function updatedDiskonTransaksi(): void
    {
        $this->validateCart();
    }

    /**
     * Hook saat bayar diubah - validasi cart terlebih dahulu
     */
    public function updatedBayar(): void
    {
        $this->validateCart();
    }

    /**
     * Hook saat gudang diubah - kosongkan cart karena barang berbeda per gudang
     * Hanya clear cart jika memang ada barang dan user yang ubah
     */
    public function updatedGudangId($value): void
    {
        // Hanya kosongkan cart jika ada item dan gudang benar-benar berubah
        if (!empty($this->cart)) {
            // Cek apakah ada barang yang tidak sesuai dengan gudang baru
            $hasInvalidItems = false;
            foreach ($this->cart as $item) {
                $barang = Barang::where('id', $item['barang_id'])
                    ->where('gudang_id', $value)
                    ->first();
                if (!$barang) {
                    $hasInvalidItems = true;
                    break;
                }
            }
            
            if ($hasInvalidItems) {
                $this->cart = [];
                $this->bayar = '0';
                $this->diskon_transaksi = '0';
                $this->dispatch('notify', message: 'Keranjang dikosongkan karena gudang berubah.');
            }
        }
    }

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
            $barang = Barang::find($item['barang_id']);
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
                    'gudang_id' => $this->gudang_id,
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
                        'status_bayar' => 'pending',
                        'catatan' => 'Cicilan ke-' . $key . ' dari ' . $this->jumlah_cicilan . ' cicilan',
                    ]);
                }
            }

            $this->clearCart();
            $this->metode_pembayaran = 'tunai';
            $this->jumlah_cicilan = 1;
            $this->termin_cicilan = [];
            $this->bayar = '0';
            
            // Log untuk debugging
            \Log::info('Transaksi berhasil disimpan', [
                'penjualan_id' => $penjualan->id,
                'no_faktur' => $penjualan->no_faktur,
                'status' => $penjualan->status,
                'metode_pembayaran' => $penjualan->metode_pembayaran,
                'total_bayar' => $penjualan->total_bayar,
            ]);
            
            // Redirect ke halaman penjualan dengan pesan sukses
            session()->flash('success', 'Transaksi berhasil! No Faktur: ' . $penjualan->no_faktur);
            $this->dispatch('notify', message: 'Transaksi berhasil! No Faktur: ' . $penjualan->no_faktur);

        } catch (\Exception $e) {
            \Log::error('Error saat menyimpan transaksi: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
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
                // Hanya set gudang_id jika belum ada, untuk mencegah trigger updatedGudangId
                if ($this->gudang_id === null) {
                    $this->gudang_id = $user->gudang->id;
                }
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
