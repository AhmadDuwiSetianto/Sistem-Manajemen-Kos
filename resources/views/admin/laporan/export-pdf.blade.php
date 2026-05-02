<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan - Inna Kos</title>
    <style>
        @page { margin: 40px 40px 60px 40px; }
        body { 
            font-family: 'Helvetica', Arial, sans-serif; 
            color: #1a1a1a;
            font-size: 11px;
            line-height: 1.4;
        }
        
        .header-doc {
            border-bottom: 2px solid #165DFF;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header-doc h1 {
            color: #165DFF;
            font-size: 24px;
            margin: 0;
            letter-spacing: 1px;
        }
        .header-doc p { margin: 4px 0 0 0; color: #666; font-size: 10px; }

        .title-section { text-align: center; margin-bottom: 25px; }
        .title-section h2 { font-size: 16px; margin: 0; text-transform: uppercase; }
        .title-section p { font-size: 11px; color: #555; margin-top: 5px; }

        table.grid-layout { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        table.grid-layout td { vertical-align: top; width: 50%; }

        .info-box {
            background-color: #f8fafc;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            margin-right: 10px;
        }
        .summary-box {
            background-color: #eff6ff;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #bfdbfe;
            margin-left: 10px;
            text-align: right;
        }
        .summary-box .amount { font-size: 20px; font-weight: bold; color: #1e3a8a; margin: 5px 0; }

        /* Tabel Data Transaksi */
        .table-data { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .table-data th {
            background-color: #165DFF;
            color: #ffffff;
            font-size: 10px;
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #165DFF;
        }
        .table-data td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
        }
        .table-data tr:nth-child(even) { background-color: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* Footer */
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #888;
            border-top: 1px dashed #ccc;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header-doc">
        <h1>Inna Kos</h1>
        <p>Jl. Perum Sinar Muncar, Perum Villa Pisma Asri, Podo, Kec. Kedungwuni, Kabupaten Pekalongan, Jawa Tengah 51173 | Telepon: 0856-4276-3667</p>
    </div>

    <div class="title-section">
        <h2>Laporan Pendapatan Keuangan</h2>
        <p>Periode: <strong>{{ $periodeTampil }}</strong></p>
    </div>

    <table class="grid-layout">
        <tr>
            <td>
                <div class="info-box">
                    <strong style="font-size: 12px; display:block; margin-bottom:5px;">Detail Laporan</strong>
                    <table style="width: 100%; font-size: 10px;">
                        <tr><td width="35%">Jenis Data</td><td>: Transaksi Sukses Lunas</td></tr>
                        <tr><td>Dicetak Pada</td><td>: {{ $tanggalCetak }}</td></tr>
                        <tr><td>Dicetak Oleh</td><td>: Administrator System</td></tr>
                    </table>
                </div>
            </td>
            <td>
                <div class="summary-box">
                    <span style="font-size: 10px; color:#3b82f6; text-transform:uppercase; font-weight:bold;">Total Pemasukan Periode Ini</span>
                    <div class="amount">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                    <span style="font-size: 10px; color:#64748b;">Dari {{ $transaksi->count() }} Transaksi</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="table-data">
        <thead>
            <tr>
                <th width="5%" class="text-center">NO</th>
                <th width="12%">ID TRX</th>
                <th width="25%">NAMA PENYEWA</th>
                <th width="15%">KAMAR</th>
                <th width="20%">WAKTU BAYAR</th>
                <th width="23%" class="text-right">JUMLAH (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>#{{ $item->id }}</strong></td>
                <td>{{ $item->user->name ?? 'N/A' }}</td>
                <td>Kamar {{ $item->kamar->nomor_kamar ?? '-' }}</td>
                <td>{{ $item->tanggal_formatted }}<br><span style="font-size:8px; color:#666;">{{ $item->jam_formatted }} WIB</span></td>
                <td class="text-right font-bold">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 30px; color: #888;">
                    Tidak ada data transaksi lunas pada periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
        @if($transaksi->count() > 0)
        <tfoot>
            <tr>
                <td colspan="5" class="text-right" style="font-weight: bold; background-color:#e2e8f0;">TOTAL PENDAPATAN KESELURUHAN</td>
                <td class="text-right" style="font-weight: bold; font-size:12px; background-color:#e2e8f0;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        Dokumen Laporan Keuangan Sistem Informasi Manajemen Inna Kos | Digenerate secara otomatis pada {{ $waktuCetak }}
    </div>

</body>
</html>