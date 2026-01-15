<div>
    <h2 class="text-lg font-bold mb-4">Laporan Neraca</h2>
    <input type="date" wire:model="tanggal" wire:change="loadData" class="mb-2" />
    <table class="table-auto w-full">
        <thead>
            <tr>
                <th>Kode Pos</th>
                <th>Nama Pos</th>
                <th>Jenis</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ $item->pos->kode }}</td>
                <td>{{ $item->pos->nama }}</td>
                <td>{{ ucfirst($item->pos->jenis) }}</td>
                <td class="text-right">{{ number_format($item->jumlah, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
