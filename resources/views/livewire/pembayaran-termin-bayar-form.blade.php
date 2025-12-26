<div>
    <form wire:submit.prevent="bayar" class="card max-w-md mx-auto mt-8">
        <h2 class="text-lg font-bold mb-4">Pembayaran Termin</h2>
        <div class="mb-4">
            <label class="label">Jumlah</label>
            <input type="number" wire:model.defer="jumlah" class="input-field" min="1">
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
