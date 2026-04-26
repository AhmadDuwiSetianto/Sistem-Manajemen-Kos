<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pembayaran;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\PaymentReminder;

class CheckPaymentStatus extends Command
{
    protected $signature = 'payment:check';
    protected $description = 'Cek pembayaran jatuh tempo (H+2 batal otomatis & kirim pengingat)';

    public function handle()
    {
        $now = Carbon::now();

        // ==========================================
        // 1. LOGIKA H+2: BATAL, KAMAR KOSONG, USER NONAKTIF
        // ==========================================
        $overduePayments = Pembayaran::with(['booking.kamar', 'booking.user'])
            ->where('status', Pembayaran::STATUS_PENDING)
            ->where('tanggal_jatuh_tempo', '<=', $now->copy()->subDays(2)) // Lewat 2 hari (H+2)
            ->get();

        foreach ($overduePayments as $payment) {
            DB::beginTransaction();
            try {
                // Expire pembayaran
                $payment->update(['status' => Pembayaran::STATUS_EXPIRED]);

                if ($booking = $payment->booking) {
                    // Cancel Booking
                    $booking->update(['status' => Booking::STATUS_CANCELLED]);

                    // Kamar kembali tersedia
                    if ($booking->kamar) {
                        $booking->kamar->update(['status' => 'tersedia']);
                    }

                    // Nonaktifkan User
                    if ($user = $booking->user) {
                        $user->update(['is_active' => false]);
                    }
                }
                DB::commit();
                $this->info("Booking ID {$booking->id} dibatalkan. User nonaktif.");
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Gagal memproses H+2 expired: " . $e->getMessage());
            }
        }

        // ==========================================
        // 2. LOGIKA H-0 (HARI H): KIRIM NOTIFIKASI PENGINGAT
        // ==========================================
        $dueTodayPayments = Pembayaran::with(['booking.user'])
            ->where('status', Pembayaran::STATUS_PENDING)
            ->whereDate('tanggal_jatuh_tempo', $now->toDateString()) // Jatuh tempo hari ini
            ->get();

        foreach ($dueTodayPayments as $payment) {
            if ($user = $payment->booking->user) {
                // Kirim notifikasi via class Notification
                $user->notify(new PaymentReminder($payment));
                $this->info("Pengingat dikirim ke {$user->email}");
            }
        }

        $this->info('Pengecekan pembayaran selesai.');
    }
}