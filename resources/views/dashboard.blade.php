<x-layouts.app>
    <x-slot:title>Dashboard - Penak Stock Management</x-slot:title>
    <x-slot:header>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-sm text-gray-500 mt-1">Selamat datang di sistem manajemen stok</p>
            </div>
        </div>
    </x-slot:header>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Penjualan Hari Ini -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all border border-gray-100 p-6 group">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-lg shadow-green-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0 text-right">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Penjualan Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900 mb-1">Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</p>
                    <div class="flex items-center justify-end gap-1">
                        @if($trendHarian > 0)
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            <span class="text-xs font-semibold text-green-600">+{{ number_format($trendHarian, 1) }}%</span>
                        @elseif($trendHarian < 0)
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                            </svg>
                            <span class="text-xs font-semibold text-red-600">{{ number_format($trendHarian, 1) }}%</span>
                        @else
                            <span class="text-xs font-medium text-gray-400">0%</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-100">
                <p class="text-sm text-gray-600">{{ $transaksiHariIni }} transaksi hari ini</p>
            </div>
        </div>

        <!-- Penjualan Bulan Ini -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all border border-gray-100 p-6 group">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0 text-right">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Penjualan Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-900 mb-1">Rp {{ number_format($penjualanBulanIni, 0, ',', '.') }}</p>
                    <div class="flex items-center justify-end gap-1">
                        @if($trendPenjualan > 0)
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            <span class="text-xs font-semibold text-green-600">+{{ number_format($trendPenjualan, 1) }}%</span>
                        @elseif($trendPenjualan < 0)
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                            </svg>
                            <span class="text-xs font-semibold text-red-600">{{ number_format($trendPenjualan, 1) }}%</span>
                        @else
                            <span class="text-xs font-medium text-gray-400">0%</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-100">
                <p class="text-sm text-gray-600">vs bulan lalu</p>
            </div>
        </div>

        <!-- Total Barang & Pelanggan -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all border border-gray-100 p-6 group">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0 text-right">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Total Barang</p>
                    <p class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($totalBarang) }}</p>
                    <div class="flex items-center justify-end gap-1">
                        <span class="text-xs font-medium text-purple-600">{{ number_format($totalPelanggan) }} pelanggan</span>
                    </div>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-100">
                <p class="text-sm text-gray-600">{{ $pelangganBaru }} pelanggan baru bulan ini</p>
            </div>
        </div>

        <!-- Stok Alert  -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all border border-gray-100 p-6 group">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-sky-500 to-sky-600 rounded-lg flex items-center justify-center shadow-lg shadow-sky-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18M3 7h18M3 11h18M3 15h18M3 19h18"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0 text-right">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Total Stok Barang</p>
                    <p class="text-2xl font-bold text-sky-700 mb-1">{{ isset($totalStokSemuaGudang) ? number_format($totalStokSemuaGudang) : '-' }}</p>
                    <div class="flex items-center justify-end gap-1">
                        @if(auth()->user() && auth()->user()->isAdmin())
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-sky-100 text-sky-800">
                                Gudang Saya
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-sky-100 text-sky-800">
                                Seluruh Gudang
                            </span>
                        @endif
                        </span>
                    </div>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-100">
                <p class="text-sm text-gray-600">Total stok barang aktif di gudang</p>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Grafik Penjualan vs Pembelian -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Penjualan & Pembelian</h3>
                    <p class="text-sm text-gray-500 mt-1">7 hari terakhir</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                        <span class="text-xs text-gray-600">Penjualan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <span class="text-xs text-gray-600">Pembelian</span>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <canvas id="salesChart" class="w-full" style="height: 180px;"></canvas>
            </div>
        </div>

        <!-- Penjualan per Kategori -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Penjualan per Kategori</h3>
                <p class="text-sm text-gray-500 mt-1">Bulan ini</p>
            </div>
            @if($penjualanPerKategori->count() > 0)
                <div class="flex items-center justify-center mb-4">
                    <canvas id="categoryChart" class="w-full" style="height: 150px; max-width: 250px;"></canvas>
                </div>
                <div class="space-y-2">
                    @foreach($penjualanPerKategori as $index => $kategori)
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'][$index % 5] }}"></div>
                                <span class="text-gray-700">{{ $kategori->kategori ?? 'Tanpa Kategori' }}</span>
                            </div>
                            <span class="font-semibold text-gray-900">Rp {{ number_format($kategori->total, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p class="text-sm text-gray-500">Belum ada data penjualan</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Barang Terlaris & Transaksi Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Barang Terlaris -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-2 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center shadow-lg shadow-yellow-500/30">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Barang Terlaris</h3>
                    <p class="text-xs text-gray-500">Bulan ini</p>
                </div>
            </div>
            <div class="space-y-3">
                @forelse($barangTerlaris ?? [] as $index => $barang)
                    <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors group">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-lg {{ $index == 0 ? 'bg-gradient-to-br from-yellow-400 to-yellow-500' : ($index == 1 ? 'bg-gradient-to-br from-gray-300 to-gray-400' : ($index == 2 ? 'bg-gradient-to-br from-orange-400 to-orange-500' : 'bg-gray-100')) }} flex items-center justify-center font-bold {{ $index < 3 ? 'text-white' : 'text-gray-600' }} text-sm shadow">
                                {{ $index + 1 }}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate group-hover:text-blue-600 transition-colors">{{ $barang->nama_barang }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-gray-500">{{ $barang->total_terjual ?? 0 }} terjual</span>
                                @if($barang->kategori)
                                    <span class="text-xs px-2 py-0.5 bg-blue-50 text-blue-600 rounded">{{ $barang->kategori }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <p class="text-sm text-gray-500">Belum ada data penjualan</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Transaksi Terbaru -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Transaksi Terbaru</h3>
                    <p class="text-sm text-gray-500 mt-1">Aktivitas penjualan terkini</p>
                </div>
                <a href="{{ route('penjualan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="overflow-x-auto -mx-6">
                <table class="w-full">
                    <thead>
                        <tr class="border-y border-gray-200 bg-gray-50">
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">No Faktur</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Pelanggan</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Kasir</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($penjualanTerbaru as $penjualan)
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $penjualan->no_faktur }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm text-gray-900">{{ $penjualan->tanggal->format('d/m/Y') }}</span>
                                        <span class="text-xs text-gray-500">{{ $penjualan->tanggal->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-xs font-semibold text-blue-600">{{ substr($penjualan->pelanggan?->nama_pelanggan ?? 'U', 0, 1) }}</span>
                                        </div>
                                        <span class="text-sm text-gray-900">{{ $penjualan->pelanggan?->nama_pelanggan ?? 'Umum' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $penjualan->user?->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-bold text-gray-900">Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500">Belum ada transaksi</p>
                                    <p class="text-xs text-gray-400 mt-1">Transaksi akan muncul di sini</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Sales Chart
        const salesCtx = document.getElementById('salesChart');
        if (salesCtx) {
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_column($grafikPenjualan, 'tanggal')) !!},
                    datasets: [{
                        label: 'Penjualan',
                        data: {!! json_encode(array_column($grafikPenjualan, 'total')) !!},
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }, {
                        label: 'Pembelian',
                        data: {!! json_encode(array_column($grafikPembelian, 'total')) !!},
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointBackgroundColor: 'rgb(239, 68, 68)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000000 >= 1 ? (value / 1000000).toFixed(1) + 'jt' : (value / 1000).toFixed(0) + 'rb');
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Category Chart
        const categoryCtx = document.getElementById('categoryChart');
        if (categoryCtx) {
            const categoryData = {!! json_encode($penjualanPerKategori) !!};
            if (categoryData.length > 0) {
                new Chart(categoryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: categoryData.map(item => item.kategori || 'Tanpa Kategori'),
                        datasets: [{
                            data: categoryData.map(item => item.total),
                            backgroundColor: [
                                'rgb(59, 130, 246)',
                                'rgb(16, 185, 129)',
                                'rgb(245, 158, 11)',
                                'rgb(239, 68, 68)',
                                'rgb(139, 92, 246)'
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return label + ': Rp ' + value.toLocaleString('id-ID') + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }

        // Refresh Dashboard
        function refreshDashboard() {
            location.reload();
        }
    </script>
</x-layouts.app>
