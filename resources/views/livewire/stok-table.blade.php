<div>
    <div class="card mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari barang..." class="input-field w-full md:w-80" />
    </div>
    <div class="card-no-padding overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="table-header px-4 py-3">Kode</th>
                        <th class="table-header px-4 py-3">Nama Barang</th>
                        <th class="table-header px-4 py-3">Gudang</th>
                        <th class="table-header px-4 py-3 text-right">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stoks as $stok)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="table-cell px-4 font-mono text-sm">{{ $stok->barangMaster->kode_barang ?? '-' }}</td>
                            <td class="table-cell px-4">{{ $stok->barangMaster->nama_barang ?? '-' }}</td>
                            <td class="table-cell px-4">{{ $stok->gudang->nama_gudang ?? '-' }}</td>
                            <td class="table-cell px-4 text-right">{{ $stok->jumlah }}</td>
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
        </div>
    </div>
</div>
