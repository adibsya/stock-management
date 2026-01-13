<div class="card mb-6">
    <h4 class="text-lg font-bold mb-4">Daftar Termin Penjualan</h4>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="table-header">No Faktur</th>
                    <th class="table-header">Pelanggan</th>
                    <th class="table-header">Jatuh Tempo</th>
                    <th class="table-header text-right">Jumlah</th>
                    <th class="table-header text-right">Dibayar</th>
                    <th class="table-header text-right">Sisa Tagihan</th>
                    <th class="table-header text-center">Status</th>
                    <th class="table-header text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($termins as $row)
                    <tr wire:key="termin-{{ $row->id }}" class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="table-cell">{{ $row->penjualan->no_faktur }}</td>
                        <td class="table-cell">{{ $row->penjualan->pelanggan?->nama_pelanggan?? 'Umum' }}</td>
                        <td class="table-cell">{{ $row->tanggal_jatuh_tempo }}</td>
                        <td class="table-cell text-right">
                            Rp {{ number_format($row->jumlah, 0, ',', '.') }}
                        </td>
                        <td class="table-cell text-right">
                            Rp {{ number_format($row->jumlah_bayar ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="table-cell text-right">
                            Rp {{ number_format(max(0, $row->jumlah - ($row->jumlah_bayar ?? 0)), 0, ',', '.') }}
                        </td>
                        <td class="table-cell text-center">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $row->status === 'lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($row->status) }}
                            </span>
                        </td>
                        <td class="table-cell text-center" style="min-width: 200px;">
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; align-items: center;">
                                {{-- Kolom Kiri: Bayar --}}
                                <div style="text-align: center;">
                                    @if($row->status !== 'lunas')
                                        <button
                                            class="btn-primary"
                                            style="display: inline-flex; align-items: center; justify-content: center; gap: 4px; padding: 6px 12px; font-size: 13px; border-radius: 6px; width: 80px;"
                                            wire:click="openModalBayar({{ $row->id }})">
                                            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            Bayar
                                        </button>
                                    @else
                                        <span style="color: #9ca3af; font-size: 13px;">-</span>
                                    @endif
                                </div>
                                {{-- Kolom Kanan: Print --}}
                                <div style="text-align: center;">
                                    @if(($row->jumlah_bayar ?? 0) > 0)
                                        <a href="{{ route('penjualan.termin.print', $row->id) }}"
                                           target="_blank"
                                           style="display: inline-flex; align-items: center; justify-content: center; gap: 4px; padding: 6px 12px; font-size: 13px; border-radius: 6px; background-color: #22c55e; color: white; text-decoration: none; width: 80px;"
                                           title="Cetak Kwitansi">
                                            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                            </svg>
                                            Print
                                        </a>
                                    @else
                                        <span style="color: #9ca3af; font-size: 13px;">-</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- MODAL --}}
    @if($showModal && $selectedTermin)
        <div
            wire:key="modal-termin-{{ $selectedTermin->id }}"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

            <div class="bg-white rounded p-6 w-96">
                <h3 class="font-bold mb-4">Bayar Termin</h3>

                <form wire:submit.prevent="bayar">
                    <div class="mb-3">
                        <label>Jumlah Termin</label>
                        <input type="text" class="input-field bg-gray-100"
                            value="Rp {{ number_format($selectedTermin->jumlah,0,',','.') }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Sisa Tagihan</label>
                        <input type="text" class="input-field bg-gray-100"
                            value="Rp {{ number_format(
                                $selectedTermin->jumlah - ($selectedTermin->jumlah_bayar ?? 0),
                                0, ',', '.'
                            ) }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Nominal Pembayaran</label>
                        <input type="number"
                            class="input-field w-full"
                            wire:model.defer="jumlah"
                            min="1"
                            max="{{ $selectedTermin->jumlah - ($selectedTermin->jumlah_bayar ?? 0) }}">
                    </div>

                    <div class="mb-3">
                        <label>Tanggal Bayar</label>
                        <input type="date" class="input-field w-full" wire:model.defer="tanggal_bayar">
                    </div>

                    <div class="mb-3">
                        <label>Metode Pembayaran</label>
                        <select class="input-field w-full" wire:model.defer="metode_pembayaran">
                            <option value="tunai">Tunai</option>
                            <option value="transfer">Transfer</option>
                            <option value="kartu">Kartu</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">
                            Tutup
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Bayar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
