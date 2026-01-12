<div class="mt-8">
    <h2 class="text-lg font-semibold mb-4">Riwayat Mutasi Stok Terbaru</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded shadow">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-3 py-2 text-left">Tanggal</th>
                    <th class="px-3 py-2 text-left">Barang</th>
                    <th class="px-3 py-2 text-left">Jumlah</th>
                    <th class="px-3 py-2 text-left">Gudang Asal</th>
                    <th class="px-3 py-2 text-left">Stok Tersedia</th>
                    <th class="px-3 py-2 text-left">Gudang Tujuan</th>
                    <th class="px-3 py-2 text-left">User</th>
                    <th class="px-3 py-2 text-left">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $mutasi)
                    <tr class="border-b">
                        <td class="px-3 py-2">{{ $mutasi->created_at->format('d-m-Y H:i') }}</td>
                        <td class="px-3 py-2">{{ $mutasi->barang->nama_barang ?? '-' }}</td>
                        <td class="px-3 py-2">{{ $mutasi->jumlah }}</td>
                        <td class="px-3 py-2">{{ $mutasi->gudangAsal->nama_gudang ?? '-' }}</td>
                        <td class="px-3 py-2">
                            @php
                                $stok = \App\Models\StokBarang::where('barang_master_id', $mutasi->barang_id)
                                    ->where('gudang_id', $mutasi->gudang_asal_id)
                                    ->first();
                            @endphp
                            {{ $stok ? $stok->jumlah : 0 }}
                        </td>
                        <td class="px-3 py-2">{{ $mutasi->gudangTujuan->nama_gudang ?? '-' }}</td>
                        <td class="px-3 py-2">{{ $mutasi->user->name ?? '-' }}</td>
                        <td class="px-3 py-2">{{ $mutasi->catatan }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-3 py-2 text-center text-gray-500">Belum ada riwayat mutasi stok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
