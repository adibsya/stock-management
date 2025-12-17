<div>
    <form wire:submit="save" class="card max-w-xl">
        <div class="space-y-6">
            <div>
                <label for="kode_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Kode Pelanggan</label>
                <input type="text" id="kode_pelanggan" wire:model="kode_pelanggan" class="input-field" placeholder="PLG-001 (opsional)">
                @error('kode_pelanggan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Nama Pelanggan *</label>
                <input type="text" id="nama_pelanggan" wire:model="nama_pelanggan" class="input-field" placeholder="Nama pelanggan">
                @error('nama_pelanggan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                <input type="text" id="no_hp" wire:model="no_hp" class="input-field" placeholder="08xxxxxxxxxx">
                @error('no_hp') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" wire:model="email" class="input-field" placeholder="email@example.com">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="jenis_pelanggan" class="block text-sm font-medium text-gray-700 mb-1">Jenis Pelanggan</label>
                <select id="jenis_pelanggan" wire:model="jenis_pelanggan" class="input-field">
                    <option value="eceran">Eceran</option>
                    <option value="grosir">Grosir</option>
                </select>
                @error('jenis_pelanggan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea id="alamat" wire:model="alamat" class="input-field" rows="3" placeholder="Alamat lengkap"></textarea>
                @error('alamat') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
            <a href="{{ route('pelanggan.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
