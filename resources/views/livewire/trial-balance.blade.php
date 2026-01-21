<div class="bg-white rounded-2xl shadow-md border p-6">

    {{-- Header --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h3 class="text-xl font-semibold text-gray-800">Neraca Saldo</h3>
            <p class="text-sm text-gray-500">
                Rekap saldo akun berdasarkan jurnal
            </p>
        </div>

        {{-- Filter --}}
        <div class="flex gap-2">
            <input type="date" wire:model="tanggalAwal"
                class="rounded-xl border-2 border-gray-300 px-3 py-2 text-sm
                       focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition">

            <input type="date" wire:model="tanggalAkhir"
                class="rounded-xl border-2 border-gray-300 px-3 py-2 text-sm
                       focus:border-blue-600 focus:ring-4 focus:ring-blue-100 transition">
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-xl border">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-left">Nama Akun</th>
                    <th class="px-4 py-3 text-right">Debit</th>
                    <th class="px-4 py-3 text-right">Kredit</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @foreach($this->trialBalance as $row)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 font-mono">{{ $row->kode }}</td>
                        <td class="px-4 py-2">{{ $row->nama }}</td>
                        <td class="px-4 py-2 text-right font-mono">
                            {{ number_format($row->debit, 2) }}
                        </td>
                        <td class="px-4 py-2 text-right font-mono">
                            {{ number_format($row->kredit, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>

            {{-- Footer --}}
            <tfoot class="bg-gray-50 font-semibold">
                <tr>
                    <td colspan="2" class="px-4 py-3 text-right">TOTAL</td>
                    <td class="px-4 py-3 text-right">
                        {{ number_format($this->totalDebit, 2) }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        {{ number_format($this->totalKredit, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Status --}}
    <div class="mt-4 text-sm">
        @if($this->totalDebit == $this->totalKredit)
            <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-green-700 font-medium">
                ✅ Seimbang
            </span>
        @else
            <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-red-700 font-medium">
                ❌ Tidak Seimbang
            </span>
        @endif
    </div>

</div>
