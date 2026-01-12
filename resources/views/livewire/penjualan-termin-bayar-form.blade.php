<div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
        <button type="button" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-xl font-bold focus:outline-none" wire:click="$dispatch('closeModalBayar')">&times;</button>
        <h2 class="text-xl font-bold mb-4">Bayar Termin</h2>
        <form wire:submit.prevent="bayar">
            <div class="mb-3">
                <label class="block text-gray-700 mb-1">Jumlah Termin (Tagihan)</label>
                <input type="text" class="input-field bg-gray-100" value="Rp {{ number_format($termin->jumlah, 0, ',', '.') }}" readonly>
            </div>
            <div class="mb-3">
                <label class="block text-gray-700 mb-1">Sisa Tagihan</label>
                <input type="text" class="input-field bg-gray-100" value="Rp {{ number_format($termin->jumlah - ($termin->jumlah_bayar ?? 0), 0, ',', '.') }}" readonly>
            </div>
            <div class="mb-3">
                <label class="block text-gray-700 mb-1">Nominal Pembayaran</label>
                <input type="number" class="input-field" wire:model.defer="jumlah" min="1" max="{{ $termin->jumlah - ($termin->jumlah_bayar ?? 0) }}" placeholder="Masukkan nominal pembayaran">
            </div>
            <div class="mb-3">
                <label class="block text-gray-700 mb-1">Tanggal Bayar</label>
                <input type="date" class="input-field" wire:model.defer="tanggal_bayar">
            </div>
            <div class="mb-3">
                <label class="block text-gray-700 mb-1">Metode Pembayaran</label>
                <select class="input-field" wire:model.defer="metode_pembayaran">
                    <option value="tunai">Tunai</option>
                    <option value="transfer">Transfer</option>
                    <option value="kartu">Kartu</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-gray-700 mb-1">Catatan</label>
                <input type="text" class="input-field" wire:model.defer="catatan" placeholder="Catatan (opsional)">
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" class="btn btn-secondary" wire:click="$dispatch('closeModalBayar')">Tutup</button>
                <button type="submit" class="btn btn-primary">Bayar</button>
            </div>
        </form>
    </div>
</div>

