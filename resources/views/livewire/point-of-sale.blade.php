<div class="flex gap-6 h-[calc(100vh-180px)]">
    <!-- Left: Product List -->
    <div class="w-2/3 flex flex-col">
        <!-- Search -->
        <div class="card-compact mb-4">
            <div class="relative">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" 
                       wire:model.live.debounce.300ms="searchBarang" 
                       placeholder="Cari barang (kode / nama)..." 
                       class="input-with-icon-left">
            </div>
        </div>

        <!-- Product Grid -->
        <div class="card-compact flex-1 overflow-y-auto">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($barangs as $barang)
                    <button wire:click="addToCart({{ $barang->id }})" 
                            class="p-4 border border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition text-left">
                        <p class="font-medium text-gray-800 truncate">{{ $barang->nama_barang }}</p>
                        <p class="text-xs text-gray-500">{{ $barang->kode_barang }}</p>
                        <p class="text-blue-600 font-bold mt-2">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-1">Stok: {{ $barang->stok }} {{ $barang->satuan }}</p>
                    </button>
                @empty
                    <div class="col-span-full py-8 text-center text-gray-500">
                        Tidak ada barang ditemukan
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right: Cart -->
    <div class="w-1/3 flex flex-col">
        <div class="card flex-1 flex flex-col">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Keranjang</h3>
            
            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto space-y-3 mb-4">
                @forelse($cart as $index => $item)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <p class="font-medium text-gray-800 text-sm">{{ $item['nama_barang'] }}</p>
                            <p class="text-xs text-gray-500">Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button wire:click="updateQty({{ $index }}, {{ $item['jumlah'] - 1 }})" class="w-6 h-6 bg-gray-200 rounded flex items-center justify-center hover:bg-gray-300">
                                -
                            </button>
                            <span class="w-8 text-center">{{ $item['jumlah'] }}</span>
                            <button wire:click="updateQty({{ $index }}, {{ $item['jumlah'] + 1 }})" class="w-6 h-6 bg-gray-200 rounded flex items-center justify-center hover:bg-gray-300">
                                +
                            </button>
                        </div>
                        <p class="w-24 text-right font-medium">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                        <button wire:click="removeFromCart({{ $index }})" class="text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-500">
                        Keranjang kosong
                    </div>
                @endforelse
            </div>

            <!-- Gudang -->
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Gudang</label>
                @php
                    $user = auth()->user();
                @endphp
                @if($user && $user->isAdmin() && $user->gudang)
                    <div class="input-field bg-gray-100 cursor-not-allowed">{{ $user->gudang->nama_gudang }}</div>
                @else
                    <select wire:model="gudang_id" class="input-field">
                        <option value="">Pilih Gudang</option>
                        @foreach($gudangs as $gudang)
                            <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <!-- Pelanggan -->
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Pelanggan</label>
                <select wire:model="pelanggan_id" class="input-field">
                    <option value="">Pelanggan Umum</option>
                    @foreach($pelanggans as $pelanggan)
                        <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Diskon -->
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Diskon</label>
                <div class="input-with-prefix">
                    <span class="input-prefix">Rp</span>
                    <input type="number" wire:model.live="diskon_transaksi" class="input-field" placeholder="0">
                </div>
            </div>

            <!-- Summary -->
            <div class="border-t pt-4 space-y-2">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Diskon</span>
                    <span class="text-red-600">- Rp {{ number_format($this->diskon, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xl font-bold text-gray-800 pt-2 border-t">
                    <span>Total</span>
                    <span class="text-blue-600">Rp {{ number_format($this->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Payment -->
            <div class="mt-4 space-y-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Metode Pembayaran</label>
                    <select wire:model="metode_pembayaran" class="input-field">
                        <option value="tunai">Tunai</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Bayar</label>
                    <div class="input-with-prefix">
                        <span class="input-prefix">Rp</span>
                        <input type="number" wire:model.live="bayar" class="input-field" placeholder="0">
                    </div>
                </div>
                <div class="flex justify-between text-lg font-bold text-green-600">
                    <span>Kembalian</span>
                    <span>Rp {{ number_format($this->kembalian, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-4 flex gap-3">
                <button wire:click="clearCart" class="btn-secondary flex-1">
                    Batal
                </button>
                <button wire:click="prosesTransaksi" class="btn-success flex-1">
                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Bayar
                </button>
            </div>
        </div>
    </div>
</div>
