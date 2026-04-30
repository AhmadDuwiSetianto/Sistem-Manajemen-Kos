<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class PaymentNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $pembayaran;
    public $type;

    /**
     * @param $pembayaran
     * @param string $type ('success' atau 'expired')
     */
    public function __construct($pembayaran, $type)
    {
        $this->pembayaran = $pembayaran;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        // Menyimpan ke database dan memancarkan ke Reverb
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        $kamar = $this->pembayaran->booking->kamar->nomor_kamar ?? '-';
        
        if ($this->type === 'success') {
            $title = 'Pembayaran Berhasil';
            $message = "Tagihan Kamar {$kamar} telah lunas. Terima kasih!";
            $icon = 'check-circle';
            $color = 'success';
        } else {
            // Menangani status expired atau cancelled
            $title = 'Tagihan Dibatalkan';
            $message = "Pesanan/Perpanjangan Kamar {$kamar} telah dibatalkan atau kedaluwarsa.";
            $icon = 'x-circle';
            $color = 'error';
        }

        return [
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'color' => $color,
            'url' => route('user.pembayaran')
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => $this->toArray($notifiable)
        ]);
    }
}