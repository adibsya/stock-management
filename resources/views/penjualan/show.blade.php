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

    @if($penjualan->mode_termin === 'termin')
        <div class="card mb-6 border border-yellow-400 bg-yellow-50">
            <div class="p-4">
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-block px-2 py-1 rounded bg-yellow-400 text-white text-xs font-bold">BELUM LUNAS</span>
                    <span class="text-yellow-700 font-semibold">Transaksi ini menggunakan sistem termin (cicilan).</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-yellow-200">
                                <th class="py-2 px-3">Jatuh Tempo</th>
                                <th class="py-2 px-3 text-right">Jumlah Termin</th>
                                <th class="py-2 px-3 text-right">Sudah Dibayar</th>
                                <th class="py-2 px-3 text-right">Sisa Tagihan</th>
                                <th class="py-2 px-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($penjualan->pembayaranPenjualan as $termin)
                                <tr class="border-b border-yellow-100">
                                    <td class="py-2 px-3">{{ $termin->tanggal_jatuh_tempo }}</td>
                                    <td class="py-2 px-3 text-right">Rp {{ number_format($termin->jumlah, 0, ',', '.') }}</td>
                                    <td class="py-2 px-3 text-right">Rp {{ number_format($termin->jumlah_bayar ?? 0, 0, ',', '.') }}</td>
                                    <td class="py-2 px-3 text-right">Rp {{ number_format(max(0, $termin->jumlah - ($termin->jumlah_bayar ?? 0)), 0, ',', '.') }}</td>
                                    <td class="py-2 px-3 text-center">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $termin->status === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                            {{ ucfirst($termin->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div class="card-no-padding">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 px-6 pt-6">Detail Item</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="table-header">No</th>
                        <th class="table-header">Kode Barang</th>
                        <th class="table-header">Nama Barang</th>
                        <th class="table-header text-right">Harga</th>
                        <th class="table-header text-center">Qty</th>
                        <th class="table-header text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penjualan->detailPenjualan as $index => $detail)
                        <tr class="border-b border-gray-100">
                            <td class="table-cell">{{ $index + 1 }}</td>
                            <td class="table-cell">{{ $detail->barang->kode_barang }}</td>
                            <td class="table-cell">
                                {{ $detail->barang->nama_barang }}
                                @if($detail->bonus > 0)
                                    <span class="ml-2 px-2 py-0.5 rounded bg-green-100 text-green-700 font-semibold text-xs">Bonus +{{ $detail->bonus }} pcs</span>
                                @endif
                            </td>
                            <td class="table-cell text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="table-cell text-center">{{ $detail->jumlah }}</td>
                            <td class="table-cell text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
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

    <div class="mt-6 flex gap-4">
        <a href="{{ route('penjualan.index') }}" class="btn-secondary">
            &larr; Kembali
        </a>
        <button onclick="openReturModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
            </svg>
            Retur
        </button>
        @if($penjualan->status === 'selesai')
        <a href="{{ route('penjualan.print', $penjualan) }}" target="_blank" class="btn-primary">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Cetak Invoice
        </a>
        @endif
    </div>

    <!-- Modal Retur -->
    <div id="returModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Proses Retur</h3>
                    <button onclick="closeReturModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <livewire:retur-form />
            </div>
        </div>
    </div>

    <script>
        function openReturModal() {
            document.getElementById('returModal').classList.remove('hidden');
            document.getElementById('returModal').classList.add('flex');
            if (typeof Livewire !== 'undefined') {
                Livewire.dispatch('openReturModal', { penjualanId: {{ $penjualan->id }} });
            }
        }

        function closeReturModal() {
            document.getElementById('returModal').classList.add('hidden');
            document.getElementById('returModal').classList.remove('flex');
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Livewire !== 'undefined') {
                Livewire.on('close-modal', () => {
                    closeReturModal();
                });

                Livewire.on('retur-saved', () => {
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                });
            }
        });
    </script>
</x-layouts.app>
