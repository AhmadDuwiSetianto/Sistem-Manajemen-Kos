<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Pembayaran;
use App\Notifications\PaymentNotification; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Transaction;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->_configureMidtrans();
    }

    private function _configureMidtrans()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized', true);
        Config::$is3ds = config('services.midtrans.is_3ds', true);

        if (config('app.env') === 'local') {
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTPHEADER => [],
            ];
        }
    }

    // =========================================================================
    // 1. BUAT BOOKING BARU
    // =========================================================================
    public function create(Kamar $kamar)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login untuk memesan kamar.');
        }

        $user = Auth::user();

        if (!$user->isCalonPenghuni() && !$user->isAdmin()) {
            return redirect()->route('user.dashboard')->with('info', 'Anda sudah terdaftar sebagai penghuni.');
        }
        if ($user->hasActiveBooking()) {
            return redirect()->route('user.dashboard')->with('warning', 'Anda sudah memiliki sewa kamar yang aktif.');
        }

        $pendingBooking = $user->bookings()
            ->where('status', Booking::STATUS_PENDING)
            ->whereHas('pembayaran', function ($q) {
                $q->whereIn('status', [Pembayaran::STATUS_PENDING]);
            })->first();

        if ($pendingBooking && $pendingBooking->pembayaran) {
            return redirect()->route('booking.payment', $pendingBooking->pembayaran->id)
                ->with('info', 'Selesaikan pembayaran booking Anda sebelumnya.');
        }

        if (strtolower($kamar->status) !== 'tersedia') {
            return redirect()->route('kamar.index')->with('error', 'Maaf, kamar ini baru saja dipesan orang lain.');
        }

        return view('booking.create', compact('kamar'));
    }

    // =========================================================================
    // 2. SIMPAN DATA KE DATABASE
    // =========================================================================
    public function store(Request $request, Kamar $kamar)
    {
        $request->validate([
            'tanggal_masuk' => 'required|date|after_or_equal:today',
            'durasi' => 'required|integer|min:1|max:12',
        ]);

        if ($kamar->status !== 'tersedia') {
            return back()->with('error', 'Mohon maaf, kamar ini baru saja dipesan orang lain.');
        }

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $durasiBulan = (int) $request->durasi;
            $totalHarga = $kamar->harga * $durasiBulan;
            $tanggalKeluar = Carbon::parse($request->tanggal_masuk)->addMonths($durasiBulan);

            $booking = Booking::create([
                'user_id' => $user->id,
                'kamar_id' => $kamar->id,
                'tanggal_masuk' => $request->tanggal_masuk,
                'tanggal_keluar' => $tanggalKeluar,
                'durasi' => $durasiBulan,
                'total_harga' => $totalHarga,
                'status' => Booking::STATUS_PENDING,
            ]);

            $kodePembayaran = 'INV-KOS-' . time() . rand(100, 999);
            $pembayaran = Pembayaran::create([
                'user_id' => $user->id,
                'booking_id' => $booking->id,
                'kode_pembayaran' => $kodePembayaran,
                'jumlah' => $totalHarga,
                'status' => Pembayaran::STATUS_PENDING,
                'tanggal_jatuh_tempo' => Carbon::now()->addDays(3),
            ]);

            $snapToken = $this->generateSnapToken($pembayaran, $user, $kamar);
            $pembayaran->update(['snap_token' => $snapToken]);

            $kamar->update(['status' => 'booking']);

            DB::commit();

            return redirect()->route('booking.payment', $pembayaran->id)
                ->with('success', 'Pesanan berhasil dibuat, silakan lakukan pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // 3. HALAMAN PEMBAYARAN
    // =========================================================================
    public function payment($id)
    {
        $pembayaran = Pembayaran::with(['booking.kamar', 'user'])->findOrFail($id);

        if ($pembayaran->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        if ($pembayaran->status === Pembayaran::STATUS_PENDING) {
            $this->checkMidtransStatus($pembayaran);
            $pembayaran->refresh();
        }

        if ($pembayaran->status === Pembayaran::STATUS_PAID) {
            return redirect()->route('booking.receipt', $pembayaran->id);
        }

        if ($pembayaran->status === Pembayaran::STATUS_EXPIRED) {
            return redirect()->route('home')->with('error', 'Waktu pembayaran telah habis.');
        }

        if (empty($pembayaran->snap_token)) {
            $snapToken = $this->generateSnapToken($pembayaran, $pembayaran->user, $pembayaran->booking->kamar);
            $pembayaran->update(['snap_token' => $snapToken]);
        }

        return view('booking.payment', compact('pembayaran'));
    }

    // =========================================================================
    // 4. MANUAL CHECK STATUS
    // =========================================================================
    public function checkStatus($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $this->checkMidtransStatus($pembayaran);
        $pembayaran->refresh();

        if ($pembayaran->status === Pembayaran::STATUS_PAID) {
            return redirect()->route('booking.receipt', $pembayaran->id)
                ->with('success', 'Pembayaran Berhasil! Terima kasih.');
        }

        return redirect()->route('booking.payment', $pembayaran->id)
            ->with('info', 'Pembayaran sedang diproses.');
    }

    // =========================================================================
    // 5. HALAMAN STRUK
    // =========================================================================
    public function receipt($id)
    {
        $pembayaran = Pembayaran::with(['booking.kamar', 'user'])->findOrFail($id);

        if ($pembayaran->status !== Pembayaran::STATUS_PAID) {
            $this->checkMidtransStatus($pembayaran);
            $pembayaran->refresh();
        }

        return view('booking.receipt', compact('pembayaran'));
    }

    // =========================================================================
    // 6. LOGIKA INTI: UPDATE STATUS DB & PICU NOTIFIKASI REAL-TIME
    // =========================================================================
    private function checkMidtransStatus(Pembayaran $pembayaran)
    {
        $this->_configureMidtrans();

        try {
            $status = Transaction::status($pembayaran->kode_pembayaran);
            $transactionStatus = $status->transaction_status;

            $type = $status->payment_type;
            if ($type == 'bank_transfer') {
                if (isset($status->va_numbers) && !empty($status->va_numbers)) {
                    $bank = $status->va_numbers[0]->bank;
                    $type = $bank . '_va';
                } elseif (isset($status->permata_va_number)) {
                    $type = 'permata_va';
                }
            }

            $newStatus = null;
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                $newStatus = Pembayaran::STATUS_PAID;
            } else if ($transactionStatus == 'pending') {
                $newStatus = Pembayaran::STATUS_PENDING;
            } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $newStatus = Pembayaran::STATUS_EXPIRED;
            }

            if ($newStatus) {
                $this->updatePaymentToDB($pembayaran, $newStatus, $type);
            }
        } catch (\Exception $e) {
            Log::error("Midtrans Check Error: " . $e->getMessage());
        }
    }

    private function updatePaymentToDB(Pembayaran $pembayaran, $status, $method = null)
    {
        if ($pembayaran->status === $status) return;

        DB::transaction(function () use ($pembayaran, $status, $method) {
            $dataUpdate = [
                'status' => $status,
                'tanggal_bayar' => ($status == Pembayaran::STATUS_PAID) ? now() : null
            ];

            if (!empty($method)) {
                $dataUpdate['metode_pembayaran'] = $method;
            }

            $pembayaran->update($dataUpdate);

            $isPerpanjangan = str_contains($pembayaran->booking->catatan ?? '', 'Perpanjangan');

            if ($status == Pembayaran::STATUS_PAID) {
                $pembayaran->booking->update(['status' => Booking::STATUS_CONFIRMED]);
                $pembayaran->booking->kamar->update(['status' => 'terisi']);

                if ($pembayaran->user->role === 'calon_penghuni') {
                    $pembayaran->user->update(['role' => 'penghuni']);
                }

                // Notifikasi Email & Real-time Reverb
                $this->sendNotificationEmail($pembayaran, 'success');
                $pembayaran->user->notify(new PaymentNotification($pembayaran, 'success'));

            } else if ($status == Pembayaran::STATUS_EXPIRED || $status == 'cancelled') {
                $pembayaran->booking->update(['status' => Booking::STATUS_CANCELLED]);
                
                if (!$isPerpanjangan) {
                    $pembayaran->booking->kamar->update(['status' => 'tersedia']);
                }
                
                $user = $pembayaran->user;
                $hasActiveBooking = $user->bookings()->whereIn('status', [Booking::STATUS_CONFIRMED, Booking::STATUS_CHECKED_IN])->exists();
                
                if (!$hasActiveBooking && $user->role !== 'admin') {
                    $user->update(['role' => 'calon_penghuni']);
                }

                // Notifikasi Email & Real-time Reverb
                $this->sendNotificationEmail($pembayaran, 'expired');
                $pembayaran->user->notify(new PaymentNotification($pembayaran, 'expired'));
            }
        });
    }

    // =========================================================================
    // 7. FUNGSI PENGIRIMAN EMAIL (FOKUS UTAMA)
    // =========================================================================
    private function sendNotificationEmail(Pembayaran $pembayaran, $type)
    {
        $user = $pembayaran->user;
        $kamar = $pembayaran->booking->kamar;
        $isPerpanjangan = str_contains($pembayaran->booking->catatan ?? '', 'Perpanjangan');

        $subject = "";
        $htmlContent = "";

        if ($type === 'success') {
            $subject = "Pembayaran Berhasil - Inna Kos";
            $statusText = $isPerpanjangan ? "diperpanjang" : "dipesan";
            $htmlContent = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;'>
                    <h2 style='color: #16a34a;'>Pembayaran Berhasil! 🎉</h2>
                    <p>Halo <strong>{$user->name}</strong>, pembayaran kamar <strong>{$kamar->nomor_kamar}</strong> telah kami terima.</p>
                    <p>Status sewa Anda telah resmi {$statusText}.</p>
                    <div style='background-color: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                        <p><strong>No. Invoice:</strong> {$pembayaran->kode_pembayaran}</p>
                        <p><strong>Total:</strong> Rp " . number_format($pembayaran->jumlah, 0, ',', '.') . "</p>
                    </div>
                </div>";
        } else {
            $subject = $isPerpanjangan ? "Tagihan Perpanjangan Dibatalkan" : "Pesanan Dibatalkan";
            $htmlContent = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;'>
                    <h2 style='color: #dc2626;'>Pemberitahuan Pembatalan</h2>
                    <p>Halo <strong>{$user->name}</strong>, waktu pembayaran untuk Kamar <strong>{$kamar->nomor_kamar}</strong> telah habis atau dibatalkan.</p>
                    <p>Silakan lakukan pengajuan ulang jika Anda masih berminat.</p>
                </div>";
        }

        try {
            Mail::html($htmlContent, function($message) use ($user, $subject) {
                $message->to($user->email)->subject($subject);
            });
        } catch (\Exception $e) {
            Log::error("Gagal kirim Email: " . $e->getMessage());
        }
    }

    // =========================================================================
    // 8. GENERATE SNAP TOKEN & UTILS
    // =========================================================================
    private function generateSnapToken($pembayaran, $user, $kamar)
    {
        $this->_configureMidtrans();
        $params = [
            'transaction_details' => [
                'order_id' => $pembayaran->kode_pembayaran,
                'gross_amount' => (int) $pembayaran->jumlah,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [[
                'id' => $kamar->id,
                'price' => (int) $kamar->harga,
                'quantity' => (int) $pembayaran->booking->durasi,
                'name' => 'Sewa Kamar ' . $kamar->nomor_kamar,
            ]],
            'callbacks' => ['finish' => route('booking.check-status', $pembayaran->id)]
        ];
        return Snap::getSnapToken($params);
    }

    public function cancelPayment($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        try {
            $this->_configureMidtrans();
            Transaction::cancel($pembayaran->kode_pembayaran);
        } catch (\Exception $e) {}

        $this->updatePaymentToDB($pembayaran, 'cancelled');
        return redirect()->route('home')->with('success', 'Pemesanan dibatalkan.');
    }

    public function extendForm(Booking $booking)
    {
        if ($booking->user_id !== Auth::id() || !$booking->isActive()) {
            return redirect()->route('user.dashboard')->with('error', 'Tidak valid.');
        }
        return view('booking.extend', compact('booking'));
    }

    public function processExtend(Request $request, Booking $booking)
    {
        $request->validate(['durasi' => 'required|integer|min:1|max:12']);
        $durasiBulan = (int) $request->durasi; 

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $kamar = $booking->kamar;
            $tanggalMasukBaru = Carbon::parse($booking->tanggal_keluar);
            $totalHarga = $kamar->harga * $durasiBulan;

            $newBooking = Booking::create([
                'user_id' => $user->id,
                'kamar_id' => $kamar->id,
                'tanggal_masuk' => $tanggalMasukBaru,
                'tanggal_keluar' => (clone $tanggalMasukBaru)->addMonths($durasiBulan),
                'durasi' => $durasiBulan,
                'total_harga' => $totalHarga,
                'catatan' => 'Perpanjangan dari Booking #' . $booking->id,
                'status' => Booking::STATUS_PENDING,
            ]);

            $pembayaran = Pembayaran::create([
                'user_id' => $user->id,
                'booking_id' => $newBooking->id,
                'kode_pembayaran' => 'INV-EXT-' . time(),
                'jumlah' => $totalHarga,
                'status' => Pembayaran::STATUS_PENDING,
                'tanggal_jatuh_tempo' => Carbon::now()->addDays(3),
            ]);

            $pembayaran->update(['snap_token' => $this->generateSnapToken($pembayaran, $user, $kamar)]);
            DB::commit();

            return redirect()->route('booking.payment', $pembayaran->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal.');
        }
    }
}