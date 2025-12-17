<div>
    <form wire:submit="save" class="card max-w-2xl">
        <div class="space-y-6">
            <!-- Kode & Nama Barang -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="kode_barang" class="block text-sm font-medium text-gray-700 mb-1">Kode Barang *</label>
                    <input type="text" id="kode_barang" wire:model="kode_barang" class="input-field" placeholder="BRG-00001">
                    @error('kode_barang') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-1">Nama Barang *</label>
                    <input type="text" id="nama_barang" wire:model="nama_barang" class="input-field" placeholder="Nama barang">
                    @error('nama_barang') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Kategori & Satuan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <input type="text" id="kategori" wire:model="kategori" class="input-field" placeholder="Kategori barang">
                    @error('kategori') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="satuan" class="block text-sm font-medium text-gray-700 mb-1">Satuan *</label>
                    <select id="satuan" wire:model="satuan" class="input-field">
                        <option value="pcs">Pcs</option>
                        <option value="unit">Unit</option>
                        <option value="kg">Kg</option>
                        <option value="gram">Gram</option>
                        <option value="liter">Liter</option>
                        <option value="meter">Meter</option>
                        <option value="box">Box</option>
                        <option value="pack">Pack</option>
                        <option value="lusin">Lusin</option>
                    </select>
                    @error('satuan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Harga -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="harga_beli" class="block text-sm font-medium text-gray-700 mb-1">Harga Beli *</label>
                    <div class="input-with-prefix">
                        <span class="input-prefix">Rp</span>
                        <input type="number" id="harga_beli" wire:model="harga_beli" class="input-field" placeholder="0">
                    </div>
                    @error('harga_beli') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="harga_jual" class="block text-sm font-medium text-gray-700 mb-1">Harga Jual *</label>
                    <div class="input-with-prefix">
                        <span class="input-prefix">Rp</span>
                        <input type="number" id="harga_jual" wire:model="harga_jual" class="input-field" placeholder="0">
                    </div>
                    @error('harga_jual') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Stok -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="stok" class="block text-sm font-medium text-gray-700 mb-1">Stok Awal *</label>
                    <input type="number" id="stok" wire:model="stok" class="input-field" placeholder="0" min="0">
                    @error('stok') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="stok_minimum" class="block text-sm font-medium text-gray-700 mb-1">Stok Minimum *</label>
                    <input type="number" id="stok_minimum" wire:model="stok_minimum" class="input-field" placeholder="0" min="0">
                    @error('stok_minimum') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Pemasok & Gudang -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="pemasok_id" class="block text-sm font-medium text-gray-700 mb-1">Pemasok</label>
                    <select id="pemasok_id" wire:model="pemasok_id" class="input-field">
                        <option value="">Pilih Pemasok</option>
                        @foreach($pemasoks as $pemasok)
                            <option value="{{ $pemasok->id }}">{{ $pemasok->nama }}</option>
                        @endforeach
                    </select>
                    @error('pemasok_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="gudang_id" class="block text-sm font-medium text-gray-700 mb-1">Gudang</label>
                    <select id="gudang_id" wire:model="gudang_id" class="input-field">
                        <option value="">Pilih Gudang</option>
                        @foreach($gudangs as $gudang)
                            <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                        @endforeach
                    </select>
                    @error('gudang_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Foto -->
            <div>
                <label for="foto" class="block text-sm font-medium text-gray-700 mb-1">Foto Barang</label>
                <input type="file" id="foto" wire:model="foto" class="input-field" accept="image/*">
                @error('foto') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                
                @if($foto)
                    <div class="mt-2">
                        <img src="{{ $foto->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg">
                    </div>
                @endif
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
            <a href="{{ route('barang.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
