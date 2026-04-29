<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail; // WAJIB DITAMBAHKAN UNTUK EMAIL
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
            'durasi' => 'required|integer|min:1|max:24',
            'tanggal_masuk' => 'required|date|after_or_equal:today',
            'identity_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();

            $user->update([
                'identity_number' => $request->identity_number,
                'address' => $request->address,
                'phone' => $request->phone ?? $user->phone
            ]);

            $kamarLocked = Kamar::where('id', $kamar->id)->lockForUpdate()->first();
            if ($kamarLocked->status !== 'tersedia') {
                throw new \Exception('Kamar sudah tidak tersedia.');
            }

            $totalHarga = $kamar->harga * (int) $request->durasi;

            $booking = Booking::create([
                'user_id' => $user->id,
                'kamar_id' => $kamar->id,
                'tanggal_masuk' => $request->tanggal_masuk,
                'tanggal_keluar' => Carbon::parse($request->tanggal_masuk)->addMonths((int)$request->durasi),
                'durasi' => $request->durasi,
                'total_harga' => $totalHarga,
                'catatan' => $request->catatan,
                'status' => Booking::STATUS_PENDING,
            ]);

            $kodePembayaran = 'INV-' . time() . rand(100, 999);
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

            DB::commit();

            return redirect()->route('booking.payment', $pembayaran->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
            return redirect()->route('home')->with('error', 'Waktu pembayaran telah habis. Akun Anda ditangguhkan.');
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

        if ($pembayaran->status === Pembayaran::STATUS_EXPIRED) {
            return redirect()->route('home')->with('error', 'Pembayaran kadaluwarsa.');
        }

        return redirect()->route('booking.payment', $pembayaran->id)
            ->with('info', 'Pembayaran sedang diproses. Silakan refresh halaman jika sudah transfer.');
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

            if ($pembayaran->status !== Pembayaran::STATUS_PAID) {
                return redirect()->route('booking.payment', $pembayaran->id)
                    ->with('warning', 'Pembayaran belum terkonfirmasi.');
            }
        }

        return view('booking.receipt', compact('pembayaran'));
    }

    // =========================================================================
    // 6. LOGIKA INTI: UPDATE STATUS DB & PICU NOTIFIKASI
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
            } elseif ($type == 'echannel') {
                $type = 'mandiri_bill';
            }

            $newStatus = null;
            if ($transactionStatus == 'capture') {
                $newStatus = ($status->fraud_status == 'challenge') ? Pembayaran::STATUS_PENDING : Pembayaran::STATUS_PAID;
            } else if ($transactionStatus == 'settlement') {
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
        // Cegah eksekusi berulang (jangan kirim email/WA 2x)
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

            // LOGIKA PEMBAYARAN SUKSES
            if ($status == Pembayaran::STATUS_PAID) {
                $pembayaran->booking->update(['status' => Booking::STATUS_CONFIRMED]);
                $pembayaran->booking->kamar->update(['status' => 'terisi']);

                if ($pembayaran->user->role === 'calon_penghuni') {
                    $pembayaran->user->update(['role' => 'penghuni']);
                }

                // Kirim Notifikasi EMAIL dan WA
                $this->sendNotificationEmail($pembayaran, 'success');
                $this->sendNotificationWA($pembayaran, 'success');
            } 
            // LOGIKA KADALUWARSA / GAGAL / AUTO-SUSPEND
            else if ($status == Pembayaran::STATUS_EXPIRED || $status == 'cancelled') {
                $pembayaran->booking->update(['status' => Booking::STATUS_CANCELLED]);
                $pembayaran->booking->kamar->update(['status' => 'tersedia']);
                $pembayaran->user->update(['is_active' => false]);

                // Kirim Notifikasi EMAIL dan WA
                $this->sendNotificationEmail($pembayaran, 'expired');
                $this->sendNotificationWA($pembayaran, 'expired');
            }
        });
    }

    // =========================================================================
    // 7. FUNGSI PENGIRIMAN EMAIL (BERHASIL & KADALUWARSA)
    // =========================================================================
    private function sendNotificationEmail(Pembayaran $pembayaran, $type)
    {
        $user = $pembayaran->user;
        $kamar = $pembayaran->booking->kamar;
        
        $subject = "";
        $htmlContent = "";

        if ($type === 'success') {
            $subject = "Pembayaran Berhasil - Inna Kos";
            $htmlContent = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;'>
                    <h2 style='color: #16a34a;'>Pembayaran Berhasil! 🎉</h2>
                    <p>Halo <strong>{$user->name}</strong>,</p>
                    <p>Terima kasih, pembayaran untuk pesanan kamar kos Anda telah kami terima.</p>
                    <div style='background-color: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                        <p style='margin: 5px 0;'><strong>No. Invoice:</strong> {$pembayaran->kode_pembayaran}</p>
                        <p style='margin: 5px 0;'><strong>Kamar:</strong> {$kamar->nomor_kamar}</p>
                        <p style='margin: 5px 0;'><strong>Durasi:</strong> {$pembayaran->booking->durasi} Bulan</p>
                        <p style='margin: 5px 0;'><strong>Total:</strong> Rp " . number_format($pembayaran->jumlah, 0, ',', '.') . "</p>
                    </div>
                    <p>Sekarang Anda resmi menjadi penghuni Inna Kos. Anda dapat melihat struk pembayaran di dashboard akun Anda.</p>
                </div>
            ";
        } else if ($type === 'expired') {
            $subject = "Peringatan: Akun Dinonaktifkan - Inna Kos";
            $htmlContent = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 10px;'>
                    <h2 style='color: #dc2626;'>Waktu Pembayaran Habis</h2>
                    <p>Halo <strong>{$user->name}</strong>,</p>
                    <p>Sayang sekali, waktu untuk melakukan pembayaran pesanan <strong>Kamar {$kamar->nomor_kamar}</strong> telah berakhir (jatuh tempo).</p>
                    <p>Sesuai dengan kebijakan Inna Kos, pesanan Anda telah dibatalkan secara otomatis dan status <strong>akun Anda saat ini telah dinonaktifkan</strong>.</p>
                    <p>Silakan hubungi Admin jika Anda ingin mengaktifkan kembali akun Anda.</p>
                </div>
            ";
        }

        try {
            Mail::html($htmlContent, function($message) use ($user, $subject) {
                $message->to($user->email)
                        ->subject($subject);
            });
        } catch (\Exception $e) {
            Log::error("Gagal kirim Email Notification ($type): " . $e->getMessage());
        }
    }

    // =========================================================================
    // 8. FUNGSI PENGIRIMAN WA (BERHASIL & KADALUWARSA)
    // =========================================================================
    private function sendNotificationWA(Pembayaran $pembayaran, $type)
    {
        $user = $pembayaran->user;
        $kamar = $pembayaran->booking->kamar;

        if (!$user->phone) return;

        $pesan = "";
        if ($type === 'success') {
            $pesan = "*PEMBAYARAN BERHASIL - INNA KOS*\n\n";
            $pesan .= "Halo {$user->name},\n";
            $pesan .= "Pembayaran sewa Kamar {$kamar->nomor_kamar} sebesar Rp " . number_format($pembayaran->jumlah, 0, ',', '.') . " telah lunas.\n\n";
            $pesan .= "Terima kasih telah bergabung menjadi penghuni Inna Kos!";
        } else if ($type === 'expired') {
            $pesan = "*PEMBATALAN OTOMATIS - INNA KOS*\n\n";
            $pesan .= "Halo {$user->name},\n";
            $pesan .= "Waktu pembayaran Kamar {$kamar->nomor_kamar} telah habis (jatuh tempo).\n\n";
            $pesan .= "Pesanan Anda telah dibatalkan dan akun Anda dinonaktifkan. Hubungi Admin untuk info lebih lanjut.";
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', [
                'target' => $user->phone,
                'message' => $pesan,
                'countryCode' => '62', // Otomatis ubah 08 jadi 628
            ]);

            // Tambahkan log untuk melihat respon Fonnte jika WA tidak masuk
            if (!$response->successful()) {
                Log::error('Fonnte API Error: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error("Gagal kirim WA Notification ($type): " . $e->getMessage());
        }
    }

    // =========================================================================
    // 9. GENERATE SNAP TOKEN
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
                'phone' => $user->phone ?? '08123456789',
            ],
            'item_details' => [[
                'id' => $kamar->id,
                'price' => (int) $kamar->harga,
                'quantity' => (int) $pembayaran->booking->durasi,
                'name' => 'Sewa Kamar ' . $kamar->nomor_kamar,
            ]],
            'expiry' => [
                'start_time' => Carbon::now()->format('Y-m-d H:i:s O'),
                'unit' => 'day',
                'duration' => 3
            ],
            'callbacks' => [
                'finish' => route('booking.check-status', $pembayaran->id),
            ]
        ];

        return Snap::getSnapToken($params);
    }

    // =========================================================================
    // 10. UTILS
    // =========================================================================
    public function retryPayment($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $newKode = 'INV-' . time() . rand(100, 999);
        $pembayaran->update([
            'kode_pembayaran' => $newKode,
            'status' => Pembayaran::STATUS_PENDING,
            'snap_token' => null,
            'tanggal_jatuh_tempo' => Carbon::now()->addDays(3)
        ]);

        return redirect()->route('booking.payment', $pembayaran->id);
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
}