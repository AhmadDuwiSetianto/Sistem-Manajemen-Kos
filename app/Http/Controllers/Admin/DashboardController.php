<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Jangan lupa import Auth

class DashboardController extends Controller
{
    public function index()
    {
        // 🔥 SATPAM 2: Jika User Biasa coba masuk sini, Tampilkan Error 403
        if (Auth::user()->role !== 'admin') {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES ADMIN!');
        }

        try {
            // Stats untuk Admin Dashboard
            $totalKamar = Kamar::count();
            $kamarTersedia = Kamar::where('status', 'tersedia')->count();
            $kamarTerisi = Kamar::where('status', 'terisi')->count();
            $kamarMaintenance = Kamar::where('status', 'maintenance')->count();
            
            $totalPenghuni = User::where('role', 'penghuni')->count();
            $totalCalonPenghuni = User::where('role', 'calon_penghuni')->count();
            $totalUsers = User::where('role', '!=', 'admin')->count();
            
            $totalBooking = Booking::count();
            $pendingBookingsCount = Booking::where('status', 'pending')->count();
            $confirmedBookingsCount = Booking::where('status', 'confirmed')->count();
            $checkedInBookingsCount = Booking::where('status', 'checked_in')->count();
            $pendingPaymentsCount = Pembayaran::where('status', 'pending')->count();

            // Data untuk tables
            $recentBookings = Booking::with(['user', 'kamar'])
                ->latest()
                ->take(5)
                ->get();

            $recentUsers = User::where('role', '!=', 'admin')
                ->latest()
                ->take(5)
                ->get();

            return view('admin.dashboard', compact(
                'totalKamar',
                'kamarTersedia',
                'kamarTerisi',
                'kamarMaintenance',
                'totalPenghuni',
                'totalCalonPenghuni',
                'totalUsers',
                'totalBooking',
                'pendingBookingsCount',
                'confirmedBookingsCount',
                'checkedInBookingsCount',
                'pendingPaymentsCount',
                'recentBookings',
                'recentUsers'
            ));

        } catch (\Exception $e) {
            // Fallback values jika ada error
            return view('admin.dashboard', [
                'totalKamar' => 0,
                'kamarTersedia' => 0,
                'kamarTerisi' => 0,
                'kamarMaintenance' => 0,
                'totalPenghuni' => 0,
                'totalCalonPenghuni' => 0,
                'totalUsers' => 0,
                'totalBooking' => 0,
                'pendingBookingsCount' => 0,
                'confirmedBookingsCount' => 0,
                'checkedInBookingsCount' => 0,
                'pendingPaymentsCount' => 0,
                'recentBookings' => collect(),
                'recentUsers' => collect(),
            ]);
        }
    }
}