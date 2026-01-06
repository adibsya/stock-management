<div class="flex gap-6 h-[calc(100vh-180px)] overflow-hidden">
    <!-- Left: Product List -->
    <div class="w-2/3 flex flex-col h-full">
        <!-- Search -->
        <div class="card-compact mb-4 flex-shrink-0">
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

        <!-- Product List by Category -->
        <div class="flex-1 overflow-y-auto bg-gray-50 rounded-lg min-h-0">
            @if($barangs->isEmpty())
                <div class="py-8 text-center text-gray-500">
                    Tidak ada barang ditemukan
                </div>
            @else
                <div class="pt-4">
                    @foreach($barangs as $kategori => $items)
                        <!-- Category Header - Sticky -->
                        <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-500 text-white px-4 py-2.5 shadow-lg z-10">
                            <h3 class="font-semibold text-sm uppercase tracking-wide">{{ $kategori }}</h3>
                        </div>
                        
                        <!-- Items List -->
                        <div class="bg-white border-x border-b border-gray-200 divide-y divide-gray-100 mb-4">
                            @foreach($items as $barang)
                                <button wire:click="addToCart({{ $barang->id }})" 
                                        class="w-full px-4 py-3 hover:bg-blue-50 transition text-left group cursor-pointer">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-800 text-sm truncate group-hover:text-blue-600">
                                                {{ $barang->nama_barang }}
                                            </p>
                                            <div class="flex items-center gap-3 mt-1">
                                                <span class="text-xs text-gray-500 font-mono">{{ $barang->kode_barang }}</span>
                                                <span class="text-xs px-2 py-0.5 bg-green-100 text-green-700 rounded-full font-medium">
                                                    Stok: {{ $barang->stok }} {{ $barang->satuan }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-blue-600 font-bold text-sm">
                                                Rp {{ number_format($barang->master->harga_jual ?? $barang->harga_jual, 0, ',', '.') }}
                                            </p>
                                            <p class="text-xs text-gray-400">{{ $barang->gudang->nama_gudang ?? '-' }}</p>
                                        </div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Right: Cart -->
    <div class="w-1/3 flex flex-col h-full">
        <div class="card flex-1 flex flex-col min-h-0">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2 flex-shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Keranjang
                @if(count($cart) > 0)
                    <span class="ml-auto text-xs font-normal bg-blue-100 text-blue-700 px-2 py-1 rounded-full">{{ count($cart) }} items</span>
                @endif
            </h3>
            
            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto pr-2 -mr-2 min-h-0">
                <!-- Cart Items -->
                <div class="space-y-2 mb-4">
                @forelse($cart as $index => $item)
                    <div class="relative bg-gradient-to-r from-white to-gray-50 border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow">
                        <!-- Delete Button -->
                        <button wire:click="removeFromCart({{ $index }})" 
                                class="absolute top-2 right-2 text-gray-400 hover:text-red-500 hover:bg-red-50 p-1.5 rounded-full transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        
                        <!-- Item Info -->
                        <div class="pr-8 mb-2">
                            <p class="font-semibold text-gray-900 text-sm leading-tight mb-1">{{ $item['nama_barang'] }}</p>
                            <div class="flex items-center gap-2 text-xs">
                                <span class="text-blue-600 font-medium">@ Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}</span>
                                <span class="text-gray-300">•</span>
                                <span class="px-1.5 py-0.5 bg-orange-100 text-orange-700 rounded font-medium">Stok: {{ $item['stok'] ?? 0 }}</span>
                            </div>
                        </div>
                        
                        <!-- Quantity Controls and Subtotal -->
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-1.5">
                                <button wire:click="updateQty({{ $index }}, {{ $item['jumlah'] - 1 }})" 
                                        class="w-8 h-8 bg-white border border-gray-300 rounded-lg flex items-center justify-center hover:bg-gray-50 hover:border-gray-400 active:bg-gray-100 transition shadow-sm"
                                        {{ $item['jumlah'] <= 1 ? 'disabled' : '' }}>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <span class="w-12 text-center font-bold text-gray-900">{{ $item['jumlah'] }}</span>
                                <button wire:click="updateQty({{ $index }}, {{ $item['jumlah'] + 1 }})" 
                                        class="w-8 h-8 bg-blue-600 border border-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-700 active:bg-blue-800 transition shadow-sm {{ ($item['jumlah'] >= ($item['stok'] ?? 0)) ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ ($item['jumlah'] >= ($item['stok'] ?? 0)) ? 'disabled' : '' }}>
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 mb-0.5">Subtotal</p>
                                <p class="font-bold text-gray-900 text-sm">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                        <svg class="w-16 h-16 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p class="text-sm font-medium">Keranjang Kosong</p>
                        <p class="text-xs mt-1">Tambahkan produk dari daftar</p>
                    </div>
                @endforelse
            </div>

            <!-- Gudang -->
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                    </svg>
                    Gudang
                </label>
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
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Pelanggan
                </label>
                <select wire:model="pelanggan_id" class="input-field">
                    <option value="">Pelanggan Umum</option>
                    @foreach($pelanggans as $pelanggan)
                        <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Diskon -->
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Diskon
                </label>
                <div class="input-with-prefix">
                    <span class="input-prefix">Rp</span>
                    <input type="number" wire:model.blur="diskon_transaksi" class="input-field" placeholder="0">
                </div>
            </div>

            <!-- Summary -->
            <div class="border-t border-b border-gray-200 py-3 mb-3 space-y-2">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span class="font-medium">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Diskon</span>
                    <span class="text-red-600 font-medium">- Rp {{ number_format($this->diskon, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold text-gray-900 pt-2 border-t border-gray-200">
                    <span>Total</span>
                    <span class="text-blue-600">Rp {{ number_format($this->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Payment -->
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Metode Pembayaran
                    </label>
                    <select wire:model.live="metode_pembayaran" class="input-field">
                        <option value="tunai">💵 Tunai</option>
                        <option value="transfer">🏦 Transfer Bank</option>
                        <option value="qris">📱 QRIS</option>
                        <option value="termin">📅 Termin / Cicilan</option>
                    </select>
                </div>

                @if($metode_pembayaran === 'termin')
                    <!-- Termin Options -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 space-y-3">
                        <p class="text-xs font-semibold text-blue-800 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Pengaturan Termin
                        </p>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Cicilan</label>
                            <select wire:model.live="jumlah_cicilan" class="input-field text-sm">
                                <option value="1">1x Cicilan</option>
                                <option value="2">2x Cicilan</option>
                                <option value="3">3x Cicilan</option>
                                <option value="4">4x Cicilan</option>
                                <option value="5">5x Cicilan</option>
                                <option value="6">6x Cicilan</option>
                                <option value="12">12x Cicilan</option>
                            </select>
                        </div>

                        @if(!empty($termin_cicilan))
                            <div class="space-y-2 mt-3">
                                <p class="text-xs font-medium text-gray-700">Detail Cicilan:</p>
                                @foreach($termin_cicilan as $key => $cicilan)
                                    <div class="bg-white border border-blue-200 rounded-lg p-3 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-semibold text-gray-700">Cicilan ke-{{ $key }}</span>
                                            <span class="text-xs font-bold text-blue-700">Rp {{ number_format($cicilan['jumlah'], 0, ',', '.') }}</span>
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-1">Jumlah Bayar</label>
                                            <div class="input-with-prefix">
                                                <span class="input-prefix text-xs">Rp</span>
                                                <input type="number" 
                                                       wire:model="termin_cicilan.{{ $key }}.jumlah" 
                                                       class="input-field text-sm"
                                                       step="0.01">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-1">Tanggal Jatuh Tempo</label>
                                            <input type="date" 
                                                   wire:model="termin_cicilan.{{ $key }}.tanggal_jatuh_tempo" 
                                                   class="input-field text-sm"
                                                   required>
                                        </div>
                                    </div>
                                @endforeach
                                
                                <div class="bg-blue-100 rounded p-2 text-xs">
                                    <div class="flex justify-between">
                                        <span class="text-gray-700">Total Cicilan:</span>
                                        <span class="font-bold text-blue-800">Rp {{ number_format(collect($termin_cicilan)->sum('jumlah'), 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bayar</label>
                        <div class="input-with-prefix mb-2">
                            <span class="input-prefix">Rp</span>
                            <input type="number" wire:model.blur="bayar" class="input-field" placeholder="0">
                        </div>
                        <!-- Quick Amount Buttons -->
                        <div class="grid grid-cols-3 gap-2 mb-2">
                            <button type="button" wire:click="setBayar('pas')" class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition font-medium">
                                Pas
                            </button>
                            <button type="button" wire:click="setBayar('50rb')" class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition font-medium">
                                50rb
                            </button>
                            <button type="button" wire:click="setBayar('100rb')" class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition font-medium">
                                100rb
                            </button>
                        </div>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Kembalian</span>
                        <span class="text-lg font-bold text-green-700">Rp {{ number_format($this->kembalian, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>
            </div>
            <!-- End Scrollable Content -->

            <!-- Actions - Fixed at bottom -->
            <div class="mt-4 pt-4 border-t border-gray-200 flex gap-3 flex-shrink-0">
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
