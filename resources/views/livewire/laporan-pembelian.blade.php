<div>
    <div class="mb-4 flex gap-2 items-center">
        <label for="gudang_id">Gudang:</label>
        <select wire:model="gudang_id" id="gudang_id" class="input-field">
            <option value="">Semua Gudang</option>
            @foreach($gudangs as $gudang)
                <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
            @endforeach
        </select>
        <label for="startDate">Dari:</label>
        <input type="date" wire:model="startDate" id="startDate" class="input-field">
        <label for="endDate">Sampai:</label>
        <input type="date" wire:model="endDate" id="endDate" class="input-field">
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="table-header">Tanggal</th>
                    <th class="table-header">No Faktur</th>
                    <th class="table-header">Pemasok</th>
                    <th class="table-header">Total Biaya</th>
                    <th class="table-header">User</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembelians as $pembelian)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="table-cell">{{ $pembelian->tanggal->format('d/m/Y') }}</td>
                        <td class="table-cell">{{ $pembelian->no_faktur_supplier }}</td>
                        <td class="table-cell">{{ $pembelian->pemasok->nama_supplier ?? '-' }}</td>
                        <td class="table-cell">Rp {{ number_format($pembelian->total_biaya, 0, ',', '.') }}</td>
                        <td class="table-cell">{{ $pembelian->user->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">Tidak ada data pembelian</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $pembelians->links() }}
    </div>
</div>
