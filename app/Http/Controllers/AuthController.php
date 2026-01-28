<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            Log::info('Login attempt', ['email' => $request->email]);

            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:255',
                'password' => 'required|string',
            ], [
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'password.required' => 'Password wajib diisi.',
            ]);

            if ($validator->fails()) {
                Log::warning('Login validation failed', $validator->errors()->toArray());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput($request->except('password'))
                    ->with('error', 'Validasi gagal. Periksa data Anda.');
            }

            $credentials = $request->only('email', 'password');
            $remember = $request->filled('remember');

            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();
                $user = Auth::user();

                Log::info('Login successful', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'role' => $user->role
                ]);

                if ($user->isAdmin()) {
                    return redirect()->intended(route('admin.dashboard'))
                        ->with('success', 'Selamat datang, Admin!');
                }

                return redirect()->intended(route('home'))
                    ->with('success', 'Login berhasil! Selamat datang.');
            } else {
                Log::warning('Login failed - invalid credentials', ['email' => $request->email]);
                return back()
                    ->withErrors(['email' => 'Email atau password salah.'])
                    ->withInput($request->except('password'))
                    ->with('error', 'Login gagal. Periksa email dan password Anda.');
            }
        } catch (\Exception $e) {
            Log::error('Login system error: ' . $e->getMessage());
            return back()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.')
                ->withInput($request->except('password'));
        }
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // 1. Validasi Sederhana (TANPA NIK & ALAMAT)
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'required|string|max:15',
            'terms' => 'required|accepted',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'terms.required' => 'Anda harus menyetujui syarat dan ketentuan.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Registrasi gagal. Periksa data Anda.');
        }

        try {
            // 2. Create User (NIK & Alamat dibiarkan NULL)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                // 'address' => NULL, (Akan diisi saat booking)
                // 'identity_number' => NULL, (Akan diisi saat booking)
                'role' => 'calon_penghuni',
                'is_active' => true,
            ]);

            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan login untuk melanjutkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            Log::info('User logout', ['user_id' => $user?->id]);

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')
                ->with('success', 'Logout berhasil. Sampai jumpa lagi!');
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return redirect('/')->with('info', 'Anda telah logout.');
        }
    }
}