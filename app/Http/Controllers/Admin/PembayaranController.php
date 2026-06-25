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
     * * @param string $id
     */
    public function show(string $id) // ✅ Penambahan tipe data 'string' di sini
    {
        $pembayaran = Pembayaran::with(['user', 'booking.kamar'])->findOrFail($id);
        
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    /**
     * Verifikasi (Approve) pembayaran yang masuk (Manual)
     * * @param string $id
     */
    public function verify(string $id) // ✅ Penambahan tipe data 'string' di sini
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
                    /** @var \App\Models\Kamar $kamar */
                    $kamar = $booking->kamar;
                    $kamar->update(['status' => 'terisi']);
                }
            }
        }

        // Ubah role user jadi penghuni jika sebelumnya masih calon
        if ($pembayaran->user && $pembayaran->user->role === 'calon_penghuni') {
            /** @var \App\Models\User $user */
            $user = $pembayaran->user;
            $user->update(['role' => 'penghuni']);
        }

        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi & dikonfirmasi!');
    }

    /**
     * Tolak (Reject) pembayaran jika bukti transfer tidak valid (Manual)
     * * @param string $id
     */
    public function reject(string $id) // ✅ Penambahan tipe data 'string' di sini
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
                    /** @var \App\Models\Kamar $kamar */
                    $kamar = $booking->kamar;
                    $kamar->update(['status' => 'tersedia']);
                }
            }
        }

        return redirect()->back()->with('error', 'Pembayaran telah ditolak dan dibatalkan.');
    }
}