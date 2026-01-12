<div class="flex justify-center items-center bg-gray-50">
    <form wire:submit="save" class="card max-w-xl w-full shadow-lg p-8">
        <div class="space-y-6">
            <div>
                <label for="nama_supplier" class="block text-sm font-medium text-gray-700 mb-1">Nama Supplier *</label>
                <input type="text" id="nama_supplier" wire:model="nama_supplier" class="input-field" placeholder="Nama pemasok / supplier">
                @error('nama_supplier') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="kontak" class="block text-sm font-medium text-gray-700 mb-1">Kontak</label>
                <input type="text" id="kontak" wire:model="kontak" class="input-field" placeholder="Telepon / Email">
                @error('kontak') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea id="alamat" wire:model="alamat" class="input-field" rows="3" placeholder="Alamat lengkap"></textarea>
                @error('alamat') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="catatan_termin_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea id="catatan_termin_pembayaran" wire:model="catatan_termin_pembayaran" class="input-field" rows="2" placeholder="Tambahkan Keterangan Jika Perlu"></textarea>
                @error('catatan_termin_pembayaran') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
            <a href="{{ route('pemasok.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
