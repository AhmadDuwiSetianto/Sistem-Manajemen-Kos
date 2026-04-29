<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

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

            // VALIDASI BACKEND: Mewajibkan checkbox 'remember' diisi
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:255',
                'password' => 'required|string',
                'remember' => 'required', // Wajib diisi (dicentang)
            ], [
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'password.required' => 'Password wajib diisi.',
                'remember.required' => 'Anda wajib menceklis "Ingat saya di perangkat ini" untuk melanjutkan login.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput($request->except('password'))
                    ->with('error', 'Validasi gagal. Pastikan Anda mencentang Ingat Saya.');
            }

            $credentials = $request->only('email', 'password');
            $remember = $request->filled('remember');

            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();
                $user = Auth::user();

                Log::info('Login successful', ['user_id' => $user->id, 'email' => $user->email]);

                if ($user->isAdmin()) {
                    return redirect()->intended(route('admin.dashboard'))
                        ->with('success', 'Selamat datang, Admin!');
                }

                return redirect()->intended(route('home'))
                    ->with('success', 'Login berhasil! Selamat datang.');
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

            // 1. Memicu pengiriman Email Verifikasi bawaan Laravel
            event(new Registered($user));

            // 2. Mengirim Notifikasi WhatsApp
            $this->sendWhatsAppWelcome($user);

            // KODE Auth::login($user) DIHAPUS AGAR TIDAK OTOMATIS LOGIN

            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan Login terlebih dahulu, lalu periksa email untuk verifikasi akun Anda.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Logout berhasil. Sampai jumpa lagi!');
    }

    // ==========================================
    // LOGIKA LOGIN GOOGLE (SOCIALITE)
    // ==========================================
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari user berdasarkan email, jika tidak ada, buat akun baru
            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'password' => Hash::make(Str::random(24)), // Beri password acak
                    'role' => 'calon_penghuni',
                    'is_active' => true,
                    'email_verified_at' => now(), // Otomatis terverifikasi karena dari Google
                ]
            );

            Auth::login($user, true);

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Berhasil masuk via Google.');
            }

            return redirect()->intended(route('home'))->with('success', 'Berhasil masuk via Google.');

        } catch (\Exception $e) {
            Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Gagal masuk menggunakan Google. Silakan coba lagi.');
        }
    }

    // ==========================================
    // FUNGSI HELPER WHATSAPP
    // ==========================================
    protected function sendWhatsAppWelcome(User $user)
    {
        if (!$user->phone) return;

        $pesan = "*SELAMAT DATANG DI INNA KOS*\n\n";
        $pesan .= "Halo {$user->name},\n";
        $pesan .= "Akun Anda berhasil didaftarkan. Kami telah mengirimkan tautan verifikasi ke email Anda ({$user->email}).\n\n";
        $pesan .= "Silakan lakukan verifikasi sebelum melakukan pemesanan kamar.\n\n";
        $pesan .= "Terima kasih!";

        try {
            Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN', 'A1mfS41ATJCcB923cAXn'),
            ])->post('https://api.fonnte.com/send', [
                'target' => $user->phone,
                'message' => $pesan,
                'countryCode' => '62',
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal kirim WA Welcome: ' . $e->getMessage());
        }
        
    }
}