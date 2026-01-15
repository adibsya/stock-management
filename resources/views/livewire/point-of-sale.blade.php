<div class="flex gap-6 h-[calc(100vh-180px)] overflow-hidden">
    <!-- Left: Product List -->
    <div class="w-2/3 flex flex-col h-full">
        <!-- Search & Gudang Header -->
        <div class="card-compact mb-4 flex-shrink-0 bg-gradient-to-r from-sky-50 to-blue-50 border-sky-200">
            <div class="flex gap-4 items-center">
                <!-- Search -->
                <div class="relative flex-1">
                    <svg class="w-5 h-5 text-sky-500 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" 
                           wire:model.live.debounce.300ms="searchBarang" 
                           placeholder="Cari barang (kode / nama)..." 
                           class="input-with-icon-left border-sky-200 focus:border-sky-400">
                </div>
                <!-- Gudang Selector (Compact) -->
                @php $user = auth()->user(); @endphp
                @if($user && $user->isSuperAdmin())
                <div class="w-48">
                    <select wire:model.live="gudang_id" class="input-field text-sm border-sky-200 bg-white">
                        <option value="">Pilih Gudang</option>
                        @foreach($gudangs as $gudang)
                            <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
        </div>

        <!-- Product Grid -->
        <div class="card-compact flex-1 overflow-y-auto relative">
            <!-- Loading Overlay -->
            <div wire:loading wire:target="gudang_id, searchBarang" class="absolute inset-0 bg-white/80 backdrop-blur-sm z-10 flex flex-col items-center justify-center rounded-xl">
                <div class="relative">
                    <div class="w-16 h-16 border-4 border-sky-200 border-t-sky-600 rounded-full animate-spin"></div>
                    <svg class="w-8 h-8 text-sky-600 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                    </svg>
                </div>
                <p class="mt-4 text-sky-600 font-medium animate-pulse">Memuat data barang...</p>
            </div>

            @if(!$gudang_id)
                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                    <svg class="w-20 h-20 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                    </svg>
                    <p class="text-lg font-medium text-gray-500">Pilih gudang terlebih dahulu</p>
                    <p class="text-sm text-gray-400 mt-1">Klik dropdown gudang di atas untuk memulai</p>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" wire:poll.5s>
                    @forelse($barangs as $barang)
                        @php
                            $stok = $barang->stok->first();
                            $jumlahStok = $stok ? $stok->jumlah : 0;
                            $inCart = collect($cart)->contains('barang_id', $barang->id);
                        @endphp
                        <button wire:click="addToCart({{ $barang->id }})"
                            class="group p-4 border-2 rounded-xl transition-all duration-200 text-left relative overflow-hidden
                                {{ $jumlahStok <= 0 ? 'border-gray-200 bg-gray-50 opacity-60 cursor-not-allowed' : 
                                   ($inCart ? 'border-sky-400 bg-sky-50 shadow-md' : 'border-gray-200 hover:border-sky-400 hover:shadow-lg hover:bg-gradient-to-br hover:from-white hover:to-sky-50') }}"
                            @if($jumlahStok <= 0) disabled @endif>
                            
                            <!-- In Cart Indicator -->
                            @if($inCart)
                            <div class="absolute top-2 right-2">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-sky-500 text-white text-xs font-bold shadow">
                                    {{ collect($cart)->firstWhere('barang_id', $barang->id)['jumlah'] ?? 0 }}
                                </span>
                            </div>
                            @endif
                            
                            <div class="flex flex-col h-full">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800 truncate pr-8 group-hover:text-sky-700 transition-colors">{{ $barang->nama_barang }}</p>
                                    <p class="text-xs text-gray-400 font-mono">{{ $barang->kode_barang }}</p>
                                    <p class="text-sky-600 font-bold mt-3 text-lg">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</p>
                                </div>
                                <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between">
                                    <span class="text-xs {{ $jumlahStok <= 5 ? 'text-amber-600 font-semibold' : 'text-gray-400' }}">
                                        Stok: <span class="font-bold {{ $jumlahStok <= 0 ? 'text-red-500' : ($jumlahStok <= 5 ? 'text-amber-600' : 'text-gray-700') }}">{{ $jumlahStok }}</span> {{ $barang->satuan }}
                                    </span>
                                    @if($jumlahStok <= 0)
                                        <span class="px-2 py-0.5 bg-red-100 text-red-600 rounded-full text-xs font-semibold">Habis</span>
                                    @elseif($jumlahStok <= 5)
                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-600 rounded-full text-xs font-semibold">Sedikit</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Hover Add Icon -->
                            @if($jumlahStok > 0)
                            <div class="absolute inset-0 bg-sky-500/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                <div class="w-12 h-12 bg-sky-500 rounded-full flex items-center justify-center shadow-lg transform scale-0 group-hover:scale-100 transition-transform">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                            </div>
                            @endif
                        </button>
                    @empty
                        <div class="col-span-full py-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-gray-500 font-medium">Tidak ada barang ditemukan</p>
                            <p class="text-sm text-gray-400 mt-1">Coba kata kunci lain</p>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>

    <!-- Right: Cart -->
    <div class="w-1/3 flex flex-col h-full overflow-hidden">
        <div class="card h-full min-h-0 flex flex-col shadow-xl bg-gradient-to-b from-white to-gray-50 rounded-2xl overflow-hidden border-0">
            <!-- Cart Header -->
            <div class="bg-gradient-to-r from-sky-600 to-blue-600 text-white px-6 py-4 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold tracking-wide">Keranjang</h3>
                            <p class="text-sky-100 text-xs">{{ count($cart) }} item</p>
                        </div>
                    </div>
                    @if(count($cart) > 0)
                    <button wire:click="clearCart" class="text-white/80 hover:text-white hover:bg-white/20 p-2 rounded-lg transition" title="Kosongkan">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                    @endif
                </div>
            </div>
            
            <!-- Scrollable Content Area -->
            <div class="flex-1 min-h-0 overflow-y-auto p-4 space-y-4">
                <!-- Cart Items -->
                @forelse($cart as $index => $item)
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="flex gap-3">
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate">{{ $item['nama_barang'] }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }} / {{ $item['satuan'] ?? 'pcs' }}
                                    @php
                                        $isNuklerr = false;
                                        $nama = strtolower($item['nama_barang'] ?? '');
                                        $kode = strtolower($item['kode_barang'] ?? '');
                                        if (strpos($nama, 'nuklerr') !== false || strpos($kode, 'rkk') !== false) {
                                            $isNuklerr = true;
                                        }
                                    @endphp
                                    @if($isNuklerr && isset($item['bonus']) && $item['bonus'] > 0)
                                        <span class="ml-1 px-1.5 py-0.5 rounded bg-green-100 text-green-700 font-semibold text-xs">+{{ $item['bonus'] }} bonus</span>
                                    @endif
                                </p>
                            </div>
                            <button wire:click="removeFromCart({{ $index }})" class="text-gray-400 hover:text-red-500 transition p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
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
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <p class="font-medium text-gray-500">Keranjang kosong</p>
                        <p class="text-sm text-gray-400 mt-1">Klik produk untuk menambahkan</p>
                    </div>
                @endforelse

                @if(count($cart) > 0)
                <!-- Customer & Payment Options -->
                <div class="space-y-3 pt-3 border-t border-gray-200">
                    <!-- Gudang (if Admin) -->
                    @if($user && $user->isAdmin() && $user->gudang)
                    <div class="flex items-center gap-3 p-3 bg-sky-50 rounded-xl">
                        <div class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-sky-600 font-medium">Gudang</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $user->gudang->nama_gudang }}</p>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Pelanggan -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Pelanggan</label>
                        <select wire:model="pelanggan_id" class="input-field text-sm">
                            <option value="">Pelanggan Umum</option>
                            @foreach($pelanggans as $pelanggan)
                                <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama_pelanggan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Cara Bayar -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Cara Bayar</label>
                        <select wire:model.live="termin" class="input-field text-sm">
                            <option value="0">💵 Tunai</option>
                            <option value="1">📅 Termin Sekali (1x Jatuh Tempo)</option>
                            <option value="2">📆 Termin Bertahap (Multi Cicilan)</option>
                        </select>
                    </div>

                    @if(isset($termin) && $termin === '1')
                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-200">
                        <label class="block text-xs font-medium text-amber-700 mb-2">Termin Sekali</label>
                        <div class="flex gap-3">
                            <input type="number" wire:model.live="termins.0.jumlah" class="input-field text-sm bg-white" readonly placeholder="Jumlah">
                            <input type="date" wire:model.live="termins.0.tanggal_jatuh_tempo" class="input-field text-sm bg-white">
                        </div>
                    </div>
                    @endif

                    @if(isset($termin) && $termin === '2')
                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-200">
                        <label class="block text-xs font-medium text-amber-700 mb-2">Termin Bertahap</label>
                        <div class="flex gap-3 mb-3">
                            <input type="number" wire:model.blur="jumlah_termin" class="input-field text-sm bg-white" min="2" placeholder="Jml Termin">
                            <input type="date" wire:model.live="tanggal_mulai_termin" class="input-field text-sm bg-white">
                        </div>
                        @if(isset($termins) && is_array($termins) && count($termins) > 0)
                        <div class="space-y-2 max-h-32 overflow-y-auto">
                            @foreach($termins as $i => $terminRow)
                            <div class="flex gap-2 items-center text-xs">
                                <span class="w-6 h-6 bg-amber-200 text-amber-800 rounded-full flex items-center justify-center font-bold">{{ $i + 1 }}</span>
                                <input type="number" class="input-field text-xs py-1.5 bg-white" wire:model.live="termins.{{ $i }}.jumlah" readonly>
                                <input type="date" class="input-field text-xs py-1.5 bg-white" wire:model.live="termins.{{ $i }}.tanggal_jatuh_tempo">
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Diskon -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Diskon</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">Rp</span>
                            <input type="number" wire:model.blur="diskon" class="input-with-prefix-left text-sm" min="0" placeholder="0">
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 text-white rounded-xl p-4 space-y-2">
                    <div class="flex justify-between text-sm text-gray-300">
                        <span>Subtotal ({{ count($cart) }} item)</span>
                        <span>Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @php $diskonValue = $this->getDiskonProperty(); @endphp
                    @if($diskonValue > 0)
                    <div class="flex justify-between text-sm text-red-400">
                        <span>Diskon</span>
                        <span>- Rp {{ number_format($diskonValue, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-xl font-bold pt-2 border-t border-gray-700">
                        <span>Total</span>
                        <span class="text-emerald-400">Rp {{ number_format($this->total, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- Payment -->

            <!-- Actions -->
            <div class="mt-4 flex gap-3">
                <button wire:click="clearCart" class="btn-secondary flex-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Batal
                </button>
                <button wire:click="prosesTransaksi" class="btn-success flex-1 text-base py-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Bayar
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('open-print-invoice', (event) => {
            // Buka halaman print invoice di tab baru
            window.open(event[0].url, '_blank');
        });
    });
</script>
@endpush
