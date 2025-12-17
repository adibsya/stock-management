<div>
    <!-- Header -->
    <div class="card mb-6">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex flex-col md:flex-row gap-4 flex-1 w-full md:w-auto">
                <div class="relative w-full md:w-64 lg:w-80">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Cari keterangan..." 
                           class="input-with-icon-left">
                </div>
                <input type="date" wire:model.live="startDate" class="input-field w-full md:w-40">
                <input type="date" wire:model.live="endDate" class="input-field w-full md:w-40">
                <select wire:model.live="kategori" class="input-field w-full md:w-40">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat }}">{{ $kat }}</option>
                    @endforeach
                </select>
            </div>
            @if(auth()->user()->canModify())
            <a href="{{ route('pengeluaran.create') }}" class="btn-primary whitespace-nowrap">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Pengeluaran
            </a>
            @endif
        </div>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="card bg-gradient-to-br from-red-500 to-red-600 text-white">
            <p class="text-sm text-red-100">Total Pengeluaran (Periode)</p>
            <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Table -->
    <div class="card-no-padding overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="table-header px-4 py-3 cursor-pointer" wire:click="sortBy('tanggal')">
                            Tanggal
                            @if($sortBy === 'tanggal')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="table-header px-4 py-3">Kategori</th>
                        <th class="table-header px-4 py-3">Keterangan</th>
                        <th class="table-header px-4 py-3 text-right cursor-pointer" wire:click="sortBy('jumlah_biaya')">
                            Jumlah
                            @if($sortBy === 'jumlah_biaya')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="table-header px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengeluarans as $pengeluaran)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="table-cell px-4">{{ $pengeluaran->tanggal->format('d/m/Y') }}</td>
                            <td class="table-cell px-4">
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">{{ $pengeluaran->jenis_pengeluaran }}</span>
                            </td>
                            <td class="table-cell px-4">{{ $pengeluaran->keterangan ?? '-' }}</td>
                            <td class="table-cell px-4 text-right font-medium text-red-600">Rp {{ number_format($pengeluaran->jumlah_biaya, 0, ',', '.') }}</td>
                            <td class="table-cell px-4 text-center">
                                @if(auth()->user()->canModify())
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('pengeluaran.edit', $pengeluaran) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <button wire:click="delete({{ $pengeluaran->id }})" wire:confirm="Yakin ingin menghapus pengeluaran ini?" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
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
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                Tidak ada data pengeluaran
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $pengeluarans->links() }}
        </div>
    </div>
</div>
