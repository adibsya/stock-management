<div>
    <div class="max-w-lg mx-auto card">
        <h2 class="text-xl font-bold mb-4">Tambah Stok Barang</h2>
        <form wire:submit.prevent="save" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                <input type="text" class="input-field bg-gray-100" value="{{ $barangMaster->nama_barang }}" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barang</label>
                <input type="text" class="input-field bg-gray-100" value="{{ $barangMaster->kode_barang }}" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gudang</label>
                <select wire:model="gudangId" class="input-field">
                    <option value="">Pilih Gudang</option>
                    @foreach($gudangs as $gudang)
                        <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                    @endforeach
                </select>
                @error('gudangId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pemasok (Opsional)</label>
                <select wire:model="pemasokId" class="input-field">
                    <option value="">Pilih Pemasok</option>
                    @foreach($pemasoks as $pemasok)
                        <option value="{{ $pemasok->id }}">{{ $pemasok->nama_supplier }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Stok Masuk</label>
                <input type="number" wire:model="jumlah" min="1" class="input-field">
                @error('jumlah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli</label>
                <input type="number" wire:model="harga_beli" min="0" class="input-field">
                @error('harga_beli') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stok Minimum (Opsional)</label>
                <input type="number" wire:model="stok_minimum" min="0" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea wire:model="keterangan" class="input-field"></textarea>
            </div>
            <div class="flex gap-3 pt-4 border-t">
                <button type="submit" class="btn-primary">Simpan Stok</button>
                <a href="{{ route('stok-barang.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
