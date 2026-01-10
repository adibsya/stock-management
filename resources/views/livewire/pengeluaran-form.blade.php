<div class="flex justify-center py-4 px-2">
    <form wire:submit.prevent="save" class="card w-full max-w-xl shadow-lg p-6 space-y-5">

        {{-- Tanggal --}}
        <div>
            <label class="block text-sm font-medium mb-1">Tanggal *</label>
            <input type="date" wire:model="tanggal" class="input-field w-full">
            @error('tanggal') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Jenis --}}
        <div>
            <label class="block text-sm font-medium mb-1">Jenis Pengeluaran *</label>
            <input type="text" wire:model="jenis_pengeluaran" class="input-field w-full">
            @error('jenis_pengeluaran') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Jumlah --}}
        <div>
            <label class="block text-sm font-medium mb-1">Jumlah Biaya *</label>
            <input type="number" wire:model="jumlah_biaya" class="input-field w-full">
            @error('jumlah_biaya') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        {{-- Gudang (HANYA SUPERADMIN) --}}
        @if($isSuperadmin)
        <div>
            <label class="block text-sm font-medium mb-1">Gudang *</label>
            <select wire:model="gudang_id" class="input-field w-full">
                <option value="">Pilih Gudang</option>
                @foreach($gudangs as $gudang)
                    <option value="{{ $gudang->id }}">
                        {{ $gudang->nama_gudang }} - {{ $gudang->lokasi }}
                    </option>
                @endforeach
            </select>
            @error('gudang_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        @endif

        {{-- Keterangan --}}
        <div>
            <label class="block text-sm font-medium mb-1">Keterangan</label>
            <textarea wire:model="keterangan" class="input-field w-full" rows="3"></textarea>
        </div>

        <div class="mt-8 flex gap-4">
            <button type="submit" class="btn-primary w-full">
                Simpan
            </button>
            <a href="{{ route('pengeluaran.index') }}" class="btn-secondary w-full">
                Kembali
            </a>
        </div>

    </form>
</div>
