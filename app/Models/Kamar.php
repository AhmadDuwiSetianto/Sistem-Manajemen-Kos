<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';

    protected $fillable = [
        'nomor_kamar',
        'tipe_kamar',
        'harga',
        'ukuran',
        'fasilitas',
        'status',
        'deskripsi',
        'gambar',
        'lantai',
        'kapasitas',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'ukuran' => 'integer',
        'kapasitas' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'fasilitas_list',
        'gambar_url',
        'status_badge_class',
        'tipe_kamar_display',
        'harga_formatted',
    ];

    // ============ CONSTANTS (DIPERBAIKI) ============
    const STATUS_TERSEDIA = 'tersedia'; // ✅ FIX: Sebelumnya TYPO (STATUS_TESEDIA)
    const STATUS_DIPESAN = 'dipesan';
    const STATUS_TERISI = 'terisi';
    const STATUS_MAINTENANCE = 'maintenance';

    const TIPE_STANDARD = 'standard';
    const TIPE_DELUXE = 'deluxe';
    const TIPE_SUPERIOR = 'superior';
    const TIPE_EXECUTIVE = 'executive';
    const TIPE_VIP = 'vip';

    // ============ RELATIONSHIPS ============
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function pembayarans(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function activeBooking()
    {
        return $this->hasOne(Booking::class)->whereIn('status', ['confirmed', 'checked_in', 'active']);
    }

    public function pendingBooking()
    {
        return $this->hasOne(Booking::class)->where('status', 'pending');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ============ ACCESSORS ============
    public function getFasilitasListAttribute(): array
    {
        if (!$this->fasilitas) {
            return [];
        }

        if (str_starts_with($this->fasilitas, '[')) {
            $decoded = json_decode($this->fasilitas, true);
            return is_array($decoded) ? $decoded : [];
        }

        return array_map('trim', explode(',', $this->fasilitas));
    }

    public function getGambarUrlAttribute(): string
    {
        if (!$this->gambar) {
            return $this->getDefaultImageUrl();
        }

        if (filter_var($this->gambar, FILTER_VALIDATE_URL)) {
            return $this->gambar;
        }

        if (Storage::disk('public')->exists($this->gambar)) {
            return Storage::disk('public')->url($this->gambar);
        }

        return $this->getDefaultImageUrl();
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_TERSEDIA => 'bg-green-100 text-green-800 border border-green-200',
            self::STATUS_DIPESAN => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
            self::STATUS_TERISI => 'bg-blue-100 text-blue-800 border border-blue-200',
            self::STATUS_MAINTENANCE => 'bg-red-100 text-red-800 border border-red-200',
            default => 'bg-gray-100 text-gray-800 border border-gray-200'
        };
    }

    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            self::STATUS_TERSEDIA => 'Tersedia',
            self::STATUS_DIPESAN => 'Dipesan',
            self::STATUS_TERISI => 'Terisi',
            self::STATUS_MAINTENANCE => 'Maintenance',
            default => 'Tidak Diketahui'
        };
    }

    public function getTipeKamarDisplayAttribute(): string
    {
        return match($this->tipe_kamar) {
            self::TIPE_STANDARD => 'Standard',
            self::TIPE_DELUXE => 'Deluxe',
            self::TIPE_SUPERIOR => 'Superior',
            self::TIPE_EXECUTIVE => 'Executive',
            self::TIPE_VIP => 'VIP',
            default => ucfirst($this->tipe_kamar)
        };
    }

    public function getHargaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function getUkuranDisplayAttribute(): string
    {
        return $this->ukuran ? $this->ukuran . ' m²' : '-';
    }

    public function getKapasitasDisplayAttribute(): string
    {
        return $this->kapasitas ? $this->kapasitas . ' orang' : '-';
    }

    // ============ METHODS ============
    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_TERSEDIA && $this->is_active;
    }

    public function isBooked(): bool
    {
        return $this->status === self::STATUS_DIPESAN;
    }

    public function isOccupied(): bool
    {
        return $this->status === self::STATUS_TERISI;
    }

    public function isUnderMaintenance(): bool
    {
        return $this->status === self::STATUS_MAINTENANCE;
    }

    public function canBeBooked(): bool
    {
        return $this->isAvailable();
    }

    public function markAsBooked(): bool
    {
        return $this->update([
            'status' => self::STATUS_DIPESAN
        ]);
    }

    public function markAsAvailable(): bool
    {
        return $this->update([
            'status' => self::STATUS_TERSEDIA
        ]);
    }

    public function markAsOccupied(): bool
    {
        return $this->update([
            'status' => self::STATUS_TERISI
        ]);
    }

    // ============ SCOPES ============
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_TERSEDIA)
                     ->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeTersedia($query)
    {
        return $query->where('status', self::STATUS_TERSEDIA);
    }

    // ============ BOOT METHOD ============
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kamar) {
            if (auth()->check()) {
                $kamar->created_by = auth()->id();
            }
        });
    }

    protected function getDefaultImageUrl(): string
    {
        return asset('images/default-room.jpg');
    }

    // ============ CUSTOM METHODS ============
    public function getBookingInfo(): array
    {
        return [
            'id' => $this->id,
            'nomor_kamar' => $this->nomor_kamar,
            'tipe_kamar' => $this->tipe_kamar_display,
            'harga' => $this->harga,
            'harga_formatted' => $this->harga_formatted,
            'status' => $this->status,
            'status_display' => $this->status_display,
            'is_available' => $this->isAvailable(),
            'fasilitas' => $this->fasilitas_list,
        ];
    }
}