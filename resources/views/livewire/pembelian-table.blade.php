<div>
    <!-- Header -->
    <div class="card mb-6">
        <!-- Row 1: Filters -->
        <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-end mb-4">
            @if($showGudangFilter)
            <div class="flex flex-col w-full lg:w-auto">
                <label class="text-xs text-gray-600 font-medium mb-1">Gudang</label>
                <select wire:model.live="gudangId" class="input-field w-full lg:w-40">
                    <option value="">Semua Gudang</option>
                    @foreach($gudangs as $gudang)
                        <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="flex flex-col w-full lg:w-auto">
                <label class="text-xs text-gray-600 font-medium mb-1">Tanggal Mulai</label>
                <input type="date" wire:model.live="startDate" class="input-field w-full lg:w-40">
            </div>
            <div class="flex flex-col w-full lg:w-auto">
                <label class="text-xs text-gray-600 font-medium mb-1">Tanggal Sampai</label>
                <input type="date" wire:model.live="endDate" class="input-field w-full lg:w-40">
            </div>
            <div class="flex flex-col w-full lg:w-auto">
                <label class="text-xs text-gray-600 font-medium mb-1">Status Bayar</label>
                <select wire:model.live="statusBayar" class="input-field w-full lg:w-36">
                    <option value="">Semua Status</option>
                    <option value="lunas">Lunas</option>
                    <option value="belum_lunas">Belum Lunas</option>
                </select>
            </div>
            <div class="flex flex-col w-full lg:w-auto">
                <label class="text-xs text-gray-600 font-medium mb-1">Kategori Produk</label>
                <select wire:model.live="kategoriProduk" class="input-field w-full lg:w-40">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori }}">{{ $kategori }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <!-- Row 2: Action Buttons -->
        <div class="flex gap-3 flex-wrap">
            <a href="{{ route('pembelian.kasir') }}" class="btn-primary whitespace-nowrap flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Kasir Pembelian
            </a>
            @php
                $adaTermin = isset($pembelians) && $pembelians->where('status_bayar', 'belum_lunas')->where('jatuh_tempo', '!=', null)->count() > 0;
            @endphp
            <a href="{{ $adaTermin ? route('pembelian.termin') : '#' }}"
               class="btn-success whitespace-nowrap flex items-center gap-2 px-4 py-2 rounded-lg shadow transition {{ !$adaTermin ? 'bg-gray-300 text-gray-500 cursor-not-allowed border border-gray-300 hover:bg-gray-300 hover:text-gray-500' : 'hover:bg-green-600' }}"
               @if(!$adaTermin) tabindex="-1" aria-disabled="true" title="Tidak ada termin aktif" onclick="return false;" @endif>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a5 5 0 00-10 0v2a2 2 0 00-2 2v7a2 2 0 002 2h10a2 2 0 002-2v-7a2 2 0 00-2-2zm-5 4v2m0 0v2m0-2h.01" />
                </svg>
                <span class="font-semibold">Kasir Termin</span>
            </a>
        </div>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="card bg-gradient-to-br from-blue-500 to-blue-600 text-white">
            <p class="text-sm text-blue-100">Total Pembelian (Periode)</p>
            <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</p>
        </div>
    </div>

    
                

    <!-- Table -->
    <div class="card-no-padding w-full">
        <div class="w-full">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="table-header cursor-pointer" wire:click="sortBy('no_faktur_supplier')">
                            No Faktur Supplier
                            @if($sortBy === 'no_faktur_supplier')
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
                        <th class="table-header">Pemasok</th>
                        <th class="table-header text-right cursor-pointer" wire:click="sortBy('total_biaya')">
                            Total
                            @if($sortBy === 'total_biaya')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="table-header">Jatuh Tempo</th>
                        <th class="table-header">Status</th>
                        <th class="table-header text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php use Illuminate\Support\Str; @endphp
                    @forelse($pembelians as $pembelian)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="table-cell font-mono text-sm">{{ $pembelian->no_faktur_supplier ?: '-' }}</td>
                            <td class="table-cell">{{ $pembelian->tanggal->format('d/m/Y') }}</td>
                            <td class="table-cell">{{ $pembelian->gudang?->nama_gudang ?? '-' }}</td>
                            <td class="table-cell">{{ $pembelian->pemasok?->nama_supplier ?? '-' }}</td>
                            <td class="table-cell text-right font-medium">Rp {{ number_format($pembelian->total_biaya, 0, ',', '.') }}</td>
                            <td class="table-cell">
                                @if($pembelian->jatuh_tempo)
                                    @php
                                        $today = \Carbon\Carbon::now();
                                        $jatuhTempo = $pembelian->jatuh_tempo;
                                        $diff = $jatuhTempo ? $today->diffInDays($jatuhTempo, false) : null;
                                    @endphp
                                    <span class="{{ ($pembelian->isJatuhTempo() || ($diff !== null && $diff <= 10 && $diff >= 0)) ? 'text-red-600 font-bold' : '' }}">
                                        {{ $pembelian->jatuh_tempo->format('d/m/Y') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="table-cell">
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $pembelian->status_bayar === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $pembelian->status_bayar === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                            </td>
                            <td class="table-cell text-center">
                                <a href="{{ route('pembelian.show', $pembelian) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition inline-block" title="Detail Pembelian">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition inline-block ml-2" title="Hapus Pembelian" onclick="hapusPembelian({{ $pembelian->id }})">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            <!-- ...existing code... -->
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                Tidak ada data pembelian
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function hapusPembelian(id) {
        Swal.fire({
            title: 'Yakin hapus data ini?',
            text: 'Data pembelian dan stok akan dihapus!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.Livewire.dispatch('delete', [id]);
            }
        });
    }
    window.addEventListener('pembelian-dihapus', function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data pembelian berhasil dihapus.',
            timer: 1800,
            showConfirmButton: false
        });
    });
</script>
@endpush

        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $pembelians->links() }}
        </div>
    </div>
</div>
