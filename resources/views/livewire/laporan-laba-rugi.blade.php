<div>
    <!-- Periode Filter -->
    <div class="card mb-8 p-6 bg-white shadow rounded-2xl flex flex-wrap gap-4 items-center justify-between">
        <div class="flex gap-2 flex-wrap">
            <button wire:click="setPeriode('hari_ini')" class="px-4 py-2 rounded-lg font-semibold transition-all duration-150 {{ $periode === 'hari_ini' ? 'bg-sky-600 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-sky-50' }}">Hari Ini</button>
            <button wire:click="setPeriode('minggu_ini')" class="px-4 py-2 rounded-lg font-semibold transition-all duration-150 {{ $periode === 'minggu_ini' ? 'bg-sky-600 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-sky-50' }}">Minggu Ini</button>
            <button wire:click="setPeriode('bulan_ini')" class="px-4 py-2 rounded-lg font-semibold transition-all duration-150 {{ $periode === 'bulan_ini' ? 'bg-sky-600 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-sky-50' }}">Bulan Ini</button>
            <button wire:click="setPeriode('tahun_ini')" class="px-4 py-2 rounded-lg font-semibold transition-all duration-150 {{ $periode === 'tahun_ini' ? 'bg-sky-600 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-sky-50' }}">Tahun Ini</button>
        </div>
        <div class="flex gap-2 items-center">
            <input type="date" wire:model.live="startDate" class="input-field border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sky-400">
            <span class="text-gray-500">s/d</span>
            <input type="date" wire:model.live="endDate" class="input-field border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-sky-400">
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Laba Rugi -->
        <div class="card bg-white shadow rounded-2xl p-8">
            <h3 class="text-2xl font-bold text-sky-700 mb-8 flex items-center gap-2">
                <svg class="w-7 h-7 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Laporan Laba Rugi
            </h3>
            <!-- Pendapatan -->
            <div class="space-y-3 mb-8">
                <h4 class="font-semibold text-gray-700 uppercase tracking-wider text-xs">Pendapatan</h4>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Penjualan Kotor</span>
                    <span class="font-semibold">Rp {{ number_format($pendapatanKotor, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Diskon Penjualan</span>
                    <span class="font-semibold text-red-600">- Rp {{ number_format($totalDiskon, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2 bg-green-50 px-3 rounded-lg">
                    <span class="font-semibold text-gray-800">Penjualan Bersih</span>
                    <span class="font-bold text-green-600">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</span>
                </div>
            </div>
            <!-- HPP -->
            <div class="space-y-3 mb-8">
                <h4 class="font-semibold text-gray-700 uppercase tracking-wider text-xs">Harga Pokok Penjualan</h4>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">HPP</span>
                    <span class="font-semibold text-red-600">- Rp {{ number_format($hpp, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2 bg-blue-50 px-3 rounded-lg">
                    <span class="font-semibold text-gray-800">Laba Kotor</span>
                    <span class="font-bold text-blue-600">Rp {{ number_format($labaKotor, 0, ',', '.') }}</span>
                </div>
            </div>
            <!-- Beban -->
            <div class="space-y-3 mb-8">
                <h4 class="font-semibold text-gray-700 uppercase tracking-wider text-xs">Beban Operasional</h4>
                @foreach($pengeluaranPerKategori as $kategori)
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">{{ $kategori->kategori }}</span>
                        <span class="font-semibold text-red-600">- Rp {{ number_format($kategori->total, 0, ',', '.') }}</span>
                    </div>
                @endforeach
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600 font-semibold">Total Beban</span>
                    <span class="font-semibold text-red-600">- Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</span>
                </div>
            </div>
            <!-- Laba Bersih -->
            <div class="p-6 {{ $labaBersih >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-xl flex flex-col items-center">
                <span class="text-lg font-bold text-gray-800 mb-2">LABA/RUGI BERSIH</span>
                <span class="text-3xl font-extrabold {{ $labaBersih >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    Rp {{ number_format($labaBersih, 0, ',', '.') }}
                </span>
            </div>
        </div>
        <!-- Summary Cards -->
        <div class="space-y-6">
            <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white shadow-lg rounded-2xl p-6">
                <p class="text-sm text-green-100">Total Penjualan</p>
                <p class="text-3xl font-extrabold mt-2 drop-shadow">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
            </div>
            <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg rounded-2xl p-6">
                <p class="text-sm text-blue-100">Total Pembelian</p>
                <p class="text-3xl font-extrabold mt-2 drop-shadow">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</p>
            </div>
            <div class="card bg-gradient-to-br from-red-500 to-red-600 text-white shadow-lg rounded-2xl p-6">
                <p class="text-sm text-red-100">Total Pengeluaran</p>
                <p class="text-3xl font-extrabold mt-2 drop-shadow">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
            </div>
            <div class="card bg-white shadow rounded-2xl p-6">
                <h4 class="font-semibold text-gray-700 mb-4">Margin Keuntungan</h4>
                @php
                    $margin = $totalPenjualan > 0 ? ($labaKotor / $totalPenjualan) * 100 : 0;
                @endphp
                <div class="flex items-center gap-4">
                    <div class="flex-1 bg-gray-200 rounded-full h-4 overflow-hidden">
                        <div class="bg-blue-600 h-4 rounded-full transition-all duration-500" style="width: {{ min($margin, 100) }}%"></div>
                    </div>
                    <span class="font-bold text-gray-800">{{ number_format($margin, 1) }}%</span>
                </div>
            </div>
        </div>
    </div>
</div>
