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
                       placeholder="Cari berdasarkan no faktur atau nama pemasok..." 
                       class="w-full pl-14 pr-5 py-3.5 bg-gray-50/80 border border-gray-200 rounded-2xl text-sm text-gray-700 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400/50 focus:border-blue-400 focus:bg-white transition-all duration-200">
            </div>
        </div>

        {{-- Filters Grid --}}
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
            @if($showGudangFilter)
            <div class="space-y-2">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Gudang</label>
                <div class="relative">
                    <select wire:model.live="gudangId" 
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
            @endif

            {{-- Status Bayar --}}
            <div class="space-y-2">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Bayar</label>
                <div class="relative">
                    <select wire:model.live="statusBayar" 
                            class="w-full px-4 py-3 pr-10 bg-gray-50/80 border border-gray-200 rounded-xl text-sm text-gray-700 shadow-sm hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400/50 focus:border-blue-400 focus:bg-white transition-all duration-200 appearance-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="lunas">Lunas</option>
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
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-5 text-white shadow-lg shadow-blue-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-100">Total Pembelian (Periode)</p>
                    <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('pembelian.kasir') }}" 
               class="inline-flex items-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl shadow-sm shadow-blue-500/30 transition-all hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Kasir Pembelian
            </a>
            @php
                $adaTermin = isset($pembelians) && $pembelians->where('status_bayar', 'belum_lunas')->where('jatuh_tempo', '!=', null)->count() > 0;
            @endphp
            <a href="{{ $adaTermin ? route('pembelian.termin') : '#' }}"
               class="inline-flex items-center gap-2 px-5 py-3 text-sm font-medium rounded-xl shadow-sm transition-all
                      {{ $adaTermin ? 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-emerald-500/30 hover:shadow-md' : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}"
               @if(!$adaTermin) tabindex="-1" aria-disabled="true" title="Tidak ada termin aktif" onclick="return false;" @endif>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                </svg>
                Kasir Termin
            </a>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        {{-- Sortable: No Faktur Supplier --}}
                        <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider group cursor-pointer hover:bg-gray-100 transition-colors" 
                            wire:click="sortBy('no_faktur_supplier')" 
                            title="Klik untuk urutkan berdasarkan No Faktur">
                            <div class="flex items-center gap-2">
                                <span>No Faktur</span>
                                <svg class="w-4 h-4 transition-all {{ $sortColumn === 'no_faktur_supplier' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-500' }} {{ $sortColumn === 'no_faktur_supplier' && $sortDirection === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                        <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pemasok</th>
                        {{-- Sortable: Total --}}
                        <th class="px-5 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider group cursor-pointer hover:bg-gray-100 transition-colors" 
                            wire:click="sortBy('total_biaya')" 
                            title="Klik untuk urutkan berdasarkan Total">
                            <div class="flex items-center justify-end gap-2">
                                <span>Total</span>
                                <svg class="w-4 h-4 transition-all {{ $sortColumn === 'total_biaya' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-500' }} {{ $sortColumn === 'total_biaya' && $sortDirection === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jatuh Tempo</th>
                        <th class="px-5 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pembelians as $pembelian)
                        <tr class="hover:bg-blue-50/50 transition-colors">
                            <td class="px-5 py-4">
                                <span class="font-mono text-sm font-semibold text-gray-900">
                                    {{ $pembelian->no_faktur_supplier ?: '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600">
                                {{ $pembelian->tanggal->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-600">
                                {{ $pembelian->gudang?->nama_gudang ?? '-' }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $pembelian->pemasok?->nama_supplier ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <span class="text-sm font-bold text-gray-900 whitespace-nowrap">
                                    Rp {{ number_format($pembelian->total_biaya, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @if($pembelian->jatuh_tempo)
                                    @php
                                        $today = \Carbon\Carbon::now();
                                        $jatuhTempo = $pembelian->jatuh_tempo;
                                        $diff = $jatuhTempo ? $today->diffInDays($jatuhTempo, false) : null;
                                        $isWarning = $pembelian->isJatuhTempo() || ($diff !== null && $diff <= 10 && $diff >= 0);
                                    @endphp
                                    <span class="text-sm {{ $isWarning ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                                        {{ $pembelian->jatuh_tempo->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium
                                    {{ $pembelian->status_bayar === 'lunas' 
                                        ? 'bg-emerald-100 text-emerald-700' 
                                        : 'bg-amber-100 text-amber-700' }}">
                                    {{ $pembelian->status_bayar === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    {{-- Detail Button --}}
                                    <a href="{{ route('pembelian.show', $pembelian) }}" 
                                       class="w-9 h-9 flex items-center justify-center bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors"
                                       title="Detail Pembelian">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    {{-- Delete Button --}}
                                    <button onclick="hapusPembelian({{ $pembelian->id }})" 
                                            class="w-9 h-9 flex items-center justify-center bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors"
                                            title="Hapus Pembelian">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm font-medium">Tidak ada data pembelian</p>
                                    <p class="text-xs mt-1">Silakan ubah filter atau tambah data baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="px-5 py-4 border-t border-gray-200 bg-gray-50/50">
            {{ $pembelians->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function hapusPembelian(id) {
        Swal.fire({
            title: 'Yakin hapus data ini?',
            text: 'Data pembelian dan stok akan dihapus!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl',
                cancelButton: 'rounded-xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.Livewire.dispatch('delete', [id]);
            }
        });
    }
    
    window.addEventListener('pembelian-dihapus', function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data pembelian berhasil dihapus.',
            timer: 1800,
            showConfirmButton: false,
            customClass: {
                popup: 'rounded-2xl'
            }
        });
    });
</script>
@endpush
