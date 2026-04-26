<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Auth\MustVerifyEmail; // 1. TAMBAHKAN INI

// 2. TAMBAHKAN "implements MustVerifyEmail"
class User extends Authenticatable implements MustVerifyEmail 
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'phone',
        'address',
        'identity_number',
        'email_verified_at',
        'profile_image',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $attributes = [
        'role' => 'calon_penghuni',
        'is_active' => true,
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    // ============ SCOPES ============
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCalonPenghuni($query)
    {
        return $query->where('role', 'calon_penghuni');
    }

    public function scopePenghuni($query)
    {
        return $query->where('role', 'penghuni');
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    // ============ RELATIONSHIPS ============
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function pembayarans(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    // ============ ROLE METHODS ============
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPenghuni(): bool
    {
        return $this->role === 'penghuni';
    }

    public function isCalonPenghuni(): bool
    {
        return $this->role === 'calon_penghuni';
    }

    public function getRoleDisplayName(): string
    {
        return match ($this->role) {
            'admin' => 'Administrator',
            'penghuni' => 'Penghuni',
            'calon_penghuni' => 'Calon Penghuni',
            default => 'Calon Penghuni'
        };
    }

    // ============ STATUS METHODS ============
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    public function isVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function isSuspended(): bool
    {
        return $this->is_active === false;
    }

    // ============ BOOKING METHODS ============
    public function hasActiveBooking(): bool
    {
        return $this->bookings()
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->exists();
    }

    public function hasPendingBooking(): bool
    {
        return $this->bookings()
            ->where('status', 'pending')
            ->exists();
    }

    public function getActiveBooking()
    {
        return $this->bookings()
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->with(['kamar', 'pembayaran'])
            ->first();
    }

    public function getPendingBooking()
    {
        return $this->bookings()
            ->where('status', 'pending')
            ->with(['kamar', 'pembayaran'])
            ->first();
    }

    public function getBookingHistory()
    {
        return $this->bookings()
            ->with(['kamar', 'pembayaran'])
            ->whereIn('status', ['checked_out', 'cancelled', 'expired'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // ============ PROFILE METHODS ============
    public function getInitials(): string
    {
        $names = explode(' ', $this->name);
        if (count($names) >= 2) {
            return strtoupper(substr($names[0], 0, 1) . substr($names[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    public function getProfileImageUrl(): string
    {
        if ($this->profile_image && Storage::disk('public')->exists('profile_images/' . $this->profile_image)) {
            return Storage::disk('public')->url('profile_images/' . $this->profile_image);
        }
        
        // Default avatar
        $params = http_build_query([
            'name' => $this->name,
            'color' => '3B82F6',
            'background' => 'DBEAFE',
            'size' => '128',
            'length' => '2'
        ]);
        return "https://ui-avatars.com/api/?{$params}";
    }

    // ============ AUTH METHODS ============
    public function recordLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    public function canBookKamar(): bool
    {
        $allowedRoles = ['calon_penghuni', 'admin'];

        return in_array($this->role, $allowedRoles) && 
               !$this->hasActiveBooking() && 
               $this->isActive();
    }

    // ============ UTILITY METHODS ============
    public function markAsVerified(): bool
    {
        return $this->update(['email_verified_at' => now()]);
    }

    public function suspend(): bool
    {
        return $this->update(['is_active' => false]);
    }

    public function activate(): bool
    {
        return $this->update(['is_active' => true]);
    }

    public function updateProfile(array $data): bool
    {
        return $this->update($data);
    }

    // ============ ATTRIBUTE ACCESSORS ============
    public function getFormattedPhoneAttribute(): string
    {
        return $this->phone ?: '-';
    }

    public function getFormattedIdentityNumberAttribute(): string
    {
        return $this->identity_number ?: '-';
    }

    public function getRegistrationDateAttribute(): string
    {
        return $this->created_at->format('d F Y');
    }
}