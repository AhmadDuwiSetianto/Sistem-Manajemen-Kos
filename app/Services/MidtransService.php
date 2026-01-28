<?php

namespace App\Services;

use App\Models\Pembayaran;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MidtransService
{
    public function __construct()
    {
        $this->setupMidtrans();
    }

    private function setupMidtrans()
    {
        // Pastikan config diambil dengan benar
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        
        // Default ke false (sandbox) jika config tidak ada
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = config('services.midtrans.is_sanitized', true);
        Config::$is3ds = config('services.midtrans.is_3ds', true);

        // 🔴 TAMBAHKAN KODE INI UNTUK MEMPERBAIKI ERROR CURL SSL
        // Ini akan mematikan verifikasi SSL (Hanya untuk Localhost/Dev)
        // Config::$curlOptions = [
        //     CURLOPT_SSL_VERIFYPEER => false,
        //     CURLOPT_SSL_VERIFYHOST => 0
        // ];
    }

    public function prepareTransactionData(Pembayaran $pembayaran)
    {
        try {
            $booking = $pembayaran->booking;
            $kamar = $booking->kamar;
            $user = $pembayaran->user;

            $transactionDetails = [
                'order_id' => $pembayaran->kode_pembayaran,
                'gross_amount' => (int) $pembayaran->jumlah, // Pastikan INT
            ];

            // Item Details (Optional tapi bagus untuk detail di dashboard midtrans)
            $itemDetails = [
                [
                    'id' => 'KMR-' . $kamar->id,
                    'price' => (int) $kamar->harga, // Harga per bulan
                    'quantity' => (int) $booking->durasi, // Jumlah bulan
                    'name' => 'Sewa Kamar ' . $kamar->nomor_kamar,
                    'category' => 'Rental',
                    'merchant_name' => 'MyKos'
                ]
            ];

            $customerDetails = [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->no_telepon ?? '08123456789', // Default jika null
            ];

            // Custom Expiry (24 Jam)
            $customExpiry = [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'hour',
                'duration' => 24
            ];

            $transactionData = [
                'transaction_details' => $transactionDetails,
                'item_details' => $itemDetails,
                'customer_details' => $customerDetails,
                'expiry' => $customExpiry,
                // Callback URL agar setelah bayar user diarahkan kembali ke web kita
                'callbacks' => [
                    'finish' => route('booking.receipt', $pembayaran->id),
                ]
            ];

            Log::info('Midtrans Data Prepared', ['order_id' => $pembayaran->kode_pembayaran]);

            return $transactionData;

        } catch (\Exception $e) {
            Log::error('Midtrans Preparation Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getSnapToken($params)
    {
        // Wrapper agar controller tetap bisa panggil method ini
        return Snap::getSnapToken($params);
    }

    public function createTransaction(Pembayaran $pembayaran)
    {
        try {
            $transactionData = $this->prepareTransactionData($pembayaran);
            $snapToken = Snap::getSnapToken($transactionData);

            Log::info('Snap Token Generated', ['token' => substr($snapToken, 0, 10) . '...']);

            return $snapToken;

        } catch (\Exception $e) {
            Log::error('Create Transaction Error: ' . $e->getMessage());
            throw $e;
        }
    }

    // Method untuk cek status transaksi secara manual (misal tombol refresh status)
    public function getTransactionStatus($orderId)
    {
        try {
            return Transaction::status($orderId);
        } catch (\Exception $e) {
            Log::error('Get Status Error: ' . $e->getMessage());
            return null;
        }
    }

    // Method untuk handle notifikasi (Webhook) dari Midtrans
    public function handleNotification($request)
    {
        try {
            $notification = new Notification();

            $transaction = $notification->transaction_status;
            $type = $notification->payment_type;
            $orderId = $notification->order_id;
            $fraud = $notification->fraud_status;

            return [
                'status' => 'success',
                'transaction_status' => $transaction,
                'payment_type' => $type,
                'order_id' => $orderId,
                'fraud_status' => $fraud
            ];
        } catch (\Exception $e) {
            Log::error('Notification Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}