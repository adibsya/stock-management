@php($isPrint = true)
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $pembayaran->penjualan->no_faktur }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Inter', Arial, sans-serif; 
            background: #fff;
            color: #333; 
            font-size: 12px;
            padding: 20px;
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
            padding: 15px 20px;
            border-bottom: 2px solid #4CAF50;
        }
        
        .company-info {
            flex: 1;
        }
        
        .logo {
            font-size: 28px;
            font-weight: 700;
            color: #4CAF50;
            letter-spacing: 1px;
        }
        
        .logo span {
            color: #FF9800;
        }
        
        .company-name {
            font-size: 11px;
            font-weight: 600;
            color: #4CAF50;
            margin-top: 2px;
        }
        
        .nota-title {
            font-size: 36px;
            font-weight: 700;
            color: #4CAF50;
            letter-spacing: 2px;
        }
        
        /* Info Section */
        .info-section {
            display: flex;
            justify-content: space-between;
            padding: 15px 20px;
            border-bottom: 2px solid #4CAF50;
            background: #fff;
        }
        
        .address-info {
            flex: 1;
            font-size: 11px;
            line-height: 1.5;
        }
        
        .customer-info {
            text-align: right;
            font-size: 11px;
        }
        
        .customer-info table {
            margin-left: auto;
        }
        
        .customer-info td {
            padding: 2px 5px;
        }
        
        .customer-info td:first-child {
            color: #FF9800;
            font-weight: 500;
        }
        
        .nota-number {
            margin-top: 10px;
            font-weight: 600;
        }
        
        /* Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .items-table thead {
            background: linear-gradient(to right, #4CAF50, #8BC34A);
        }
        
        .items-table th {
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            border-bottom: 2px solid #4CAF50;
        }
        
        .items-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        
        .items-table tbody tr:hover {
            background: #f9f9f9;
        }
        
        /* Total Row */
        .total-row {
            background: linear-gradient(to right, #4CAF50, #8BC34A);
        }
        
        .total-row td {
            color: white;
            font-weight: 700;
            font-size: 13px;
            padding: 10px 8px;
            border: none;
        }
        
        /* Footer */
        .footer-section {
            display: flex;
            justify-content: space-between;
            padding: 15px 20px;
            border-top: 2px solid #4CAF50;
        }
        
        .signature-left {
            flex: 1;
        }
        
        .payment-summary {
            width: 250px;
        }
        
        .payment-summary table {
            width: 100%;
        }
        
        .payment-summary td {
            padding: 4px 8px;
            font-size: 11px;
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
        
        .payment-summary .sisa-row .value {
            background: #fff3e0;
        }
        
        .signature-right {
            text-align: right;
        }
        
        .signature-name {
            margin-top: 40px;
            font-size: 11px;
        }
        
        /* Bottom Logo */
        .bottom-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            border-top: 1px solid #ddd;
        }
        
        .bottom-logo {
            font-size: 24px;
            font-weight: 700;
            color: #4CAF50;
        }
        
        .bottom-logo span {
            color: #FF9800;
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
                No. NOTA : {{ $pembayaran->penjualan->no_faktur }}
            </div>
        </div>
        <div class="customer-info">
            <table>
                <tr>
                    <td>Nganjuk,</td>
                    <td>{{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar ?? now())->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Kepada Yth.</td>
                    <td>{{ $pembayaran->penjualan->pelanggan?->nama_pelanggan ?? 'UMUM' }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>{{ $pembayaran->penjualan->gudang?->nama_gudang ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
    
    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Amount</th>
                <th>Information</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Pembayaran Termin</td>
                <td>1</td>
                <td>Rp {{ number_format($pembayaran->pembayaran_terakhir ?? $pembayaran->jumlah_bayar ?? 0, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($pembayaran->pembayaran_terakhir ?? $pembayaran->jumlah_bayar ?? 0, 0, ',', '.') }}</td>
                <td>{{ $pembayaran->catatan ?? '-' }}</td>
            </tr>
            <tr>
                <td colspan="5" style="height: 30px;"></td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2">Jumlah</td>
                <td></td>
                <td colspan="2">Rp {{ number_format($pembayaran->pembayaran_terakhir ?? $pembayaran->jumlah_bayar ?? 0, 0, ',', '.') }}</td>
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
                    <td class="label">Total Tagihan</td>
                    <td class="value">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label">Dibayar</td>
                    <td class="value">Rp {{ number_format($pembayaran->jumlah_bayar ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr class="sisa-row">
                    <td class="label">SISA</td>
                    <td class="value">Rp ({{ number_format(max(0, $pembayaran->jumlah - ($pembayaran->jumlah_bayar ?? 0)), 0, ',', '.') }})</td>
                </tr>
            </table>
        </div>
        <div class="signature-right">
            <div>Penerima,</div>
            <div class="signature-name">(..................)</div>
        </div>
    </div>
    
    <!-- Bottom Logo -->
    <div class="bottom-section">
        <div class="bottom-logo">NU<span>Klerr</span></div>
        <div style="font-size: 11px; color: #666;">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
    </div>
</div>
</body>
</html>
