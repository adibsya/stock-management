<div>
    <h3 class="font-bold mb-2">Riwayat Pembayaran Termin</h3>
    <table class="w-full mb-4">
        <thead>
            <tr>
                <th class="text-left">Tanggal</th>
                <th class="text-right">Jumlah</th>
                <th class="text-left">Metode</th>
                <th class="text-left">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembayaranList as $bayar)
                <tr>
                    <td>{{ $bayar->tanggal_bayar }}</td>
                    <td class="text-right">Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</td>
                    <td>{{ $bayar->metode_pembayaran }}</td>
                    <td>{{ $bayar->catatan }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-gray-400">Belum ada pembayaran</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="mb-2">
        <span class="font-semibold">Total Dibayar:</span> Rp {{ number_format($totalDibayar, 0, ',', '.') }}<br>
        <span class="font-semibold">Sisa:</span> Rp {{ number_format($sisa, 0, ',', '.') }}
    </div>
    <form wire:submit.prevent="simpanPembayaran" class="space-y-2">
        <div class="flex gap-2">
            <input type="date" wire:model.defer="tanggal_bayar" class="input-field">
            <input type="number" wire:model.defer="jumlah_bayar" class="input-field" placeholder="Jumlah bayar">
            <select wire:model.defer="metode_pembayaran" class="input-field">
                <option value="tunai">Tunai</option>
                <option value="transfer">Transfer</option>
                <option value="e-wallet">E-Wallet</option>
                <option value="kredit">Kredit</option>
            </select>
            <input type="text" wire:model.defer="catatan" class="input-field" placeholder="Catatan">
            <button type="submit" class="btn-primary">Tambah</button>
        </div>
    </form>
    @if (session()->has('success'))
        <div class="text-green-600 mt-2">{{ session('success') }}</div>
    @endif
</div>
