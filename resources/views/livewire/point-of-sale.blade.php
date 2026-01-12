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

        <!-- Product Grid -->
        <div class="card-compact flex-1 overflow-y-auto">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" wire:poll.5s>
                @if(!$gudang_id)
                    <div class="col-span-full py-8 text-center text-gray-500">
                        Pilih gudang terlebih dahulu
                    </div>
                @else
                    @forelse($barangs as $barang)
                        @php
                            $stok = $barang->stok->first();
                            $jumlahStok = $stok ? $stok->jumlah : 0;
                        @endphp
                        <button wire:click="addToCart({{ $barang->id }})"
                            class="p-4 border border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition text-left relative group"
                            @if($jumlahStok <= 0) disabled style="opacity:0.5;cursor:not-allowed;" @endif>
                            <div class="flex flex-col h-full">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800 truncate">{{ $barang->nama_barang }}</p>
                                    <p class="text-xs text-gray-500">{{ $barang->kode_barang }}</p>
                                    <p class="text-blue-600 font-bold mt-2">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</p>
                                </div>
                                <div class="mt-2 flex items-center justify-between">
                                    <span class="text-xs text-gray-400">Stok: <span class="font-bold text-gray-700">{{ $jumlahStok }}</span> {{ $barang->satuan }}</span>
                                    @if($jumlahStok <= 0)
                                        <span class="text-xs text-red-500 font-semibold">0 / Habis</span>
                                    @endif
                                </div>
                            </div>
                        </button>
                    @empty
                        <div class="col-span-full py-8 text-center text-gray-500">
                            Tidak ada barang ditemukan
                        </div>
                    @endforelse
                @endif
            </div>
        </div>
    </div>

    <!-- Right: Cart -->
    <div class="w-1/3 flex flex-col">
        <div class="card flex-1 flex flex-col shadow-lg bg-white rounded-2xl p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6 tracking-wide">Keranjang</h3>
            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto space-y-4 mb-6">
                @forelse($cart as $index => $item)
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl shadow-sm">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 text-base leading-tight">{{ $item['nama_barang'] }}</p>
                            <p class="text-xs text-gray-400">Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}
                                @php
                                    $isNuklerr = false;
                                    $nama = strtolower($item['nama_barang'] ?? '');
                                    $kode = strtolower($item['kode_barang'] ?? '');
                                    if (strpos($nama, 'nuklerr') !== false || strpos($kode, 'rkk') !== false) {
                                        $isNuklerr = true;
                                    }
                                @endphp
                                @if($isNuklerr)
                                    @if(isset($item['bonus']) && $item['bonus'] > 0)
                                        <span class="ml-2 px-2 py-0.5 rounded bg-green-100 text-green-700 font-semibold text-xs">Bonus +{{ $item['bonus'] }} pcs</span>
                                    @endif
                                    @if($item['jumlah'] >= 600)
                                        <span class="ml-2 px-2 py-0.5 rounded bg-blue-100 text-blue-700 font-semibold text-xs">Harga khusus 600 pcs</span>
                                    @elseif($item['jumlah'] >= 100)
                                        <span class="ml-2 px-2 py-0.5 rounded bg-blue-100 text-blue-700 font-semibold text-xs">Harga khusus 100 pcs</span>
                                    @elseif($item['jumlah'] >= 20)
                                        <span class="ml-2 px-2 py-0.5 rounded bg-blue-100 text-blue-700 font-semibold text-xs">Harga khusus 20 pcs</span>
                                    @endif
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button wire:click="updateQty({{ $index }}, {{ $item['jumlah'] - 1 }})" class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center hover:bg-gray-300 text-lg font-bold transition">-</button>
                            <input type="number" min="1" :max="$item['stok']" class="w-14 text-center text-lg font-semibold border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" value="{{ $item['jumlah'] }}"
                                wire:change="updateQty({{ $index }}, $event.target.value)"
                                wire:keydown.enter="updateQty({{ $index }}, $event.target.value)"
                                >
                            <button wire:click="updateQty({{ $index }}, {{ $item['jumlah'] + 1 }})" class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center hover:bg-gray-300 text-lg font-bold transition">+</button>
                            @php
                                $isNuklerr = false;
                                $nama = strtolower($item['nama_barang'] ?? '');
                                $kode = strtolower($item['kode_barang'] ?? '');
                                if (strpos($nama, 'nuklerr') !== false || strpos($kode, 'rkk') !== false) {
                                    $isNuklerr = true;
                                }
                            @endphp
                        </div>
                        <p class="w-28 text-right font-bold text-lg text-gray-800">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                        <button wire:click="removeFromCart({{ $index }})" class="w-8 h-8 flex items-center justify-center text-red-500 hover:text-white hover:bg-red-500 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <input type="number" 
                                       value="{{ $item['jumlah'] }}"
                                       wire:change="updateQty({{ $index }}, $event.target.value)"
                                       min="1"
                                       max="{{ $item['stok'] ?? 999 }}"
                                       class="w-14 text-center font-bold text-gray-900 border border-gray-300 rounded-lg py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                    <div class="py-10 text-center text-gray-400 text-lg font-semibold">
                        Keranjang kosong
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
                        <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama_pelanggan }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Diskon -->
            <!-- Pembayaran Termin -->
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Cara Bayar</label>
                <select wire:model.live="termin" class="input-field">
                    <option value="0">Tunai</option>
                    <option value="1">Termin Sekali (1x Jatuh Tempo)</option>
                    <option value="2">Termin Bertahap (Multi Cicilan)</option>
                </select>
            </div>

            @if(isset($termin) && $termin === '1')
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Termin (Sekali)</label>
                <div class="flex gap-4">
                    <input type="number" wire:model.live="termins.0.jumlah" class="input-field" readonly>
                    <input type="date" wire:model.live="termins.0.tanggal_jatuh_tempo" class="input-field">
                </div>
            </div>
            @endif

            @if(isset($termin) && $termin === '2')
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Termin Bertahap</label>
                <div class="flex gap-4 items-center">
                    <input type="number" wire:model="jumlah_termin" class="input-field" min="2" placeholder="Jumlah Termin">
                    <input type="date" wire:model.live="tanggal_mulai_termin" class="input-field">
                </div>
                <div class="space-y-2 mt-4">
                    @if(isset($termins) && is_array($termins))
                        @foreach($termins as $i => $terminRow)
                        <div class="flex gap-4 items-center">
                            <input type="number" class="input-field" wire:model.live="termins.{{ $i }}.jumlah" readonly>
                            <input type="date" class="input-field" wire:model.live="termins.{{ $i }}.tanggal_jatuh_tempo">
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @endif

            <!-- Summary -->
            <div class="border-t border-b border-gray-200 py-3 mb-3 space-y-2">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal</span>
                    <span class="font-medium">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xl font-bold text-gray-800 pt-2 border-t">
                    <span>Total</span>
                    <span class="text-blue-600">Rp {{ number_format($this->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Payment -->

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
