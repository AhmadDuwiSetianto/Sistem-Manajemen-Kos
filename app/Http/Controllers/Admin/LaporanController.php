<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\User;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function keuangan(Request $request)
    {
        $activeBooking = Booking::whereIn('status', ['confirmed', 'checked_in'])->count();
        $filterStatus = $request->get('status', 'all');
        $filterBulan = $request->get('bulan', date('Y-m'));
        $filterTahun = $request->get('tahun', date('Y'));

        // Base query for successful payments only (lunas)
        $baseQuery = Pembayaran::where('status', 'lunas')
            ->with(['user', 'kamar']);

        // Apply month filter
        if ($filterBulan) {
            $baseQuery->whereYear('created_at', Carbon::parse($filterBulan)->year)
                ->whereMonth('created_at', Carbon::parse($filterBulan)->month);
        }

        // Apply year filter
        if ($filterTahun && !$filterBulan) {
            $baseQuery->whereYear('created_at', $filterTahun);
        }

        // Calculate financial metrics
        $pendapatanBulanIni = (clone $baseQuery)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('jumlah');

        $pendapatanBulanLalu = (clone $baseQuery)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('jumlah');

        $persentasePendapatan = $pendapatanBulanLalu > 0
            ? (($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100
            : ($pendapatanBulanIni > 0 ? 100 : 0);

        $totalPendapatan = (clone $baseQuery)->sum('jumlah');

        // Average per room
        $totalKamar = Kamar::count();
        $rataRataKamar = $totalKamar > 0 ? $totalPendapatan / $totalKamar : 0;

        // Pending payments (tunggakan)
        $totalTunggakan = Pembayaran::where('status', 'pending')->sum('jumlah');

        // Revenue by room type - FIXED VERSION
        $pendapatanPerTipe = Kamar::get()->map(function ($kamar) use ($filterBulan, $filterTahun, $totalPendapatan) {
            $query = Pembayaran::where('status', 'lunas')
                ->whereHas('booking', function ($q) use ($kamar) {
                    $q->where('kamar_id', $kamar->id);
                });

            if ($filterBulan) {
                $query->whereYear('created_at', Carbon::parse($filterBulan)->year)
                    ->whereMonth('created_at', Carbon::parse($filterBulan)->month);
            }
            if ($filterTahun && !$filterBulan) {
                $query->whereYear('created_at', $filterTahun);
            }

            $pendapatan = $query->sum('jumlah');

            return [
                'tipe' => $kamar->tipe_kamar,
                'pendapatan' => $pendapatan,
                'persentase' => $totalPendapatan > 0 ? round(($pendapatan / $totalPendapatan) * 100, 1) : 0
            ];
        })->filter(fn($item) => $item['pendapatan'] > 0)->values();

        // Recent transactions - Hanya yang lunas
        $transaksiTerbaru = (clone $baseQuery)
            ->with(['user', 'kamar'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Yearly summary (actual data)
        $tahunIni = $filterTahun ?: date('Y');
        $pendapatanPerBulan = [];

        for ($i = 1; $i <= 12; $i++) {
            $pendapatanBulan = (clone $baseQuery)
                ->whereYear('created_at', $tahunIni)
                ->whereMonth('created_at', $i)
                ->sum('jumlah');

            $pendapatanPerBulan[] = [
                'bulan' => Carbon::create()->month($i)->format('F'),
                'pendapatan' => $pendapatanBulan
            ];
        }

        $pendapatanTertinggi = collect($pendapatanPerBulan)->max('pendapatan');
        $bulanTerbaik = collect($pendapatanPerBulan)
            ->where('pendapatan', $pendapatanTertinggi)
            ->first()['bulan'] ?? 'Tidak ada data';
        $rataRataBulanan = collect($pendapatanPerBulan)->avg('pendapatan');

        // Room performance
        $kamarTerisi = Kamar::where('status', 'terisi')->count();
        $kamarTersedia = Kamar::where('status', 'tersedia')->count();
        $occupancyRate = $totalKamar > 0 ? round(($kamarTerisi / $totalKamar) * 100, 1) : 0;

        // Payment statistics
        $pembayaranLunas = Pembayaran::where('status', 'lunas')->count();
        $menungguPembayaran = Pembayaran::where('status', 'pending')->count();
        $pembayaranGagal = Pembayaran::where('status', 'gagal')->count();

        // Chart data for monthly revenue
        $chartData = $pendapatanPerBulan;

        return view('admin.laporan.keuangan', compact(
            'pendapatanBulanIni',
            'persentasePendapatan',
            'totalPendapatan',
            'rataRataKamar',
            'totalTunggakan',
            'pendapatanPerTipe',
            'transaksiTerbaru',
            'pendapatanTertinggi',
            'bulanTerbaik',
            'rataRataBulanan',
            'totalKamar',
            'kamarTerisi',
            'kamarTersedia',
            'occupancyRate',
            'pembayaranLunas',
            'menungguPembayaran',
            'pembayaranGagal',
            'chartData',
            'filterStatus',
            'filterBulan',
            'filterTahun',
            'activeBooking'
        ));
    }

    public function exportPDF(Request $request)
    {
        try {
            // FORCE TIMEZONE INDONESIA
            date_default_timezone_set('Asia/Jakarta');
            config(['app.timezone' => 'Asia/Jakarta']);

            $filterBulan = $request->get('bulan', date('Y-m'));
            $filterTahun = $request->get('tahun', date('Y'));

            // Get data for PDF - Hanya yang lunas
            $baseQuery = Pembayaran::where('status', 'lunas')
                ->with(['user', 'kamar']);

            if ($filterBulan) {
                $baseQuery->whereYear('created_at', Carbon::parse($filterBulan)->year)
                    ->whereMonth('created_at', Carbon::parse($filterBulan)->month);
            }

            if ($filterTahun && !$filterBulan) {
                $baseQuery->whereYear('created_at', $filterTahun);
            }

            $totalPendapatan = $baseQuery->sum('jumlah');
            $transaksi = $baseQuery->orderBy('created_at', 'desc')->get();

            // FORMAT TANGGAL INDONESIA MANUAL
            $bulanIndonesia = [
                'January' => 'Januari',
                'February' => 'Februari',
                'March' => 'Maret',
                'April' => 'April',
                'May' => 'Mei',
                'June' => 'Juni',
                'July' => 'Juli',
                'August' => 'Agustus',
                'September' => 'September',
                'October' => 'Oktober',
                'November' => 'November',
                'December' => 'Desember'
            ];

            $hariIndonesia = [
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu'
            ];

            // DAPATKAN WAKTU INDONESIA YANG BENAR
            $waktuSekarang = now()->setTimezone('Asia/Jakarta');

            $hari = $waktuSekarang->format('l');
            $tanggal = $waktuSekarang->format('d');
            $bulan = $waktuSekarang->format('F');
            $tahun = $waktuSekarang->format('Y');
            $jam = $waktuSekarang->format('H:i');

            // Format periode laporan
            if ($filterBulan) {
                $bulanPeriode = Carbon::parse($filterBulan)->format('F');
                $tahunPeriode = Carbon::parse($filterBulan)->format('Y');
                $periodeTampil = $bulanIndonesia[$bulanPeriode] . ' ' . $tahunPeriode;
            } else {
                $periodeTampil = 'Tahun ' . $filterTahun;
            }

            // Format variabel untuk view
            $waktuCetak = $hariIndonesia[$hari] . ', ' . $tanggal . ' ' . $bulanIndonesia[$bulan] . ' ' . $tahun . ' ' . $jam . ' WIB';
            $tanggalCetak = $tanggal . ' ' . $bulanIndonesia[$bulan] . ' ' . $tahun . ' ' . $jam . ' WIB';
            $tanggalTtd = $tanggal . ' ' . $bulanIndonesia[$bulan] . ' ' . $tahun;

            // Format tanggal transaksi
            $transaksi->transform(function ($item) use ($bulanIndonesia) {
                $item->created_at->setTimezone('Asia/Jakarta');
                $tanggal = $item->created_at->format('d/m/Y');
                $item->tanggal_formatted = $tanggal;
                $item->jam_formatted = $item->created_at->format('H:i') . ' WIB';
                return $item;
            });

            $dompdf = new \Dompdf\Dompdf();

            $html = view('admin.laporan.export-pdf', compact(
                'transaksi',
                'totalPendapatan',
                'filterBulan',
                'filterTahun',
                'periodeTampil',
                'waktuCetak',
                'tanggalCetak',
                'tanggalTtd'
            ))->render();

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $fileName = 'Laporan_Keuangan_' . ($filterBulan ? str_replace('-', '_', $filterBulan) : $filterTahun) . '.pdf';

            return $dompdf->stream($fileName);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor PDF: ' . $e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            // FORCE TIMEZONE INDONESIA
            date_default_timezone_set('Asia/Jakarta');
            config(['app.timezone' => 'Asia/Jakarta']);

            $filterBulan = $request->get('bulan', date('Y-m'));
            $filterTahun = $request->get('tahun', date('Y'));

            // Get data for Excel export - Hanya yang lunas
            $baseQuery = Pembayaran::where('status', 'lunas')
                ->with(['user', 'kamar']);

            if ($filterBulan) {
                $baseQuery->whereYear('created_at', Carbon::parse($filterBulan)->year)
                    ->whereMonth('created_at', Carbon::parse($filterBulan)->month);
            }

            if ($filterTahun && !$filterBulan) {
                $baseQuery->whereYear('created_at', $filterTahun);
            }

            $transaksi = $baseQuery->orderBy('created_at', 'desc')->get();

            // Format tanggal Indonesia untuk Excel
            $transaksi->transform(function ($item) {
                $item->created_at->setTimezone('Asia/Jakarta');
                return $item;
            });

            // Pastikan ada data sebelum export
            if ($transaksi->isEmpty()) {
                return back()->with('warning', 'Tidak ada data transaksi untuk diekspor.');
            }

            $fileName = 'laporan-keuangan-' . ($filterBulan ?: $filterTahun) . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ];

            $callback = function () use ($transaksi) {
                $file = fopen('php://output', 'w');

                // Add BOM for UTF-8
                fwrite($file, "\xEF\xBB\xBF");

                // Headers
                fputcsv($file, [
                    'No',
                    'ID Transaksi',
                    'Nama Penyewa',
                    'Email',
                    'Nomor Kamar',
                    'Tipe Kamar',
                    'Jumlah Pembayaran',
                    'Tanggal Transaksi',
                    'Waktu Transaksi',
                    'Metode Pembayaran',
                    'Status'
                ], ';');

                // Data
                foreach ($transaksi as $index => $item) {
                    fputcsv($file, [
                        $index + 1,
                        $item->id,
                        $item->user->name ?? 'N/A',
                        $item->user->email ?? 'N/A',
                        $item->kamar->nomor_kamar ?? 'N/A',
                        $item->kamar->tipe_kamar ?? 'N/A',
                        number_format($item->jumlah, 0, ',', '.'),
                        $item->created_at->format('d/m/Y'),
                        $item->created_at->format('H:i') . ' WIB',
                        $item->metode_pembayaran ?? 'Transfer',
                        ucfirst($item->status)
                    ], ';');
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor Excel: ' . $e->getMessage());
        }
    }
    public function statistik()
    {
        try {
            // Data statistik sederhana
            $totalKamar = Kamar::count();
            $totalPenghuni = User::where('role', 'penghuni')->count();
            $totalBooking = Booking::count();

            // Occupancy rate
            $kamarTerisi = Kamar::where('status', 'terisi')->count();
            $occupancyRate = $totalKamar > 0 ? round(($kamarTerisi / $totalKamar) * 100, 1) : 0;

            return view('admin.laporan.statistik', compact(
                'totalKamar',
                'totalPenghuni',
                'totalBooking',
                'occupancyRate'
            ));
        } catch (\Exception $e) {
            // Fallback jika ada error
            return view('admin.laporan.statistik', [
                'totalKamar' => 0,
                'totalPenghuni' => 0,
                'totalBooking' => 0,
                'occupancyRate' => 0
            ]);
        }
    }
}
