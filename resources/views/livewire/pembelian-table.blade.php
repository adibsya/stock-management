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
                           placeholder="Cari no faktur / pemasok..." 
                           class="input-with-icon-left">
                </div>
                <input type="date" wire:model.live="startDate" class="input-field w-full md:w-40">
                <input type="date" wire:model.live="endDate" class="input-field w-full md:w-40">
                <select wire:model.live="statusBayar" class="input-field w-full md:w-40">
                    <option value="">Semua Status</option>
                    <option value="lunas">Lunas</option>
                    <option value="belum_lunas">Belum Lunas</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white">
            <p class="text-sm text-blue-100">Total Pembelian (Periode)</p>
            <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Table -->
    <div class="card-no-padding overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="table-header px-4 py-3 cursor-pointer" wire:click="sortBy('no_faktur_supplier')">
                            No Faktur Supplier
                            @if($sortBy === 'no_faktur_supplier')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="table-header px-4 py-3 cursor-pointer" wire:click="sortBy('tanggal')">
                            Tanggal
                            @if($sortBy === 'tanggal')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="table-header px-4 py-3">Pemasok</th>
                        <th class="table-header px-4 py-3 text-right cursor-pointer" wire:click="sortBy('total_biaya')">
                            Total
                            @if($sortBy === 'total_biaya')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="table-header px-4 py-3">Jatuh Tempo</th>
                        <th class="table-header px-4 py-3">Status</th>
                        <th class="table-header px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembelians as $pembelian)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="table-cell px-4 font-mono text-sm">{{ $pembelian->no_faktur_supplier ?: '-' }}</td>
                            <td class="table-cell px-4">{{ $pembelian->tanggal->format('d/m/Y') }}</td>
                            <td class="table-cell px-4">{{ $pembelian->pemasok?->nama ?? '-' }}</td>
                            <td class="table-cell px-4 text-right font-medium">Rp {{ number_format($pembelian->total_biaya, 0, ',', '.') }}</td>
                            <td class="table-cell px-4">
                                @if($pembelian->jatuh_tempo)
                                    <span class="{{ $pembelian->isJatuhTempo() ? 'text-red-600' : '' }}">
                                        {{ $pembelian->jatuh_tempo->format('d/m/Y') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="table-cell px-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $pembelian->status_bayar === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $pembelian->status_bayar === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                            </td>
                            <td class="table-cell px-4 text-center">
                                <a href="{{ route('pembelian.show', $pembelian) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition inline-block">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                Tidak ada data pembelian
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $pembelians->links() }}
        </div>
    </div>
</div>
