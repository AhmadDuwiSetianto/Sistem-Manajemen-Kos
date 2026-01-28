<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kamar;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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

        // ✅ FIX PHP 8.2 & SSL BypasS
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
            return redirect()->route('kamar.index')->with('error', 'Maaf, kamar ini baru saja dipesan orang lain. Status: ' . $kamar->status);
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

            // Update Data User
            $user->update([
                'identity_number' => $request->identity_number,
                'address' => $request->address,
                'phone' => $request->phone ?? $user->phone
            ]);

            // Lock Kamar untuk mencegah Race Condition
            $kamarLocked = Kamar::where('id', $kamar->id)->lockForUpdate()->first();
            if ($kamarLocked->status !== 'tersedia') {
                throw new \Exception('Kamar sudah tidak tersedia.');
            }

            // Hitung Biaya
            $totalHarga = $kamar->harga * (int) $request->durasi;

            // Create Booking
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

            // Create Pembayaran
            $kodePembayaran = 'INV-' . time() . rand(100, 999);
            $pembayaran = Pembayaran::create([
                'user_id' => $user->id,
                'booking_id' => $booking->id,
                'kode_pembayaran' => $kodePembayaran,
                'jumlah' => $totalHarga,
                'status' => Pembayaran::STATUS_PENDING,
                'tanggal_jatuh_tempo' => Carbon::now()->addHours(24),
            ]);

            // Generate Snap Token
            $snapToken = $this->generateSnapToken($pembayaran, $user, $kamar);
            $pembayaran->update(['snap_token' => $snapToken]);

            DB::commit();

            // Redirect ke halaman pembayaran
            return redirect()->route('booking.payment', $pembayaran->id);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // 3. HALAMAN PEMBAYARAN (WAITING FOR PAYMENT)
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
    // 4. MANUAL CHECK STATUS (SOLUSI LOCALHOST)
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
    // 5. HALAMAN STRUK / SUKSES (RECEIPT)
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
    // 6. LOGIKA INTI: UPDATE STATUS DARI MIDTRANS (FIX EKSTRAKSI BANK)
    // =========================================================================
    private function checkMidtransStatus(Pembayaran $pembayaran)
    {
        $this->_configureMidtrans();

        try {
            $status = Transaction::status($pembayaran->kode_pembayaran);
            $transactionStatus = $status->transaction_status;

            // --- LOGIKA MENGAMBIL TIPE PEMBAYARAN SPESIFIK ---
            $type = $status->payment_type;

            if ($type == 'bank_transfer') {
                if (isset($status->va_numbers) && !empty($status->va_numbers)) {
                    $bank = $status->va_numbers[0]->bank;
                    $type = $bank . '_va'; // ✅ HASIL AKHIR YANG DIHARAPKAN
                } elseif (isset($status->permata_va_number)) {
                    $type = 'permata_va';
                }
            } elseif ($type == 'echannel') {
                $type = 'mandiri_bill';
            } elseif ($type == 'qris' || $type == 'gopay') {
                $type = $type; // qris atau gopay sudah spesifik
            }
            // ---------------------------------------------------

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

            // Simpan Tipe Pembayaran Spesifik ($type) ke database
            if ($newStatus) {
                $this->updatePaymentToDB($pembayaran, $newStatus, $type);
            }
        } catch (\Exception $e) {
            Log::error("Midtrans Check Error: " . $e->getMessage());
        }
    }

    // Fungsi Private untuk Update DB (Transaction Safe)
    private function updatePaymentToDB(Pembayaran $pembayaran, $status, $method = null)
    {
        DB::transaction(function () use ($pembayaran, $status, $method) {

            $dataUpdate = [
                'status' => $status,
                'tanggal_bayar' => ($status == Pembayaran::STATUS_PAID) ? now() : null
            ];

            // ✅ Ini yang memastikan nama bank tersimpan di DB
            if (!empty($method)) {
                $dataUpdate['metode_pembayaran'] = $method;
            }

            $pembayaran->update($dataUpdate);

            // Update Booking & Kamar & User
            if ($status == Pembayaran::STATUS_PAID) {
                $pembayaran->booking->update(['status' => Booking::STATUS_CONFIRMED]);
                $pembayaran->booking->kamar->update(['status' => 'terisi']);

                if ($pembayaran->user->role === 'calon_penghuni') {
                    $pembayaran->user->update(['role' => 'penghuni']);
                }
            } else if ($status == Pembayaran::STATUS_EXPIRED || $status == 'cancelled') {
                $pembayaran->booking->update(['status' => Booking::STATUS_CANCELLED]);
                $pembayaran->booking->kamar->update(['status' => 'tersedia']);
            }
        });
    }

    // =========================================================================
    // 7. GENERATE SNAP TOKEN
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
                'name' => 'Sewa Kos ' . $kamar->nomor_kamar . ' (' . $pembayaran->booking->durasi . ' Bln)',
            ]],
            'callbacks' => [
                'finish' => route('booking.check-status', $pembayaran->id),
            ]
        ];

        return Snap::getSnapToken($params);
    }

    // =========================================================================
    // 8. UTILS (RETRY & CANCEL)
    // =========================================================================
    public function retryPayment($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $newKode = 'INV-' . time() . rand(100, 999);

        $pembayaran->update([
            'kode_pembayaran' => $newKode,
            'status' => Pembayaran::STATUS_PENDING,
            'snap_token' => null
        ]);

        return redirect()->route('booking.payment', $pembayaran->id);
    }

    public function cancelPayment($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        try {
            $this->_configureMidtrans();
            Transaction::cancel($pembayaran->kode_pembayaran);
        } catch (\Exception $e) {
            // Abaikan error
        }

        $this->updatePaymentToDB($pembayaran, 'cancelled');

        return redirect()->route('home')->with('success', 'Pemesanan dibatalkan.');
    }
}
