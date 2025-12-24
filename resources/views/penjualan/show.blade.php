<x-layouts.app>
    <x-slot:title>Detail Penjualan - Ngarumi</x-slot:title>
    <x-slot:header>Detail Penjualan</x-slot:header>

    <div class="card mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $penjualan->no_faktur }}</h2>
                <p class="text-gray-500">{{ $penjualan->tanggal->format('d F Y') }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $penjualan->status === 'selesai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                {{ ucfirst($penjualan->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <p class="text-sm text-gray-500">Pelanggan</p>
                <p class="font-medium text-gray-800">{{ $penjualan->pelanggan?->nama ?? 'Pelanggan Umum' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Kasir</p>
                <p class="font-medium text-gray-800">{{ $penjualan->user?->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Metode Pembayaran</p>
                <p class="font-medium text-gray-800">{{ ucfirst($penjualan->metode_pembayaran) }}</p>
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
                        <th class="table-header px-4 py-3 text-right">Harga</th>
                        <th class="table-header px-4 py-3 text-center">Qty</th>
                        <th class="table-header px-4 py-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penjualan->detailPenjualan as $index => $detail)
                        <tr class="border-b border-gray-100">
                            <td class="table-cell px-4">{{ $index + 1 }}</td>
                            <td class="table-cell px-4">{{ $detail->barang->kode_barang }}</td>
                            <td class="table-cell px-4">{{ $detail->barang->nama_barang }}</td>
                            <td class="table-cell px-4 text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="table-cell px-4 text-center">{{ $detail->jumlah }}</td>
                            <td class="table-cell px-4 text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-right font-medium text-gray-600">Subtotal</td>
                        <td class="px-4 py-3 text-right font-medium">Rp {{ number_format($penjualan->total_kotor, 0, ',', '.') }}</td>
                    </tr>
                    @if($penjualan->diskon_transaksi > 0)
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-right font-medium text-gray-600">Diskon</td>
                            <td class="px-4 py-3 text-right font-medium text-red-600">- Rp {{ number_format($penjualan->diskon_transaksi, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    @if($penjualan->pajak > 0)
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-right font-medium text-gray-600">Pajak</td>
                            <td class="px-4 py-3 text-right font-medium">Rp {{ number_format($penjualan->pajak, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    <tr class="border-t-2 border-gray-300">
                        <td colspan="5" class="px-4 py-3 text-right text-lg font-bold text-gray-800">Total Bayar</td>
                        <td class="px-4 py-3 text-right text-lg font-bold text-blue-600">Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    @if($penjualan->mode_termin === 'termin')
        <div class="card mt-8">
            <livewire:penjualan-termin-form :penjualan="$penjualan" />
        </div>
    @endif

    <div class="mt-6 flex gap-4">
        <a href="{{ route('penjualan.index') }}" class="btn-secondary">
            &larr; Kembali
        </a>
        <button onclick="window.print()" class="btn-primary">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Cetak
        </button>
    </div>
</x-layouts.app>
