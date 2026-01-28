<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';

    protected $fillable = [
        'user_id',
        'booking_id',
        'kode_pembayaran',
        'jumlah',
        'metode',
        'status',
        'snap_token',
        'tanggal_bayar',
        'tanggal_jatuh_tempo',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_bayar' => 'datetime',
        'tanggal_jatuh_tempo' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============ CONSTANTS ============
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_CHALLENGE = 'challenge';

    // ============ RELATIONSHIPS ============
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // ============ METHODS ============
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    // ✅ PERBAIKAN: Logic isOverdue yang benar dan konsisten
    public function isOverdue(): bool
    {
        // Hanya cek overdue untuk pembayaran yang statusnya masih pending
        if (!$this->isPending()) {
            return false;
        }

        // Pastikan tanggal_jatuh_tempo ada
        if (!$this->tanggal_jatuh_tempo) {
            return false;
        }

        try {
            $jatuhTempo = Carbon::parse($this->tanggal_jatuh_tempo);
            $sekarang = Carbon::now();

            return $sekarang->greaterThan($jatuhTempo);
        } catch (\Exception $e) {
            \Log::error('Error checking overdue: ' . $e->getMessage());
            return false;
        }
    }

    public function markAsPaid($paymentMethod = null): bool
    {
        return $this->update([
            'status' => self::STATUS_PAID,
            'metode' => $paymentMethod,
            'tanggal_bayar' => now()
        ]);
    }

    public function markAsExpired(): bool
    {
        return $this->update([
            'status' => self::STATUS_EXPIRED
        ]);
    }

    public function markAsCancelled(): bool
    {
        return $this->update([
            'status' => self::STATUS_CANCELLED
        ]);
    }

    // ✅ PERBAIKAN: Get time remaining yang lebih akurat
    public function getTimeRemaining(): string
    {
        if (!$this->isPending()) {
            return '00:00:00';
        }

        try {
            $jatuhTempo = Carbon::parse($this->tanggal_jatuh_tempo);
            $sekarang = Carbon::now();

            // ✅ PERBAIKAN: Hitung selisih yang benar (jatuh tempo - sekarang)
            $remaining = $jatuhTempo->diffInSeconds($sekarang, false);

            // Jika sudah lewat, return 00:00:00
            if ($remaining <= 0) {
                return '00:00:00';
            }

            $hours = floor($remaining / 3600);
            $minutes = floor(($remaining % 3600) / 60);
            $seconds = $remaining % 60;

            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        } catch (\Exception $e) {
            \Log::error('Error calculating time remaining: ' . $e->getMessage(), [
                'payment_id' => $this->id,
                'tanggal_jatuh_tempo' => $this->tanggal_jatuh_tempo
            ]);
            return '00:00:00';
        }
    }

    // ✅ METHOD BARU: Cek apakah pembayaran masih valid
    public function isValidForPayment(): bool
    {
        return $this->isPending() && 
               !$this->isOverdue() && 
               !empty($this->snap_token) &&
               $this->jumlah > 0;
    }

    // ✅ METHOD BARU: Dapatkan info status yang user-friendly
    public function getPaymentStatusInfo(): array
    {
        $isOverdue = $this->isOverdue();
        $isValid = $this->isValidForPayment();

        $statusInfo = [
            'status' => $this->status,
            'status_display' => $isOverdue ? 'Kedaluwarsa' : 'Menunggu Pembayaran',
            'is_valid' => $isValid,
            'is_overdue' => $isOverdue,
            'time_remaining' => $this->getTimeRemaining(),
            'can_retry' => in_array($this->status, [self::STATUS_PENDING, self::STATUS_EXPIRED, self::STATUS_FAILED]),
            'can_cancel' => $this->isPending(),
            'show_payment_button' => $isValid,
            'has_snap_token' => !empty($this->snap_token),
            'amount_valid' => $this->jumlah > 0,
        ];

        // Debug info
        if ($this->isPending()) {
            $statusInfo['debug'] = [
                'snap_token_exists' => !empty($this->snap_token),
                'jatuh_tempo' => $this->tanggal_jatuh_tempo ? $this->tanggal_jatuh_tempo->format('Y-m-d H:i:s') : null,
                'sekarang' => Carbon::now()->format('Y-m-d H:i:s'),
                'is_overdue_calculated' => $isOverdue,
                'amount' => $this->jumlah
            ];
        }

        return $statusInfo;
    }

    public function getPaymentInfo(): array
    {
        return [
            'id' => $this->id,
            'kode_pembayaran' => $this->kode_pembayaran,
            'jumlah' => $this->jumlah,
            'jumlah_formatted' => $this->jumlah_formatted,
            'status' => $this->status,
            'status_badge_class' => $this->status_badge_class,
            'tanggal_jatuh_tempo' => $this->tanggal_jatuh_tempo,
            'tanggal_bayar' => $this->tanggal_bayar,
            'is_overdue' => $this->isOverdue(),
            'time_remaining' => $this->getTimeRemaining(),
            'snap_token_exists' => !empty($this->snap_token),
            'is_valid_for_payment' => $this->isValidForPayment(),
        ];
    }

    // ============ SCOPES ============
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_PENDING)
            ->where('tanggal_jatuh_tempo', '<', Carbon::now());
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeValidForPayment($query)
    {
        return $query->where('status', self::STATUS_PENDING)
            ->where('tanggal_jatuh_tempo', '>', Carbon::now())
            ->where('jumlah', '>', 0)
            ->whereNotNull('snap_token');
    }

    // ============ ACCESSORS ============
    public function getStatusBadgeClassAttribute(): string
    {
        if ($this->isOverdue()) {
            return 'bg-red-100 text-red-800 border border-red-200';
        }

        return match ($this->status) {
            self::STATUS_PAID => 'bg-green-100 text-green-800 border border-green-200',
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
            self::STATUS_CHALLENGE => 'bg-orange-100 text-orange-800 border border-orange-200',
            self::STATUS_EXPIRED, self::STATUS_CANCELLED, self::STATUS_FAILED => 'bg-red-100 text-red-800 border border-red-200',
            default => 'bg-gray-100 text-gray-800 border border-gray-200'
        };
    }

    public function getJumlahFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    public function getStatusDisplayAttribute(): string
    {
        if ($this->isOverdue()) {
            return 'Kedaluwarsa';
        }

        return match ($this->status) {
            self::STATUS_PAID => 'Lunas',
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_CHALLENGE => 'Butuh Verifikasi',
            self::STATUS_EXPIRED => 'Kedaluwarsa',
            self::STATUS_CANCELLED => 'Dibatalkan',
            self::STATUS_FAILED => 'Gagal',
            default => 'Tidak Diketahui'
        };
    }

    // ✅ ACCESSOR BARU: Untuk tampilan countdown
    public function getCountdownDataAttribute(): array
    {
        return [
            'display' => $this->getTimeRemaining(),
            'is_expired' => $this->isOverdue(),
            'target_time' => $this->tanggal_jatuh_tempo ? $this->tanggal_jatuh_tempo->timestamp * 1000 : null,
            'current_time' => Carbon::now()->timestamp * 1000
        ];
    }

    public function getTimeDebugInfo(): array
    {
        try {
            $jatuhTempo = Carbon::parse($this->tanggal_jatuh_tempo);
            $sekarang = Carbon::now();

            // ✅ PERBAIKAN: Gunakan diffInSeconds dengan parameter false untuk mendapatkan nilai dengan tanda
            $diff_seconds = $jatuhTempo->diffInSeconds($sekarang, false);

            return [
                'jatuh_tempo' => $jatuhTempo->format('Y-m-d H:i:s'),
                'sekarang' => $sekarang->format('Y-m-d H:i:s'),
                'is_overdue' => $this->isOverdue(),
                'diff_seconds' => $diff_seconds,
                'diff_hours' => $diff_seconds / 3600,
                'time_remaining' => $this->getTimeRemaining(),
                'is_pending' => $this->isPending(),
                'timezone' => config('app.timezone', 'UTC')
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    // ✅ METHOD BARU: Reset payment untuk retry
    public function resetForRetry(): bool
    {
        return $this->update([
            'status' => self::STATUS_PENDING,
            'tanggal_jatuh_tempo' => Carbon::now()->addHours(24),
            'snap_token' => null,
            'metode' => null,
            'tanggal_bayar' => null
        ]);
    }

    // ✅ METHOD BARU: Get available payment methods based on amount
    public function getAvailablePaymentMethodsInfo(): array
    {
        $amount = $this->jumlah;
        
        $methods = [
            'bank_transfer' => [
                'name' => 'Transfer Bank',
                'methods' => [
                    'bca_va' => ['min_amount' => 10000, 'name' => 'BCA Virtual Account'],
                    'bni_va' => ['min_amount' => 1, 'name' => 'BNI Virtual Account'],
                    'bri_va' => ['min_amount' => 1, 'name' => 'BRI Virtual Account'],
                    'permata_va' => ['min_amount' => 1, 'name' => 'Permata Virtual Account'],
                    'mandiri_bill' => ['min_amount' => 1, 'name' => 'Mandiri Bill'],
                    'cimb_va' => ['min_amount' => 1, 'name' => 'CIMB Virtual Account'],
                ]
            ],
            'ewallet' => [
                'name' => 'E-Wallet',
                'methods' => [
                    'gopay' => ['min_amount' => 1, 'name' => 'GoPay'],
                    'shopeepay' => ['min_amount' => 1, 'name' => 'ShopeePay'],
                    'linkaja' => ['min_amount' => 1, 'name' => 'LinkAja'],
                ]
            ],
            'qris' => [
                'name' => 'QRIS',
                'methods' => [
                    'qris' => ['min_amount' => 1, 'name' => 'QRIS']
                ]
            ],
            'retail' => [
                'name' => 'Gerai Ritel',
                'methods' => [
                    'alfamart' => ['min_amount' => 1, 'name' => 'Alfamart'],
                    'indomaret' => ['min_amount' => 10000, 'name' => 'Indomaret'],
                ]
            ],
            'card' => [
                'name' => 'Kartu Kredit/Debit',
                'methods' => [
                    'credit_card' => ['min_amount' => 10000, 'name' => 'Kartu Kredit'],
                ]
            ],
            'installment' => [
                'name' => 'Kredit Tanpa Kartu',
                'methods' => [
                    'akulaku' => ['min_amount' => 5000, 'name' => 'Akulaku'],
                    'kredivo' => ['min_amount' => 1, 'name' => 'Kredivo (Bayar 30 Hari)'],
                    'kredivo_installment' => ['min_amount' => 500000, 'name' => 'Kredivo (Cicilan)'],
                ]
            ]
        ];

        // Filter methods yang available berdasarkan amount
        foreach ($methods as $category => $categoryData) {
            foreach ($categoryData['methods'] as $methodCode => $methodInfo) {
                $methods[$category]['methods'][$methodCode]['available'] = $amount >= $methodInfo['min_amount'];
            }
        }

        return $methods;
    }
}