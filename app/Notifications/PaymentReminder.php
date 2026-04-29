<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentReminder extends Notification
{
    use Queueable;

    protected $pembayaran;

    public function __construct(Pembayaran $pembayaran)
    {
        $this->pembayaran = $pembayaran;
    }

    public function via(object $notifiable): array
    {
        // Menggunakan channel mail bawaan laravel
        // Untuk WA kita eksekusi terpisah di method pengiriman
        $this->sendWhatsApp($notifiable); 
        
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Pengingat: Tagihan Inna Kos Jatuh Tempo Hari Ini')
                    ->greeting('Halo ' . $notifiable->name . ',')
                    ->line('Ini adalah pengingat bahwa tagihan pemesanan kamar Anda sebesar ' . $this->pembayaran->jumlah_formatted . ' akan jatuh tempo hari ini.')
                    ->line('Batas waktu: ' . $this->pembayaran->tanggal_jatuh_tempo->format('d/m/Y H:i'))
                    ->line('Penting: Jika tidak dibayar hingga H+2, akun Anda akan dinonaktifkan secara otomatis.')
                    ->action('Bayar Sekarang', url('/user/pembayaran/' . $this->pembayaran->id))
                    ->line('Terima kasih telah memilih Inna Kos!');
    }

    // Fungsi custom untuk kirim WA (Contoh menggunakan API Fonnte)
    protected function sendWhatsApp($user)
    {
        if (!$user->phone) return;

        $pesan = "*PENGINGAT INNA KOS*\n\n";
        $pesan .= "Halo {$user->name},\n";
        $pesan .= "Tagihan kamar Anda sebesar *{$this->pembayaran->jumlah_formatted}* jatuh tempo *HARI INI*.\n\n";
        $pesan .= "Batas: {$this->pembayaran->tanggal_jatuh_tempo->format('d/m/Y H:i')}\n\n";
        $pesan .= "Mohon segera bayar agar akun Anda tidak dinonaktifkan otomatis. Terima kasih.";

        try {
            // Ganti URL dan Token sesuai provider WA Gateway Anda
            Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN', 'A1mfS41ATJCcB923cAXn'),
            ])->post('https://api.fonnte.com/send', [
                'target' => $user->phone,
                'message' => $pesan,
                'countryCode' => '62', // Kode negara Indonesia
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal kirim WA pengingat: ' . $e->getMessage());
        }
    }
}