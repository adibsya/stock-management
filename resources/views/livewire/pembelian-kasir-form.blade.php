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
                    <option value="{{ $pemasok->id }}">{{ $pemasok->nama_supplier }}</option>
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
                                <select wire:model="items.{{ $i }}.barang_master_id" class="input-field">
                                    <option value="">Pilih Barang</option>
                                    @foreach($barangs as $barang)
                                        <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" wire:model="items.{{ $i }}.qty" class="input-field" min="1"></td>
                            <td><input type="number" wire:model="items.{{ $i }}.harga" class="input-field" min="0" readonly></td>
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
            <label class="label">Cara Bayar *</label>
            <div class="flex gap-6">
                <label class="inline-flex items-center">
                    <input type="radio" wire:model="termin" value="0" class="form-radio">
                    <span class="ml-2">Tunai</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" wire:model="termin" value="1" class="form-radio">
                    <span class="ml-2">Termin Sekali (1x Jatuh Tempo)</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" wire:model="termin" value="2" class="form-radio">
                    <span class="ml-2">Termin Bertahap (Multi Cicilan)</span>
                </label>
            </div>
        </div>

        @if($termin == '1')
        <div class="mb-4">
            <label class="label">Termin (Sekali)</label>
            <div class="flex gap-4">
                <input type="number" wire:model="termins.0.jumlah" class="input-field" placeholder="Jumlah" min="0">
                <input type="date" wire:model="termins.0.tanggal_jatuh_tempo" class="input-field" placeholder="Jatuh Tempo">
            </div>
        </div>
        @elseif($termin == '2')
        <div class="mb-4">
            <label class="label">Termin Bertahap (Multi Cicilan)</label>
            <div class="flex gap-4 items-center">
                <input type="number" wire:model="jumlah_termin" class="input-field" min="2" placeholder="Jumlah Termin (cicilan)">
                <input type="date" wire:model="tanggal_mulai_termin" class="input-field" placeholder="Tanggal Mulai Cicilan">
            </div>
            <div class="space-y-2 mt-4">
                @if(isset($termins) && count($termins) > 0)
                    @foreach($termins as $i => $termin)
                    <div class="flex gap-4 items-center">
                        <input type="number" class="input-field" value="{{ $termin['jumlah'] }}" readonly>
                        <input type="date" class="input-field" wire:model="termins.{{ $i }}.tanggal_jatuh_tempo">
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
        @endif
        <div class="mt-8 flex gap-4">
            <button class="btn-primary" type="submit">Simpan</button>
            <a href="{{ route('pembelian.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
