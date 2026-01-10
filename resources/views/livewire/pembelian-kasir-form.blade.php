<div>
    <form wire:submit.prevent="save" class="card max-w-3xl mx-auto">

        {{-- GUDANG --}}
        @if(auth()->user()->isSuperAdmin())
        <div class="mb-4">
            <label class="label">Gudang *</label>
            <select wire:model.defer="gudang_id" class="input-field">
                <option value="">Pilih Gudang</option>
                @foreach($gudangs as $gudang)
                    <option value="{{ $gudang->id }}">{{ $gudang->nama_gudang }}</option>
                @endforeach
            </select>

        </div>
        @endif

        {{-- TANGGAL --}}
        <div class="mb-4">
            <label class="label">Tanggal</label>
            <input type="date" wire:model.defer="tanggal" class="input-field">
        </div>

        {{-- PEMASOK --}}
        <div class="mb-4">
            <label class="label">Pemasok *</label>
            <select wire:model.defer="pemasok_id" class="input-field">
                <option value="">Pilih Pemasok</option>
                @foreach($pemasoks as $pemasok)
                    <option value="{{ $pemasok->id }}">{{ $pemasok->nama_supplier }}</option>
                @endforeach
            </select>
        </div>

        {{-- NO FAKTUR --}}
        <div class="mb-4">
            <label class="label">No Faktur Supplier</label>
            <input type="text" wire:model.defer="no_faktur_supplier" class="input-field">
        </div>

        {{-- DAFTAR BARANG --}}
        <div class="mb-6">
            <label class="label">Daftar Barang *</label>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="table-header">Barang</th>
                            <th class="table-header w-24">Qty</th>
                            <th class="table-header w-32">Harga</th>
                            <th class="table-header w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $i => $item)
                            <tr class="border-b border-gray-100">
                                <td class="table-cell">
                                    <select wire:model.live="items.{{ $i }}.barang_master_id"
                                            class="input-field">
                                        <option value="">Pilih Barang</option>
                                        @foreach($barangs as $barang)
                                            <option value="{{ $barang->id }}">
                                                {{ $barang->nama_barang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="table-cell">
                                    <input type="number"
                                           wire:model.live.debounce.200ms="items.{{ $i }}.qty"
                                           class="input-field text-center text-lg font-semibold ring-1 ring-sky-200 focus:ring-2 focus:ring-sky-400 rounded-md"
                                           min="1"
                                           style="width: 100px;">
                                </td>

                                <td class="table-cell">
                                    <input type="number"
                                           wire:model.live="items.{{ $i }}.harga"
                                           class="input-field text-right text-base font-medium bg-gray-50 ring-1 ring-gray-200 rounded-md"
                                           readonly
                                           style="width: 110px;">
                                </td>

                                <td class="table-cell text-center">
                                    <button type="button"
                                            wire:click="removeItem({{ $i }})"
                                            class="btn-secondary px-3 py-1 text-sm">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <button type="button"
                    wire:click="addItem"
                    class="btn-primary mt-3">
                + Tambah Barang
            </button>
        </div>

        {{-- TOTAL PEMBELIAN --}}
        <div class="mb-6 flex justify-end">
            <div class="text-lg font-bold">
                Total Pembelian: Rp {{ number_format(collect($items)->sum(function($item) { return (float)($item['qty'] ?? 0) * (float)($item['harga'] ?? 0); }), 0, ',', '.') }}
            </div>
        </div>

        {{-- CARA BAYAR --}}
        <div class="mb-4">
            <label class="label">Cara Bayar *</label>
            <select wire:model.live="termin" class="input-field">
                <option value="0">Tunai</option>
                <option value="1">Termin Sekali (1x Jatuh Tempo)</option>
                <option value="2">Termin Bertahap (Multi Cicilan)</option>
            </select>
        </div>

        {{-- TERMIN SEKALI --}}
        @if($termin === '1')
        <div class="mb-4">
            <label class="label">Termin (Sekali)</label>
            <div class="flex gap-4">
                <input type="number"
                       wire:model.live="termins.0.jumlah"
                       class="input-field"
                       readonly>

                <input type="date"
                       wire:model.live="termins.0.tanggal_jatuh_tempo"
                       class="input-field">
            </div>
        </div>
        @endif

        {{-- TERMIN BERTAHAP --}}
        @if($termin === '2')
        <div class="mb-4">
            <label class="label">Termin Bertahap</label>

            <div class="flex gap-4 items-center">
                <input type="number"
                       wire:model.live="jumlah_termin"
                       class="input-field"
                       min="2"
                       placeholder="Jumlah Termin">

                <input type="date"
                       wire:model.live="tanggal_mulai_termin"
                       class="input-field">
            </div>

            <div class="space-y-2 mt-4">
                @foreach($termins as $i => $termin)
                <div class="flex gap-4 items-center">
                    <input type="number"
                           class="input-field"
                           wire:model.live="termins.{{ $i }}.jumlah"
                           readonly>

                    <input type="date"
                           class="input-field"
                           wire:model.live="termins.{{ $i }}.tanggal_jatuh_tempo">
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ACTION --}}
        <div class="mt-8 flex gap-4">
            <button class="btn-primary" type="submit">
                Simpan
            </button>

            <a href="{{ route('pembelian.index') }}"
               class="btn-secondary">
                Batal
            </a>
        </div>

    </form>
</div>
