@extends('layouts.admin')

@section('title', 'Daftar Booking')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-foreground">Kelola Booking</h1>
        <p class="text-secondary mt-1">Pantau dan kelola reservasi kamar kos</p>
    </div>
    <div class="flex gap-3">
        <button class="inline-flex items-center px-4 py-2.5 bg-white border border-border text-foreground font-semibold rounded-xl hover:bg-muted transition-colors shadow-sm">
            <i data-lucide="download" class="size-4 mr-2"></i> Export Data
        </button>
        <a href="#" class="inline-flex items-center px-4 py-2.5 bg-primary hover:bg-primary-hover text-white font-semibold rounded-xl transition-all shadow-sm shadow-primary/30">
            <i data-lucide="plus" class="size-4 mr-2"></i> Booking Manual
        </a>
    </div>
</div>

@if(session('success'))
<div class="bg-success-light border border-success/20 p-4 mb-6 rounded-2xl flex items-center gap-3">
    <div class="size-8 bg-success/20 rounded-full flex items-center justify-center shrink-0">
        <i data-lucide="check-circle" class="size-5 text-success"></i>
    </div>
    <p class="text-success font-medium">{{ session('success') }}</p>
</div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="flex flex-col rounded-2xl border border-border p-5 bg-white shadow-sm">
        <div class="flex items-center gap-3 mb-2">
            <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center">
                <i data-lucide="calendar" class="size-5 text-primary"></i>
            </div>
            <p class="font-medium text-secondary">Total Booking</p>
        </div>
        <p class="font-bold text-3xl text-foreground">{{ $totalBooking ?? 0 }}</p>
    </div>
    <div class="flex flex-col rounded-2xl border border-border p-5 bg-white shadow-sm">
        <div class="flex items-center gap-3 mb-2">
            <div class="size-10 bg-warning-light rounded-xl flex items-center justify-center">
                <i data-lucide="clock" class="size-5 text-warning-dark"></i>
            </div>
            <p class="font-medium text-secondary">Pending</p>
        </div>
        <p class="font-bold text-3xl text-foreground">{{ $pendingBooking ?? 0 }}</p>
    </div>
    <div class="flex flex-col rounded-2xl border border-border p-5 bg-white shadow-sm">
        <div class="flex items-center gap-3 mb-2">
            <div class="size-10 bg-success-light rounded-xl flex items-center justify-center">
                <i data-lucide="check-circle-2" class="size-5 text-success"></i>
            </div>
            <p class="font-medium text-secondary">Confirmed</p>
        </div>
        <p class="font-bold text-3xl text-foreground">{{ $confirmedBooking ?? 0 }}</p>
    </div>
    <div class="flex flex-col rounded-2xl border border-border p-5 bg-white shadow-sm">
        <div class="flex items-center gap-3 mb-2">
            <div class="size-10 bg-error-light rounded-xl flex items-center justify-center">
                <i data-lucide="x-circle" class="size-5 text-error"></i>
            </div>
            <p class="font-medium text-secondary">Dibatalkan</p>
        </div>
        <p class="font-bold text-3xl text-foreground">{{ $cancelledBooking ?? 0 }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden">
    <div class="px-6 py-5 border-b border-border flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h2 class="text-lg font-bold text-foreground">Daftar Reservasi</h2>
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative w-full sm:w-48">
                <i data-lucide="filter" class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                <select class="w-full pl-10 pr-8 py-2.5 bg-muted border-none rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm appearance-none text-foreground cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="active">Sedang Berjalan</option>
                    <option value="completed">Selesai</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
                <i data-lucide="chevron-down" class="absolute right-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
            </div>
            <div class="relative w-full sm:w-64">
                <input type="text" placeholder="Cari nama atau ID..." class="w-full pl-10 pr-4 py-2.5 bg-muted border-none rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm placeholder:text-secondary text-foreground">
                <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-border">
            <thead class="bg-muted/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">ID / Tanggal</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Penyewa</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Kamar</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Periode Sewa</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-secondary uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-border">
                @forelse($bookings ?? [] as $booking)
                <tr class="hover:bg-muted/30 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-bold text-foreground">#BK-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-[11px] text-secondary mt-0.5">{{ $booking->created_at->format('d M Y') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold text-foreground">{{ $booking->user->name ?? 'N/A' }}</p>
                        <p class="text-[11px] text-secondary mt-0.5">{{ $booking->user->phone ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-bold text-primary">Kamar {{ $booking->kamar->nomor_kamar ?? '-' }}</p>
                        <p class="text-[11px] text-secondary mt-0.5">{{ $booking->kamar->tipe_kamar ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-foreground">{{ \Carbon\Carbon::parse($booking->tanggal_mulai)->format('d M Y') }}</p>
                        <p class="text-[11px] text-secondary mt-0.5">{{ $booking->durasi }} Bulan</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusMap = [
                                'pending' => ['bg' => 'bg-warning-light', 'text' => 'text-warning-dark', 'icon' => 'clock'],
                                'confirmed' => ['bg' => 'bg-success-light', 'text' => 'text-success', 'icon' => 'check-circle'],
                                'active' => ['bg' => 'bg-primary/10', 'text' => 'text-primary', 'icon' => 'play-circle'],
                                'completed' => ['bg' => 'bg-muted', 'text' => 'text-secondary', 'icon' => 'flag'],
                                'cancelled' => ['bg' => 'bg-error-light', 'text' => 'text-error', 'icon' => 'x-circle']
                            ];
                            $currStatus = $statusMap[$booking->status] ?? ['bg' => 'bg-muted', 'text' => 'text-secondary', 'icon' => 'info'];
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $currStatus['bg'] }} {{ $currStatus['text'] }}">
                            <i data-lucide="{{ $currStatus['icon'] }}" class="size-3"></i>
                            {{ $booking->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-2">
                            @if($booking->status == 'pending')
                            <button class="size-8 flex items-center justify-center rounded-lg bg-success/10 text-success hover:bg-success hover:text-white transition-colors" title="Konfirmasi">
                                <i data-lucide="check" class="size-4"></i>
                            </button>
                            <button class="size-8 flex items-center justify-center rounded-lg bg-error/10 text-error hover:bg-error hover:text-white transition-colors" title="Batalkan">
                                <i data-lucide="x" class="size-4"></i>
                            </button>
                            @endif
                            <button class="size-8 flex items-center justify-center rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition-colors" title="Detail Booking">
                                <i data-lucide="eye" class="size-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i data-lucide="calendar-x" class="size-12 text-muted mb-3"></i>
                            <p class="text-sm font-semibold text-foreground">Tidak ada data booking</p>
                            <p class="text-xs text-secondary mt-1">Belum ada reservasi kamar yang tercatat.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($bookings) && method_exists($bookings, 'links'))
    <div class="px-6 py-4 border-t border-border bg-white flex items-center justify-between">
        <p class="text-xs text-secondary font-medium">
            Menampilkan {{ $bookings->firstItem() }} - {{ $bookings->lastItem() }} dari {{ $bookings->total() }}
        </p>
        <div class="flex gap-2">
            @if(!$bookings->onFirstPage())
            <a href="{{ $bookings->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-border text-xs font-semibold text-foreground hover:bg-muted transition-colors">Prev</a>
            @endif
            @if($bookings->hasMorePages())
            <a href="{{ $bookings->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-border text-xs font-semibold text-foreground hover:bg-muted transition-colors">Next</a>
            @endif
        </div>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection