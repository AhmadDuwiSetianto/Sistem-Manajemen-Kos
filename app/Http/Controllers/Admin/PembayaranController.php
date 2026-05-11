<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Booking;

class PembayaranController extends Controller
{
    /**
     * Menampilkan daftar pembayaran
     */
    public function index(Request $request)
    {
        // PERBAIKAN: Sesuaikan query dengan status dari Midtrans
        $totalPemasukan = Pembayaran::where('status', 'paid')->sum('jumlah');
        $pendingPembayaran = Pembayaran::where('status', 'pending')->count();
        $suksesPembayaran = Pembayaran::where('status', 'paid')->count();
        $gagalPembayaran = Pembayaran::whereIn('status', ['expired', 'cancelled'])->count();

        // Query data pembayaran beserta relasi user dan kamar
        $query = Pembayaran::with(['user', 'booking.kamar'])->latest();

        // Fitur Pencarian 
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_pembayaran', 'like', "%{$search}%") // Lebih relevan cari pakai kode pembayaran
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Fitur Filter Status
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
        $pembayaran = Pembayaran::with(['user', 'booking.kamar'])->findOrFail($id);
        
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    /**
     * Verifikasi (Approve) pembayaran yang masuk (Manual)
     */
    public function verify($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        
        // PERBAIKAN: Update status pembayaran menjadi 'paid'
        $pembayaran->update([
            'status' => 'paid',
            'tanggal_bayar' => now()
        ]);

        // Update status booking menjadi 'confirmed' dan kamar menjadi 'terisi'
        if ($pembayaran->booking_id) {
            $booking = Booking::with('kamar')->find($pembayaran->booking_id);
            if ($booking) {
                $booking->update(['status' => 'confirmed']);
                
                if ($booking->kamar) {
                    $booking->kamar->update(['status' => 'terisi']);
                }
            }
        }

        // Ubah role user jadi penghuni jika sebelumnya masih calon
        if ($pembayaran->user && $pembayaran->user->role === 'calon_penghuni') {
            $pembayaran->user->update(['role' => 'penghuni']);
        }

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi & dikonfirmasi!');
    }

    /**
     * Tolak (Reject) pembayaran jika bukti transfer tidak valid (Manual)
     */
    public function reject($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        
        // PERBAIKAN: Update status pembayaran menjadi 'cancelled'
        $pembayaran->update([
            'status' => 'cancelled'
        ]);

        // Batalkan booking dan kembalikan status kamar jadi tersedia
        if ($pembayaran->booking_id) {
            $booking = Booking::with('kamar')->find($pembayaran->booking_id);
            if ($booking) {
                $booking->update(['status' => 'cancelled']);
                
                // Pastikan kamar dikosongkan lagi JIKA ini bukan perpanjangan (extend)
                $isPerpanjangan = str_contains($booking->catatan ?? '', 'Perpanjangan');
                if ($booking->kamar && !$isPerpanjangan) {
                    $booking->kamar->update(['status' => 'tersedia']);
                }
            }
        }

        return redirect()->back()->with('error', 'Pembayaran telah ditolak dan dibatalkan.');
    }
}