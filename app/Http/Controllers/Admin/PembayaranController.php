<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran; // Pastikan model Pembayaran sudah ada
use App\Models\Booking;    // Pastikan model Booking sudah ada

class PembayaranController extends Controller
{
    /**
     * Menampilkan daftar pembayaran
     */
    public function index(Request $request)
    {
        // Menghitung Statistik Pembayaran
        // (Asumsi kolom 'jumlah' untuk nominal dan 'status' untuk status bayar)
        $totalPemasukan = Pembayaran::where('status', 'success')->sum('jumlah');
        $pendingPembayaran = Pembayaran::where('status', 'pending')->count();
        $suksesPembayaran = Pembayaran::where('status', 'success')->count();
        $gagalPembayaran = Pembayaran::where('status', 'failed')->count();

        // Query data pembayaran beserta relasi user dan kamar
        $query = Pembayaran::with(['user', 'booking.kamar'])->latest();

        // Fitur Pencarian (Opsional - Backend filter)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Fitur Filter Status (Opsional - Backend filter)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Pagination
        $pembayarans = $query->paginate(10)->withQueryString();

        return view('admin.pembayaran.index', compact(
            'pembayarans',
            'totalPemasukan',
            'pendingPembayaran',
            'suksesPembayaran',
            'gagalPembayaran'
        ));
    }

    /**
     * Menampilkan detail pembayaran (Invoice / Bukti Bayar)
     */
    public function show($id)
    {
        $pembayaran = Pembayaran::with(['user', 'kamar', 'booking'])->findOrFail($id);
        
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    /**
     * Verifikasi (Approve) pembayaran yang masuk
     */
    public function verify($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        
        // Update status pembayaran menjadi sukses
        $pembayaran->update([
            'status' => 'success'
        ]);

        // (Opsional) Update status booking yang terkait menjadi confirmed/active
        if ($pembayaran->booking_id) {
            Booking::where('id', $pembayaran->booking_id)->update([
                'status' => 'confirmed' // sesuaikan dengan enum status di tabel bookings mu
            ]);
        }

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi & dikonfirmasi!');
    }

    /**
     * Tolak (Reject) pembayaran jika bukti transfer tidak valid
     */
    public function reject($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        
        // Update status pembayaran menjadi gagal
        $pembayaran->update([
            'status' => 'failed'
        ]);

        return redirect()->back()->with('error', 'Pembayaran telah ditolak.');
    }
}