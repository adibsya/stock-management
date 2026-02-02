<?php

namespace App\Livewire;

use App\Models\BarangMaster;
use App\Models\Gudang;
use App\Models\Pelanggan;
use App\Models\PembayaranPenjualan;
use App\Models\Penjualan;
use App\Models\StokBarang;
use App\Services\TransaksiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PointOfSale extends Component
{
    // Konstanta untuk tipe termin
    public const TERMIN_TUNAI = '0';
    public const TERMIN_SEKALI = '1';
    public const TERMIN_BERTAHAP = '2';

    // Public properties
    public string $bayar = '0';
    public array $cart = [];
    public string $searchBarang = '';
    public ?int $pelanggan_id = null;
    public string $termin = self::TERMIN_TUNAI;
    public array $termins = [];
    public string $jumlah_termin = '2';
    public string $tanggal_mulai_termin = '';
    public ?int $gudang_id = null;
    public string $diskon = '';

    protected $listeners = ['refreshCart' => '$refresh'];

    /*
    |--------------------------------------------------------------------------
    | Lifecycle Hooks
    |--------------------------------------------------------------------------
    */

    public function updatedGudangId($value): void
    {
        $this->cart = [];
    }

    public function updatedTermin($value): void
    {
        $this->setDefaultTermins();
    }

    public function updatedJumlahTermin($value): void
    {
        if ($this->termin === self::TERMIN_BERTAHAP) {
            $this->generateTerminBertahap();
        }
    }

    public function updatedTanggalMulaiTermin($value): void
    {
        if ($this->termin === self::TERMIN_BERTAHAP) {
            $this->generateTerminBertahap();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Termin Methods
    |--------------------------------------------------------------------------
    */

    private function setDefaultTermins(): void
    {
        $total = $this->total;

        match ($this->termin) {
            self::TERMIN_SEKALI => $this->termins = [[
                'jumlah' => $total,
                'tanggal_jatuh_tempo' => $this->tanggal_mulai_termin ?: date('Y-m-d'),
            ]],
            self::TERMIN_BERTAHAP => $this->generateTerminBertahap(),
            default => $this->termins = [],
        };
    }

    private function generateTerminBertahap(): void
    {
        $total = $this->total;
        $jumlahTermin = max(2, (int) $this->jumlah_termin);
        $tanggalMulai = $this->tanggal_mulai_termin ?: date('Y-m-d');
        $cicilan = floor($total / $jumlahTermin);
        $sisa = $total - ($cicilan * $jumlahTermin);

        $this->termins = collect(range(0, $jumlahTermin - 1))
            ->map(fn($i) => [
                'jumlah' => $cicilan + ($i === 0 ? $sisa : 0),
                'tanggal_jatuh_tempo' => date('Y-m-d', strtotime("$tanggalMulai +$i month")),
            ])
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Cart Methods
    |--------------------------------------------------------------------------
    */

    public function addToCart(int $barangMasterId): void
    {
        // Validasi gudang
        if (!$this->validateGudangSelected()) {
            return;
        }

        // Get stok dan validasi
        $stok = $this->getStokBarang($barangMasterId);
        if (!$this->validateStokAvailable($stok)) {
            return;
        }

        // Get barang
        $barang = BarangMaster::find($barangMasterId);
        if (!$barang) {
            $this->showError('Barang tidak ditemukan!');
            return;
        }

        // Process cart
        $this->processCartItem($barang, $stok);

        // Reset search dan notify
        $this->searchBarang = '';
        $this->dispatch('notify', message: 'Berhasil ditambahkan ke keranjang!');
    }

    private function processCartItem(BarangMaster $barang, StokBarang $stok): void
    {
        $isNukleer = $this->isNukleerProduct($barang);
        $existingKey = $this->findCartItemIndex($barang->id);

        if ($existingKey !== null) {
            $this->updateExistingCartItem($existingKey, $barang, $stok, $isNukleer);
        } else {
            $this->addNewCartItem($barang, $stok, $isNukleer);
        }
    }

    private function updateExistingCartItem(int $key, BarangMaster $barang, StokBarang $stok, bool $isNukleer): void
    {
        if ($this->cart[$key]['jumlah'] >= $stok->jumlah) {
            $this->showError('Stok tidak mencukupi!');
            return;
        }

        $this->cart[$key]['jumlah']++;
        $qty = $this->cart[$key]['jumlah'];

        if ($isNukleer) {
            $this->cart[$key]['subtotal'] = $this->calculateBundlePrice($qty, $barang->harga_jual);
            $this->cart[$key]['harga_satuan'] = $barang->harga_jual;
        } else {
            $this->cart[$key]['subtotal'] = $qty * $this->cart[$key]['harga_satuan'];
        }
    }

    private function addNewCartItem(BarangMaster $barang, StokBarang $stok, bool $isNukleer): void
    {
        $subtotal = $isNukleer
            ? $this->calculateBundlePrice(1, $barang->harga_jual)
            : $barang->harga_jual;

        $this->cart[] = [
            'barang_id' => $barang->id,
            'kode_barang' => $barang->kode_barang,
            'nama_barang' => $barang->nama_barang,
            'satuan' => $barang->satuan,
            'harga_satuan' => $barang->harga_jual,
            'jumlah' => 1,
            'subtotal' => $subtotal,
            'stok' => $stok->jumlah,
            'bonus' => 0,
        ];
    }

    public function updateQty(int $index, $qty): void
    {
        $qty = (int) $qty;

        if (!isset($this->cart[$index])) {
            return;
        }

        if ($qty <= 0) {
            $this->removeFromCart($index);
            return;
        }

        // Cek stok terbaru
        $stok = $this->getStokBarang($this->cart[$index]['barang_id']);
        $stokJumlah = $stok ? $stok->jumlah : 0;

        if ($qty > $stokJumlah) {
            $this->showError('Stok tidak mencukupi!');
            return;
        }

        $this->cart[$index]['jumlah'] = $qty;
        $this->cart[$index]['stok'] = $stokJumlah;

        // Recalculate subtotal
        $barang = BarangMaster::find($this->cart[$index]['barang_id']);
        if ($this->isNukleerProduct($barang)) {
            $this->cart[$index]['subtotal'] = $this->calculateBundlePrice($qty, $barang->harga_jual);
            $this->cart[$index]['harga_satuan'] = $barang->harga_jual;
        } else {
            $this->cart[$index]['subtotal'] = $qty * $this->cart[$index]['harga_satuan'];
        }
    }

    public function updateBonus(int $index, $bonus): void
    {
        $bonus = max(0, (int) $bonus);

        if (!isset($this->cart[$index])) {
            return;
        }

        $stok = $this->getStokBarang($this->cart[$index]['barang_id']);
        $stokJumlah = $stok ? $stok->jumlah : 0;
        $totalDiperlukan = $this->cart[$index]['jumlah'] + $bonus;

        if ($totalDiperlukan > $stokJumlah) {
            $this->showError("Stok tidak mencukupi untuk bonus! Sisa stok: $stokJumlah");
            return;
        }

        $this->cart[$index]['bonus'] = $bonus;
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
        $this->diskon = '';
    }

    /*
    |--------------------------------------------------------------------------
    | Computed Properties
    |--------------------------------------------------------------------------
    */

    public function getSubtotalProperty(): float
    {
        return collect($this->cart)->sum('subtotal');
    }

    public function getDiskonProperty(): float
    {
        $persen = trim($this->diskon);
        if ($persen === '') {
            return 0.0;
        }
        $persen = max(0, min(100, (float) $persen));
        return $this->subtotal * ($persen / 100);
    }

    public function getTotalProperty(): float
    {
        return $this->subtotal - $this->getDiskonProperty();
    }

    public function getKembalianProperty(): float
    {
        return max(0, (float) $this->bayar - $this->total);
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Methods
    |--------------------------------------------------------------------------
    */

    public function setBayar(string $type): void
    {
        $this->validateCart();
        $total = $this->total;

        $this->bayar = (string) match ($type) {
            'pas' => $total,
            '50rb' => ceil($total / 50000) * 50000,
            '100rb' => ceil($total / 100000) * 100000,
            default => $total,
        };
    }

    public function prosesTransaksi(): void
    {
        // Validasi
        if (!$this->validateTransaction()) {
            return;
        }

        try {
            $penjualan = $this->executeTransaction();
            $this->saveTerminPayments($penjualan);

            $this->clearCart();
            $this->showSuccess("Transaksi berhasil! No Faktur: {$penjualan->no_faktur}");
            $this->dispatch('open-print-invoice', ['url' => route('penjualan.print', $penjualan->id)]);

        } catch (\Exception $e) {
            Log::error('POS Transaction Error', ['error' => $e->getMessage()]);
            $this->showError($e->getMessage());
        }
    }

    private function executeTransaction(): Penjualan
    {
        $transaksiService = app(TransaksiService::class);
        $user = Auth::user();

        $items = array_map(fn($item) => [
            'barang_id' => $item['barang_id'],
            'jumlah' => $item['jumlah'],
            'harga_satuan' => $item['harga_satuan'],
            'subtotal' => $item['subtotal'],
            'bonus' => $item['bonus'] ?? 0,
        ], $this->cart);

        $modeTermin = $this->termin === self::TERMIN_TUNAI ? 'cash' : 'termin';
        $jatuhTempo = $this->termin === self::TERMIN_SEKALI
            ? ($this->termins[0]['tanggal_jatuh_tempo'] ?? null)
            : null;

        return $transaksiService->simpanPenjualan([
            'user_id' => $user->id,
            'pelanggan_id' => $this->pelanggan_id,
            'gudang_id' => $this->gudang_id,
            'items' => $items,
            'diskon_transaksi' => $this->getDiskonProperty(),
            'mode_termin' => $modeTermin,
            'jatuh_tempo' => $jatuhTempo,
            'status' => $modeTermin === 'termin' ? 'belum_lunas' : 'selesai',
        ]);
    }

    private function saveTerminPayments(Penjualan $penjualan): void
    {
        $terminsToSave = match ($this->termin) {
            self::TERMIN_BERTAHAP => $this->termins,
            self::TERMIN_SEKALI => isset($this->termins[0]) ? [$this->termins[0]] : [],
            default => [],
        };

        foreach ($terminsToSave as $termin) {
            PembayaranPenjualan::create([
                'penjualan_id' => $penjualan->id,
                'jumlah' => $termin['jumlah'],
                'tanggal_jatuh_tempo' => $termin['tanggal_jatuh_tempo'],
                'status' => 'belum_lunas',
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Validation Methods
    |--------------------------------------------------------------------------
    */

    private function validateTransaction(): bool
    {
        if (empty($this->cart)) {
            $this->showError('Keranjang kosong!');
            return false;
        }

        if (!$this->gudang_id) {
            $this->showError('Pilih gudang terlebih dahulu!');
            return false;
        }

        if (!$this->pelanggan_id) {
            $this->showError('Pilih pelanggan terlebih dahulu!');
            return false;
        }

        return $this->validateTerminData();
    }

    private function validateTerminData(): bool
    {
        if ($this->termin === self::TERMIN_SEKALI) {
            if (empty($this->termins[0]['jumlah']) || empty($this->termins[0]['tanggal_jatuh_tempo'])) {
                $this->showError('Isi data termin (jumlah & tanggal jatuh tempo)!');
                return false;
            }
        }

        if ($this->termin === self::TERMIN_BERTAHAP) {
            if (empty($this->jumlah_termin) || empty($this->tanggal_mulai_termin)) {
                $this->showError('Isi jumlah termin dan tanggal mulai termin!');
                return false;
            }

            foreach ($this->termins as $termin) {
                if (empty($termin['jumlah']) || empty($termin['tanggal_jatuh_tempo'])) {
                    $this->showError('Isi semua jumlah & tanggal jatuh tempo termin!');
                    return false;
                }
            }
        }

        return true;
    }

    private function validateGudangSelected(): bool
    {
        if (!$this->gudang_id) {
            $this->showError('Pilih gudang terlebih dahulu!');
            return false;
        }
        return true;
    }

    private function validateStokAvailable(?StokBarang $stok): bool
    {
        if (!$stok || $stok->jumlah <= 0) {
            $this->showError('Stok barang habis di gudang ini!');
            return false;
        }
        return true;
    }

    protected function validateCart(): void
    {
        $validCart = [];

        foreach ($this->cart as $item) {
            $barang = BarangMaster::find($item['barang_id']);
            if ($barang) {
                // Query stok dari StokBarang, bukan dari property barang
                $stok = $this->getStokBarang($item['barang_id']);
                $item['stok'] = $stok ? $stok->jumlah : 0;
                $validCart[] = $item;
            }
        }

        if (count($validCart) !== count($this->cart)) {
            $this->cart = $validCart;
            $this->dispatch('notify', message: 'Beberapa barang telah dihapus dari keranjang karena sudah tidak tersedia.');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    private function isNukleerProduct(BarangMaster $barang): bool
    {
        $identifiers = config('pricing.nukleer_identifiers', [
            'nama' => ['nuklerr'],
            'kode' => ['rkk'],
        ]);

        $nama = strtolower($barang->nama_barang);
        $kode = strtolower($barang->kode_barang);

        foreach ($identifiers['nama'] as $keyword) {
            if (str_contains($nama, $keyword)) {
                return true;
            }
        }

        foreach ($identifiers['kode'] as $keyword) {
            if (str_contains($kode, $keyword)) {
                return true;
            }
        }

        return false;
    }

    private function calculateBundlePrice(int $qty, mixed $hargaSatuan): float
    {
        $hargaSatuan = (float) ($hargaSatuan ?? 0);
        
        $bundles = config('pricing.nukleer_bundles', [
            600 => 5100000,
            100 => 870000,
            10 => 91000,
        ]);

        // Sort descending by key
        krsort($bundles);

        $sisa = $qty;
        $subtotal = 0;

        foreach ($bundles as $bundleQty => $bundlePrice) {
            $bundleCount = intdiv($sisa, $bundleQty);
            $subtotal += $bundleCount * $bundlePrice;
            $sisa -= $bundleCount * $bundleQty;
        }

        return $subtotal + ($sisa * $hargaSatuan);
    }

    private function getStokBarang(int $barangMasterId): ?StokBarang
    {
        return StokBarang::where('barang_master_id', $barangMasterId)
            ->where('gudang_id', $this->gudang_id)
            ->first();
    }

    private function findCartItemIndex(int $barangMasterId): ?int
    {
        foreach ($this->cart as $index => $item) {
            if ($item['barang_id'] == $barangMasterId) {
                return $index;
            }
        }
        return null;
    }

    private function showError(string $message): void
    {
        $this->dispatch('show-alert', [
            'type' => 'error',
            'message' => $message,
        ]);
    }

    private function showSuccess(string $message): void
    {
        $this->dispatch('show-alert', [
            'type' => 'success',
            'message' => $message,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        $user = Auth::user();
        $gudangs = $this->getGudangsForUser($user);
        $barangs = $this->getBarangsQuery()->get();
        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();

        return view('livewire.point-of-sale', [
            'barangs' => $barangs,
            'pelanggans' => $pelanggans,
            'gudangs' => $gudangs,
        ]);
    }

    private function getGudangsForUser($user)
    {
        if ($user->isSuperAdmin()) {
            return Gudang::orderBy('nama_gudang')->get();
        }

        if ($user->isAdmin() && $user->gudang) {
            if (!$this->gudang_id) {
                $this->gudang_id = $user->gudang->id;
            }
            return collect([$user->gudang]);
        }

        return collect();
    }

    private function getBarangsQuery()
    {
        return BarangMaster::query()
            ->with(['stok' => fn($q) => $q->where('gudang_id', $this->gudang_id)])
            ->when($this->searchBarang, fn($query) =>
                $query->where(fn($q) =>
                    $q->where('nama_barang', 'like', "%{$this->searchBarang}%")
                      ->orWhere('kode_barang', 'like', "%{$this->searchBarang}%")
                )
            );
    }
}
