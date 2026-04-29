<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    // ✅ PERBAIKAN 1: Menambahkan 'tanggal_keluar' ke fillable
    protected $fillable = [
        'user_id',
        'kamar_id',
        'tanggal_masuk',
        'tanggal_keluar', // <-- Ini yang sebelumnya hilang
        'durasi',
        'total_harga',
        'catatan',
        'status',
        'alasan_pembatalan',
    ];

    // ✅ PERBAIKAN 2: Menambahkan 'tanggal_keluar' ke tipe data tanggal
    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date', // <-- Ini juga wajib ditambahkan
        'total_harga' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============ CONSTANTS ============
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CHECKED_IN = 'checked_in';
    const STATUS_CHECKED_OUT = 'checked_out';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    // ============ RELATIONSHIPS ============
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class);
    }

    // ============ METHODS ============
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    public function isActive(): bool
    {
        return in_array($this->status, [self::STATUS_CONFIRMED, self::STATUS_CHECKED_IN]);
    }

    public function canBeCancelled(): bool
    {
        return $this->isPending();
    }

    public function getTotalHargaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_CONFIRMED => 'bg-green-100 text-green-800 border border-green-200',
            self::STATUS_CHECKED_IN => 'bg-blue-100 text-blue-800 border border-blue-200',
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
            self::STATUS_EXPIRED, self::STATUS_CANCELLED => 'bg-red-100 text-red-800 border border-red-200',
            self::STATUS_CHECKED_OUT => 'bg-gray-100 text-gray-800 border border-gray-200',
            default => 'bg-gray-100 text-gray-800 border border-gray-200'
        };
    }

    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Menunggu Pembayaran',
            self::STATUS_CONFIRMED => 'Terkonfirmasi',
            self::STATUS_CHECKED_IN => 'Check In',
            self::STATUS_CHECKED_OUT => 'Check Out',
            self::STATUS_CANCELLED => 'Dibatalkan',
            self::STATUS_EXPIRED => 'Kedaluwarsa',
            default => 'Tidak Diketahui'
        };
    }

    public function getBookingInfo(): array
    {
        return [
            'id' => $this->id,
            'kamar' => $this->kamar ? $this->kamar->nomor_kamar : 'N/A',
            'tipe_kamar' => $this->kamar ? $this->kamar->tipe_kamar_display : 'N/A',
            'tanggal_masuk' => $this->tanggal_masuk->format('d F Y'),
            'durasi' => $this->durasi,
            'total_harga' => $this->total_harga_formatted,
            'status' => $this->status,
            'status_display' => $this->status_display,
            'can_cancel' => $this->canBeCancelled(),
            'is_active' => $this->isActive(),
        ];
    }

    // ============ SCOPES ============
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_CONFIRMED, self::STATUS_CHECKED_IN]);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}