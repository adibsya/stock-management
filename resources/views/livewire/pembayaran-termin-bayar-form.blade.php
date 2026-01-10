<div x-data="{ open: true }" x-show="open" @close-modal-bayar.window="open = false" class="fixed inset-0 z-50 flex items-center justify-center bg-transparent backdrop-blur-sm">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
        <button @click="open = false; $wire.dispatch('closeModalBayar')" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-xl">&times;</button>
        <form wire:submit.prevent="bayar">
            <h2 class="text-lg font-bold mb-4">Pembayaran Termin</h2>
            @php
                $today = \Carbon\Carbon::now();
                $jatuhTempo = $terminObj->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($terminObj->tanggal_jatuh_tempo) : null;
                $diff = $jatuhTempo ? $today->diffInDays($jatuhTempo, false) : null;
            @endphp
            @if($jatuhTempo && $diff !== null && $diff <= 10 && $diff >= 0)
                <div class="mb-4 p-2 rounded bg-red-100 text-red-700 border border-red-400">
                    <strong>Perhatian:</strong> Tanggal jatuh tempo termin ini kurang dari 10 hari lagi ({{ $jatuhTempo->format('d-m-Y') }})!
                </div>
            @endif
            <div class="mb-4">
                <label class="label">Sisa Tagihan</label>
                <input type="text" class="input-field bg-gray-100" value="Rp {{ number_format(max(0, $terminObj->jumlah - $terminObj->jumlah_bayar), 0, ',', '.') }}" readonly>
            </div>
            <div class="mb-4">
                <label class="label">Jumlah Pembayaran</label>
                <input type="number" wire:model.defer="jumlah" class="input-field" min="1" placeholder="">
            </div>
            <div class="mb-4">
                <label class="label">Tanggal Bayar</label>
                <input type="date" wire:model.defer="tanggal_bayar" class="input-field">
            </div>
            <div class="mb-4">
                <label class="label">Metode Pembayaran</label>
                <select wire:model.defer="metode_pembayaran" class="input-field">
                    <option value="tunai">Tunai</option>
                    <option value="transfer">Transfer</option>
                    <option value="e-wallet">E-Wallet</option>
                    <option value="kredit">Kredit</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="label">Catatan</label>
                <input type="text" wire:model.defer="catatan" class="input-field">
            </div>
            <button type="submit" class="btn-primary w-full">Bayar</button>
            @if (session()->has('success'))
                <div class="text-green-600 mt-2">{{ session('success') }}</div>
            @endif
        </form>
    </div>
</div>
