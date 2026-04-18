<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function index()
    {
        // Hitung statistik untuk dashboard admin
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', Booking::STATUS_PENDING)->count();
        $confirmedBookings = Booking::where('status', Booking::STATUS_CONFIRMED)->count();
        $cancelledBookings = Booking::where('status', Booking::STATUS_CANCELLED)->count();
        $checkedInBookings = Booking::where('status', 'checked_in')->count();
        $checkedOutBookings = Booking::where('status', 'checked_out')->count();
        
        // Statistik booking aktif (Confirmed + Checked In)
        $activeBooking = $confirmedBookings + $checkedInBookings;

        // Ambil data dengan eager loading untuk performa
        $bookings = Booking::with(['user', 'kamar', 'pembayaran'])
            ->latest()
            ->paginate(10);

        return view('admin.booking.index', compact(
            'bookings',
            'totalBookings',
            'pendingBookings',
            'confirmedBookings',
            'cancelledBookings',
            'checkedInBookings',
            'checkedOutBookings',
            'activeBooking'
        ));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'kamar', 'pembayaran']);
        return view('admin.booking.show', compact('booking'));
    }

    // ✅ METHOD CONFIRM - Update Booking, Kamar, Pembayaran, dan User Role
    public function confirm(Booking $booking)
    {
        DB::beginTransaction();
        try {
            // 1. Update Booking
            $booking->update(['status' => Booking::STATUS_CONFIRMED]);

            // 2. Update Kamar jadi Terisi
            if ($booking->kamar) {
                $booking->kamar->update(['status' => 'terisi']);
            }

            // 3. Update Pembayaran jadi Paid (Jika admin confirm manual, diasumsikan sudah bayar)
            if ($booking->pembayaran && $booking->pembayaran->status !== Pembayaran::STATUS_PAID) {
                $booking->pembayaran->update([
                    'status' => Pembayaran::STATUS_PAID,
                    'tanggal_bayar' => now(),
                    'metode_pembayaran' => 'manual_admin' // Tandai bahwa ini manual
                ]);
            }

            // 4. Update Role User jadi Penghuni
            $user = $booking->user;
            if ($user && $user->role === 'calon_penghuni') {
                $user->update(['role' => 'penghuni']);
            }

            DB::commit();

            return redirect()->route('admin.booking.index')
                ->with('success', 'Booking #' . $booking->id . ' berhasil dikonfirmasi. Status kamar, pembayaran, dan user telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin Confirm Booking Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengkonfirmasi booking: ' . $e->getMessage());
        }
    }

    //  METHOD CANCEL - Batalkan Booking dan Bebaskan Kamar
    public function cancel(Booking $booking)
    {
        DB::beginTransaction();
        try {
            // 1. Update Booking
            $booking->update(['status' => Booking::STATUS_CANCELLED]);

            // 2. Update Pembayaran (jika ada)
            if ($booking->pembayaran) {
                $booking->pembayaran->update(['status' => Pembayaran::STATUS_CANCELLED]);
            }

            // 3. Kembalikan Status Kamar jadi Tersedia
            if ($booking->kamar) {
                $booking->kamar->update(['status' => 'tersedia']);
            }

            DB::commit();

            return redirect()->route('admin.booking.index')
                ->with('success', 'Booking #' . $booking->id . ' berhasil dibatalkan. Kamar kembali tersedia.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membatalkan booking: ' . $e->getMessage());
        }
    }

    // METHOD CHECK-IN - Tamu Datang
    public function checkin(Booking $booking)
    {
        try {
            if ($booking->status !== Booking::STATUS_CONFIRMED) {
                return redirect()->back()->with('error', 'Hanya booking berstatus CONFIRMED yang bisa Check-In.');
            }

            $booking->update(['status' => 'checked_in']);
            
            // Pastikan kamar tetap terisi
            $booking->kamar->update(['status' => 'terisi']);

            return redirect()->route('admin.booking.index')
                ->with('success', 'Check-in berhasil. Penghuni telah menempati kamar.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal check-in: ' . $e->getMessage());
        }
    }

    // METHOD CHECK-OUT - Tamu Pulang (Kamar jadi kosong)
    public function checkout(Booking $booking)
    {
        DB::beginTransaction();
        try {
            // 1. Update status booking
            $booking->update(['status' => 'checked_out']);

            // 2. Kembalikan kamar jadi tersedia
            if ($booking->kamar) {
                $booking->kamar->update(['status' => 'tersedia']);
            }

            // 3. Opsional: Kembalikan role user jadi calon_penghuni jika tidak punya booking lain
            // (Logika ini opsional, tergantung kebijakan kos)
            $user = $booking->user;
            $hasOtherActiveBookings = Booking::where('user_id', $user->id)
                ->whereIn('status', [Booking::STATUS_CONFIRMED, 'checked_in'])
                ->where('id', '!=', $booking->id)
                ->exists();

            if (!$hasOtherActiveBookings && $user->role === 'penghuni') {
                $user->update(['role' => 'calon_penghuni']);
            }

            DB::commit();

            return redirect()->route('admin.booking.index')
                ->with('success', 'Check-out berhasil. Kamar #' . $booking->kamar->nomor_kamar . ' sekarang tersedia.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal check-out: ' . $e->getMessage());
        }
    }

    // ✅ METHOD DESTROY - Hapus Data Permanen
    public function destroy(Booking $booking)
    {
        try {
            // Validasi: Jangan hapus booking yang masih aktif
            $allowedStatuses = [Booking::STATUS_CANCELLED, Booking::STATUS_EXPIRED, 'checked_out'];
            
            if (!in_array($booking->status, $allowedStatuses)) {
                return redirect()->route('admin.booking.index')
                    ->with('error', 'Hanya booking yang Dibatalkan, Expired, atau Check-out yang boleh dihapus.');
            }

            // Hapus pembayaran terkait jika ada (Cascade delete biasanya sudah handle ini di database, tapi untuk aman)
            if ($booking->pembayaran) {
                $booking->pembayaran->delete();
            }

            $booking->delete();

            return redirect()->route('admin.booking.index')
                ->with('success', 'Data booking berhasil dihapus permanen.');

        } catch (\Exception $e) {
            return redirect()->route('admin.booking.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}