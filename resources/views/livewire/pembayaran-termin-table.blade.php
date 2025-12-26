<div>
    <div class="mb-4 flex gap-2 items-center">
        <input type="text" wire:model.live="search" class="input-field w-64" placeholder="Cari faktur / pemasok...">
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="table-header px-4 py-3">No Faktur</th>
                    <th class="table-header px-4 py-3">Pemasok</th>
                    <th class="table-header px-4 py-3">Jatuh Tempo</th>
                    <th class="table-header px-4 py-3 text-right">Jumlah</th>
                    <th class="table-header px-4 py-3">Status</th>
                    <th class="table-header px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($termins as $termin)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="table-cell px-4">{{ $termin->pembelian->no_faktur_supplier ?? '-' }}</td>
                        <td class="table-cell px-4">{{ $termin->pembelian->pemasok->nama_supplier ?? '-' }}</td>
                        <td class="table-cell px-4">{{ $termin->tanggal_jatuh_tempo ? date('d/m/Y', strtotime($termin->tanggal_jatuh_tempo)) : '-' }}</td>
                        <td class="table-cell px-4 text-right">Rp {{ number_format($termin->jumlah, 0, ',', '.') }}</td>
                        <td class="table-cell px-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Belum Lunas</span>
                        </td>
                        <td class="table-cell px-4 text-center">
                            <a href="#" class="btn-primary btn-sm">Bayar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada termin jatuh tempo</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $termins->links() }}
    </div>
</div>
