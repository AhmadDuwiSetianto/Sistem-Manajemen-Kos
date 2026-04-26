<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        return view('admin.user.create', compact('activeBooking'));
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

        $oldRole = $user->role; // Simpan status role lama sebelum diubah

        $data = $request->only(['name', 'email', 'role', 'phone', 'address', 'identity_number']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        DB::beginTransaction();
        try {
            $user->update($data);

            // LOGIKA BARU: Jika user diturunkan dari 'penghuni' menjadi 'calon_penghuni'
            // Kosongkan kamarnya secara otomatis!
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
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        $newStatus = !$user->is_active;

        DB::beginTransaction();
        try {
            // LOGIKA BARU: Jika user dinonaktifkan (status menjadi false)
            // Kosongkan kamarnya secara otomatis!
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
        if ($user->id === auth()->id()) {
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

    /**
     * FUNGSI HELPER BARU: Untuk melepaskan kamar dan menutup booking aktif
     */
    protected function releaseUserRoom(User $user)
    {
        $activeBooking = $user->getActiveBooking();

        if ($activeBooking) {
            // 1. Kembalikan kamar menjadi tersedia
            if ($activeBooking->kamar) {
                $activeBooking->kamar->update(['status' => 'tersedia']);
            }

            // 2. Tutup booking menjadi checked_out (agar histori tetap aman dan valid)
            $activeBooking->update(['status' => 'checked_out']);
        }
    }
}