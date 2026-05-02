@extends('layouts.admin')

@section('title', 'Detail User')

@section('content')
<div class="flex-1 p-4 md:p-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 md:mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-foreground">Detail User</h1>
            <p class="text-secondary mt-1 text-sm md:text-base">Informasi lengkap profil dan riwayat user</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.user.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-border text-foreground font-bold text-xs md:text-sm rounded-xl hover:bg-muted transition-colors">
                <i data-lucide="arrow-left" class="size-4 mr-1.5 text-secondary"></i> Kembali
            </a>
            <a href="{{ route('admin.user.edit', $user) }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-hover text-white font-bold text-xs md:text-sm rounded-xl transition-all shadow-sm shadow-primary/30">
                <i data-lucide="pencil" class="size-4 mr-1.5"></i> Edit User
            </a>
        </div>
    </div>

    <!-- Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
        
        <!-- Kolom Utama -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-border p-5 md:p-8">
                <div class="flex items-center gap-4 mb-6 md:mb-8 pb-5 border-b border-border">
                    <div class="size-14 md:size-16 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold text-xl md:text-2xl shrink-0">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-lg md:text-xl font-bold text-foreground truncate">{{ $user->name }}</h2>
                        <p class="text-xs md:text-sm text-secondary truncate">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-5 gap-x-6 md:gap-x-8">
                    <div>
                        <p class="text-[10px] md:text-xs font-bold text-secondary uppercase tracking-wider mb-1">Role Akses</p>
                        @php
                            $roleMap = [
                                'admin' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Administrator'],
                                'penghuni' => ['bg' => 'bg-success-light', 'text' => 'text-success', 'label' => 'Penghuni'],
                                'calon_penghuni' => ['bg' => 'bg-warning-light', 'text' => 'text-warning-dark', 'label' => 'Calon Penghuni']
                            ];
                            $currRole = $roleMap[$user->role] ?? ['bg' => 'bg-muted', 'text' => 'text-secondary', 'label' => 'Unknown'];
                        @endphp
                        <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] md:text-[11px] font-bold uppercase tracking-wider {{ $currRole['bg'] }} {{ $currRole['text'] }}">
                            {{ $currRole['label'] }}
                        </span>
                    </div>
                    <div>
                        <p class="text-[10px] md:text-xs font-bold text-secondary uppercase tracking-wider mb-1">Nomor Telepon</p>
                        <p class="text-sm font-semibold text-foreground">{{ $user->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] md:text-xs font-bold text-secondary uppercase tracking-wider mb-1">Nomor Identitas (KTP)</p>
                        <p class="text-sm font-semibold text-foreground">{{ $user->identity_number ?? '-' }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-[10px] md:text-xs font-bold text-secondary uppercase tracking-wider mb-1">Alamat Lengkap</p>
                        <p class="text-sm font-semibold text-foreground leading-relaxed">{{ $user->address ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Sidebar -->
        <div class="lg:col-span-1 space-y-4 md:space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-border p-5 md:p-6">
                <h3 class="font-bold text-sm md:text-base text-foreground mb-4 flex items-center gap-2 border-b border-border pb-2">
                    <i data-lucide="shield" class="size-4 md:size-5 text-primary"></i> Status Akun
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-xs md:text-sm text-secondary">ID User</span>
                        <span class="text-xs md:text-sm font-bold text-foreground">#{{ $user->id }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs md:text-sm text-secondary">Bergabung</span>
                        <span class="text-xs md:text-sm font-bold text-foreground">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs md:text-sm text-secondary">Verifikasi Email</span>
                        @if($user->email_verified_at)
                            <span class="px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider bg-success-light text-success rounded">Terverifikasi</span>
                        @else
                            <span class="px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider bg-warning-light text-warning-dark rounded">Pending</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-border p-5 md:p-6">
                <h3 class="font-bold text-sm md:text-base text-foreground mb-4 flex items-center gap-2">
                    <i data-lucide="home" class="size-4 md:size-5 text-primary"></i> Status Kos
                </h3>
                @php
                    $activeBooking = $user->getActiveBooking();
                    $pendingBooking = $user->getPendingBooking();
                @endphp
                
                @if($activeBooking)
                <div class="p-3 bg-success-light border border-success/20 rounded-xl flex items-start gap-2.5">
                    <i data-lucide="key" class="size-4 md:size-5 text-success mt-0.5"></i>
                    <div>
                        <p class="text-xs md:text-sm font-bold text-success">Sedang Menempati</p>
                        <p class="text-[10px] md:text-xs text-success/80 mt-0.5">Kamar {{ $activeBooking->kamar->nomor_kamar ?? '-' }}</p>
                    </div>
                </div>
                @elseif($pendingBooking)
                <div class="p-3 bg-warning-light border border-warning/30 rounded-xl flex items-start gap-2.5">
                    <i data-lucide="clock" class="size-4 md:size-5 text-warning-dark mt-0.5"></i>
                    <div>
                        <p class="text-xs md:text-sm font-bold text-warning-dark">Menunggu Konfirmasi</p>
                        <p class="text-[10px] md:text-xs text-warning-dark/80 mt-0.5">Booking Kamar {{ $pendingBooking->kamar->nomor_kamar ?? '-' }}</p>
                    </div>
                </div>
                @else
                <div class="p-3 bg-muted border border-border rounded-xl flex items-start gap-2.5">
                    <i data-lucide="info" class="size-4 md:size-5 text-secondary mt-0.5"></i>
                    <div>
                        <p class="text-xs md:text-sm font-bold text-foreground">Tidak Ada Booking</p>
                        <p class="text-[10px] md:text-xs text-secondary mt-0.5">User ini belum menyewa kamar.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection