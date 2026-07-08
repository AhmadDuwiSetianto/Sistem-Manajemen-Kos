<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KamarController as AdminKamarController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;

use App\Http\Controllers\User\DashboardController as UserDashboardController;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Route;

// Wajib untuk verifikasi email
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// ====================================================
// PUBLIC ROUTES
// ====================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kamar', [KamarController::class, 'index'])->name('kamar.index');
Route::get('/kamar/{id}', [KamarController::class, 'show'])->name('kamar.show');

// ====================================================
// AUTHENTICATION ROUTES
// ====================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
    
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ====================================================
// EMAIL VERIFICATION ROUTES
// ====================================================
Route::middleware('auth')->group(function () {
    
    // 1. Menampilkan halaman peringatan verifikasi email
    Route::get('/email/verify', [AuthController::class, 'verifyNotice'])
        ->name('verification.notice');

    // 2. Memproses klik tautan dari email
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->middleware(['signed'])
        ->name('verification.verify');

    // 3. Mengirim ulang tautan verifikasi
    Route::post('/email/verification-notification', [AuthController::class, 'verifyResend'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');
});

// ====================================================
// BOOKING & PAYMENT ROUTES (USER WAJIB VERIFIKASI EMAIL)
// ====================================================
Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. CREATE BOOKING
    Route::get('/booking/{kamar}/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/{kamar}', [BookingController::class, 'store'])->name('booking.store');

    // 2. PAYMENT PAGES
    Route::get('/payment/{pembayaran}', [BookingController::class, 'payment'])->name('booking.payment');
    
    // Route Cek Status Manual
    Route::get('/booking/check-status/{id}', [BookingController::class, 'checkStatus'])->name('booking.check-status');
    Route::get('/payment/{id}/midtrans-direct', [BookingController::class, 'redirectToMidtrans'])->name('booking.midtrans-direct');
    
    // 3. RECEIPT / STRUK
    Route::get('/receipt/{pembayaran}', [BookingController::class, 'receipt'])->name('booking.receipt');
    Route::get('/receipt/{pembayaran}/print', [BookingController::class, 'printReceipt'])->name('booking.receipt.print');

    // 4. CANCEL & RETRY ACTIONS & EXTEND
    Route::post('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::get('/booking/{booking}/extend', [BookingController::class, 'extendForm'])->name('booking.extend');
    Route::post('/booking/{booking}/extend', [BookingController::class, 'processExtend'])->name('booking.process-extend');
    
    Route::get('/payment/{pembayaran}/retry', [BookingController::class, 'retryPayment'])->name('booking.retry-payment');
    Route::post('/payment/{pembayaran}/cancel', [BookingController::class, 'cancelPayment'])->name('payment.cancel');
});

// Payment Callback Midtrans (Wajib Public)
Route::post('/payment/callback', [BookingController::class, 'handlePaymentCallback'])->name('payment.callback');

// ====================================================
// USER DASHBOARD ROUTES (WAJIB VERIFIKASI EMAIL)
// ====================================================
Route::prefix('user')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('user.profile');
    Route::put('/profile', [UserDashboardController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('/bookings', [UserDashboardController::class, 'bookings'])->name('user.bookings');
    Route::get('/bookings/{id}', [UserDashboardController::class, 'showBooking'])->name('user.bookings.show');
    Route::get('/pembayaran', [UserDashboardController::class, 'pembayaran'])->name('user.pembayaran');
    Schedule::command('reminder:h2')->dailyAt('08:00');
});

// ====================================================
// ADMIN DASHBOARD ROUTES
// ====================================================
// PERBAIKAN: Middleware 'verified' dihapus dari grup Admin
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // CRUD Resource
    Route::resource('kamar', AdminKamarController::class)->names('admin.kamar');
    Route::resource('user', AdminUserController::class)->names('admin.user');
    Route::patch('/user/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('admin.user.toggle-status');
    Route::resource('booking', AdminBookingController::class)->names('admin.booking');
    Route::resource('pembayaran', AdminPembayaranController::class)->names('admin.pembayaran');

    // Profil Admin
    Route::get('/profile', [AdminDashboardController::class, 'profile'])->name('admin.profile');
    Route::put('/profile', [AdminDashboardController::class, 'updateProfile'])->name('admin.profile.update');
    Route::put('/profile/password', [AdminDashboardController::class, 'updatePassword'])->name('admin.profile.password');

    // Notifikasi Admin
    Route::get('/notifications', [AdminDashboardController::class, 'notifications'])->name('admin.notifications.index');
    Route::post('/notifications/mark-all-read', [AdminDashboardController::class, 'markAllRead'])->name('admin.notifications.markAllRead');
    Route::get('/api/notifications/latest', [AdminDashboardController::class, 'latestNotifications'])->name('admin.api.notifications.latest');

    // Laporan
    Route::get('/laporan/keuangan', [AdminLaporanController::class, 'keuangan'])->name('admin.laporan.keuangan');
    Route::get('/laporan/keuangan/export-pdf', [AdminLaporanController::class, 'exportPDF'])->name('admin.laporan.export-pdf');
    Route::get('/laporan/keuangan/export-excel', [AdminLaporanController::class, 'exportExcel'])->name('admin.laporan.export-excel');
    Route::get('/laporan/statistik', [AdminLaporanController::class, 'statistik'])->name('admin.laporan.statistik');
    
    // Pengaturan Sistem
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('admin.settings.index');
    Route::put('/settings', [AdminSettingController::class, 'update'])->name('admin.settings.update');
});

// ====================================================
// API ROUTES
// ====================================================
Route::prefix('api')->middleware(['auth'])->group(function () {
    Route::get('/check-booking-status/{pembayaran}', [BookingController::class, 'checkBookingStatus'])->name('api.booking.status');
});
// ====================================================
// DEBUGGING ROUTE (TAMBAHKAN INI DI PALING BAWAH)
// ====================================================
use Illuminate\Support\Facades\DB;

Route::get('/cek-db', function () {
    try {
        return 'Berhasil konek ke database bernama: ' . DB::connection()->getDatabaseName() . ' | Menggunakan driver: ' . DB::connection()->getDriverName();
    } catch (\Exception $e) {
        return 'Gagal konek: ' . $e->getMessage();
    }
});
Route::get('/cek-env', function () {
    $cloudinary = env('CLOUDINARY_URL');
    
    if ($cloudinary) {
        // Menampilkan 25 karakter pertama saja demi keamanan kredensial kamu
        return "<h3>Berhasil!</h3> Laravel di Vercel bisa membaca CLOUDINARY_URL dengan aman.<br>Potongan URL: <code>" . substr($cloudinary, 0, 25) . "...</code>";
    } else {
        return "<h3>GAGAL!</h3> Laravel di Vercel membaca CLOUDINARY_URL sebagai <strong>NULL</strong> (kosong).";
    }
});
Route::get('/clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return "<h3>Sapu Jagat Berhasil!</h3> Cache Laravel di Vercel sudah dibersihkan total.";
});