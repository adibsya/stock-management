<div>
    <!-- Header Actions -->
    <div class="card mb-6">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:flex-1">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Cari gudang..." 
                       class="input-with-icon-left">
            </div>
            @if(auth()->user()->role === 'super_admin')
            <a href="{{ route('gudang.create') }}" class="btn-primary whitespace-nowrap">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Gudang
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
                        <th class="table-header px-4 py-3 cursor-pointer" wire:click="sortBy('nama_gudang')">
                            Nama Gudang
                            @if($sortBy === 'nama_gudang')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="table-header px-4 py-3">Lokasi</th>
                        <th class="table-header px-4 py-3 text-center">Jumlah Barang</th>
                        <th class="table-header px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gudangs as $gudang)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="table-cell px-4 font-medium">{{ $gudang->nama_gudang }}</td>
                            <td class="table-cell px-4">{{ $gudang->lokasi ?? '-' }}</td>
                            <td class="table-cell px-4 text-center">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">
                                    {{ $gudang->barang_count ?? 0 }} item
                                </span>
                            </td>
                            <td class="table-cell px-4 text-center">
                                @if(auth()->user()->role === 'super_admin')
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('gudang.edit', $gudang) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <button wire:click="delete({{ $gudang->id }})" wire:confirm="Yakin ingin menghapus gudang ini?" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
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
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                Tidak ada data gudang
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $gudangs->links() }}
        </div>
    </div>
</div>
