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
                           placeholder="Cari pelanggan..." 
                           class="w-full pl-12 pr-4 py-3 bg-gray-50/80 border border-gray-200 rounded-xl text-sm text-gray-700 placeholder-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400/50 focus:border-blue-400 focus:bg-white transition-all duration-200">
                </div>

                {{-- Lokasi Dropdown --}}
                <div class="w-full sm:w-48" x-data="{ open: false, value: @entangle('kota').live }" @click.away="open = false">
                    <div class="relative">
                        <button @click="open = !open" type="button"
                                class="w-full px-4 py-3 bg-gray-50/80 border border-gray-200 rounded-xl text-sm text-left text-gray-700 shadow-sm hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400/50 focus:border-blue-400 focus:bg-white transition-all duration-200 flex items-center justify-between">
                            <span x-text="value || 'Semua Lokasi'"></span>
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
                                    Semua Lokasi
                                </button>
                                @foreach($kotas as $k)
                                <button @click="value = '{{ $k }}'; open = false" type="button"
                                        class="w-full px-4 py-2.5 text-left text-sm flex items-center gap-2 transition-colors"
                                        :class="value === '{{ $k }}' ? 'bg-blue-50 text-blue-600 font-medium' : 'hover:bg-gray-50 text-gray-700'">
                                    <span class="w-4"><svg x-show="value === '{{ $k }}'" class="w-3.5 h-3.5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></span>
                                    {{ $k }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Add Button --}}
            @if(auth()->user()->canModify())
            <a href="{{ route('pelanggan.create') }}" 
               class="inline-flex items-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl shadow-sm shadow-blue-500/30 transition-all hover:shadow-md whitespace-nowrap">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Pelanggan
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
                        <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider group cursor-pointer hover:bg-gray-100 transition-colors" 
                            wire:click="sortBy('nama_pelanggan')" 
                            title="Klik untuk urutkan">
                            <div class="flex items-center gap-2">
                                <span>Nama</span>
                                <svg class="w-4 h-4 transition-all {{ $sortBy === 'nama_pelanggan' ? 'text-blue-600' : 'text-gray-300 group-hover:text-gray-500' }} {{ $sortBy === 'nama_pelanggan' && $sortDirection === 'desc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                </svg>
                            </div>
                        </th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No. HP</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Alamat</th>
                        <th class="px-5 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pelanggans as $pelanggan)
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="px-5 py-4">
                            <span class="font-mono text-sm text-gray-500">{{ $pelanggan->kode_pelanggan ?? '-' }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-sm font-semibold text-gray-900">{{ $pelanggan->nama_pelanggan }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-sm text-gray-600">{{ $pelanggan->no_hp ?? '-' }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-sm text-gray-600 truncate max-w-xs block">{{ $pelanggan->alamat ?? '-' }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @if(auth()->user()->canModify())
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('pelanggan.edit', $pelanggan) }}" 
                                   class="w-9 h-9 flex items-center justify-center bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors"
                                   title="Edit Pelanggan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button onclick="event.preventDefault(); confirmDelete(@this, {{ $pelanggan->id }})" 
                                        class="w-9 h-9 flex items-center justify-center bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors"
                                        title="Hapus Pelanggan">
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
                        <td colspan="5" class="px-5 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-sm font-medium">Tidak ada data pelanggan</p>
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
            {{ $pelanggans->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(livewire, id) {
        Swal.fire({
            title: 'Yakin ingin menghapus pelanggan ini?',
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

    window.addEventListener('pelanggan-deleted', function(e) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Pelanggan berhasil dihapus.',
            timer: 2000,
            showConfirmButton: false,
            customClass: {
                popup: 'rounded-2xl'
            }
        });
    });
</script>
@endpush
