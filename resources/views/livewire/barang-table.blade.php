@include('components.sweetalert2-cdn')

<div class="space-y-6">
    {{-- Filter Section --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col md:flex-row gap-4 items-start md:items-end justify-between">
            {{-- Search & Filter --}}
            <div class="flex flex-col sm:flex-row gap-4 flex-1 w-full">
                {{-- Search Input --}}
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                    </span>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Cari barang..." 
                           class="w-full pl-12 pr-4 py-3 bg-gray-50/80 border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400/50 focus:border-blue-400 focus:bg-white transition-all duration-200">
                </div>

                {{-- Kategori Dropdown --}}
                <div class="w-full sm:w-48" x-data="{ open: false, value: @entangle('kategori').live }" @click.away="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                                class="w-full px-4 py-3 bg-gray-50/80 border border-gray-200 rounded-xl text-sm text-left text-gray-700 shadow-sm hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400/50 focus:border-blue-400 focus:bg-white transition-all duration-200 flex items-center justify-between">
                            <span x-text="value || 'Semua Kategori'"></span>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl overflow-hidden">
                            <div class="py-2 max-h-60 overflow-y-auto">
                                <button @click="value = ''; open = false" type="button"
                                        class="w-full px-4 py-2.5 text-left text-sm flex items-center gap-2 transition-colors"
                                        :class="!value ? 'bg-blue-50 text-blue-600 font-medium' : 'hover:bg-gray-50 text-gray-700'">
                                    <span class="w-4"><svg x-show="!value" class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></span>
                                    Semua Kategori
                                </button>
                                @foreach($kategoris as $kat)
                                <button @click="value = '{{ $kat }}'; open = false" type="button"
                                        class="w-full px-4 py-2.5 text-left text-sm flex items-center gap-2 transition-colors"
                                        :class="value === '{{ $kat }}' ? 'bg-blue-50 text-blue-600 font-medium' : 'hover:bg-gray-50 text-gray-700'">
                                    <span class="w-4"><svg x-show="value === '{{ $kat }}'" class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></span>
                                    {{ $kat }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Add Button --}}
            @if(auth()->user()->canModify())
            <a href="{{ route('barang.create') }}" 
               class="inline-flex items-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl shadow-sm shadow-blue-500/30 transition-all hover:shadow-md whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Barang
            </a>
            @endif
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        {{-- Sortable: Kode --}}
                        <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider group cursor-pointer hover:bg-gray-100 transition-colors" 
                            wire:click="sortBy('kode_barang')" 
                            title="Klik untuk urutkan">
                            <div class="flex items-center gap-2">
                                <span>Kode</span>
                                <svg class="w-4 h-4 transition-all {{ $sortBy === 'kode_barang' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-500' }} {{ $sortBy === 'kode_barang' && $sortDirection === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                </svg>
                            </div>
                        </th>
                        {{-- Sortable: Nama --}}
                        <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider group cursor-pointer hover:bg-gray-100 transition-colors" 
                            wire:click="sortBy('nama_barang')" 
                            title="Klik untuk urutkan">
                            <div class="flex items-center gap-2">
                                <span>Nama Barang</span>
                                <svg class="w-4 h-4 transition-all {{ $sortBy === 'nama_barang' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-500' }} {{ $sortBy === 'nama_barang' && $sortDirection === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                        {{-- Sortable: Harga Beli --}}
                        <th class="px-5 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider group cursor-pointer hover:bg-gray-100 transition-colors" 
                            wire:click="sortBy('harga_beli')" 
                            title="Klik untuk urutkan">
                            <div class="flex items-center justify-end gap-2">
                                <span>Harga Beli</span>
                                <svg class="w-4 h-4 transition-all {{ $sortBy === 'harga_beli' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-500' }} {{ $sortBy === 'harga_beli' && $sortDirection === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                </svg>
                            </div>
                        </th>
                        {{-- Sortable: Harga Jual --}}
                        <th class="px-5 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider group cursor-pointer hover:bg-gray-100 transition-colors" 
                            wire:click="sortBy('harga_jual')" 
                            title="Klik untuk urutkan">
                            <div class="flex items-center justify-end gap-2">
                                <span>Harga Jual</span>
                                <svg class="w-4 h-4 transition-all {{ $sortBy === 'harga_jual' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-500' }} {{ $sortBy === 'harga_jual' && $sortDirection === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-5 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Satuan</th>
                        <th class="px-5 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($barangs as $barang)
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="px-5 py-4">
                            <span class="font-mono text-sm font-semibold text-gray-900">{{ $barang->kode_barang }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-sm font-medium text-gray-900">{{ $barang->nama_barang }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @if($barang->kategori)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-700">
                                    {{ $barang->kategori }}
                                </span>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <span class="text-sm text-gray-600">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <span class="text-sm font-bold text-gray-900">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-100 text-blue-700">
                                {{ strtoupper($barang->satuan) }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            @if(auth()->user()->canModify())
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('barang.edit', $barang) }}" 
                                   class="w-9 h-9 flex items-center justify-center bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors"
                                   title="Edit Barang">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button onclick="event.preventDefault(); confirmDelete(@this, {{ $barang->id }})" 
                                        class="w-9 h-9 flex items-center justify-center bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors"
                                        title="Hapus Barang">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                            @else
                            <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <p class="text-sm font-medium">Tidak ada data barang</p>
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
            {{ $barangs->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(livewire, id) {
        Swal.fire({
            title: 'Yakin ingin menghapus barang ini?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
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
                livewire.call('delete', id);
            }
        });
    }
</script>
@endpush
