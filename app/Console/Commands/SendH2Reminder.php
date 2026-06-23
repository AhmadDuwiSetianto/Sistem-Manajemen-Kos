<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendH2Reminder extends Command
{
    protected $signature = 'reminder:h2';
    protected $description = 'Kirim email peringatan perpanjangan H-2 sebelum jatuh tempo';

    public function handle()
    {
        // Cari booking yang jatuh temponya persis 2 hari lagi
        $targetDate = Carbon::now()->addDays(2)->toDateString();

        $bookings = Booking::whereIn('status', ['confirmed', 'checked_in'])
            ->whereDate('tanggal_keluar', $targetDate)
            ->with(['user', 'kamar'])
            ->get();

        foreach ($bookings as $booking) {
            $user = $booking->user;
            $kamar = $booking->kamar;

            // HTML Email sederhana
            $htmlContent = "
                <div style='font-family: Arial, sans-serif; padding: 20px;'>
                    <h2 style='color: #d97706;'>Peringatan H-2 Perpanjangan Sewa! ⏰</h2>
                    <p>Halo <strong>{$user->name}</strong>,</p>
                    <p>Ini adalah pengingat otomatis bahwa masa sewa <strong>Kamar {$kamar->nomor_kamar}</strong> Anda akan jatuh tempo pada <strong>" . Carbon::parse($booking->tanggal_keluar)->format('d M Y') . "</strong>.</p>
                    <p>Silakan segera lakukan perpanjangan sewa melalui dashboard Anda agar kamar tidak dialihkan ke orang lain.</p>
                </div>";

            Mail::html($htmlContent, function($message) use ($user) {
                $message->to($user->email)->subject("Peringatan Jatuh Tempo Kos H-2");
            });

            $this->info("Email terkirim ke: {$user->email}");
        }
    }
}