<div>
    <form wire:submit.prevent="save" class="card max-w-3xl mx-auto">
        <div class="mb-4">
            <label class="label">Tanggal</label>
            <input type="date" wire:model.defer="tanggal" class="input-field">
        </div>
        <div class="mb-4">
            <label class="label">Pemasok *</label>
            <select wire:model.defer="pemasok_id" class="input-field">
                <option value="">Pilih Pemasok</option>
                @foreach($pemasoks as $pemasok)
                    <option value="{{ $pemasok->id }}">{{ $pemasok->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="label">No Faktur Supplier</label>
            <input type="text" wire:model.defer="no_faktur_supplier" class="input-field">
        </div>
        <div class="mb-6">
            <label class="label">Daftar Barang</label>
            <table class="w-full mb-2">
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $i => $item)
                        <tr>
                            <td>
                                <select wire:model.defer="items.{{ $i }}.barang_master_id" class="input-field">
                                    <option value="">Pilih Barang</option>
                                    @foreach($barangs as $barang)
                                        <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" wire:model.defer="items.{{ $i }}.qty" class="input-field" min="1"></td>
                            <td><input type="number" wire:model.defer="items.{{ $i }}.harga" class="input-field" min="0"></td>
                            <td>
                                <button type="button" wire:click="removeItem({{ $i }})" class="btn-secondary">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" wire:click="addItem" class="btn-primary">Tambah Barang</button>
        </div>
        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" wire:model="termin" class="form-checkbox">
                <span class="ml-2">Pembelian dengan Termin (Cicilan)</span>
            </label>
        </div>
        @if($termin)
        <div class="mb-4">
            <label class="label">Termin Bertahap</label>
            <table class="w-full mb-2">
                <thead>
                    <tr>
                        <th>Jumlah</th>
                        <th>Jatuh Tempo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($termins as $i => $termin)
                        <tr>
                            <td><input type="number" wire:model.defer="termins.{{ $i }}.jumlah" class="input-field" min="0"></td>
                            <td><input type="date" wire:model.defer="termins.{{ $i }}.tanggal_jatuh_tempo" class="input-field"></td>
                            <td>
                                <button type="button" wire:click="removeTermin({{ $i }})" class="btn-secondary">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" wire:click="addTermin" class="btn-primary">Tambah Termin</button>
        </div>
        @endif
        <div class="mt-8 flex gap-4">
            <button class="btn-primary" type="submit">Simpan</button>
            <a href="{{ route('pembelian.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
