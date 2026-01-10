@include('components.sweetalert2-cdn')

<div>
    <!-- Header Actions -->
    <div class="card mb-6">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex flex-col md:flex-row gap-4 flex-1 w-full md:w-auto">
                <div class="relative w-full md:w-64 lg:w-80">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Cari barang..." 
                           class="input-with-icon-left">
                </div>
                <select wire:model.live="kategori" class="input-field w-full md:w-48">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat }}">{{ $kat }}</option>
                    @endforeach
                </select>
            </div>
            @if(auth()->user()->canModify())
            <a href="{{ route('barang.create') }}" class="btn-primary whitespace-nowrap">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Barang
            </a>
            @endif
        </div>
    </div>

    <!-- Table -->
    <div class="card-no-padding overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                <tr class="border-b border-gray-200">
                    <th class="table-header cursor-pointer" wire:click="sortBy('kode_barang')">
                        Kode
                        @if($sortBy === 'kode_barang')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>

                    <th class="table-header cursor-pointer" wire:click="sortBy('nama_barang')">
                        Nama Barang
                        @if($sortBy === 'nama_barang')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>

                    <th class="table-header">Kategori</th>

                    <th class="table-header text-right cursor-pointer" wire:click="sortBy('harga_beli')">
                        Harga Beli
                    </th>

                    <th class="table-header text-right cursor-pointer" wire:click="sortBy('harga_jual')">
                        Harga Jual
                    </th>

                    <th class="table-header text-center">Satuan</th>

                    <th class="table-header text-center">Aksi</th>
                </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $barang)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="table-cell font-mono text-sm">
                            {{ $barang->kode_barang }}
                        </td>

                        <td class="table-cell">
                            <p class="font-medium">{{ $barang->nama_barang }}</p>
                        </td>

                        <td class="table-cell">
                            @if($barang->kategori)
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">
                                    {{ $barang->kategori }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="table-cell text-right">
                            Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}
                        </td>

                        <td class="table-cell text-right font-semibold">
                            Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}
                        </td>

                        <td class="table-cell text-center">
                            {{ strtoupper($barang->satuan) }}
                        </td>

                        <td class="table-cell">
                            @if(auth()->user()->canModify())
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('barang.edit', $barang) }}"
                                    class="w-9 h-9 flex items-center justify-center
                                            text-blue-600 hover:bg-blue-50
                                            rounded-lg transition">
                                        ✏️
                                    </a>

                                    <button onclick="event.preventDefault(); confirmDelete(@this, {{ $barang->id }})"
                                            class="w-9 h-9 flex items-center justify-center
                                                text-red-600 hover:bg-red-50
                                                rounded-lg transition">
                                        🗑️
                                    </button>
                                </div>
                            @else
                                <div class="flex justify-center text-gray-400">-</div>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            Tidak ada data barang
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-200">
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
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                livewire.call('delete', id);
            }
        });
    }
</script>
@endpush
