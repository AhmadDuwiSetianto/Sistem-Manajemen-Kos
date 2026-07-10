<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - Inna Kos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #666;
        }
        .info {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            font-weight: bold;
            background-color: #f4f4f4;
        }
        .footer {
            margin-top: 30px;
            width: 100%;
        }
        .signature {
            float: right;
            width: 250px;
            text-align: center;
        }
        .signature p {
            margin: 0;
        }
        .signature-space {
            height: 80px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Keuangan Inna Kos</h2>
        <p>Periode: {{ $periodeTampil }}</p>
    </div>

    <div class="info">
        <p><strong>Dicetak pada:</strong> {{ $waktuCetak }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="25%">Nama Penyewa</th>
                <th width="15%">Kamar</th>
                <th width="15%">Metode</th>
                <th width="25%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $item->tanggal_formatted }}</td>
                    <td>{{ optional($item->user)->name ?? 'N/A' }}</td>
                    <td class="text-center">{{ optional($item->kamar)->nomor_kamar ?? '-' }} ({{ optional($item->kamar)->tipe_kamar ?? '-' }})</td>
                    <td class="text-center">{{ $item->metode_pembayaran ?? 'Transfer' }}</td>
                    <td class="text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data transaksi pada periode ini.</td>
                </tr>
            @endforelse
            
            @if($transaksi->count() > 0)
                <tr class="total-row">
                    <td colspan="5" class="text-right">Total Pendapatan:</td>
                    <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Pekalongan, {{ $tanggalTtd }}</p>
            <p>Mengetahui,</p>
            <div class="signature-space"></div>
            <p><strong>Admin Inna Kos</strong></p>
        </div>
    </div>

</body>
</html>