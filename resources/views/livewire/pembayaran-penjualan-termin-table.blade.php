<div>
    <div class="mb-4 flex gap-2 items-center">
        <a href="{{ url()->previous() }}" class="btn-secondary">Kembali</a>
        <input type="text" wire:model.live="search" class="input-field w-64" placeholder="Cari faktur / pelanggan...">
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="table-header">No Faktur</th>
                    <th class="table-header">Pelanggan</th>
                    <th class="table-header">Jatuh Tempo</th>
                    <th class="table-header text-right">Jumlah</th>
                    <th class="table-header text-right">Sisa Tagihan</th>
                    <th class="table-header">Status</th>
                    <th class="table-header text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($termins as $termin)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="table-cell">{{ $termin->penjualan->no_faktur ?? '-' }}</td>
                        <td class="table-cell">{{ $termin->penjualan->pelanggan->nama ?? '-' }}</td>
                        <td class="table-cell">{{ $termin->tanggal_jatuh_tempo ? date('d/m/Y', strtotime($termin->tanggal_jatuh_tempo)) : '-' }}</td>
                        <td class="table-cell text-right">Rp {{ number_format($termin->jumlah, 0, ',', '.') }}</td>
                        <td class="table-cell text-right">Rp {{ number_format(max(0, $termin->jumlah - ($termin->jumlah_bayar ?? 0)), 0, ',', '.') }}</td>
                        <td class="table-cell">
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Belum Lunas</span>
                        </td>
                        <td class="table-cell text-center">
                            <button wire:click="openModalBayar({{ $termin->id }})" class="btn-primary btn-sm">Bayar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada termin jatuh tempo</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $termins->links() }}
    </div>

    @if($showModal && $modalTerminId)
        @livewire('pembayaran-penjualan-termin-bayar-form', ['termin' => $modalTerminId], key('modal-'.$modalTerminId))
    @endif
</div>
