<x-layouts.app>
    <x-slot:title>Detail Pembelian - Ngarumi</x-slot:title>
    <x-slot:header>Detail Pembelian</x-slot:header>

    <div class="card mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $pembelian->no_faktur_supplier ?: 'Tanpa Faktur' }}</h2>
                <p class="text-gray-500">{{ $pembelian->tanggal->format('d F Y') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $pembelian->status_bayar === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ $pembelian->status_bayar === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                </span>
                @if(Str::contains($pembelian->mode_termin, 'termin'))
                    <a href="{{ route('pembelian.termin', $pembelian->id) }}" class="btn-primary">Pembayaran Termin</a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <p class="text-sm text-gray-500">Pemasok</p>
                <p class="font-medium text-gray-800">{{ $pembelian->pemasok?->nama ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Jatuh Tempo</p>
                <p class="font-medium text-gray-800">{{ $pembelian->jatuh_tempo?->format('d F Y') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Biaya</p>
                <p class="font-medium text-gray-800 text-xl">Rp {{ number_format($pembelian->total_biaya, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="card-no-padding">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 px-6 pt-6">Detail Item</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="table-header px-4 py-3">No</th>
                        <th class="table-header px-4 py-3">Kode Barang</th>
                        <th class="table-header px-4 py-3">Nama Barang</th>
                        <th class="table-header px-4 py-3 text-right">Harga Beli</th>
                        <th class="table-header px-4 py-3 text-center">Qty</th>
                        <th class="table-header px-4 py-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pembelian->detailPembelian as $index => $detail)
                        <tr class="border-b border-gray-100">
                            <td class="table-cell px-4">{{ $index + 1 }}</td>
                            <td class="table-cell px-4">{{ $detail->barangmaster->kode_barang }}</td>
                            <td class="table-cell px-4">{{ $detail->barangmaster->nama_barang }}</td>
                            <td class="table-cell px-4 text-right">Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                            <td class="table-cell px-4 text-center">{{ $detail->jumlah }}</td>
                            <td class="table-cell px-4 text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr class="border-t-2 border-gray-300">
                        <td colspan="5" class="px-4 py-3 text-right text-lg font-bold text-gray-800">Total</td>
                        <td class="px-4 py-3 text-right text-lg font-bold text-blue-600">Rp {{ number_format($pembelian->total_biaya, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @if($pembelian->mode_termin === 'termin')
        <div class="card mt-8">
            <livewire:pembelian-termin-form :pembelian="$pembelian" />
        </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('pembelian.index') }}" class="btn-secondary">
            &larr; Kembali
        </a>
    </div>
</x-layouts.app>
