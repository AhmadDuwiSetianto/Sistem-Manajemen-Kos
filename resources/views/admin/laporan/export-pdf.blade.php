<!DOCTYPE html>
<html>
<head>
    <title>LAPORAN KEUANGAN - KOSTKU</title>
    <style>
        @page { margin: 100px 30px 80px 30px; }
        
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            margin: 0;
            padding: 0;
            color: #2c3e50;
            font-size: 11px;
            line-height: 1.3;
        }
        
        .header { 
            position: fixed; 
            top: -80px; 
            left: 0; 
            right: 0; 
            height: 70px;
            text-align: center;
            border-bottom: 3px double #2c3e50;
            padding-bottom: 10px;
        }
        
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            color: #2c3e50;
        }
        
        .company-address {
            font-size: 9px;
            margin: 2px 0;
            color: #7f8c8d;
        }
        
        .report-title {
            text-align: center;
            margin: 80px 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }
        
        .report-title h1 {
            font-size: 16px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .report-info {
            margin-bottom: 15px;
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-table td {
            padding: 3px 5px;
            border-bottom: 1px dashed #dee2e6;
        }
        
        .summary {
            margin: 15px 0;
            padding: 15px;
            background: #e8f4fd;
            border: 1px solid #3498db;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .summary-table td {
            text-align: center;
            padding: 10px 5px;
            background: white;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 9px;
        }
        
        .data-table th {
            background: #2c3e50;
            color: white;
            font-weight: bold;
            padding: 8px 4px;
            text-align: center;
            border: 1px solid #2c3e50;
        }
        
        .data-table td {
            padding: 6px 4px;
            border: 1px solid #bdc3c7;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .footer {
            position: fixed; 
            bottom: -50px; 
            left: 0; 
            right: 0; 
            height: 40px;
            text-align: center;
            border-top: 1px solid #bdc3c7;
            padding-top: 10px;
            font-size: 8px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <div class="header">
        <h1 class="company-name">KOSTKU - KOST MODERN TERPADU</h1>
        <p class="company-address">Jl. Merdeka No. 123, Bandung, Jawa Barat 40123 | Telp: (022) 1234-5678</p>
    </div>

    <!-- Judul Laporan -->
    <div class="report-title">
        <h1>LAPORAN KEUANGAN RESMI</h1>
        <p>Periode: <strong>{{ $periodeTampil }}</strong> | Dicetak: {{ $waktuCetak }}</p>
    </div>

    <!-- Informasi Laporan -->
    <div class="report-info">
        <table class="info-table">
            <tr>
                <td><strong>Tanggal Cetak:</strong></td>
                <td>{{ $tanggalCetak }}</td>
                <td><strong>Jenis Laporan:</strong></td>
                <td>Laporan Keuangan</td>
            </tr>
            <tr>
                <td><strong>Periode Laporan:</strong></td>
                <td>{{ $periodeTampil }}</td>
                <td><strong>Status Data:</strong></td>
                <td>Pembayaran Lunas</td>
            </tr>
        </table>
    </div>

    <!-- Ringkasan Keuangan -->
    <div class="summary">
        <table class="summary-table">
            <tr>
                <td>
                    <div style="font-size: 14px; font-weight: bold;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                    <div style="font-size: 9px;">TOTAL PENDAPATAN</div>
                </td>
                <td>
                    <div style="font-size: 14px; font-weight: bold;">{{ $transaksi->count() }}</div>
                    <div style="font-size: 9px;">JUMLAH TRANSAKSI</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Tabel Transaksi -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="10%">ID TRANSAKSI</th>
                <th width="20%">PENYEWA</th>
                <th width="15%">KAMAR</th>
                <th width="15%">TANGGAL</th>
                <th width="15%">WAKTU</th>
                <th width="20%">JUMLAH</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">#{{ $item->id }}</td>
                <td>{{ $item->user->name ?? 'N/A' }}</td>
                <td>Kamar {{ $item->kamar->nomor_kamar ?? 'N/A' }}</td>
                <td class="text-center">{{ $item->tanggal_formatted }}</td>
                <td class="text-center">{{ $item->jam_formatted }}</td>
                <td class="text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 40px; color: #7f8c8d;">
                    TIDAK ADA DATA TRANSAKSI
                </td>
            </tr>
            @endforelse
        </tbody>
        @if($transaksi->count() > 0)
        <tfoot>
            <tr style="background: #2c3e50; color: white;">
                <td colspan="6" class="text-right" style="text-align: right; padding: 8px;">
                    <strong>TOTAL PENDAPATAN:</strong>
                </td>
                <td class="text-right" style="padding: 8px;">
                    <strong>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tfoot>
        @endif
    </table>

    <!-- Footer -->
    <div class="footer">
        Laporan Keuangan KOSTKU • Dicetak otomatis pada {{ $waktuCetak }} • Dokumen resmi
    </div>
</body>
</html>