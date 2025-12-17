<div>
    <!-- Filter -->
    <div class="card mb-6">
        <div class="flex flex-col md:flex-row gap-4 items-center">
            <div class="relative w-full md:w-64 lg:w-80">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Cari barang..." 
                       class="input-with-icon-left">
            </div>
            <select wire:model.live="barang_id" class="input-field w-full md:w-64">
                <option value="">Semua Barang</option>
                @foreach($barangs as $barang)
                    <option value="{{ $barang->id }}">{{ $barang->kode_barang }} - {{ $barang->nama_barang }}</option>
                @endforeach
            </select>
            <input type="date" wire:model.live="startDate" class="input-field w-full md:w-40">
            <input type="date" wire:model.live="endDate" class="input-field w-full md:w-40">
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Summary Stok -->
        @foreach($summaryStok->take(6) as $summary)
            <div class="card">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-gray-500">{{ $summary->kode_barang }}</p>
                        <p class="font-medium text-gray-800">{{ $summary->nama_barang }}</p>
                    </div>
                    <span class="px-2 py-1 rounded-full text-sm font-medium {{ $summary->stok <= 0 ? 'bg-red-100 text-red-700' : ($summary->stok < $summary->stok_minimum ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                        {{ $summary->stok }} {{ $summary->satuan }}
                    </span>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Masuk</p>
                        <p class="font-medium text-green-600">+{{ $summary->total_masuk ?? 0 }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Keluar</p>
                        <p class="font-medium text-red-600">-{{ $summary->total_keluar ?? 0 }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Riwayat Stok Table -->
    <div class="card-no-padding overflow-hidden">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 px-6 pt-6">Riwayat Pergerakan Stok</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="table-header px-4 py-3">Tanggal</th>
                        <th class="table-header px-4 py-3">Barang</th>
                        <th class="table-header px-4 py-3">Jenis</th>
                        <th class="table-header px-4 py-3 text-center">Masuk</th>
                        <th class="table-header px-4 py-3 text-center">Keluar</th>
                        <th class="table-header px-4 py-3 text-center">Sisa Stok</th>
                        <th class="table-header px-4 py-3">Referensi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatStok as $riwayat)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="table-cell px-4">{{ $riwayat->tanggal->format('d/m/Y') }}</td>
                            <td class="table-cell px-4">
                                <p class="font-medium">{{ $riwayat->barang->nama_barang }}</p>
                                <p class="text-xs text-gray-500">{{ $riwayat->barang->kode_barang }}</p>
                            </td>
                            <td class="table-cell px-4">
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $riwayat->jenis_transaksi === 'penjualan' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                    {{ ucfirst($riwayat->jenis_transaksi) }}
                                </span>
                            </td>
                            <td class="table-cell px-4 text-center text-green-600 font-medium">
                                {{ $riwayat->jumlah_masuk > 0 ? '+' . $riwayat->jumlah_masuk : '-' }}
                            </td>
                            <td class="table-cell px-4 text-center text-red-600 font-medium">
                                {{ $riwayat->jumlah_keluar > 0 ? '-' . $riwayat->jumlah_keluar : '-' }}
                            </td>
                            <td class="table-cell px-4 text-center font-medium">{{ $riwayat->sisa_stok }}</td>
                            <td class="table-cell px-4 text-sm text-gray-500">{{ $riwayat->referensi_id }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                Tidak ada data riwayat stok
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $riwayatStok->links() }}
        </div>
    </div>
</div>
