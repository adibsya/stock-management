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
                        <th class="table-header px-4 py-3 cursor-pointer" wire:click="sortBy('kode_barang')">
                            Kode
                            @if($sortBy === 'kode_barang')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="table-header px-4 py-3 cursor-pointer" wire:click="sortBy('nama_barang')">
                            Nama Barang
                            @if($sortBy === 'nama_barang')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="table-header px-4 py-3">Kategori</th>
                        <th class="table-header px-4 py-3 text-right cursor-pointer" wire:click="sortBy('harga_beli')">
                            Harga Beli
                            @if($sortBy === 'harga_beli')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="table-header px-4 py-3 text-right cursor-pointer" wire:click="sortBy('harga_jual')">
                            Harga Jual
                            @if($sortBy === 'harga_jual')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="table-header px-4 py-3 text-center cursor-pointer" wire:click="sortBy('stok')">
                            Stok
                            @if($sortBy === 'stok')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="table-header px-4 py-3">Satuan</th>
                        <th class="table-header px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $barang)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="table-cell px-4 font-mono text-sm">{{ $barang->kode_barang }}</td>
                            <td class="table-cell px-4">
                                <div>
                                    <p class="font-medium">{{ $barang->nama_barang }}</p>
                                    @if($barang->pemasok)
                                        <p class="text-xs text-gray-500">{{ $barang->pemasok->nama }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="table-cell px-4">
                                @if($barang->kategori)
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">{{ $barang->kategori }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="table-cell px-4 text-right">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                            <td class="table-cell px-4 text-right font-medium">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                            <td class="table-cell px-4 text-center">
                                @if($barang->stok <= 0)
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Habis</span>
                                @elseif($barang->stok < $barang->stok_minimum)
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">{{ $barang->stok }}</span>
                                @else
                                    <span class="font-medium">{{ $barang->stok }}</span>
                                @endif
                            </td>
                            <td class="table-cell px-4">{{ $barang->satuan }}</td>
                            <td class="table-cell px-4 text-center">
                                @if(auth()->user()->canModify())
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('barang.edit', $barang) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <button wire:click="delete({{ $barang->id }})" wire:confirm="Yakin ingin menghapus barang ini?" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
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
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">
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
