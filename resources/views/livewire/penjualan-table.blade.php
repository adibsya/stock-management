<div>
<!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-300 p-6 mb-6">
        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
            <div class="flex flex-col md:flex-row gap-3 flex-1 w-full md:w-auto">
                <div class="flex flex-col w-full md:w-40">
                    <label class="text-xs text-gray-600 font-medium mb-1">Tanggal Mulai</label>
                    <input type="date" wire:model.live="startDate" class="input-field">
                </div>
                <div class="flex flex-col w-full md:w-40">
                    <label class="text-xs text-gray-600 font-medium mb-1">Tanggal Sampai</label>
                    <input type="date" wire:model.live="endDate" class="input-field">
                </div>
                <select wire:model.live="gudang_id" class="input-field w-full md:w-40">
                    <option value="">Semua Gudang</option>
                    @foreach($gudangs as $gudang)
                        <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                    @endforeach
                </select>
                <select wire:model.live="status" class="input-field w-full md:w-40">
                    <option value="">Semua Status</option>
                    <option value="selesai">Selesai</option>
                    <option value="belum_lunas">Belum Lunas</option>
                </select>
                <select wire:model.live="kategoriProduk" class="input-field w-full md:w-44">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori }}">{{ $kategori }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="card bg-gradient-to-br from-green-500 to-green-600 text-white">
            <p class="text-sm text-green-100">Total Penjualan (Periode)</p>
            <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
        </div>
    </div>


    <!-- Tombol Kasir Pembayaran Termin -->
    <div class="mb-6 flex justify-end">
        <a href="{{ route('penjualan-termin.index') }}" class="btn-success flex items-center gap-2 px-4 py-2 rounded-lg shadow-sm hover:shadow transition-all whitespace-nowrap">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 10c-4.418 0-8-1.79-8-4V7a2 2 0 012-2h2m12 0h2a2 2 0 012 2v7c0 2.21-3.582 4-8 4z" />
            </svg>
            <span class="font-medium">Kasir Pembayaran Termin</span>
        </a>
    </div>
    <div class="card-no-padding overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="table-header cursor-pointer" wire:click="sortBy('no_faktur')">
                            No Faktur
                            @if($sortBy === 'no_faktur')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="table-header cursor-pointer" wire:click="sortBy('tanggal')">
                            Tanggal
                            @if($sortBy === 'tanggal')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="table-header">Gudang</th>
                        <th class="table-header">Pelanggan</th>
                        <th class="table-header">Kasir</th>
                        <th class="table-header">Termin</th>
                        <th class="table-header text-right cursor-pointer" wire:click="sortBy('total_bayar')">
                            Total
                            @if($sortBy === 'total_bayar')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="table-header">Status</th>
                        <th class="table-header text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($penjualans as $penjualan)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="table-cell">
                                <span class="font-mono text-sm font-medium text-gray-900">
                                    {{ $penjualan->no_faktur }}
                                </span>
                            </td>

                            <td class="table-cell text-sm text-gray-700">
                                {{ $penjualan->tanggal?->format('d/m/Y') }}
                            </td>

                            <td class="table-cell text-sm text-gray-700">
                                {{ $penjualan->gudang?->nama_gudang ?? '-' }}
                            </td>

                            <td class="table-cell text-sm font-semibold text-gray-900">
                                {{ $penjualan->pelanggan?->nama_pelanggan ?? 'Umum' }}
                            </td>

                            <td class="table-cell text-sm text-gray-600">
                                {{ $penjualan->user?->name ?? '-' }}
                            </td>

                            <td class="table-cell">
                                @if($penjualan->mode_termin === 'termin')
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                        Termin
                                    </span>

                                    @php $termins = $penjualan->pembayaranPenjualan; @endphp
                                    @if($termins && $termins->count())
                                        <div class="mt-1 space-y-1">
                                            @foreach($termins as $t)
                                                <div class="text-xs">
                                                    <span class="font-mono">{{ $t->tanggal_jatuh_tempo }}</span> :
                                                    <span>Rp {{ number_format($t->jumlah, 0, ',', '.') }}</span>
                                                    <span class="font-semibold {{ $t->status === 'lunas' ? 'text-green-600' : 'text-red-600' }}">
                                                        {{ ucfirst($t->status) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        Tunai
                                    </span>
                                @endif
                            </td>

                            <td class="table-cell text-right">
                                <span class="text-sm font-bold text-gray-900 whitespace-nowrap">
                                    Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}
                                </span>
                            </td>

                            <td class="table-cell">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $penjualan->status === 'selesai'
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($penjualan->status) }}
                                </span>
                            </td>

                            <td class="table-cell text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('penjualan.show', $penjualan) }}"
                                       class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg"
                                       title="Detail Penjualan">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    <button
                                        onclick="konfirmasiHapusPenjualan({{ $penjualan->id }})"
                                        class="p-2 text-red-600 hover:bg-red-100 rounded-lg"
                                        title="Hapus Penjualan">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m2 0v12a2 2 0 01-2 2H8a2 2 0 01-2-2V7h12zM10 11v6m4-6v6" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <p class="text-gray-500 font-medium">
                                    Tidak ada data penjualan
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $penjualans->links() }}
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function konfirmasiHapusPenjualan(id) {
            Swal.fire({
                title: 'Yakin hapus penjualan?',
                text: 'Data dan stok akan dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let root = document.querySelector('[wire\\:id]');
                    if (root) {
                        window.Livewire.find(root.getAttribute('wire:id')).call('hapusPenjualan', id);
                    }
                }
            });
        }
        window.addEventListener('penjualan-dihapus', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data penjualan berhasil dihapus.',
                timer: 1800,
                showConfirmButton: false
            });
        });
    </script>
</div>
