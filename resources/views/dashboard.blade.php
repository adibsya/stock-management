<x-layouts.app>
    <x-slot:title>Dashboard - Ngarumi</x-slot:title>
    <x-slot:header>Dashboard</x-slot:header>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Penjualan Hari Ini -->
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-500 mb-2">Penjualan Hari Ini</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1 truncate">Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-500">{{ $transaksiHariIni }} transaksi</p>
                </div>
                <div class="w-14 h-14 flex-shrink-0 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Penjualan Bulan Ini -->
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-500 mb-2">Penjualan Bulan Ini</p>
                    <p class="text-3xl font-bold text-gray-900 truncate">Rp {{ number_format($penjualanBulanIni, 0, ',', '.') }}</p>
                </div>
                <div class="w-14 h-14 flex-shrink-0 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Barang -->
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-500 mb-2">Total Barang</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($totalBarang) }}</p>
                    <p class="text-sm text-gray-500">{{ $totalPelanggan }} pelanggan</p>
                </div>
                <div class="w-14 h-14 flex-shrink-0 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Stok Alert -->
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-500 mb-2">Stok Alert</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $barangHampirHabis + $barangHabis }}</p>
                    <p class="text-sm text-red-600">{{ $barangHabis }} habis, {{ $barangHampirHabis }} hampir habis</p>
                </div>
                <div class="w-14 h-14 flex-shrink-0 bg-red-100 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grafik Penjualan -->
        <div class="lg:col-span-2 card">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Penjualan 7 Hari Terakhir</h3>
                <p class="text-sm text-gray-500 mt-1">Grafik penjualan dalam seminggu terakhir</p>
            </div>
            <div class="h-64 flex items-end justify-between gap-3 px-2">
                @foreach($grafikPenjualan as $data)
                    @php
                        $maxTotal = max(array_column($grafikPenjualan, 'total')) ?: 1;
                        $height = ($data['total'] / $maxTotal) * 100;
                    @endphp
                    <div class="flex-1 flex flex-col items-center group">
                        <div class="w-full bg-gradient-to-t from-blue-500 to-blue-400 rounded-t-lg transition-all duration-300 hover:from-blue-600 hover:to-blue-500 cursor-pointer shadow-sm" 
                             style="height: {{ max($height, 5) }}%"
                             title="Rp {{ number_format($data['total'], 0, ',', '.') }}">
                        </div>
                        <span class="text-xs font-medium text-gray-600 mt-3">{{ $data['tanggal'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Barang Terlaris -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Barang Terlaris</h3>
            <div class="space-y-4">
                @forelse($barangTerlaris as $index => $barang)
                    <div class="flex items-center gap-4">
                        <span class="w-8 h-8 flex-shrink-0 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-sm font-semibold">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $barang->nama_barang }}</p>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $barang->total_terjual ?? 0 }} terjual</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-sm text-gray-500">Belum ada data penjualan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="mt-6 card">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Transaksi Terbaru</h3>
                <p class="text-sm text-gray-500 mt-1">Daftar transaksi penjualan terkini</p>
            </div>
            <a href="{{ route('penjualan.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-200">
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">No Faktur</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Pelanggan</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Kasir</th>
                        <th class="text-right px-4 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($penjualanTerbaru as $penjualan)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ $penjualan->no_faktur }}</td>
                            <td class="px-4 py-4 text-sm text-gray-600">{{ $penjualan->tanggal->format('d/m/Y') }}</td>
                            <td class="px-4 py-4 text-sm text-gray-900">{{ $penjualan->pelanggan?->nama_pelanggan ?? 'Umum' }}</td>
                            <td class="px-4 py-4 text-sm text-gray-600">{{ $penjualan->user?->name ?? '-' }}</td>
                            <td class="px-4 py-4 text-sm text-right font-semibold text-gray-900">Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm font-medium text-gray-500">Belum ada transaksi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 shadow-lg hover:shadow-xl transition-shadow">
            <p class="text-sm font-medium text-blue-100 mb-3">Pembelian Bulan Ini</p>
            <p class="text-3xl font-bold text-white">Rp {{ number_format($pembelianBulanIni, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-xl bg-gradient-to-br from-red-500 to-red-600 p-6 shadow-lg hover:shadow-xl transition-shadow">
            <p class="text-sm font-medium text-red-100 mb-3">Pengeluaran Bulan Ini</p>
            <p class="text-3xl font-bold text-white">Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-xl bg-gradient-to-br from-green-500 to-green-600 p-6 shadow-lg hover:shadow-xl transition-shadow">
            <p class="text-sm font-medium text-green-100 mb-3">Estimasi Laba</p>
            <p class="text-3xl font-bold text-white">Rp {{ number_format($penjualanBulanIni - $pembelianBulanIni - $pengeluaranBulanIni, 0, ',', '.') }}</p>
        </div>
    </div>
</x-layouts.app>
