<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // ✅ Wajib ditambahkan untuk fungsi Hash Password

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

    // ==========================================
    // FUNGSI PROFIL
    // ==========================================

    public function profile()
    {
        return view('admin.profile'); 
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            // tambahkan validasi lain jika ada input tambahan (misal no_hp)
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    // ✅ FUNGSI INI YANG TADI BIKIN ERROR (KARENA BELUM ADA)
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'], 
            'password' => ['required', 'min:8', 'confirmed'], 
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password baru minimal harus 8 karakter.'
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }

    // ==========================================
    // FUNGSI NOTIFIKASI
    // ==========================================

    public function notifications()
    {
        $notifications = Auth::user()->notifications()->paginate(10);
        return view('admin.notifications.index', compact('notifications')); 
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    public function latestNotifications()
    {
        $user = Auth::user();
        $unreadCount = $user->unreadNotifications->count();
        
        $html = '';
        if($unreadCount > 0) {
            foreach($user->unreadNotifications->take(5) as $notification) {
                $typeClass = (isset($notification->data['type']) && $notification->data['type'] == 'payment') ? 'bg-success-light' : 'bg-primary/10';
                $iconClass = (isset($notification->data['type']) && $notification->data['type'] == 'payment') ? 'text-success' : 'text-primary';
                $icon = $notification->data['icon'] ?? 'bell';
                $title = $notification->data['title'] ?? 'Notifikasi Baru';
                $message = $notification->data['message'] ?? 'Ada pembaruan sistem.';
                $time = $notification->created_at->diffForHumans();
                $url = $notification->data['url'] ?? '#';

                $html .= '
                <a href="'.$url.'" class="flex gap-3 p-4 hover:bg-muted/50 border-b border-border transition-colors bg-primary/5">
                    <div class="size-9 rounded-full '.$typeClass.' flex items-center justify-center shrink-0">
                        <i data-lucide="'.$icon.'" class="size-4 '.$iconClass.'"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-foreground">'.$title.'</p>
                        <p class="text-xs text-secondary mt-0.5">'.$message.'</p>
                        <p class="text-[10px] text-secondary mt-1">'.$time.'</p>
                    </div>
                </a>';
            }
        } else {
            $html = '
            <div class="p-6 flex flex-col items-center justify-center text-center">
                <i data-lucide="bell-off" class="size-8 text-secondary/50 mb-2"></i>
                <p class="text-sm font-medium text-secondary">Belum ada notifikasi</p>
            </div>';
        }

        return response()->json([
            'unread_count' => $unreadCount,
            'html' => $html
        ]);
    }
}