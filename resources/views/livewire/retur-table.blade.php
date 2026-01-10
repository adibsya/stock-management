<div>
    <!-- Header & Filters -->
    <div class="card mb-6">
        <div class="flex items-center gap-2 py-2 overflow-x-auto whitespace-nowrap">
            <input type="text" wire:model.live.debounce.300ms="search" class="input-field w-36 text-sm px-2 py-1" placeholder="Cari faktur/barang...">
            <input type="date" wire:model.live="tanggal_dari" class="input-field w-28 text-sm px-2 py-1" placeholder="Dari">
            <input type="date" wire:model.live="tanggal_sampai" class="input-field w-28 text-sm px-2 py-1" placeholder="Sampai">
        </div>
    </div>

    <!-- Table -->
    <div class="card-no-padding">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="table-header">Tanggal</th>
                        <th class="table-header">Jenis</th>
                        <th class="table-header">No. Faktur</th>
                        <th class="table-header">Gudang</th>
                        <th class="table-header">Barang</th>
                        <th class="table-header text-center">Jumlah</th>
                        <th class="table-header">Kondisi</th>
                        <th class="table-header">Alasan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returs as $retur)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="table-cell">{{ $retur->tanggal->format('d/m/Y') }}</td>
                            <td class="table-cell">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $retur->jenis_retur === 'retur_penjualan' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $retur->jenis_retur === 'retur_penjualan' ? 'Retur Penjualan' : 'Retur Pembelian' }}
                                </span>
                            </td>
                            <td class="table-cell font-medium">{{ $retur->referensi_faktur }}</td>
                            <td class="table-cell">{{ $retur->penjualan?->gudang?->nama_gudang ?? '-' }}</td>
                            <td class="table-cell">
                                {{ $retur->barang->nama_barang ?? '-' }}
                                <span class="text-xs text-gray-500 block">{{ $retur->barang->kode_barang ?? '-' }}</span>
                            </td>
                            <td class="table-cell text-center">{{ $retur->jumlah }} pcs</td>
                            <td class="table-cell">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $retur->kondisi_barang === 'bagus' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($retur->kondisi_barang) }}
                                </span>
                            </td>
                            <td class="table-cell text-sm text-gray-600">{{ Str::limit($retur->alasan, 50) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                    </svg>
                                    <p class="text-gray-500 font-medium text-base">Tidak ada data retur</p>
                                    <p class="text-gray-400 text-sm mt-1">Retur akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $returs->links() }}
        </div>
    </div>
</div>
