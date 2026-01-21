<div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 mb-6">

    {{-- Header --}}
    <div class="mb-6">
        <h4 class="text-xl font-semibold text-gray-800">
            Daftar Jurnal
        </h4>
        <p class="text-sm text-gray-500">
            Seluruh transaksi yang tercatat dalam jurnal umum
        </p>
    </div>

    {{-- Filter --}}
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-3">
        <input
            type="text"
            wire:model="search"
            placeholder="Cari keterangan / kode jurnal"
            class="rounded-xl border-2 border-gray-300 bg-white px-4 py-2.5 text-sm
                   hover:border-blue-400
                   focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition"
        />

        <select
            wire:model="sumber"
            class="rounded-xl border-2 border-gray-300 bg-white px-4 py-2.5 text-sm
                   hover:border-blue-400
                   focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition"
        >
            <option value="">Semua Sumber</option>
            <option value="penjualan">Penjualan</option>
            <option value="pembelian">Pembelian</option>
            <option value="pengeluaran">Pengeluaran</option>
        </select>

        <input
            type="date"
            wire:model="tanggal"
            class="rounded-xl border-2 border-gray-300 bg-white px-4 py-2.5 text-sm
                   hover:border-blue-400
                   focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition"
        />
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border border-gray-200">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Kode</th>
                    <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                    <th class="px-4 py-3 text-left font-semibold">Keterangan</th>
                    <th class="px-4 py-3 text-left font-semibold">Sumber</th>
                    <th class="px-4 py-3 text-left font-semibold">Ref</th>
                    <th class="px-4 py-3 text-left font-semibold">Detail Jurnal</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @foreach($jurnals as $jurnal)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-mono text-gray-800">
                            {{ $jurnal->kode }}
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ $jurnal->tanggal }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $jurnal->keterangan }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium
                                {{ $jurnal->sumber === 'penjualan' ? 'bg-blue-100 text-blue-700' :
                                   ($jurnal->sumber === 'pembelian' ? 'bg-emerald-100 text-emerald-700' :
                                   'bg-amber-100 text-amber-700') }}">
                                {{ ucfirst($jurnal->sumber) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $jurnal->ref_id }}
                        </td>

                        {{-- Detail --}}
                        <td class="px-4 py-3">
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-2">
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="text-gray-500 border-b">
                                            <th class="py-1 text-left">Akun</th>
                                            <th class="py-1 text-right">Debit</th>
                                            <th class="py-1 text-right">Kredit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($jurnal->details as $detail)
                                            <tr class="border-b last:border-0">
                                                <td class="py-1 text-gray-700">
                                                    {{ optional($detail->coa)->nama ?? '-' }}
                                                </td>
                                                <td class="py-1 text-right font-mono">
                                                    {{ number_format($detail->debit, 2) }}
                                                </td>
                                                <td class="py-1 text-right font-mono">
                                                    {{ number_format($detail->kredit, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-5">
        {{ $jurnals->links() }}
    </div>

</div>
