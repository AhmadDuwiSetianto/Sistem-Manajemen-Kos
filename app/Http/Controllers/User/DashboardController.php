<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 🔥 SATPAM 1: Jika Admin mencoba masuk sini, tendang ke Admin Dashboard
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // --- Logika Dashboard User ---
        $activeBooking = Booking::with(['kamar', 'pembayaran'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->first();

        $bookingHistory = Booking::with(['kamar', 'pembayaran'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('user.dashboard', compact('activeBooking', 'bookingHistory'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
        ]);

        $user->update($request->only(['name', 'phone', 'address']));

        return redirect()->route('user.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
