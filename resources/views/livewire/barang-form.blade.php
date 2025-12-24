<div>
    <form wire:submit.prevent="save" class="card max-w-2xl">
        <div class="space-y-6">

            <!-- Kode & Nama -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label">Kode Barang *</label>
                    <input type="text" wire:model.defer="kode_barang" class="input-field" placeholder="BRG-001">
                    @error('kode_barang') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label">Nama Barang *</label>
                    <input type="text" wire:model.defer="nama_barang" class="input-field">
                    @error('nama_barang') <p class="error">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Kategori & Satuan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label">Kategori</label>
                    <input type="text" wire:model.defer="kategori" class="input-field">
                </div>

                <div>
                    <label class="label">Satuan *</label>
                    <select wire:model.defer="satuan" class="input-field">
                        <option value="">Pilih</option>
                        <option value="pcs">Pcs</option>
                        <option value="unit">Unit</option>
                        <option value="kg">Kg</option>
                        <option value="liter">Liter</option>
                        <option value="box">Box</option>
                    </select>
                    @error('satuan') <p class="error">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Harga -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label">Harga Beli *</label>
                    <input type="number" wire:model.defer="harga_beli" class="input-field">
                    @error('harga_beli') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="label">Harga Jual *</label>
                    <input type="number" wire:model.defer="harga_jual" class="input-field">
                    @error('harga_jual') <p class="error">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="label">Keterangan</label>
                <textarea wire:model.defer="keterangan" class="input-field"></textarea>
            </div>

        </div>

        <!-- Actions -->
        <div class="mt-8 flex gap-4">
            <button class="btn-primary" type="submit">
                {{ $isEdit ? 'Update' : 'Simpan' }}
            </button>

            <a href="{{ route('barang-master.index') }}" class="btn-secondary">
                Batal
            </a>
        </div>
    </form>
</div>
