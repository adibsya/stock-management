<div>
    <div class="card mb-6">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:w-64 lg:w-80">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari barang..." class="input-with-icon-left">
            </div>
            <div>
                <select wire:model="gudangId" class="input-field">
                    <option value="">Semua Gudang</option>
                    @foreach($gudangs as $gudang)
                        <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="card-no-padding overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="table-header cursor-pointer" wire:click="sortBy('barang_master_id')">Kode</th>
                        <th class="table-header cursor-pointer" wire:click="sortBy('barang_master_id')">Nama Barang</th>
                        <th class="table-header">Gudang</th>
                        <th class="table-header">Stok</th>
                        <th class="table-header">Stok Minimum</th>
                        <th class="table-header">Harga Beli</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $barang)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $barang->master->kode_barang ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $barang->master->nama_barang ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $barang->gudang->nama_gudang ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $barang->stok }}</td>
                            <td class="px-4 py-3">{{ $barang->stok_minimum }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada data stok barang</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $barangs->links() }}
        </div>
    </div>
</div>
