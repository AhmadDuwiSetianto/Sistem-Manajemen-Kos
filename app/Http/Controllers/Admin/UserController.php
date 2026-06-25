<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Kamar;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $activeBooking = Booking::whereIn('status', ['confirmed', 'checked_in'])->count();
        $users = User::with(['bookings.kamar'])
            ->latest()
            ->paginate(10);

        $totalUsers = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $penghuniCount = User::where('role', 'penghuni')->count();
        $calonPenghuniCount = User::where('role', 'calon_penghuni')->count();

        return view('admin.user.index', compact(
            'users',
            'totalUsers',
            'adminCount',
            'penghuniCount',
            'calonPenghuniCount',
            'activeBooking'
        ));
    }

    public function show(User $user)
    {
        $activeBooking = Booking::whereIn('status', ['confirmed', 'checked_in'])->count();
        $user->load(['bookings.kamar']);
        return view('admin.user.show', compact('user', 'activeBooking'));
    }

    public function create()
    {
        $activeBooking = Booking::whereIn('status', ['confirmed', 'checked_in'])->count();
        $kamarTersedia = Kamar::where('status', 'tersedia')->get();

        return view('admin.user.create', compact('activeBooking', 'kamarTersedia'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,penghuni,calon_penghuni',
            'phone' => 'nullable|string|max:20',
            'identity_number' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            // Validasi kondisional khusus alokasi kamar
            'kamar_id' => 'required_if:is_assign_room,1|nullable|exists:kamar,id',
            'tanggal_bergabung' => 'required_if:is_assign_room,1|nullable|date',
            'durasi' => 'required_if:is_assign_room,1|nullable|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan Akun User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->has('is_assign_room') ? 'penghuni' : $request->role,
                'phone' => $request->phone,
                'identity_number' => $request->identity_number,
                'address' => $request->address,
                'is_active' => true,
            ]);

            // 2. Logika Alokasi Kamar untuk Penghuni Lama
            if ($request->has('is_assign_room') && $request->filled('kamar_id')) {
                $kamar = Kamar::find($request->kamar_id);
                $durasi = (int) $request->durasi;
                $totalHarga = $kamar->harga * $durasi;
                $tanggalKeluar = Carbon::parse($request->tanggal_bergabung)->addMonths($durasi);

                $booking = Booking::create([
                    'user_id' => $user->id,
                    'kamar_id' => $kamar->id,
                    'tanggal_masuk' => $request->tanggal_bergabung,
                    'tanggal_keluar' => $tanggalKeluar,
                    'durasi' => $durasi,
                    'total_harga' => $totalHarga,
                    'status' => 'confirmed',
                    'catatan' => 'Didaftarkan manual oleh Admin (Data Penghuni Lama Kos)',
                ]);

                Pembayaran::create([
                    'user_id' => $user->id,
                    'booking_id' => $booking->id,
                    'kode_pembayaran' => 'INV-MANUAL-' . time() . rand(100, 999),
                    'jumlah' => $totalHarga,
                    'status' => $request->status_pembayaran_awal ?? 'paid',
                    'metode_pembayaran' => 'Manual Admin',
                    'tanggal_bayar' => ($request->status_pembayaran_awal == 'paid') ? now() : null,
                    'tanggal_jatuh_tempo' => Carbon::now()->addDays(3),
                ]);

                $kamar->update(['status' => 'terisi']);
            }

            DB::commit();
            return redirect()->route('admin.user.index')->with('success', 'User dan alokasi kamar berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store User Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memproses tambah user: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        $activeBooking = Booking::whereIn('status', ['confirmed', 'checked_in'])->count();
        return view('admin.user.edit', compact('user', 'activeBooking'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|in:admin,penghuni,calon_penghuni',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'identity_number' => 'nullable|string|max:50'
        ]);

        $oldRole = $user->role;

        $data = $request->only(['name', 'email', 'role', 'phone', 'address', 'identity_number']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        DB::beginTransaction();
        try {
            $user->update($data);

            if ($oldRole === 'penghuni' && $request->role === 'calon_penghuni') {
                $this->releaseUserRoom($user);
            }

            DB::commit();
            return redirect()->route('admin.user.index')
                ->with('success', 'User berhasil diupdate dan status kamar telah disinkronkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update User Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate user.');
        }
    }

    public function toggleStatus(User $user)
    {
        // ✅ Menggunakan Auth::id() agar terbaca sempurna oleh IDE
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        $newStatus = !$user->is_active;

        DB::beginTransaction();
        try {
            if ($newStatus === false) {
                $this->releaseUserRoom($user);
            }

            $user->update(['is_active' => $newStatus]);

            DB::commit();
            $statusStr = $newStatus ? 'diaktifkan' : 'dinonaktifkan dan kamar dikosongkan';

            return redirect()->back()
                ->with('success', "Akun user {$user->name} berhasil {$statusStr}.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Toggle Status Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengubah status user.');
        }
    }

    public function destroy(User $user)
    {
        // ✅ Menggunakan Auth::id() agar terbaca sempurna oleh IDE
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.user.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        if ($user->hasActiveBooking()) {
            return redirect()->route('admin.user.index')
                ->with('error', 'Tidak dapat menghapus user yang memiliki booking aktif. Nonaktifkan terlebih dahulu.');
        }

        $user->delete();

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil dihapus permanen');
    }

    protected function releaseUserRoom(User $user)
    {
        $activeBooking = $user->getActiveBooking();

        if ($activeBooking) {
            if ($activeBooking->kamar) {
                $activeBooking->kamar->update(['status' => 'tersedia']);
            }
            $activeBooking->update(['status' => 'checked_out']);
        }
    }
}