<div>
    <form wire:submit="save" class="card max-w-xl">
        <div class="space-y-6">
            <div>
                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal *</label>
                <input type="date" id="tanggal" wire:model="tanggal" class="input-field">
                @error('tanggal') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="jenis_pengeluaran" class="block text-sm font-medium text-gray-700 mb-1">Jenis Pengeluaran *</label>
                <select id="jenis_pengeluaran" wire:model="jenis_pengeluaran" class="input-field">
                    <option value="">Pilih Jenis</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat }}">{{ $kat }}</option>
                    @endforeach
                </select>
                @error('jenis_pengeluaran') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="jumlah_biaya" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Biaya *</label>
                <div class="input-with-prefix">
                    <span class="input-prefix">Rp</span>
                    <input type="number" id="jumlah_biaya" wire:model="jumlah_biaya" class="input-field" placeholder="0">
                </div>
                @error('jumlah_biaya') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea id="keterangan" wire:model="keterangan" class="input-field" rows="3" placeholder="Keterangan pengeluaran"></textarea>
                @error('keterangan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 flex items-center gap-4">
            <button type="submit" class="btn-primary">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ $isEdit ? 'Update' : 'Simpan' }}
            </button>
            <a href="{{ route('pengeluaran.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
