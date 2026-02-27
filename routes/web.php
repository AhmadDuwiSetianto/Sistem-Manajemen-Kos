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
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Kamar Routes
Route::get('/kamar', [KamarController::class, 'index'])->name('kamar.index');
Route::get('/kamar/{id}', [KamarController::class, 'show'])->name('kamar.show');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ====================================================
// BOOKING & PAYMENT ROUTES (User)
// ====================================================
Route::middleware(['auth'])->group(function () {
    
    // 1. CREATE BOOKING
    Route::get('/booking/{kamar}/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/{kamar}', [BookingController::class, 'store'])->name('booking.store');

    // 2. PAYMENT PAGES
    Route::get('/payment/{pembayaran}', [BookingController::class, 'payment'])->name('booking.payment');
    
    // ✅ BARU: Route Cek Status Manual (Wajib ada untuk solusi localhost)
    Route::get('/booking/check-status/{id}', [BookingController::class, 'checkStatus'])->name('booking.check-status');
    
    Route::get('/payment/{id}/midtrans-direct', [BookingController::class, 'redirectToMidtrans'])->name('booking.midtrans-direct');
    
    // 3. RECEIPT / STRUK
    Route::get('/receipt/{pembayaran}', [BookingController::class, 'receipt'])->name('booking.receipt');
    Route::get('/receipt/{pembayaran}/print', [BookingController::class, 'printReceipt'])->name('booking.receipt.print');

    // 4. CANCEL & RETRY ACTIONS
    Route::post('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::get('/payment/{pembayaran}/retry', [BookingController::class, 'retryPayment'])->name('booking.retry-payment');
    Route::post('/payment/{pembayaran}/cancel', [BookingController::class, 'cancelPayment'])->name('payment.cancel');
});

// Payment Callback (WAJIB PUBLIC / DI LUAR AUTH)
Route::post('/payment/callback', [BookingController::class, 'handlePaymentCallback'])->name('payment.callback');

// ====================================================
// DASHBOARD ROUTES
// ====================================================

// User Dashboard
Route::prefix('user')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('user.profile');
    Route::put('/profile', [UserDashboardController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('/bookings', [UserDashboardController::class, 'bookings'])->name('user.bookings');
    Route::get('/pembayaran', [UserDashboardController::class, 'pembayaran'])->name('user.pembayaran');
});

// Admin Dashboard
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('kamar', AdminKamarController::class)->names('admin.kamar');
    Route::resource('user', AdminUserController::class)->names('admin.user');
    Route::resource('booking', AdminBookingController::class)->names('admin.booking');
    Route::resource('pembayaran', AdminPembayaranController::class)->names('admin.pembayaran');

    Route::get('/laporan/keuangan', [AdminLaporanController::class, 'keuangan'])->name('admin.laporan.keuangan');
    Route::get('/laporan/keuangan/export-pdf', [AdminLaporanController::class, 'exportPDF'])->name('admin.laporan.export-pdf');
    Route::get('/laporan/keuangan/export-excel', [AdminLaporanController::class, 'exportExcel'])->name('admin.laporan.export-excel');

    Route::get('/laporan/statistik', [AdminLaporanController::class, 'statistik'])->name('admin.laporan.statistik');
    
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('admin.settings.index');
    Route::put('/settings', [AdminSettingController::class, 'update'])->name('admin.settings.update');
});

// API Routes
Route::prefix('api')->middleware(['auth'])->group(function () {
    Route::get('/check-booking-status/{pembayaran}', [BookingController::class, 'checkBookingStatus'])->name('api.booking.status');
});