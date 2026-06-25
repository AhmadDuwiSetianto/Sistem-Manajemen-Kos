<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Pembayaran;

class DashboardController extends Controller
{
    // 1. Halaman Utama Dashboard
    public function index()
    {
        return view('user.dashboard');
    }

    // 2. Halaman Riwayat Semua Booking
    public function bookings()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with('kamar')
            ->latest()
            ->paginate(10);
            
        return view('user.bookings.index', compact('bookings'));
    }

    // 3. Halaman Detail Satu Booking (INI FUNGSI YANG HILANG TADI)
    public function showBooking(string $id) // ✅ Penambahan tipe data 'string' pada parameter $id
    {
        $booking = Booking::where('user_id', Auth::id())
            ->with(['kamar', 'pembayaran'])
            ->findOrFail($id);
            
        return view('user.bookings.show', compact('booking'));
    }

    // 4. Halaman Riwayat Tagihan / Pembayaran
    public function pembayaran()
    {
        $payments = Pembayaran::where('user_id', Auth::id())
            ->with('booking.kamar')
            ->latest()
            ->paginate(10);
            
        return view('user.pembayaran.index', compact('payments'));
    }

    // 5. Halaman Profil User
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    // 6. Proses Update Profil
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */ // ✅ Membantu IDE mengenali bahwa ini adalah Model User
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'identity_number' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'identity_number' => $request->identity_number,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}