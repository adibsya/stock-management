<div x-data="{ open: false }" x-init="window.addEventListener('open-mutasi-modal', () => open = true)" style="display: none;" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center">
    <div @click="open = false" class="absolute inset-0 bg-black bg-opacity-40 cursor-pointer"></div>
    <div @click.stop class="bg-white rounded-xl shadow-lg p-8 max-w-xl w-full relative z-10 animate-fade-in">
        <button @click="open = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl font-bold">&times;</button>
        <h3 class="text-xl font-bold mb-4 text-blue-700">Tambah Mutasi Stok</h3>
        <form wire:submit.prevent="simpan" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Barang</label>
                <select wire:model="barang_id" class="input-field w-full">
                    <option value="">Pilih Barang</option>
                    @foreach($barangs as $barang)
                        <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gudang Asal</label>
                    <select wire:model="gudang_asal_id" class="input-field w-full">
                        <option value="">Pilih Gudang Asal</option>
                        @foreach($gudangs as $gudang)
                            <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gudang Tujuan</label>
                    <select wire:model="gudang_tujuan_id" class="input-field w-full">
                        <option value="">Pilih Gudang Tujuan</option>
                        @foreach($gudangs as $gudang)
                            <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                <input type="number" wire:model="jumlah" min="1" class="input-field w-full" placeholder="Jumlah pindah">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                <input type="text" wire:model="catatan" class="input-field w-full" placeholder="Catatan mutasi">
            </div>
            <div>
            <button type="submit" class="btn-primary w-full">Simpan Mutasi Stok</button>
        </div>
        @if(session('success'))
            <div class="mt-4 text-green-600 text-sm text-center">{{ session('success') }}</div>
        @endif
    </form>
</div>
