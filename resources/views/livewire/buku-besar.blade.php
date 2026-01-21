<div class="bg-white rounded-2xl shadow-md border p-6 space-y-6">

    {{-- Header --}}
    <div>
        <h3 class="text-xl font-semibold text-gray-800">Buku Besar</h3>
        <p class="text-sm text-gray-500">Detail transaksi per akun</p>
    </div>

    {{-- Filter --}}
    <div class="flex flex-col md:flex-row gap-3">
        <select wire:model.live="coaId"
            class="rounded-xl border-2 border-gray-300 px-3 py-2 text-sm focus:border-blue-600 focus:ring-4 focus:ring-blue-100">
            <option value="">-- Pilih Akun --</option>
            @foreach($this->coas as $coa)
                <option value="{{ $coa->id }}">
                    {{ $coa->kode }} - {{ $coa->nama }}
                </option>
            @endforeach
        </select>

        <input type="date" wire:model.live="tanggalAwal"
            class="rounded-xl border-2 border-gray-300 px-3 py-2 text-sm">

        <input type="date" wire:model.live="tanggalAkhir"
            class="rounded-xl border-2 border-gray-300 px-3 py-2 text-sm">
    </div>

    {{-- Info Akun --}}
    @if($this->coa)
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-sm">
            <div class="grid grid-cols-2 gap-2">
                <div><strong>Akun:</strong> {{ $this->coa->kode }} - {{ $this->coa->nama }}</div>
                <div><strong>Normal Saldo:</strong> <span class="badge font-bold text-blue-700">{{ ucfirst($this->coa->normal_saldo) }}</span></div>
            </div>
        </div>
    @endif

    {{-- Table --}}
    <div class="overflow-x-auto border rounded-xl">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 border-b">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-left">Keterangan</th>
                    <th class="px-4 py-3 text-right">Debit</th>
                    <th class="px-4 py-3 text-right">Kredit</th>
                    <th class="px-4 py-3 text-right">Saldo</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                {{-- BARIS SALDO AWAL (Hanya muncul jika akun dipilih) --}}
                @if($this->coaId)
                <tr class="bg-gray-50/80 italic font-medium">
                    <td class="px-4 py-2" colspan="3">
                        Saldo Awal per {{ \Carbon\Carbon::parse($tanggalAwal)->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-2 text-right">-</td>
                    <td class="px-4 py-2 text-right">-</td>
                    <td class="px-4 py-2 text-right font-mono font-semibold">
                        {{ number_format($this->saldoAwal, 0, ',', '.') }}
                    </td>
                </tr>
                @endif

                {{-- LOOPING TRANSAKSI --}}
                @forelse($this->bukuBesar as $row)
                    <tr wire:key="row-{{ $row->id }}" class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-2 text-nowrap">
                            {{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-2 font-mono text-xs text-blue-600">
                            {{ $row->kode ?? '-' }}
                        </td>
                        <td class="px-4 py-2 text-gray-700">
                            {{ $row->keterangan }}
                        </td>
                        <td class="px-4 py-2 text-right font-mono {{ $row->debit > 0 ? 'text-green-600' : '' }}">
                            {{ $row->debit > 0 ? number_format($row->debit, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-4 py-2 text-right font-mono {{ $row->kredit > 0 ? 'text-red-600' : '' }}">
                            {{ $row->kredit > 0 ? number_format($row->kredit, 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-4 py-2 text-right font-mono font-bold text-gray-900">
                            {{ number_format($row->saldo, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-gray-400">
                            @if(!$coaId)
                                Silakan pilih akun terlebih dahulu.
                            @else
                                Tidak ada transaksi untuk periode ini.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>