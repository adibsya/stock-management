<div>
    {{-- Header --}}
    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
        </svg>
        Laporan Neraca
    </h2>

    {{-- Date Filter --}}
    <div class="mb-6 flex items-end gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal</label>
            <input type="date" 
                   wire:model="tanggal" 
                   wire:change="loadData"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" />
        </div>
        <button wire:click="loadData" 
                class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-lg transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
            </svg>
            Refresh
        </button>
        <button type="button"
                wire:click="exportExcel"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export Excel
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-gray-200">
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Kode Pos</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nama Pos</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Jenis</th>
                    <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <span class="font-mono text-sm font-medium text-gray-800">{{ $item->pos->kode }}</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $item->pos->nama }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full
                            {{ $item->pos->jenis === 'debit' 
                                ? 'bg-emerald-100 text-emerald-700' 
                                : 'bg-rose-100 text-rose-700' }}">
                            {{ ucfirst($item->pos->jenis) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right text-sm font-semibold text-gray-800">
                        Rp {{ number_format($item->jumlah, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                        <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                        </svg>
                        Tidak ada data neraca
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Summary Footer --}}
    @php
        $totalDebit = $data->filter(fn($d) => $d->pos->jenis === 'debit')->sum('jumlah');
        $totalKredit = $data->filter(fn($d) => $d->pos->jenis === 'kredit')->sum('jumlah');
        $selisih = $totalDebit - $totalKredit;
    @endphp
    
    @if($data->count() > 0)
    <div class="mt-4 flex justify-between items-center text-sm">
        <div class="flex gap-6">
            <span class="text-gray-600">Total Debit: <strong class="text-emerald-600">Rp {{ number_format($totalDebit, 2) }}</strong></span>
            <span class="text-gray-600">Total Kredit: <strong class="text-rose-600">Rp {{ number_format($totalKredit, 2) }}</strong></span>
        </div>
        <div>
            @if($selisih == 0)
            <span class="inline-flex items-center gap-1 text-emerald-600 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Neraca Seimbang
            </span>
            @else
            <span class="inline-flex items-center gap-1 text-amber-600 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                </svg>
                Selisih: Rp {{ number_format(abs($selisih), 2) }}
            </span>
            @endif
        </div>
    </div>
    @endif
</div>
