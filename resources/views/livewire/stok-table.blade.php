<div>
    <div class="card mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="flex flex-col md:flex-row gap-4 flex-1 w-full md:w-auto">
            <div class="relative w-full md:w-64">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari barang..." class="input-with-icon-left" />
            </div>
            <select wire:model.live="gudangId" class="input-field w-full md:w-48">
                <option value="">Semua Gudang</option>
                @foreach($gudangs as $gudang)
                    <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                @endforeach
            </select>
            <select wire:model.live="statusStok" class="input-field w-full md:w-40">
                <option value="">Semua Status</option>
                <option value="habis">🔴 Habis</option>
                <option value="sedikit">🟡 Sedikit (≤10)</option>
                <option value="aman">🟢 Aman (>10)</option>
            </select>
        </div>
    </div>
    <div class="card-no-padding overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="table-header">Kode</th>
                        <th class="table-header">Nama Barang</th>
                        <th class="table-header">Gudang</th>
                        <th class="table-header text-right">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stoks as $stok)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="table-cell font-mono text-sm">{{ $stok->barangMaster->kode_barang ?? '-' }}</td>
                            <td class="table-cell">{{ $stok->barangMaster->nama_barang ?? '-' }}</td>
                            <td class="table-cell">{{ $stok->gudang->nama_gudang ?? '-' }}</td>
                            <td class="table-cell text-right">{{ $stok->jumlah }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">Tidak ada data stok</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $stoks->links() }}
            <div class="mt-4 text-right">
                <span class="text-sm font-semibold text-gray-700">Total Stok: </span>
                <span class="text-lg font-bold text-sky-700">{{ number_format($totalStok) }}</span>
            </div>
        </div>
    </div>
</div>
