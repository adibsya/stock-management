<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Update Pembayaran Termin</h2>
        <p class="text-gray-600">No Faktur: <span class="font-semibold">{{ $penjualan->no_faktur }}</span></p>
        <p class="text-gray-600">Pelanggan: <span class="font-semibold">{{ $penjualan->pelanggan?->nama ?? 'Umum' }}</span></p>
        <p class="text-gray-600">Total: <span class="font-semibold text-blue-600">Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</span></p>
    </div>

    <!-- Summary Card -->
    <div class="card mb-6 bg-gradient-to-br from-blue-50 to-indigo-50">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Cicilan</p>
                <p class="text-2xl font-bold text-gray-800">{{ $penjualan->getTotalCicilan() }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Sudah Lunas</p>
                <p class="text-2xl font-bold text-green-600">{{ $penjualan->getJumlahCicilanLunas() }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Terbayar</p>
                <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($penjualan->getTotalTerminLunas(), 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- List Pembayaran -->
    <div class="space-y-4">
        @foreach($penjualan->pembayaranPenjualan as $pembayaran)
            <div class="card {{ $pembayaran->status_bayar === 'lunas' ? 'bg-green-50 border-green-200' : 'bg-white' }}">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $pembayaran->catatan }}</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            Jatuh Tempo: {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($pembayaran->status_bayar === 'pending')
                            <button wire:click="updateStatus({{ $pembayaran->id }}, 'lunas')" 
                                    class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                                ✓ Tandai Lunas
                            </button>
                        @else
                            <button wire:click="updateStatus({{ $pembayaran->id }}, 'pending')" 
                                    class="px-3 py-1.5 bg-yellow-600 text-white text-sm rounded-lg hover:bg-yellow-700 transition">
                                Batalkan Lunas
                            </button>
                        @endif
                        <span class="px-3 py-1.5 rounded-full text-xs font-medium {{ $pembayaran->status_bayar === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                            {{ $pembayaran->status_bayar === 'lunas' ? 'Lunas' : 'Belum Bayar' }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Jumlah Bayar -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Bayar</label>
                        <div class="input-with-prefix">
                            <span class="input-prefix">Rp</span>
                            <input type="number" 
                                   wire:model="pembayarans.{{ $pembayaran->id }}.jumlah_bayar" 
                                   class="input-field"
                                   step="0.01">
                        </div>
                    </div>

                    <!-- Tanggal Bayar -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Bayar</label>
                        <input type="date" 
                               wire:model="pembayarans.{{ $pembayaran->id }}.tanggal_bayar" 
                               class="input-field">
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Metode</label>
                        <select wire:model="pembayarans.{{ $pembayaran->id }}.metode_pembayaran" class="input-field">
                            <option value="tunai">💵 Tunai</option>
                            <option value="transfer">🏦 Transfer</option>
                            <option value="qris">📱 QRIS</option>
                            <option value="termin">📅 Termin</option>
                        </select>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <input type="text" 
                               wire:model="pembayarans.{{ $pembayaran->id }}.catatan" 
                               class="input-field"
                               placeholder="Catatan tambahan...">
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <button wire:click="updatePembayaran({{ $pembayaran->id }})" 
                            class="btn-primary">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('penjualan.index') }}" class="btn-secondary">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar Penjualan
        </a>
    </div>
</div>

