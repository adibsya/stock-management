<div>
    <form wire:submit="save" class="card max-w-xl">
        <div class="space-y-6">
            <div>
                <label for="nama_gudang" class="block text-sm font-medium text-gray-700 mb-1">Nama Gudang *</label>
                <input type="text" id="nama_gudang" wire:model="nama_gudang" class="input-field" placeholder="Nama gudang">
                @error('nama_gudang') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                <textarea id="lokasi" wire:model="lokasi" class="input-field" rows="3" placeholder="Lokasi gudang"></textarea>
                @error('lokasi') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
            <a href="{{ route('gudang.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
