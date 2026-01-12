@php($isPrint = true)
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $penjualan->no_faktur }} - Ngarumi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #1f2937; 
            margin: 0; 
            padding: 20px;
            min-height: 100vh;
        }
        
        .invoice-container { 
            max-width: 800px; 
            margin: 40px auto; 
            background: #ffffff;
            border-radius: 20px; 
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            position: relative;
        }
        
        .invoice-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        }
        
        .header-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px;
            position: relative;
            overflow: hidden;
        }
        
        .header-bg::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            position: relative;
            z-index: 1;
        }
        
        .logo { 
            font-size: 2.5rem; 
            font-weight: 700; 
            color: #ffffff;
            letter-spacing: 3px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .invoice-title { 
            font-size: 2rem; 
            font-weight: 700; 
            color: #ffffff;
            letter-spacing: 4px;
            background: rgba(255,255,255,0.2);
            padding: 12px 24px;
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }
        
        .content-wrapper {
            padding: 40px;
        }
        
        .info-section {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 32px;
            border: 1px solid #d1d5db;
        }
        
        .info-table { 
            width: 100%; 
        }
        
        .info-table td { 
            padding: 10px 0; 
            font-size: 1rem;
            vertical-align: top;
        }
        
        .info-table td:first-child {
            color: #6b7280;
            font-weight: 500;
            width: 140px;
        }
        
        .info-table strong {
            color: #111827;
            font-weight: 600;
        }
        
        .items-table { 
            width: 100%; 
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 24px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .items-table th { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 16px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }
        
        .items-table td { 
            border-bottom: 1px solid #e5e7eb;
            padding: 14px 16px;
            background: #ffffff;
        }
        
        .items-table tbody tr {
            transition: all 0.2s ease;
        }
        
        .items-table tbody tr:hover {
            background: #f9fafb;
            transform: scale(1.01);
        }
        
        .items-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .items-table tfoot td { 
            font-weight: 600;
            background: #f9fafb;
            padding: 14px 16px;
            border-top: 2px solid #e5e7eb;
        }
        
        .items-table tfoot tr:last-child td {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-top: 3px solid #f59e0b;
            font-size: 1.15em;
        }
        
        .status {
            display: inline-block; 
            padding: 6px 16px; 
            border-radius: 999px;
            font-size: 0.875rem; 
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .status.selesai { 
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #047857;
            border: 2px solid #10b981;
        }
        
        .status.draft { 
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #b45309;
            border: 2px solid #f59e0b;
        }
        
        .status.termin { 
            background: linear-gradient(135deg, #ddd6fe 0%, #c4b5fd 100%);
            color: #6b21a8;
            border: 2px solid #9333ea;
        }
        
        .ml-2 {
            margin-left: 8px;
        }
        
        .termin-section {
            margin-top: 32px;
            padding: 24px;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-radius: 16px;
            border: 2px solid #f59e0b;
        }
        
        .termin-section strong {
            font-size: 1.1rem;
            color: #92400e;
            display: block;
            margin-bottom: 16px;
        }
        
        .footer { 
            text-align: center; 
            color: #6b7280;
            font-size: 0.95rem; 
            margin-top: 40px;
            padding: 32px 40px;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            border-top: 1px solid #d1d5db;
        }
        
        .footer b {
            color: #667eea;
            font-weight: 700;
        }
        
        .footer div:first-child {
            font-size: 1.1rem;
            color: #374151;
            margin-bottom: 8px;
        }
        
        @media print {
            body { 
                background: #fff;
                padding: 0;
            }
            .invoice-container { 
                box-shadow: none; 
                margin: 0;
                border-radius: 0;
            }
            .invoice-container::before {
                display: none;
            }
            .footer { 
                display: none; 
            }
            .items-table tbody tr:hover {
                transform: none;
            }
        }
    </style>
</head>
<body onload="window.print()">
<div class="invoice-container">
    <div class="header-bg">
        <div class="header">
            <div class="logo">Ngarumi</div>
            <div class="invoice-title">INVOICE</div>
        </div>
    </div>
    
    <div class="content-wrapper">
        <div class="info-section">
            <table class="info-table">
                <tr>
                    <td>No Faktur</td>
                    <td>: <strong>{{ $penjualan->no_faktur }}</strong></td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>: {{ $penjualan->tanggal->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Pelanggan</td>
                    <td>: {{ $penjualan->pelanggan?->nama ?? 'Umum' }}</td>
                </tr>
                <tr>
                    <td>Kasir</td>
                    <td>: {{ $penjualan->user?->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:
                        <span class="status {{ $penjualan->status }}">
                            {{ ucfirst($penjualan->status) }}
                        </span>
                        @if($penjualan->mode_termin === 'termin')
                            <span class="status termin ml-2">Termin</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        
        <table class="items-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
            </thead>
            <tbody>
            @foreach($penjualan->detailPenjualan as $i => $item)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $item->barang->nama_barang }}</td>
                    <td>Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4" style="text-align:right">Subtotal</td>
                <td>Rp {{ number_format($penjualan->total_kotor, 0, ',', '.') }}</td>
            </tr>
            @if($penjualan->diskon_transaksi > 0)
                <tr>
                    <td colspan="4" style="text-align:right">Diskon</td>
                    <td>- Rp {{ number_format($penjualan->diskon_transaksi, 0, ',', '.') }}</td>
                </tr>
            @endif
            @if($penjualan->pajak > 0)
                <tr>
                    <td colspan="4" style="text-align:right">Pajak</td>
                    <td>Rp {{ number_format($penjualan->pajak, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr>
                <td colspan="4" style="text-align:right;font-size:1.1em;">Total Bayar</td>
                <td style="font-size:1.1em; color:#b45309;">Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
            </tr>
            </tfoot>
        </table>
        
        @if($penjualan->mode_termin === 'termin')
            <div class="termin-section">
                <strong>Detail Termin:</strong>
                <table class="items-table" style="margin-top:8px; margin-bottom:0;">
                    <thead>
                    <tr>
                        <th>Jatuh Tempo</th>
                        <th>Jumlah Termin</th>
                        <th>Sudah Dibayar</th>
                        <th>Sisa Tagihan</th>
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
                                <span class="status {{ $termin->status === 'lunas' ? 'selesai' : 'draft' }}">
                                    {{ ucfirst($termin->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    
    <div class="footer">
        <div>Terima kasih telah berbelanja di <b>Ngarumi</b>! 🎉</div>
        <div style="margin-top:8px;">Invoice dicetak pada {{ now()->format('d F Y H:i') }}</div>
    </div>
</div>
</body>
</html>