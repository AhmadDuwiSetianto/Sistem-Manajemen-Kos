@extends('layouts.admin')

@section('title', 'Detail User')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-foreground">Detail User</h1>
        <p class="text-secondary mt-1">Informasi lengkap profil dan riwayat user</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.user.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white border border-border text-foreground font-semibold rounded-xl hover:bg-muted transition-colors">
            <i data-lucide="arrow-left" class="size-4 mr-2 text-secondary"></i> Kembali
        </a>
        <a href="{{ route('admin.user.edit', $user) }}" class="inline-flex items-center px-4 py-2.5 bg-primary text-white font-semibold rounded-xl hover:bg-primary-hover transition-colors shadow-sm shadow-primary/30">
            <i data-lucide="pencil" class="size-4 mr-2"></i> Edit User
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-border p-6 md:p-8">
            <div class="flex items-center gap-4 mb-8 pb-6 border-b border-border">
                <div class="size-16 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold text-2xl">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-foreground">{{ $user->name }}</h2>
                    <p class="text-sm text-secondary">{{ $user->email }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                <div>
                    <p class="text-xs font-semibold text-secondary uppercase tracking-wider mb-1">Role Akses</p>
                    @php
                        $roleMap = [
                            'admin' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Administrator'],
                            'penghuni' => ['bg' => 'bg-success-light', 'text' => 'text-success', 'label' => 'Penghuni'],
                            'calon_penghuni' => ['bg' => 'bg-warning-light', 'text' => 'text-warning-dark', 'label' => 'Calon Penghuni']
                        ];
                        $currRole = $roleMap[$user->role] ?? ['bg' => 'bg-muted', 'text' => 'text-secondary', 'label' => 'Unknown'];
                    @endphp
                    <span class="inline-flex px-2.5 py-1 rounded-md text-[11px] font-bold uppercase tracking-wider {{ $currRole['bg'] }} {{ $currRole['text'] }}">
                        {{ $currRole['label'] }}
                    </span>
                </div>
                <div>
                    <p class="text-xs font-semibold text-secondary uppercase tracking-wider mb-1">Nomor Telepon</p>
                    <p class="text-sm font-medium text-foreground">{{ $user->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-secondary uppercase tracking-wider mb-1">Nomor Identitas (KTP)</p>
                    <p class="text-sm font-medium text-foreground">{{ $user->identity_number ?? '-' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-xs font-semibold text-secondary uppercase tracking-wider mb-1">Alamat Lengkap</p>
                    <p class="text-sm font-medium text-foreground">{{ $user->address ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-border p-6">
            <h3 class="font-bold text-foreground mb-4 flex items-center gap-2">
                <i data-lucide="shield" class="size-4 text-primary"></i> Status Akun
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-border">
                    <span class="text-sm text-secondary">ID User</span>
                    <span class="text-sm font-bold text-foreground">#{{ $user->id }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-border">
                    <span class="text-sm text-secondary">Bergabung</span>
                    <span class="text-sm font-medium text-foreground">{{ $user->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-secondary">Verifikasi Email</span>
                    @if($user->email_verified_at)
                        <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider bg-success-light text-success rounded-md">Terverifikasi</span>
                    @else
                        <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider bg-warning-light text-warning-dark rounded-md">Pending</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-border p-6">
            <h3 class="font-bold text-foreground mb-4 flex items-center gap-2">
                <i data-lucide="home" class="size-4 text-primary"></i> Status Kos
            </h3>
            @php
                $activeBooking = $user->getActiveBooking();
                $pendingBooking = $user->getPendingBooking();
            @endphp
            
            @if($activeBooking)
            <div class="p-4 bg-success-light border border-success/20 rounded-xl flex items-start gap-3">
                <i data-lucide="key" class="size-5 text-success mt-0.5"></i>
                <div>
                    <p class="text-sm font-bold text-success">Sedang Menempati</p>
                    <p class="text-xs text-success/80 mt-1">Kamar {{ $activeBooking->kamar->nomor_kamar ?? '-' }}</p>
                </div>
            </div>
            @elseif($pendingBooking)
            <div class="p-4 bg-warning-light border border-warning/30 rounded-xl flex items-start gap-3">
                <i data-lucide="clock" class="size-5 text-warning-dark mt-0.5"></i>
                <div>
                    <p class="text-sm font-bold text-warning-dark">Menunggu Konfirmasi</p>
                    <p class="text-xs text-warning-dark/80 mt-1">Booking Kamar {{ $pendingBooking->kamar->nomor_kamar ?? '-' }}</p>
                </div>
            </div>
            @else
            <div class="p-4 bg-muted border border-border rounded-xl flex items-start gap-3">
                <i data-lucide="info" class="size-5 text-secondary mt-0.5"></i>
                <div>
                    <p class="text-sm font-bold text-foreground">Tidak Ada Booking</p>
                    <p class="text-xs text-secondary mt-1">User ini belum menyewa kamar.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection