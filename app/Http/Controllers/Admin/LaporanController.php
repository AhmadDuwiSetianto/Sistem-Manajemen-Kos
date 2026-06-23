<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\User;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function keuangan(Request $request)
    {
        $activeBooking = Booking::whereIn('status', ['confirmed', 'checked_in'])->count();
        $filterStatus = $request->get('status', 'all');
        
        $filterBulan = $request->get('bulan', date('Y-m')); 
        $filterTahun = $request->get('tahun', date('Y'));

        $carbonDate = Carbon::parse($filterBulan);
        $bulanTerkait = $carbonDate->month;
        $tahunTerkait = $carbonDate->year;

        // ✅ PERBAIKAN: Ubah pencarian dari 'lunas' menjadi 'paid'
        $baseQuery = Pembayaran::where('status', 'paid')
            ->with(['user', 'booking.kamar']);

        if ($request->has('bulan')) {
            $baseQuery->whereYear('created_at', $tahunTerkait)
                      ->whereMonth('created_at', $bulanTerkait);
        } elseif ($request->has('tahun') && !$request->has('bulan')) {
            $baseQuery->whereYear('created_at', $filterTahun);
        } else {
            $baseQuery->whereYear('created_at', now()->year)
                      ->whereMonth('created_at', now()->month);
        }

        $semuaTransaksiFilter = (clone $baseQuery)->get();

        $pendapatanBulanIni = $semuaTransaksiFilter->sum('jumlah');

        // ✅ PERBAIKAN: 'paid'
        $pendapatanBulanLalu = Pembayaran::where('status', 'paid')
            ->whereYear('created_at', $carbonDate->copy()->subMonth()->year)
            ->whereMonth('created_at', $carbonDate->copy()->subMonth()->month)
            ->sum('jumlah');

        $persentasePendapatan = $pendapatanBulanLalu > 0
            ? (($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100
            : ($pendapatanBulanIni > 0 ? 100 : 0);

        $tahunUntukTotal = $request->has('bulan') ? $tahunTerkait : $filterTahun;
        // ✅ PERBAIKAN: 'paid'
        $totalPendapatan = Pembayaran::where('status', 'paid')
                                     ->whereYear('created_at', $tahunUntukTotal)
                                     ->sum('jumlah');

        $totalKamar = Kamar::count();
        $rataRataKamar = $totalKamar > 0 ? $totalPendapatan / $totalKamar : 0;

        $queryTunggakan = Pembayaran::where('status', 'pending');
        if ($request->has('bulan')) {
            $queryTunggakan->whereYear('created_at', $tahunTerkait)->whereMonth('created_at', $bulanTerkait);
        } else {
            $queryTunggakan->whereYear('created_at', $filterTahun);
        }
        $totalTunggakan = $queryTunggakan->sum('jumlah');

        $pendapatanPerTipe = [];
        $transaksiDikumpulkan = $semuaTransaksiFilter->groupBy(function($pembayaran) {
            return optional(optional($pembayaran->booking)->kamar)->tipe_kamar ?? 'Lainnya';
        });

        foreach ($transaksiDikumpulkan as $tipeKamar => $kumpulanPembayaran) {
            $totalPerTipe = $kumpulanPembayaran->sum('jumlah');
            
            $pendapatanPerTipe[] = [
                'tipe' => 'Tipe ' . ucfirst($tipeKamar),
                'pendapatan' => $totalPerTipe,
                'persentase' => $pendapatanBulanIni > 0 ? round(($totalPerTipe / $pendapatanBulanIni) * 100, 1) : 0
            ];
        }
        
        usort($pendapatanPerTipe, fn($a, $b) => $b['pendapatan'] <=> $a['pendapatan']);

        $transaksiTerbaru = (clone $baseQuery)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $pendapatanPerBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            // ✅ PERBAIKAN: 'paid'
            $pendapatanBulan = Pembayaran::where('status', 'paid')
                ->whereYear('created_at', $tahunUntukTotal)
                ->whereMonth('created_at', $i)
                ->sum('jumlah');

            $pendapatanPerBulan[] = [
                'bulan' => Carbon::create()->month($i)->translatedFormat('M'), 
                'pendapatan' => $pendapatanBulan
            ];
        }

        $pendapatanTertinggi = collect($pendapatanPerBulan)->max('pendapatan');
        $bulanTerbaik = collect($pendapatanPerBulan)
            ->where('pendapatan', $pendapatanTertinggi)
            ->first()['bulan'] ?? 'Tidak ada data';
        $rataRataBulanan = collect($pendapatanPerBulan)->avg('pendapatan');

        $kamarTerisi = Kamar::where('status', 'terisi')->count();
        $kamarTersedia = Kamar::where('status', 'tersedia')->count();
        $occupancyRate = $totalKamar > 0 ? round(($kamarTerisi / $totalKamar) * 100, 1) : 0;

        // ✅ PERBAIKAN: 'paid'
        $pembayaranLunas = Pembayaran::where('status', 'paid')->count();
        $menungguPembayaran = Pembayaran::where('status', 'pending')->count();
        $pembayaranGagal = Pembayaran::where('status', 'gagal')->count();

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
            date_default_timezone_set('Asia/Jakarta');
            config(['app.timezone' => 'Asia/Jakarta']);

            $filterBulan = $request->get('bulan', date('Y-m'));
            $filterTahun = $request->get('tahun', date('Y'));

            // ✅ PERBAIKAN: 'paid'
            $baseQuery = Pembayaran::where('status', 'paid')
                ->with(['user', 'booking.kamar']);

            if ($filterBulan) {
                $baseQuery->whereYear('created_at', Carbon::parse($filterBulan)->year)
                    ->whereMonth('created_at', Carbon::parse($filterBulan)->month);
            }

            if ($filterTahun && !$filterBulan) {
                $baseQuery->whereYear('created_at', $filterTahun);
            }

            $totalPendapatan = $baseQuery->sum('jumlah');
            $transaksi = $baseQuery->orderBy('created_at', 'desc')->get();

            $bulanIndonesia = [
                'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
                'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
                'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
                'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
            ];

            $hariIndonesia = [
                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
            ];

            $waktuSekarang = now()->setTimezone('Asia/Jakarta');

            $hari = $waktuSekarang->format('l');
            $tanggal = $waktuSekarang->format('d');
            $bulan = $waktuSekarang->format('F');
            $tahun = $waktuSekarang->format('Y');
            $jam = $waktuSekarang->format('H:i');

            if ($filterBulan) {
                $bulanPeriode = Carbon::parse($filterBulan)->format('F');
                $tahunPeriode = Carbon::parse($filterBulan)->format('Y');
                $periodeTampil = $bulanIndonesia[$bulanPeriode] . ' ' . $tahunPeriode;
            } else {
                $periodeTampil = 'Tahun ' . $filterTahun;
            }

            $waktuCetak = $hariIndonesia[$hari] . ', ' . $tanggal . ' ' . $bulanIndonesia[$bulan] . ' ' . $tahun . ' ' . $jam . ' WIB';
            $tanggalCetak = $tanggal . ' ' . $bulanIndonesia[$bulan] . ' ' . $tahun . ' ' . $jam . ' WIB';
            $tanggalTtd = $tanggal . ' ' . $bulanIndonesia[$bulan] . ' ' . $tahun;

            $transaksi->transform(function ($item) {
                $item->created_at->setTimezone('Asia/Jakarta');
                $item->tanggal_formatted = $item->created_at->format('d/m/Y');
                $item->jam_formatted = $item->created_at->format('H:i') . ' WIB';
                $item->kamar = optional($item->booking)->kamar;
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
            date_default_timezone_set('Asia/Jakarta');
            config(['app.timezone' => 'Asia/Jakarta']);

            $filterBulan = $request->get('bulan', date('Y-m'));
            $filterTahun = $request->get('tahun', date('Y'));

            // ✅ PERBAIKAN: 'paid'
            $baseQuery = Pembayaran::where('status', 'paid')
                ->with(['user', 'booking.kamar']);

            if ($filterBulan) {
                $baseQuery->whereYear('created_at', Carbon::parse($filterBulan)->year)
                    ->whereMonth('created_at', Carbon::parse($filterBulan)->month);
            }

            if ($filterTahun && !$filterBulan) {
                $baseQuery->whereYear('created_at', $filterTahun);
            }

            $transaksi = $baseQuery->orderBy('created_at', 'desc')->get();

            $transaksi->transform(function ($item) {
                $item->created_at->setTimezone('Asia/Jakarta');
                return $item;
            });

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
                fwrite($file, "\xEF\xBB\xBF");

                fputcsv($file, [
                    'No', 'ID Transaksi', 'Nama Penyewa', 'Email',
                    'Nomor Kamar', 'Tipe Kamar', 'Jumlah Pembayaran',
                    'Tanggal Transaksi', 'Waktu Transaksi', 'Metode Pembayaran', 'Status'
                ], ';');

                foreach ($transaksi as $index => $item) {
                    fputcsv($file, [
                        $index + 1,
                        $item->id,
                        $item->user->name ?? 'N/A',
                        $item->user->email ?? 'N/A',
                        optional(optional($item->booking)->kamar)->nomor_kamar ?? 'N/A',
                        optional(optional($item->booking)->kamar)->tipe_kamar ?? 'N/A',
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
            $totalKamar = Kamar::count();
            $totalPenghuni = User::where('role', 'penghuni')->count();
            $totalBooking = Booking::count();

            $kamarTerisi = Kamar::where('status', 'terisi')->count();
            $occupancyRate = $totalKamar > 0 ? round(($kamarTerisi / $totalKamar) * 100, 1) : 0;

            return view('admin.laporan.statistik', compact(
                'totalKamar', 'totalPenghuni', 'totalBooking', 'occupancyRate'
            ));
        } catch (\Exception $e) {
            return view('admin.laporan.statistik', [
                'totalKamar' => 0, 'totalPenghuni' => 0, 'totalBooking' => 0, 'occupancyRate' => 0
            ]);
        }
    }
}