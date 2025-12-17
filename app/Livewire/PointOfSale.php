<?php

namespace App\Livewire;

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

    protected $listeners = ['refreshCart' => '$refresh'];

    public function addToCart(int $barangId): void
    {
        $barang = Barang::find($barangId);
        
        if (!$barang) {
            $this->dispatch('notify', message: 'Barang tidak ditemukan!');
            return;
        }

        if ($barang->stok <= 0) {
            $this->dispatch('notify', message: 'Stok barang habis!');
            return;
        }

        $key = array_search($barangId, array_column($this->cart, 'barang_id'));
        
        if ($key !== false) {
            // Cek stok
            if ($this->cart[$key]['jumlah'] >= $barang->stok) {
                $this->dispatch('notify', message: 'Stok tidak mencukupi!');
                return;
            }
            $this->cart[$key]['jumlah']++;
            $this->cart[$key]['subtotal'] = $this->cart[$key]['jumlah'] * $this->cart[$key]['harga_satuan'];
        } else {
            $this->cart[] = [
                'barang_id' => $barang->id,
                'kode_barang' => $barang->kode_barang,
                'nama_barang' => $barang->nama_barang,
                'harga_satuan' => $barang->harga_jual,
                'jumlah' => 1,
                'subtotal' => $barang->harga_jual,
                'stok' => $barang->stok,
            ];
        }

        $this->searchBarang = '';
    }

    public function updateQty(int $index, int $qty): void
    {
        if (!isset($this->cart[$index])) return;

        if ($qty <= 0) {
            $this->removeFromCart($index);
            return;
        }

        if ($qty > $this->cart[$index]['stok']) {
            $this->dispatch('notify', message: 'Stok tidak mencukupi!');
            return;
        }

        $this->cart[$index]['jumlah'] = $qty;
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

        $bayar = (float) $this->bayar;
        if ($bayar < $this->total) {
            $this->dispatch('notify', message: 'Pembayaran kurang!');
            return;
        }

        try {
            $transaksiService = app(TransaksiService::class);
            
            $items = array_map(function ($item) {
                return [
                    'barang_id' => $item['barang_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                ];
            }, $this->cart);

            $penjualan = $transaksiService->simpanPenjualan([
                'user_id' => 1, // TODO: Ganti dengan auth user
                'pelanggan_id' => $this->pelanggan_id,
                'items' => $items,
                'diskon_transaksi' => $this->diskon,
                'metode_pembayaran' => $this->metode_pembayaran,
            ]);

            $this->clearCart();
            $this->dispatch('notify', message: 'Transaksi berhasil! No Faktur: ' . $penjualan->no_faktur);
            
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $barangs = Barang::query()
            ->where('stok', '>', 0)
            ->when($this->searchBarang, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_barang', 'like', '%' . $this->searchBarang . '%')
                        ->orWhere('kode_barang', 'like', '%' . $this->searchBarang . '%');
                });
            })
            ->limit(20)
            ->get();

        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();

        return view('livewire.point-of-sale', [
            'barangs' => $barangs,
            'pelanggans' => $pelanggans,
        ]);
    }
}
