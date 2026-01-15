@php($isPrint = true)
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $penjualan->no_faktur }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Inter', Arial, sans-serif; 
            background: #fff;
            color: #333; 
            font-size: 11px;
            padding: 15px;
        }
        
        .invoice-container { 
            max-width: 700px; 
            margin: 0 auto; 
            border: 2px solid #4CAF50;
        }
        
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 12px 15px;
            border-bottom: 2px solid #4CAF50;
        }
        
        .company-info { flex: 1; }
        
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #4CAF50;
        }
        
        .logo span { color: #FF9800; }
        
        .company-name {
            font-size: 10px;
            font-weight: 600;
            color: #4CAF50;
            margin-top: 2px;
        }
        
        .nota-title {
            font-size: 28px;
            font-weight: 700;
            color: #4CAF50;
        }
        
        /* Info Section */
        .info-section {
            display: flex;
            justify-content: space-between;
            padding: 12px 15px;
            border-bottom: 2px solid #4CAF50;
        }
        
        .address-info {
            flex: 1;
            font-size: 10px;
            line-height: 1.4;
        }
        
        .customer-info {
            text-align: right;
            font-size: 10px;
        }
        
        .customer-info table { margin-left: auto; }
        
        .customer-info td { padding: 2px 4px; }
        
        .customer-info td:first-child {
            color: #FF9800;
            font-weight: 500;
        }
        
        .nota-number {
            margin-top: 8px;
            font-weight: 600;
        }
        
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .items-table thead {
            background: linear-gradient(to right, #4CAF50, #8BC34A);
        }
        
        .items-table th {
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
        }
        
        .items-table td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        
        .items-table tbody tr:hover { background: #f9f9f9; }
        
        /* Total Row */
        .total-row {
            background: linear-gradient(to right, #4CAF50, #8BC34A);
        }
        
        .total-row td {
            color: white;
            font-weight: 700;
            font-size: 11px;
            padding: 8px 6px;
            border: none;
        }
        
        /* Footer */
        .footer-section {
            display: flex;
            justify-content: space-between;
            padding: 12px 15px;
            border-top: 2px solid #4CAF50;
        }
        
        .signature-left { flex: 1; }
        
        .payment-summary { width: 200px; }
        
        .payment-summary table { width: 100%; }
        
        .payment-summary td {
            padding: 3px 6px;
            font-size: 10px;
        }
        
        .payment-summary .label {
            text-align: right;
            font-weight: 500;
        }
        
        .payment-summary .value {
            text-align: right;
            border: 1px solid #4CAF50;
            background: #f9fff9;
        }
        
        .signature-name {
            margin-top: 35px;
            font-size: 10px;
        }
        
        /* Bottom */
        .bottom-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 15px;
            border-top: 1px solid #ddd;
        }
        
        .bottom-logo {
            font-size: 20px;
            font-weight: 700;
            color: #4CAF50;
        }
        
        .bottom-logo span { color: #FF9800; }
        
        /* Termin Section */
        .termin-section {
            margin: 10px 15px;
            padding: 10px;
            background: #fff8e1;
            border: 1px solid #FF9800;
            border-radius: 6px;
        }
        
        .termin-section strong {
            font-size: 10px;
            color: #e65100;
            display: block;
            margin-bottom: 8px;
        }
        
        .termin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        
        .termin-table th {
            background: #FF9800;
            color: white;
            padding: 5px;
            text-align: left;
        }
        
        .termin-table td {
            padding: 4px 5px;
            border-bottom: 1px solid #ffe0b2;
        }
        
        .status-badge {
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 600;
        }
        
        .status-lunas {
            background: #c8e6c9;
            color: #2e7d32;
        }
        
        .status-belum {
            background: #ffecb3;
            color: #f57c00;
        }
        
        @media print {
            body { padding: 0; }
            .invoice-container { border: 2px solid #4CAF50; }
        }
    </style>
</head>
<body onload="window.print()">
<div class="invoice-container">
    <!-- Header -->
    <div class="header">
        <div class="company-info">
            <div class="logo">NU<span>Klerr</span></div>
            <div class="company-name">PT PERSADA NAWA KARTIKA</div>
        </div>
        <div class="nota-title">NOTA</div>
    </div>
    
    <!-- Info Section -->
    <div class="info-section">
        <div class="address-info">
            Jl. Supriyadi No. 24 Nganjuk /<br>
            Ds. Juwono, Kec.<br>
            Kertosono, Kabupaten<br>
            Nganjuk
            <div class="nota-number">
                No. NOTA : {{ $penjualan->no_faktur }}
            </div>
        </div>
        <div class="customer-info">
            <table>
                <tr>
                    <td>Nganjuk,</td>
                    <td>{{ $penjualan->tanggal->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Kepada Yth.</td>
                    <td>{{ $penjualan->pelanggan?->nama_pelanggan ?? $penjualan->pelanggan?->nama ?? 'UMUM' }}</td>
                </tr>
                <tr>
                    <td>Kasir</td>
                    <td>{{ $penjualan->user?->name ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
    
    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Description</th>
                <th style="width: 50px;">Qty</th>
                <th style="width: 90px;">Unit Price</th>
                <th style="width: 100px;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan->detailPenjualan as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->barang->nama_barang }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            @if(count($penjualan->detailPenjualan) < 5)
                @for($i = count($penjualan->detailPenjualan); $i < 5; $i++)
                <tr><td colspan="5" style="height: 20px;"></td></tr>
                @endfor
            @endif
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3">Jumlah</td>
                <td></td>
                <td>Rp {{ number_format($penjualan->total_kotor, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
    
    <!-- Footer -->
    <div class="footer-section">
        <div class="signature-left">
            <div>Hormat kami,</div>
            <div class="signature-name">(..................)</div>
        </div>
        <div class="payment-summary">
            <table>
                <tr>
                    <td class="label">Subtotal</td>
                    <td class="value">Rp {{ number_format($penjualan->total_kotor, 0, ',', '.') }}</td>
                </tr>
                @if($penjualan->diskon_transaksi > 0)
                <tr>
                    <td class="label">Diskon</td>
                    <td class="value">Rp {{ number_format($penjualan->diskon_transaksi, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($penjualan->pajak > 0)
                <tr>
                    <td class="label">Pajak</td>
                    <td class="value">Rp {{ number_format($penjualan->pajak, 0, ',', '.') }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label" style="font-weight: 700;">TOTAL</td>
                    <td class="value" style="font-weight: 700; background: #e8f5e9;">Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        <div style="text-align: right;">
            <div>Penerima,</div>
            <div class="signature-name">(..................)</div>
        </div>
    </div>
    
    @if($penjualan->mode_termin === 'termin')
    <!-- Termin Section -->
    <div class="termin-section">
        <strong>Detail Termin Pembayaran:</strong>
        <table class="termin-table">
            <thead>
                <tr>
                    <th>Jatuh Tempo</th>
                    <th>Tagihan</th>
                    <th>Dibayar</th>
                    <th>Sisa</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penjualan->pembayaranPenjualan as $termin)
                <tr>
                    <td>{{ $termin->tanggal_jatuh_tempo }}</td>
                    <td>Rp {{ number_format($termin->jumlah, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($termin->jumlah_bayar ?? 0, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format(max(0, $termin->jumlah - ($termin->jumlah_bayar ?? 0)), 0, ',', '.') }}</td>
                    <td>
                        <span class="status-badge {{ $termin->status === 'lunas' ? 'status-lunas' : 'status-belum' }}">
                            {{ ucfirst($termin->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    <!-- Bottom Logo -->
    <div class="bottom-section">
        <div class="bottom-logo">NU<span>Klerr</span></div>
        <div style="font-size: 9px; color: #666;">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    </div>
</div>
</body>
</html>