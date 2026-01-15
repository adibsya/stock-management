<div class="card mb-6">
    <h4 class="text-lg font-bold mb-4">Daftar Jurnal</h4>
    <div class="mb-4 flex gap-2">
        <input type="text" wire:model="search" placeholder="Cari keterangan/kode..." class="input-field" />
        <select wire:model="sumber" class="input-field">
            <option value="">Semua Sumber</option>
            <option value="penjualan">Penjualan</option>
            <option value="pembelian">Pembelian</option>
            <option value="pengeluaran">Pengeluaran</option>
        </select>
        <input type="date" wire:model="tanggal" class="input-field" />
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Sumber</th>
                    <th>Ref ID</th>
                    <th>Detail Jurnal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jurnals as $jurnal)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td>{{ $jurnal->kode }}</td>
                        <td>{{ $jurnal->tanggal }}</td>
                        <td>{{ $jurnal->keterangan }}</td>
                        <td>{{ ucfirst($jurnal->sumber) }}</td>
                        <td>{{ $jurnal->ref_id }}</td>
                        <td>
                            <table class="text-xs w-full">
                                <thead>
                                    <tr>
                                        <th>COA</th>
                                        <th>Debit</th>
                                        <th>Kredit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jurnal->details as $detail)
                                        <tr>
                                            <td>{{ optional($detail->coa)->nama ?? '-' }}</td>
                                            <td class="text-right">{{ number_format($detail->debit, 2) }}</td>
                                            <td class="text-right">{{ number_format($detail->kredit, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $jurnals->links() }}</div>
    </div>
</div>
