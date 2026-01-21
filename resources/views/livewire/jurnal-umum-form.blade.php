<div class="p-6 bg-white rounded-2xl shadow border">
    <h3 class="text-lg font-bold mb-4">Input Jurnal Penyesuaian / Umum</h3>
    
    <div class="grid grid-cols-2 gap-4 mb-4">
        <input type="date" wire:model="tanggal" class="border rounded-lg p-2 text-sm">
        <select wire:model="tipe" class="border rounded-lg p-2 text-sm">
            <option value="penyesuaian">Jurnal Penyesuaian</option>
            <option value="umum">Jurnal Umum</option>
        </select>
    </div>
    
    <input type="text" wire:model="keterangan" placeholder="Keterangan transaksi..." class="w-full border rounded-lg p-2 mb-4 text-sm">

    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th>Akun (COA)</th>
                <th>Debit</th>
                <th>Kredit</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr wire:key="item-{{ $index }}">
                <td>
                    <select wire:model="items.{{ $index }}.coa_id" class="w-full border-none">
                        <option value="">Pilih Akun</option>
                        @foreach($coas as $coa)
                            <option value="{{ $coa->id }}">{{ $coa->kode }} - {{ $coa->nama }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" wire:model="items.{{ $index }}.debit" class="w-full text-right border-none"></td>
                <td><input type="number" wire:model="items.{{ $index }}.kredit" class="w-full text-right border-none"></td>
                <td><button wire:click="removeRow({{ $index }})" class="text-red-500">×</button></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4 flex justify-between items-center">
        <button wire:click="addRow" class="bg-gray-100 px-4 py-2 rounded-lg text-xs">+ Tambah Baris</button>
        <button wire:click="save" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold">Simpan Jurnal</button>
    </div>
</div>