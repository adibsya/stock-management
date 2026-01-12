<div>
    <!-- Header -->
    <div class="card mb-6">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class=" w-46">
                @if($isSuperadmin)
                    <select wire:model="gudang_id" class="input-field">
                        <option value="">Semua Gudang</option>
                        @foreach($gudangs as $gudang)
                            <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang ?? $gudang->nama ?? '-' }}</option>
                        @endforeach
                    </select>
                @else
                    <span class="font-bold">Gudang: {{ ($gudangs->firstWhere('id', $gudang_id)->nama_gudang ?? '') }}</span>
                @endif
            </div>

            <!-- Filter -->
            <div class="flex flex-col md:flex-row gap-4 flex-1 w-full md:w-auto">

                <input type="date" wire:model.live="startDate" class="input-field w-full md:w-40">
                <input type="date" wire:model.live="endDate" class="input-field w-full md:w-40">

                <select wire:model.live="kategori" class="input-field w-full md:w-40">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat }}">{{ $kat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Action -->
            @if(auth()->user()->canModify())
                <a href="{{ route('pengeluaran.create') }}" class="btn-primary whitespace-nowrap">
                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Pengeluaran
                </a>
            @endif

        </div>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="card bg-linear-to-br from-red-500 to-red-600 text-white">
            <p class="text-sm text-red-100">Total Pengeluaran (Periode)</p>
            <p class="text-2xl font-bold mt-1">
                Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Table -->
    <div class="card-no-padding overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="table-header cursor-pointer" wire:click="sortBy('tanggal')">
                            Tanggal
                            @if($sortBy === 'tanggal')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="table-header">Kategori</th>
                        <th class="table-header">Gudang</th>
                        <th class="table-header">Keterangan</th>
                        <th class="table-header text-right cursor-pointer" wire:click="sortBy('jumlah_biaya')">
                            Jumlah
                            @if($sortBy === 'jumlah_biaya')
                                <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="table-header text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($pengeluarans as $pengeluaran)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="table-cell">
                                {{ $pengeluaran->tanggal->format('d/m/Y') }}
                            </td>
                            <td class="table-cell">
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">
                                    {{ $pengeluaran->jenis_pengeluaran }}
                                </span>
                            </td>
                            <td class="table-cell">
                                {{ $pengeluaran->gudang->nama_gudang ?? '-' }}
                            </td>
                            <td class="table-cell">
                                {{ $pengeluaran->keterangan ?? '-' }}
                            </td>
                            <td class="table-cell text-right font-medium text-red-600">
                                Rp {{ number_format($pengeluaran->jumlah_biaya, 0, ',', '.') }}
                            </td>
                            <td class="table-cell text-center">
                                @if(auth()->user()->canModify())
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            wire:click.prevent="confirmDelete({{ $pengeluaran->id }})"
                                            class="p-2 rounded-lg transition shadow-sm hover:bg-red-100 group"
                                            title="Hapus Pengeluaran"
                                        >
                                            <svg wire:click.prevent="confirmDelete({{ $pengeluaran->id }})" class="w-5 h-5 text-red-500 group-hover:text-red-700 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a3 3 0 0 1 6 0v2m-9 4h12l-1.5 10.5A2 2 0 0 1 15.5 21h-7a2 2 0 0 1-2-2L5 11zm2 0v6m4-6v6" />
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                Tidak ada data pengeluaran
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $pengeluarans->links() }}
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.addEventListener('swal:confirm-delete', function (e) {
        console.log('ID yang dikirim ke SweetAlert:', e.detail.id);
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: e.detail.message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log('ID yang dikirim ke Livewire (v3):', e.detail.id);
                Livewire.dispatch('deleteConfirmed', { id: e.detail.id });
            }
        });
    });
</script>
@endpush
