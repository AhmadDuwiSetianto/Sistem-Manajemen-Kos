<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

        $data = $request->only(['name', 'email', 'role', 'phone', 'address', 'identity_number']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.user.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        // Prevent deletion if user has active booking
        if ($user->hasActiveBooking()) {
            return redirect()->route('admin.user.index')
                ->with('error', 'Tidak dapat menghapus user yang memiliki booking aktif');
        }

        $user->delete();

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil dihapus');
    }
}