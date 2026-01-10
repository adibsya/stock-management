<div>
    @if($penjualan)
        <div class="card">
            <h3 class="text-lg font-bold mb-4">Form Retur Penjualan</h3>
            <p class="text-sm text-gray-600 mb-4">No. Faktur: <span class="font-semibold">{{ $penjualan->no_faktur }}</span></p>
            <p class="text-sm text-yellow-600 bg-yellow-50 p-3 rounded-lg mb-4">
                <strong>Catatan:</strong> Barang yang diretur akan diganti dengan barang baru dari stok.
            </p>
            
            <form wire:submit.prevent="simpanRetur" class="space-y-4">
                <div>
                    <label class="label">Tanggal Retur</label>
                    <input type="date" wire:model="tanggal" class="input-field" required>
                    @error('tanggal') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="label">Pilih Barang yang Diretur</label>
                    <select wire:model.live="detail_penjualan_id" class="input-field" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($penjualan->detailPenjualan as $detail)
                            <option value="{{ $detail->id }}">
                                {{ $detail->barang->nama_barang }} 
                                (Dibeli: {{ $detail->jumlah }} {{ $detail->barang->satuan }})
                            </option>
                        @endforeach
                    </select>
                    @error('detail_penjualan_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="label">Jumlah Barang Diretur</label>
                    <input type="number" wire:model="jumlah_retur" class="input-field" min="1" placeholder="Masukkan jumlah" required>
                    <p class="text-xs text-gray-500 mt-1">Barang pengganti akan diambil dari stok</p>
                    @error('jumlah_retur') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="label">Kondisi Barang yang Diretur</label>
                    <select wire:model="kondisi_barang" class="input-field" required>
                        <option value="rusak">Rusak</option>
                        <option value="bagus">Bagus (Cacat/Tidak Sesuai)</option>
                    </select>
                    @error('kondisi_barang') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="label">Alasan Retur</label>
                    <textarea wire:model="alasan" class="input-field" rows="3" placeholder="Masukkan alasan retur..." required></textarea>
                    @error('alasan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="btn-primary">Proses Retur & Ambil Pengganti</button>
                    <button type="button" wire:click="$dispatch('close-modal')" class="btn-secondary">Batal</button>
                </div>
            </form>

            @if (session()->has('success'))
                <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
            @endif
            @if (session()->has('error'))
                <div class="mt-4 p-4 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div>
            @endif
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            Silakan pilih penjualan untuk memproses retur
        </div>
    @endif
</div>
