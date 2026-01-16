<div class="space-y-6">
    {{-- Filter Section --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        {{-- Search Input --}}
        <div class="mb-6">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-5 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                </span>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Cari berdasarkan no faktur atau nama pelanggan..." 
                       class="w-full pl-14 pr-5 py-3.5 bg-gray-50/80 border border-gray-200 rounded-2xl text-sm text-gray-700 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400/50 focus:border-blue-400 focus:bg-white transition-all duration-200">
            </div>
        </div>

        {{-- Filters Grid with Custom Dropdowns --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            {{-- Tanggal Mulai --}}
            <div class="space-y-2">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Dari Tanggal</label>
                <input type="date" wire:model.live="startDate" 
                       class="w-full px-4 py-3 bg-gray-50/80 border border-gray-200 rounded-xl text-sm text-gray-700 shadow-sm hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400/50 focus:border-blue-400 focus:bg-white transition-all duration-200">
            </div>
            {{-- Tanggal Sampai --}}
            <div class="space-y-2">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Sampai Tanggal</label>
                <input type="date" wire:model.live="endDate" 
                       class="w-full px-4 py-3 bg-gray-50/80 border border-gray-200 rounded-xl text-sm text-gray-700 shadow-sm hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400/50 focus:border-blue-400 focus:bg-white transition-all duration-200">
            </div>
            
            {{-- Gudang --}}
            <div class="space-y-2">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Gudang</label>
                <div class="relative">
                    <select wire:model.live="gudang_id" 
                            class="w-full px-4 py-3 pr-10 bg-gray-50/80 border border-gray-200 rounded-xl text-sm text-gray-700 shadow-sm hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400/50 focus:border-blue-400 focus:bg-white transition-all duration-200 appearance-none cursor-pointer">
                        <option value="">Semua Gudang</option>
                        @foreach($gudangs as $gudang)
                            <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Status --}}
            <div class="space-y-2">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</label>
                <div class="relative">
                    <select wire:model.live="status" 
                            class="w-full px-4 py-3 pr-10 bg-gray-50/80 border border-gray-200 rounded-xl text-sm text-gray-700 shadow-sm hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400/50 focus:border-blue-400 focus:bg-white transition-all duration-200 appearance-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="selesai">Selesai</option>
                        <option value="belum_lunas">Belum Lunas</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Kategori --}}
            <div class="space-y-2">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</label>
                <div class="relative">
                    <select wire:model.live="kategoriProduk" 
                            class="w-full px-4 py-3 pr-10 bg-gray-50/80 border border-gray-200 rounded-xl text-sm text-gray-700 shadow-sm hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400/50 focus:border-blue-400 focus:bg-white transition-all duration-200 appearance-none cursor-pointer">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori }}">{{ $kategori }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-5 text-white shadow-lg shadow-emerald-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-emerald-100">Total Penjualan</p>
                    <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-100">Jumlah Transaksi</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($jumlahTransaksi, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl p-5 text-white shadow-lg shadow-amber-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-amber-100">Termin Pending</p>
                    <p class="text-2xl font-bold mt-1">{{ number_format($jumlahTerminPending, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Button --}}
    <div class="flex justify-end">
        <a href="{{ route('penjualan-termin.index') }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl shadow-sm shadow-emerald-500/30 transition-all hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
            </svg>
            Kasir Pembayaran Termin
        </a>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        {{-- Sortable: No Faktur --}}
                        <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider group cursor-pointer hover:bg-gray-100 transition-colors" 
                            wire:click="sortBy('no_faktur')" 
                            title="Klik untuk urutkan berdasarkan No Faktur">
                            <div class="flex items-center gap-2">
                                <span>No Faktur</span>
                                <svg class="w-4 h-4 transition-all {{ $sortColumn === 'no_faktur' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-500' }} {{ $sortColumn === 'no_faktur' && $sortDirection === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                </svg>
                            </div>
                        </th>
                        {{-- Sortable: Tanggal --}}
                        <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider group cursor-pointer hover:bg-gray-100 transition-colors" 
                            wire:click="sortBy('tanggal')" 
                            title="Klik untuk urutkan berdasarkan Tanggal">
                            <div class="flex items-center gap-2">
                                <span>Tanggal</span>
                                <svg class="w-4 h-4 transition-all {{ $sortColumn === 'tanggal' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-500' }} {{ $sortColumn === 'tanggal' && $sortDirection === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gudang</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kasir</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pembayaran</th>
                        {{-- Sortable: Total --}}
                        <th class="px-5 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider group cursor-pointer hover:bg-gray-100 transition-colors" 
                            wire:click="sortBy('total_bayar')" 
                            title="Klik untuk urutkan berdasarkan Total">
                            <div class="flex items-center justify-end gap-2">
                                <span>Total</span>
                                <svg class="w-4 h-4 transition-all {{ $sortColumn === 'total_bayar' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-500' }} {{ $sortColumn === 'total_bayar' && $sortDirection === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-5 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($penjualans as $penjualan)
                        <tr class="hover:bg-blue-50/50 transition-colors">
                            <td class="px-5 py-4">
                                <span class="font-mono text-sm font-semibold text-gray-900">
                                    {{ $penjualan->no_faktur }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600">
                                {{ $penjualan->tanggal?->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600">
                                {{ $penjualan->gudang?->nama_gudang ?? '-' }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $penjualan->pelanggan?->nama_pelanggan ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600">
                                {{ $penjualan->user?->name ?? '-' }}
                            </td>
                            <td class="px-5 py-4">
                                @if($penjualan->mode_termin === 'termin')
                                    <div class="flex flex-col gap-1">
                                        <span class="inline-flex items-center w-fit px-2.5 py-1 rounded-lg text-xs font-medium bg-amber-100 text-amber-700">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Termin
                                        </span>
                                        @php $termins = $penjualan->pembayaranPenjualan; @endphp
                                        @if($termins && $termins->count())
                                            <span class="text-xs text-gray-500">
                                                {{ $termins->where('status', 'lunas')->count() }}/{{ $termins->count() }} lunas
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-emerald-100 text-emerald-700">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Tunai
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <span class="text-sm font-bold text-gray-900 whitespace-nowrap">
                                    Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium
                                    {{ $penjualan->status === 'selesai' 
                                        ? 'bg-emerald-100 text-emerald-700' 
                                        : 'bg-amber-100 text-amber-700' }}">
                                    {{ ucfirst(str_replace('_', ' ', $penjualan->status)) }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    {{-- Detail Button --}}
                                    <a href="{{ route('penjualan.show', $penjualan) }}"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-100:bg-blue-900/40 transition-colors"
                                       title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </a>
                                    {{-- Print Button --}}
                                    <a href="{{ route('penjualan.print', $penjualan) }}" 
                                       target="_blank"
                                       class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-emerald-600 bg-emerald-50 hover:bg-emerald-100:bg-emerald-900/40 transition-colors"
                                       title="Cetak Invoice">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/>
                                        </svg>
                                    </a>
                                    {{-- Delete Button --}}
                                    @if(auth()->user()->role === 'super_admin' || (auth()->user()->role === 'admin' && $penjualan->gudang_id === auth()->user()->gudang_id))
                                    <button onclick="konfirmasiHapusPenjualan({{ $penjualan->id }})"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-red-600 bg-red-50 hover:bg-red-100:bg-red-900/40 transition-colors"
                                            title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="text-gray-500 font-medium">Tidak ada data penjualan</p>
                                    <p class="text-gray-400 text-sm mt-1">Coba ubah filter pencarian</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-5 py-4 border-t border-gray-200 bg-gray-50">
            {{ $penjualans->links() }}
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function konfirmasiHapusPenjualan(id) {
            Swal.fire({
                title: 'Hapus Penjualan?',
                text: 'Data akan dihapus dan stok akan dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl',
                    cancelButton: 'rounded-xl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let root = document.querySelector('[wire\\:id]');
                    if (root) {
                        window.Livewire.find(root.getAttribute('wire:id')).call('hapusPenjualan', id);
                    }
                }
            });
        }

        window.addEventListener('penjualan-dihapus', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data penjualan berhasil dihapus.',
                timer: 1800,
                showConfirmButton: false,
                customClass: { popup: 'rounded-2xl' }
            });
        });

        window.addEventListener('show-alert', function(event) {
            const data = event.detail[0] || event.detail;
            Swal.fire({
                icon: data.type || 'info',
                title: data.type === 'error' ? 'Gagal!' : 'Info',
                text: data.message,
                timer: data.type === 'error' ? 3000 : 2000,
                showConfirmButton: data.type === 'error',
                customClass: { popup: 'rounded-2xl' }
            });
        });
    </script>
</div>
