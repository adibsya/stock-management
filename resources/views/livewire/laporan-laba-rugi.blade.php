<div>
    <!-- Periode Filter -->
    <div class="card mb-6">
        <div class="flex flex-wrap gap-4 items-center justify-between">
            <div class="flex gap-2">
                <button wire:click="setPeriode('hari_ini')" class="{{ $periode === 'hari_ini' ? 'btn-primary' : 'btn-secondary' }}">Hari Ini</button>
                <button wire:click="setPeriode('minggu_ini')" class="{{ $periode === 'minggu_ini' ? 'btn-primary' : 'btn-secondary' }}">Minggu Ini</button>
                <button wire:click="setPeriode('bulan_ini')" class="{{ $periode === 'bulan_ini' ? 'btn-primary' : 'btn-secondary' }}">Bulan Ini</button>
                <button wire:click="setPeriode('tahun_ini')" class="{{ $periode === 'tahun_ini' ? 'btn-primary' : 'btn-secondary' }}">Tahun Ini</button>
            </div>
            <div class="flex gap-2 items-center">
                <input type="date" wire:model.live="startDate" class="input-field">
                <span class="text-gray-500">s/d</span>
                <input type="date" wire:model.live="endDate" class="input-field">
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Laba Rugi -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Laporan Laba Rugi</h3>
            
            <!-- Pendapatan -->
            <div class="space-y-3 mb-6">
                <h4 class="font-medium text-gray-700">PENDAPATAN</h4>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Penjualan Kotor</span>
                    <span class="font-medium">Rp {{ number_format($pendapatanKotor, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Diskon Penjualan</span>
                    <span class="font-medium text-red-600">- Rp {{ number_format($totalDiskon, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2 bg-green-50 px-3 rounded">
                    <span class="font-medium text-gray-800">Penjualan Bersih</span>
                    <span class="font-bold text-green-600">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- HPP -->
            <div class="space-y-3 mb-6">
                <h4 class="font-medium text-gray-700">HARGA POKOK PENJUALAN</h4>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">HPP</span>
                    <span class="font-medium text-red-600">- Rp {{ number_format($hpp, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2 bg-blue-50 px-3 rounded">
                    <span class="font-medium text-gray-800">Laba Kotor</span>
                    <span class="font-bold text-blue-600">Rp {{ number_format($labaKotor, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Beban -->
            <div class="space-y-3 mb-6">
                <h4 class="font-medium text-gray-700">BEBAN OPERASIONAL</h4>
                @foreach($pengeluaranPerKategori as $kategori)
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">{{ $kategori->kategori }}</span>
                        <span class="font-medium text-red-600">- Rp {{ number_format($kategori->total, 0, ',', '.') }}</span>
                    </div>
                @endforeach
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600 font-medium">Total Beban</span>
                    <span class="font-medium text-red-600">- Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Laba Bersih -->
            <div class="p-4 {{ $labaBersih >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-gray-800">LABA/RUGI BERSIH</span>
                    <span class="text-2xl font-bold {{ $labaBersih >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format($labaBersih, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="space-y-6">
            <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white">
                <p class="text-sm text-green-100">Total Penjualan</p>
                <p class="text-3xl font-bold mt-2">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
            </div>

            <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white">
                <p class="text-sm text-blue-100">Total Pembelian</p>
                <p class="text-3xl font-bold mt-2">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</p>
            </div>

            <div class="card bg-gradient-to-br from-red-500 to-red-600 text-white">
                <p class="text-sm text-red-100">Total Pengeluaran</p>
                <p class="text-3xl font-bold mt-2">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
            </div>

            <div class="card">
                <h4 class="font-medium text-gray-700 mb-4">Margin Keuntungan</h4>
                @php
                    $margin = $totalPenjualan > 0 ? ($labaKotor / $totalPenjualan) * 100 : 0;
                @endphp
                <div class="flex items-center gap-4">
                    <div class="flex-1 bg-gray-200 rounded-full h-4">
                        <div class="bg-blue-600 h-4 rounded-full" style="width: {{ min($margin, 100) }}%"></div>
                    </div>
                    <span class="font-bold text-gray-800">{{ number_format($margin, 1) }}%</span>
                </div>
            </div>
        </div>
    </div>
</div>
