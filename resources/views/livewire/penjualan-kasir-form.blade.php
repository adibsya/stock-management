<div>
    <form wire:submit.prevent="simpanPembayaran">
        <div class="mb-3">
            <label for="tanggal_bayar" class="form-label">Tanggal Bayar</label>
            <input type="date" id="tanggal_bayar" class="form-control" wire:model.defer="tanggal_bayar">
        </div>
        <div class="mb-3">
            <label for="jumlah_bayar" class="form-label">Jumlah Bayar</label>
            <input type="number" id="jumlah_bayar" class="form-control" wire:model.defer="jumlah_bayar" min="1">
        </div>
        <div class="mb-3">
            <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
            <select id="metode_pembayaran" class="form-control" wire:model.defer="metode_pembayaran">
                <option value="tunai">Tunai</option>
                <option value="transfer">Transfer</option>
                <option value="kartu">Kartu</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="catatan" class="form-label">Catatan</label>
            <textarea id="catatan" class="form-control" wire:model.defer="catatan"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
    </form>

    <hr>
    <h5>Riwayat Pembayaran</h5>
    <ul class="list-group mb-3">
        @foreach($pembayaranList as $pembayaran)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>{{ $pembayaran->tanggal_bayar }} - {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }} ({{ ucfirst($pembayaran->metode_pembayaran) }})</span>
                <span>{{ $pembayaran->catatan }}</span>
            </li>
        @endforeach
    </ul>
    <div class="mb-2">
        <strong>Total Dibayar:</strong> {{ number_format($totalDibayar, 0, ',', '.') }}
    </div>
    <div class="mb-2">
        <strong>Sisa:</strong> {{ number_format($sisa, 0, ',', '.') }}
    </div>

    <hr>
    <h5>Daftar Termin Penjualan</h5>
    <div class="overflow-x-auto mb-4">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="px-4 py-2 text-left">Jatuh Tempo</th>
                    <th class="px-4 py-2 text-right">Jumlah</th>
                    <th class="px-4 py-2 text-right">Dibayar</th>
                    <th class="px-4 py-2 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penjualan->pembayaranPenjualan as $termin)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $termin->tanggal_jatuh_tempo }}</td>
                        <td class="px-4 py-2 text-right">Rp {{ number_format($termin->jumlah, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-right">Rp {{ number_format($termin->jumlah_bayar ?? 0, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-center">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $termin->status === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($termin->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
