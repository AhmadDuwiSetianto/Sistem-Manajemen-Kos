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
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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
                return $this->redirectBasedOnRole($user)->with('success', 'Selamat datang kembali!');
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

            event(new Registered($user));

            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Kami telah mengirimkan link verifikasi ke email Anda.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    // ==========================================
    // 3. LOGIKA VERIFIKASI EMAIL
    // ==========================================
    public function verifyNotice()
    {
        return view('auth.verify-email');
    }

    // ✅ DIPERBAIKI: Redirect menggunakan aturan role
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill(); 
        
        $user = $request->user();
        return $this->redirectBasedOnRole($user)->with('success', 'Email berhasil diverifikasi!');
    }

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

    // ✅ DIPERBAIKI: Redirect menggunakan aturan role
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'password' => Hash::make(Str::random(24)),
                    // Jangan timpa role jika user sudah ada (hanya set calon_penghuni jika user benar-benar baru)
                    'role' => User::where('email', $googleUser->email)->exists() ? User::where('email', $googleUser->email)->first()->role : 'calon_penghuni',
                    'is_active' => true,
                    'email_verified_at' => now(), 
                ]
            );

            Auth::login($user, false);

            return $this->redirectBasedOnRole($user)->with('success', 'Berhasil masuk via Google.');

        } catch (\Exception $e) {
            Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Gagal masuk menggunakan Google. Silakan coba lagi.');
        }
    }

    // ==========================================
    // 6. HELPER REDIRECT (FUNGSI PEMBANTU)
    // ==========================================
    // Fungsi ini dipanggil untuk memastikan setiap selesai Login/Verifikasi,
    // user diarahkan ke halaman yang benar sesuai jabatannya.
    
    private function redirectBasedOnRole(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->role === 'penghuni') {
            return redirect()->intended(route('user.dashboard'));
        }

        // Jika dia masih 'calon_penghuni' tapi ternyata punya pesanan aktif (bug role), lempar juga ke dashboard
        if ($user->hasActiveBooking()) {
            return redirect()->intended(route('user.dashboard'));
        }

        // Jika benar-benar baru mendaftar dan belum pesan kamar sama sekali
        return redirect()->intended(route('home'));
    }
}