<?php

namespace App\Services;

use App\Models\Pembayaran;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WhatsAppService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url');
        $this->apiKey = config('services.whatsapp.api_key');
    }

    /**
     * Kirim notifikasi booking created
     */
    public function sendBookingCreated(Pembayaran $pembayaran)
    {
        try {
            $booking = $pembayaran->booking;
            $user = $booking->user;
            $kamar = $booking->kamar;

            $message = "📋 *BOOKING BERHASIL DIBUAT*\n\n" .
                      "Halo {$user->name},\n\n" .
                      "Booking kamar kos Anda telah berhasil dibuat dengan detail:\n" .
                      "• Kamar: {$kamar->nomor_kamar}\n" .
                      "• Tipe: {$kamar->tipe_kamar_display}\n" .
                      "• Harga: {$kamar->harga_formatted}/bulan\n" .
                      "• Durasi: {$booking->durasi} bulan\n" .
                      "• Total: Rp " . number_format($pembayaran->jumlah, 0, ',', '.') . "\n\n" .
                      "Silakan selesaikan pembayaran dalam 24 jam.\n\n" .
                      "Terima kasih!";

            return $this->sendMessage($user->no_telepon, $message);

        } catch (\Exception $e) {
            Log::error('WhatsApp Booking Created Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim notifikasi pembayaran berhasil
     */
    public function sendPaymentSuccess(Pembayaran $pembayaran)
    {
        try {
            $booking = $pembayaran->booking;
            $user = $booking->user;
            $kamar = $booking->kamar;

            $message = "✅ *PEMBAYARAN BERHASIL*\n\n" .
                      "Halo {$user->name},\n\n" .
                      "Pembayaran untuk booking kamar kos telah berhasil:\n" .
                      "• Kamar: {$kamar->nomor_kamar}\n" .
                      "• Tipe: {$kamar->tipe_kamar_display}\n" .
                      "• Total: Rp " . number_format($pembayaran->jumlah, 0, ',', '.') . "\n" .
                      "• Metode: " . strtoupper($pembayaran->metode_pembayaran) . "\n" .
                      "• Tanggal: " . $pembayaran->tanggal_bayar->format('d/m/Y') . "\n\n" .
                      "Status booking Anda sekarang: *Dikonfirmasi*\n\n" .
                      "Terima kasih!";

            return $this->sendMessage($user->no_telepon, $message);

        } catch (\Exception $e) {
            Log::error('WhatsApp Payment Success Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim notifikasi pembayaran expired
     */
    public function sendPaymentExpired(Pembayaran $pembayaran)
    {
        try {
            $booking = $pembayaran->booking;
            $user = $booking->user;

            $message = "⏰ *PEMBAYARAN KADALUARSA*\n\n" .
                      "Halo {$user->name},\n\n" .
                      "Maaf, waktu pembayaran untuk booking Anda telah habis.\n" .
                      "Booking secara otomatis dibatalkan.\n\n" .
                      "Silakan melakukan booking ulang jika masih berminat.\n\n" .
                      "Terima kasih!";

            return $this->sendMessage($user->no_telepon, $message);

        } catch (\Exception $e) {
            Log::error('WhatsApp Payment Expired Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim notifikasi booking dibatalkan
     */
    public function sendBookingCancelled(Booking $booking)
    {
        try {
            $user = $booking->user;
            $kamar = $booking->kamar;

            $message = "❌ *BOOKING DIBATALKAN*\n\n" .
                      "Halo {$user->name},\n\n" .
                      "Booking kamar {$kamar->nomor_kamar} telah dibatalkan.\n\n" .
                      "Alasan: " . ($booking->alasan_pembatalan ?? 'Tidak disebutkan') . "\n\n" .
                      "Terima kasih!";

            return $this->sendMessage($user->no_telepon, $message);

        } catch (\Exception $e) {
            Log::error('WhatsApp Booking Cancelled Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim notifikasi reminder pembayaran booking
     */
    public function sendPaymentReminder(Pembayaran $pembayaran)
    {
        try {
            $booking = $pembayaran->booking;
            $user = $booking->user;
            $kamar = $booking->kamar;

            $message = "🔔 *REMINDER PEMBAYARAN BOOKING*\n\n" .
                      "Halo {$user->name},\n\n" .
                      "Ingatkan untuk menyelesaikan pembayaran booking:\n" .
                      "• Kamar: {$kamar->nomor_kamar}\n" .
                      "• Total: Rp " . number_format($pembayaran->jumlah, 0, ',', '.') . "\n\n" .
                      "Batas waktu: 24 jam sejak booking\n\n" .
                      "Terima kasih!";

            return $this->sendMessage($user->no_telepon, $message);

        } catch (\Exception $e) {
            Log::error('WhatsApp Payment Reminder Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * KIRIM NOTIFIKASI JATUH TEMPO BULANAN UNTUK PENGHUNI
     * Dipanggil ketika penghuni masuk bulan baru
     */
    public function sendMonthlyPaymentDueReminder(User $user, Kamar $kamar, $bulan, $tahun, $tanggalJatuhTempo)
    {
        try {
            $message = "💰 *TAGIHAN BULANAN KOS*\n\n" .
                      "Halo {$user->name},\n\n" .
                      "Berikut tagihan kos untuk bulan {$bulan} {$tahun}:\n" .
                      "• Kamar: {$kamar->nomor_kamar}\n" .
                      "• Tipe: {$kamar->tipe_kamar_display}\n" .
                      "• Jumlah: {$kamar->harga_formatted}\n" .
                      "• Jatuh Tempo: {$tanggalJatuhTempo}\n\n" .
                      "Silakan lakukan pembayaran sebelum tanggal jatuh tempo untuk menghindari denda.\n\n" .
                      "Metode Pembayaran:\n" .
                      "• Transfer Bank: BCA 123-456-7890 a.n. Management Kos\n" .
                      "• Cash: Ke admin kos\n\n" .
                      "Terima kasih!";

            return $this->sendMessage($user->no_telepon, $message);

        } catch (\Exception $e) {
            Log::error('WhatsApp Monthly Payment Due Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * NOTIFIKASI KETERLAMBATAN PEMBAYARAN
     * Dipanggil ketika penghuni melewati tanggal jatuh tempo
     */
    public function sendLatePaymentNotification(User $user, Kamar $kamar, $bulan, $tahun, $hariKeterlambatan, $denda = 0)
    {
        try {
            $dendaFormatted = $denda > 0 ? 'Rp ' . number_format($denda, 0, ',', '.') : 'Tidak ada denda';

            $message = "⚠️ *PEMBAYARAN TERTUNDA*\n\n" .
                      "Halo {$user->name},\n\n" .
                      "Pembayaran kos untuk bulan {$bulan} {$tahun} belum kami terima.\n" .
                      "• Kamar: {$kamar->nomor_kamar}\n" .
                      "• Tagihan: {$kamar->harga_formatted}\n" .
                      "• Keterlambatan: {$hariKeterlambatan} hari\n" .
                      "• Denda: {$dendaFormatted}\n\n" .
                      "Segera lakukan pembayaran untuk menghindari pemutusan layanan.\n\n" .
                      "Hubungi admin jika ada kendala.\n" .
                      "Terima kasih!";

            return $this->sendMessage($user->no_telepon, $message);

        } catch (\Exception $e) {
            Log::error('WhatsApp Late Payment Notification Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * NOTIFIKASI PEMBAYARAN BULANAN BERHASIL
     * Ketika penghuni membayar tagihan bulanan
     */
    public function sendMonthlyPaymentSuccess(User $user, Kamar $kamar, $bulan, $tahun, $jumlah, $metodePembayaran)
    {
        try {
            $message = "✅ *PEMBAYARAN BULANAN BERHASIL*\n\n" .
                      "Halo {$user->name},\n\n" .
                      "Pembayaran kos untuk bulan {$bulan} {$tahun} telah berhasil:\n" .
                      "• Kamar: {$kamar->nomor_kamar}\n" .
                      "• Periode: {$bulan} {$tahun}\n" .
                      "• Jumlah: Rp " . number_format($jumlah, 0, ',', '.') . "\n" .
                      "• Metode: " . strtoupper($metodePembayaran) . "\n" .
                      "• Tanggal: " . Carbon::now()->format('d/m/Y') . "\n\n" .
                      "Terima kasih telah membayar tepat waktu! 🎉\n\n" .
                      "Salam,\nManagement Kos";

            return $this->sendMessage($user->no_telepon, $message);

        } catch (\Exception $e) {
            Log::error('WhatsApp Monthly Payment Success Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * NOTIFIKASI PERINGATAN SEBELUM JATUH TEMPO
     * Dikirim 3 hari sebelum jatuh tempo
     */
    public function sendUpcomingDueDateReminder(User $user, Kamar $kamar, $bulan, $tahun, $tanggalJatuhTempo)
    {
        try {
            $message = "🔔 *PENGINGAT JATUH TEMPO*\n\n" .
                      "Halo {$user->name},\n\n" .
                      "Ini pengingat bahwa tagihan kos untuk bulan {$bulan} {$tahun} akan jatuh tempo pada:\n" .
                      "• Tanggal: {$tanggalJatuhTempo}\n" .
                      "• Kamar: {$kamar->nomor_kamar}\n" .
                      "• Jumlah: {$kamar->harga_formatted}\n\n" .
                      "Silakan siapkan pembayaran untuk menghindari keterlambatan.\n\n" .
                      "Terima kasih!";

            return $this->sendMessage($user->no_telepon, $message);

        } catch (\Exception $e) {
            Log::error('WhatsApp Upcoming Due Date Reminder Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * NOTIFIKASI TAHUN BARU / BULAN BARU
     */
    public function sendNewMonthGreeting(User $user, Kamar $kamar, $bulan, $tahun)
    {
        try {
            $message = "🎉 *SELAMAT BULAN BARU! {$bulan} {$tahun}*\n\n" .
                      "Halo {$user->name},\n\n" .
                      "Semoga bulan ini membawa kebahagiaan dan kesuksesan untuk Anda!\n\n" .
                      "Tagihan kos untuk bulan {$bulan} akan segera tersedia.\n" .
                      "• Kamar: {$kamar->nomor_kamar}\n" .
                      "• Jumlah: {$kamar->harga_formatted}\n\n" .
                      "Terima kasih telah menjadi bagian dari keluarga kos kami! ❤️";

            return $this->sendMessage($user->no_telepon, $message);

        } catch (\Exception $e) {
            Log::error('WhatsApp New Month Greeting Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Method utama untuk mengirim pesan
     */
    public function sendMessage($phoneNumber, $message)
    {
        try {
            // Format nomor telepon (hapus + dan spasi)
            $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
            
            // Jika nomor diawali dengan 0, ganti dengan 62
            if (substr($phoneNumber, 0, 1) === '0') {
                $phoneNumber = '62' . substr($phoneNumber, 1);
            }

            // Simulasi pengiriman (untuk development)
            if (app()->environment('local')) {
                Log::info("WHATSAPP MESSAGE TO {$phoneNumber}: " . $message);
                return true;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->post($this->apiUrl . '/messages', [
                'phone' => $phoneNumber,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully to ' . $phoneNumber);
                return true;
            } else {
                Log::error('WhatsApp API Error: ' . $response->body());
                return false;
            }

        } catch (\Exception $e) {
            Log::error('WhatsApp Service Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Cek status WhatsApp
     */
    public function checkStatus()
    {
        try {
            // Simulasi untuk development
            if (app()->environment('local')) {
                return true;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->apiUrl . '/status');

            return $response->successful();

        } catch (\Exception $e) {
            Log::error('WhatsApp Status Check Error: ' . $e->getMessage());
            return false;
        }
    }
}