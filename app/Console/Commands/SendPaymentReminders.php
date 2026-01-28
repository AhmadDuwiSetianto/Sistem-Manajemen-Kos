<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendPaymentReminders extends Command
{
    /**
     * Nama perintah untuk dijalankan di terminal.
     */
    protected $signature = 'app:send-payment-reminders';

    /**
     * Deskripsi perintah.
     */
    protected $description = 'Kirim notifikasi WA tagihan jatuh tempo H-1 dan H-2';

    /**
     * Eksekusi logika utama.
     */
    public function handle()
    {
        $this->info('Memulai proses pengiriman reminder...');

        // 1. Tentukan Tanggal H-1 (Besok) dan H-2 (Lusa)
        $besok = Carbon::now()->addDay()->format('Y-m-d');
        $lusa = Carbon::now()->addDays(2)->format('Y-m-d');

        // 2. Cari data pembayaran yang statusnya 'pending' & jatuh tempo besok/lusa
        $tagihan = Pembayaran::with(['user', 'booking.kamar'])
            ->where('status', 'pending')
            ->where(function($q) use ($besok, $lusa) {
                $q->whereDate('tanggal_jatuh_tempo', $besok)
                  ->orWhereDate('tanggal_jatuh_tempo', $lusa);
            })
            ->get();

        if ($tagihan->isEmpty()) {
            $this->info('Tidak ada tagihan yang jatuh tempo dalam 1-2 hari ke depan.');
            return;
        }

        $this->info("Ditemukan " . $tagihan->count() . " tagihan yang akan diingatkan.");

        // 3. Loop setiap tagihan dan kirim WA
        foreach ($tagihan as $bayar) {
            $user = $bayar->user;
            
            // Skip jika user tidak punya nomor HP
            if (!$user->phone) {
                $this->error("User {$user->name} tidak memiliki nomor HP. Skip.");
                continue;
            }

            // Ubah format nomor HP (08xx -> 628xx)
            $target = $this->formatPhoneNumber($user->phone);
            
            // Hitung sisa hari untuk pesan
            $jatuhTempo = Carbon::parse($bayar->tanggal_jatuh_tempo);
            
            // Isi Pesan WhatsApp
            $pesan = "*PENGINGAT PEMBAYARAN KOSTKU*\n\n";
            $pesan .= "Halo kak {$user->name}, 👋\n";
            $pesan .= "Kami mengingatkan bahwa tagihan sewa kamar Anda akan segera jatuh tempo.\n\n";
            $pesan .= "🏠 *Kamar:* {$bayar->booking->kamar->nomor_kamar}\n";
            $pesan .= "💰 *Total:* Rp " . number_format($bayar->jumlah, 0, ',', '.') . "\n";
            $pesan .= "📅 *Jatuh Tempo:* {$jatuhTempo->translatedFormat('d F Y')}\n";
            $pesan .= "📄 *Kode Tagihan:* {$bayar->kode_pembayaran}\n\n";
            $pesan .= "Mohon segera lakukan pembayaran melalui link berikut:\n";
            $pesan .= route('booking.payment', $bayar->id) . "\n\n";
            $pesan .= "Jika sudah membayar, mohon abaikan pesan ini. Terima kasih! 🙏";

            // Kirim ke Fonnte
            $this->sendWhatsapp($target, $pesan);
            
            $this->info("Pesan terkirim ke: {$user->name} ({$target})");
        }

        $this->info('Selesai mengirim semua reminder.');
    }

    /**
     * Helper: Ubah 08xx jadi 628xx agar diterima Fonnte
     */
    private function formatPhoneNumber($number)
    {
        // Hapus karakter selain angka
        $number = preg_replace('/[^0-9]/', '', $number);
        
        // Jika diawali 0, ganti dengan 62
        if (substr($number, 0, 1) == '0') {
            return '62' . substr($number, 1);
        }
        
        return $number;
    }

    /**
     * Helper: Kirim Request ke API Fonnte
     */
    private function sendWhatsapp($target, $message)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => '1WSmyxSvejeMphraycoZ', // ✅ Token Anda
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62', 
            ]);

            // Log response untuk debugging jika perlu
            // Log::info("WA Response: " . $response->body());
            
        } catch (\Exception $e) {
            Log::error("Gagal kirim WA ke $target: " . $e->getMessage());
            $this->error("Gagal kirim WA ke $target");
        }
    }
}