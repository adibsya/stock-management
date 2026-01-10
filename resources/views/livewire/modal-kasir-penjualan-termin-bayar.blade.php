@if($termin)
<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">

        <button type="button"
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-xl font-bold focus:outline-none"
            wire:click="$dispatch('closeModalBayar')">
            &times;
        </button>

        <h2 class="text-xl font-bold mb-4">Pembayaran Termin Kasir</h2>

        <div class="mb-3">
            <label class="block text-gray-700 mb-1">Sisa Tagihan</label>
            <div class="bg-gray-100 rounded px-3 py-2">
                Rp {{ number_format($termin->jumlah - ($termin->jumlah_bayar ?? 0), 0, ',', '.') }}
            </div>
        </div>

        <form wire:submit.prevent="bayar">
            <div class="mb-3">
                <label class="block text-gray-700 mb-1">Jumlah Pembayaran</label>
                <input type="number"
                    class="form-input w-full"
                    wire:model.defer="jumlah"
                    min="1"
                    max="{{ $termin->jumlah - ($termin->jumlah_bayar ?? 0) }}">
            </div>

            <div class="mb-3">
                <label class="block text-gray-700 mb-1">Tanggal Bayar</label>
                <input type="date"
                    class="form-input w-full"
                    wire:model.defer="tanggal_bayar">
            </div>

            <div class="mb-3">
                <label class="block text-gray-700 mb-1">Metode Pembayaran</label>
                <select class="form-input w-full"
                    wire:model.defer="metode_pembayaran">
                    <option value="tunai">Tunai</option>
                    <option value="transfer">Transfer</option>
                    <option value="kartu">Kartu</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="block text-gray-700 mb-1">Catatan</label>
                <textarea class="form-input w-full"
                    wire:model.defer="catatan"></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-full">
                Bayar
            </button>
        </form>

    </div>
</div>
@endif
