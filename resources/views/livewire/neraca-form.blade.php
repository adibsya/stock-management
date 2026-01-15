<div>
    <h2 class="text-lg font-bold mb-4">Input Neraca</h2>
    @if(session()->has('success'))
        <div class="bg-green-100 text-green-800 p-2 mb-2">{{ session('success') }}</div>
    @endif
    <form wire:submit.prevent="save" class="space-y-2">
        <div>
            <label>Tanggal</label>
            <input type="date" wire:model="tanggal" class="border rounded px-2 py-1" />
        </div>
        <div>
            <label>Pos</label>
            <select wire:model="pos_id" class="border rounded px-2 py-1">
                <option value="">- Pilih Pos -</option>
                @foreach($posList as $pos)
                    <option value="{{ $pos->id }}">{{ $pos->kode }} - {{ $pos->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Jumlah</label>
            <input type="number" wire:model="jumlah" class="border rounded px-2 py-1" step="0.01" />
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
    </form>
</div>
