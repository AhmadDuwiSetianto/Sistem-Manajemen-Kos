<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Services\WhatsAppService;
use Carbon\Carbon;

class SendMonthlyPaymentReminders extends Command
{
    protected $signature = 'reminders:monthly-payment';
    protected $description = 'Send monthly payment due reminders to tenants';

    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        parent::__construct();
        $this->whatsappService = $whatsappService;
    }

    public function handle()
    {
        $activeBookings = Booking::where('status', 'active')
            ->with(['user', 'kamar'])
            ->get();

        foreach ($activeBookings as $booking) {
            $bulan = Carbon::now()->translatedFormat('F');
            $tahun = Carbon::now()->year;
            $jatuhTempo = Carbon::now()->addDays(5)->format('d/m/Y'); // Jatuh tempo 5 hari dari sekarang

            $this->whatsappService->sendMonthlyPaymentDueReminder(
                $booking->user,
                $booking->kamar,
                $bulan,
                $tahun,
                $jatuhTempo
            );
        }

        $this->info('Monthly payment reminders sent successfully!');
    }
}