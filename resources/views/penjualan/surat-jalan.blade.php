<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Jalan - {{ $penjualan->no_faktur }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        @page { size: A4; margin: 10mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Inter', Arial, sans-serif; 
            background: #fff;
            color: #333; 
            font-size: 10px;
            padding: 10px;
        }
        
        .surat-jalan-container { max-width: 100%; margin: 0 auto; }
        
        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }
        
        .logo-section { display: flex; align-items: flex-start; gap: 20px; }
        .logo { font-size: 26px; font-weight: 700; color: #4CAF50; letter-spacing: -1px; font-style: italic; }
        .logo span { color: #FF9800; }
        .company-info { font-size: 9px; line-height: 1.4; color: #333; }
        .company-info strong { font-weight: 600; font-size: 10px; }
        .title-section { text-align: right; }
        .surat-jalan-title { font-size: 22px; font-weight: 700; color: #4CAF50; letter-spacing: 3px; }
        
        .info-row-container { display: flex; justify-content: flex-end; margin-bottom: 6px; }
        .info-table { font-size: 9px; }
        .info-table td { padding: 2px 4px; vertical-align: top; }
        .info-table .label { font-weight: 500; white-space: nowrap; }
        .info-table .separator { width: 10px; text-align: center; }
        .info-table .value { min-width: 150px; }
        .dotted-line { border-bottom: 1px dotted #666; min-width: 100px; display: inline-block; }
        
        .tujuan-section { margin-bottom: 10px; }
        .tujuan-table { font-size: 9px; }
        .tujuan-table td { padding: 2px 4px; vertical-align: top; }
        .tujuan-table .label { font-weight: 500; width: 60px; }
        .tujuan-table .separator { width: 10px; text-align: center; }
        .tujuan-table .value { width: 100%; }
        .tujuan-table .value .dotted-line { width: 100%; min-width: 250px; }
        
        .items-section { margin-bottom: 8px; }
        .items-title { font-size: 10px; font-weight: 600; margin-bottom: 6px; }
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table thead { background: linear-gradient(to right, #8BC34A, #4CAF50); }
        .items-table th { color: white; padding: 5px 8px; text-align: left; font-weight: 600; font-size: 9px; border: 1px solid #4CAF50; }
        .items-table th:first-child { width: 30px; text-align: center; }
        .items-table th:nth-child(3) { width: 70px; text-align: center; }
        .items-table th:last-child { width: 120px; }
        .items-table td { padding: 4px 8px; border: 1px solid #ccc; font-size: 9px; height: 18px; }
        .items-table td:first-child { text-align: center; }
        .items-table td:nth-child(3) { text-align: center; }
        
        .total-row { background: linear-gradient(to right, #8BC34A, #4CAF50); }
        .total-row td { color: white; font-weight: 700; font-size: 10px; padding: 5px 8px; border: 1px solid #4CAF50; }
        
        .signature-section { display: flex; justify-content: space-between; padding: 10px 20px; margin-top: 8px; }
        .signature-box { text-align: center; width: 120px; }
        .signature-title { font-size: 10px; font-weight: 600; }
        .signature-space { height: 40px; }
        .signature-line { font-size: 9px; }
        .signature-dots { display: inline-block; width: 80px; border-bottom: 1px dotted #666; }
        
        .barcode-box { text-align: center; }
        .barcode-lines { display: flex; justify-content: center; gap: 2px; height: 30px; align-items: flex-end; margin-bottom: 5px; }
        .barcode-line { width: 3px; background: #4CAF50; }
        
        @media print {
            body { padding: 0; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .surat-jalan-container { page-break-inside: avoid; }
        }
    </style>
</head>
<body onload="window.print()">
<div class="surat-jalan-container">
    <div class="header-row">
        <div class="logo-section">
            <div class="logo">NU<span>Klerr</span></div>
            <div class="company-info">
                <strong>PT PERSADA NAWA KARTIKA</strong><br>
                Jl. Supriyadi No. 24<br>
                Nganjuk<br>
                Phone: 082332538222
            </div>
        </div>
        <div class="title-section">
            <div class="surat-jalan-title">SURAT JALAN</div>
        </div>
    </div>
    
    <div class="info-row-container">
        <table class="info-table">
            <tr>
                <td class="label">Tanggal</td>
                <td class="separator">:</td>
                <td class="value"><span class="dotted-line">{{ $penjualan->tanggal->format('d / m / Y') }}</span></td>
            </tr>
            <tr>
                <td class="label">Kendaraan</td>
                <td class="separator">:</td>
                <td class="value">Traga / Box/ Cery AG9643VI</td>
            </tr>
            <tr>
                <td class="label">No. Polisi</td>
                <td class="separator">:</td>
                <td class="value">AG 8438VI / AG8045VI</td>
            </tr>
        </table>
    </div>
    
    <div class="tujuan-section">
        <table class="tujuan-table">
            <tr>
                <td class="label">Tujuan</td>
                <td class="separator">:</td>
                <td class="value"><span class="dotted-line"></span></td>
            </tr>
            <tr>
                <td class="label">Nama</td>
                <td class="separator">:</td>
                <td class="value"><span class="dotted-line">{{ $penjualan->pelanggan?->nama_pelanggan ?? $penjualan->pelanggan?->nama ?? 'UMUM' }}</span></td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="separator">:</td>
                <td class="value"><span class="dotted-line">{{ $penjualan->pelanggan?->alamat ?? '' }}</span></td>
            </tr>
        </table>
    </div>
    
    <div class="items-section">
        <div class="items-title">Dikirimkan barang sebagai berikut :</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Barang</th>
                    <th>Quantity</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penjualan->detailPenjualan as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->barang->nama_barang }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td></td>
                </tr>
                @endforeach
                @php $totalItems = count($penjualan->detailPenjualan); @endphp
                @if($totalItems < 12)
                    @for($i = $totalItems; $i < 12; $i++)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endfor
                @endif
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2">TOTAL</td>
                    <td>{{ $penjualan->detailPenjualan->sum('jumlah') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-title">GUDANG</div>
            <div class="signature-space"></div>
            <div class="signature-line">(<span class="signature-dots"></span>)</div>
        </div>
        <div class="signature-box">
            <div class="signature-title">DRIVER</div>
            <div class="signature-space"></div>
            <div class="signature-line">(<span class="signature-dots"></span>)</div>
        </div>
        <div class="barcode-box">
            <div class="signature-title">TANDA TERIMA</div>
            <div class="signature-space"></div>
            <div class="signature-line">(<span class="signature-dots"></span>)</div>
        </div>
    </div>
</div>
</body>
</html>
