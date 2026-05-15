<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\EmailVerificationRequest; // WAJIB DITAMBAHKAN

class AuthController extends Controller
{
    // ==========================================
    // 1. LOGIKA LOGIN
    // ==========================================
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:255',
                'password' => 'required|string',
            ], [
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'password.required' => 'Password wajib diisi.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput($request->except('password'))
                    ->with('error', 'Validasi gagal. Silakan periksa kembali input Anda.');
            }

            $credentials = $request->only('email', 'password');
            
            // PAKSA FALSE: Sesi otomatis mati jika browser ditutup
            if (Auth::attempt($credentials, false)) {
                $user = Auth::user();
                $request->session()->regenerate();
                
                Log::info('Login successful', ['user_id' => $user->id, 'email' => $user->email]);

                // PENGALIHAN (REDIRECT) BERDASARKAN ROLE
                if ($user->isAdmin()) {
                    return redirect()->intended(route('admin.dashboard'))
                        ->with('success', 'Selamat datang, Admin!');
                }

                if ($user->role === 'penghuni' || $user->hasActiveBooking()) {
                    return redirect()->intended(route('user.dashboard'))
                        ->with('success', 'Selamat datang kembali di Dashboard Kos Anda!');
                }

                // Jika hanya Calon Penghuni
                return redirect()->intended(route('home'))
                    ->with('success', 'Login berhasil! Silakan cari kamar idaman Anda.');
            } else {
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

    // ==========================================
    // 2. LOGIKA REGISTRASI
    // ==========================================
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'required|string|max:15',
            'terms' => 'required|accepted',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
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
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role' => 'calon_penghuni',
                'is_active' => true,
            ]);

            // Memicu pengiriman Email Verifikasi (Otomatis dari Laravel)
            event(new Registered($user));

            // Redirect ke halaman login tanpa Auth::login()
            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Kami telah mengirimkan link verifikasi ke email Anda. Silakan verifikasi email Anda sebelum login.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    // ==========================================
    // 3. LOGIKA VERIFIKASI EMAIL
    // ==========================================
    
    // Menampilkan halaman peringatan untuk cek email
    public function verifyNotice()
    {
        return view('auth.verify-email');
    }

    // Memproses saat user mengklik tautan dari kotak masuk email
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill(); // Mengubah status email_verified_at di database
        
        return redirect('/')->with('success', 'Email berhasil diverifikasi! Anda sekarang memiliki akses penuh untuk memesan kamar.');
    }

    // Memproses klik tombol "Kirim Ulang Email"
    public function verifyResend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        
        return back()->with('message', 'Verification link sent!');
    }

    // ==========================================
    // 4. LOGIKA LOGOUT
    // ==========================================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Logout berhasil. Sampai jumpa lagi!');
    }

    // ==========================================
    // 5. LOGIKA LOGIN GOOGLE (SOCIALITE)
    // ==========================================
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'password' => Hash::make(Str::random(24)),
                    'role' => 'calon_penghuni',
                    'is_active' => true,
                    'email_verified_at' => now(), // Otomatis terverifikasi (dari Google)
                ]
            );

            Auth::login($user, false);

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Berhasil masuk via Google.');
            }

            if ($user->role === 'penghuni' || $user->hasActiveBooking()) {
                return redirect()->intended(route('user.dashboard'))->with('success', 'Berhasil masuk via Google.');
            }

            return redirect()->intended(route('home'))->with('success', 'Berhasil masuk via Google.');

        } catch (\Exception $e) {
            Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Gagal masuk menggunakan Google. Silakan coba lagi.');
        }
    }
}