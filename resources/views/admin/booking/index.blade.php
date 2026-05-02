@extends('layouts.admin')

@section('title', 'Daftar Booking')

@section('content')
<div class="flex-1 p-4 md:p-8">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-foreground">Kelola Booking</h1>
            <p class="text-secondary mt-1 text-sm md:text-base">Pantau dan kelola reservasi kamar kos</p>
        </div>
        <div class="flex gap-2">
            <button class="inline-flex items-center px-3 py-2 bg-white border border-border text-foreground font-semibold text-xs md:text-sm rounded-xl hover:bg-muted transition-colors shadow-sm">
                <i data-lucide="download" class="size-4 md:mr-2"></i> <span class="hidden md:inline">Export</span>
            </button>
            <a href="#" class="inline-flex items-center px-3 py-2 bg-primary hover:bg-primary-hover text-white font-semibold text-xs md:text-sm rounded-xl transition-all shadow-sm">
                <i data-lucide="plus" class="size-4 md:mr-2"></i> <span class="hidden md:inline">Booking Manual</span>
            </a>
        </div>
    </div>

    <!-- Alert Message -->
    @if(session('success'))
    <div class="bg-success-light border border-success/20 p-3 md:p-4 mb-6 rounded-xl flex items-center gap-3">
        <div class="size-8 bg-success/20 rounded-full flex items-center justify-center shrink-0">
            <i data-lucide="check-circle" class="size-5 text-success"></i>
        </div>
        <p class="text-success text-sm font-medium">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Cards Statistik (Mobile: 2 Kolom, Desktop: 4 Kolom) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-8">
        <div class="flex flex-col justify-between rounded-xl border border-border p-4 bg-white shadow-sm hover:ring-1 hover:ring-primary transition-all">
            <div class="flex items-center gap-2 mb-2">
                <div class="size-8 md:size-10 bg-primary/10 rounded-lg flex items-center justify-center shrink-0">
                    <i data-lucide="calendar" class="size-4 text-primary"></i>
                </div>
                <p class="font-medium text-secondary text-[10px] md:text-xs leading-tight">Total<br>Booking</p>
            </div>
            <p class="font-black text-xl md:text-3xl text-foreground">{{ $totalBooking ?? 0 }}</p>
        </div>

        <div class="flex flex-col justify-between rounded-xl border border-border p-4 bg-white shadow-sm hover:ring-1 hover:ring-warning transition-all">
            <div class="flex items-center gap-2 mb-2">
                <div class="size-8 md:size-10 bg-warning-light rounded-lg flex items-center justify-center shrink-0">
                    <i data-lucide="clock" class="size-4 text-warning-dark"></i>
                </div>
                <p class="font-medium text-secondary text-[10px] md:text-xs leading-tight">Status<br>Pending</p>
            </div>
            <p class="font-black text-xl md:text-3xl text-foreground">{{ $pendingBooking ?? 0 }}</p>
        </div>

        <div class="flex flex-col justify-between rounded-xl border border-border p-4 bg-white shadow-sm hover:ring-1 hover:ring-success transition-all">
            <div class="flex items-center gap-2 mb-2">
                <div class="size-8 md:size-10 bg-success-light rounded-lg flex items-center justify-center shrink-0">
                    <i data-lucide="check-circle-2" class="size-4 text-success"></i>
                </div>
                <p class="font-medium text-secondary text-[10px] md:text-xs leading-tight">Status<br>Confirmed</p>
            </div>
            <p class="font-black text-xl md:text-3xl text-foreground">{{ $confirmedBooking ?? 0 }}</p>
        </div>

        <div class="flex flex-col justify-between rounded-xl border border-border p-4 bg-white shadow-sm hover:ring-1 hover:ring-error transition-all">
            <div class="flex items-center gap-2 mb-2">
                <div class="size-8 md:size-10 bg-error-light rounded-lg flex items-center justify-center shrink-0">
                    <i data-lucide="x-circle" class="size-4 text-error"></i>
                </div>
                <p class="font-medium text-secondary text-[10px] md:text-xs leading-tight">Status<br>Dibatalkan</p>
            </div>
            <p class="font-black text-xl md:text-3xl text-foreground">{{ $cancelledBooking ?? 0 }}</p>
        </div>
    </div>

    <!-- Tabel Daftar Reservasi -->
    <div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden">
        <div class="px-5 py-4 border-b border-border flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="text-base md:text-lg font-bold text-foreground">Daftar Reservasi</h2>
            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                <div class="relative w-full sm:w-40">
                    <i data-lucide="filter" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                    <select class="w-full pl-9 pr-8 py-2 bg-muted border-none rounded-xl text-xs outline-none appearance-none text-foreground cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="active">Sedang Berjalan</option>
                        <option value="completed">Selesai</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                    <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                </div>
                <div class="relative w-full sm:w-56">
                    <input type="text" placeholder="Cari nama atau ID..." class="w-full pl-9 pr-4 py-2 bg-muted border-none rounded-xl text-xs outline-none placeholder:text-secondary text-foreground">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-muted/50">
                    <tr>
                        <th class="px-5 py-3 text-left text-[10px] md:text-xs font-semibold text-secondary uppercase">ID / Tanggal</th>
                        <th class="px-5 py-3 text-left text-[10px] md:text-xs font-semibold text-secondary uppercase">Penyewa</th>
                        <th class="px-5 py-3 text-left text-[10px] md:text-xs font-semibold text-secondary uppercase">Kamar</th>
                        <th class="px-5 py-3 text-left text-[10px] md:text-xs font-semibold text-secondary uppercase">Periode Sewa</th>
                        <th class="px-5 py-3 text-left text-[10px] md:text-xs font-semibold text-secondary uppercase">Status</th>
                        <th class="px-5 py-3 text-right text-[10px] md:text-xs font-semibold text-secondary uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse($bookings ?? [] as $booking)
                    <tr class="hover:bg-muted/30 transition-colors">
                        <td class="px-5 py-3 whitespace-nowrap">
                            <p class="text-xs md:text-sm font-bold text-foreground">#BK-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-[10px] md:text-[11px] text-secondary mt-0.5">{{ $booking->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="px-5 py-3">
                            <p class="text-xs md:text-sm font-semibold text-foreground">{{ $booking->user->name ?? 'N/A' }}</p>
                            <p class="text-[10px] md:text-[11px] text-secondary mt-0.5">{{ $booking->user->phone ?? '-' }}</p>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <p class="text-xs md:text-sm font-bold text-primary">Kamar {{ $booking->kamar->nomor_kamar ?? '-' }}</p>
                            <p class="text-[10px] md:text-[11px] text-secondary mt-0.5">{{ $booking->kamar->tipe_kamar ?? '-' }}</p>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <p class="text-xs md:text-sm font-medium text-foreground">{{ \Carbon\Carbon::parse($booking->tanggal_mulai)->format('d M Y') }}</p>
                            <p class="text-[10px] md:text-[11px] text-secondary mt-0.5">{{ $booking->durasi }} Bulan</p>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
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
                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-[9px] md:text-[10px] font-bold uppercase tracking-wider {{ $currStatus['bg'] }} {{ $currStatus['text'] }}">
                                <i data-lucide="{{ $currStatus['icon'] }}" class="size-3"></i>
                                {{ $booking->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                @if($booking->status == 'pending')
                                <button class="size-7 md:size-8 flex items-center justify-center rounded-lg bg-success/10 text-success hover:bg-success hover:text-white transition-colors" title="Konfirmasi">
                                    <i data-lucide="check" class="size-3.5 md:size-4"></i>
                                </button>
                                <button class="size-7 md:size-8 flex items-center justify-center rounded-lg bg-error/10 text-error hover:bg-error hover:text-white transition-colors" title="Batalkan">
                                    <i data-lucide="x" class="size-3.5 md:size-4"></i>
                                </button>
                                @endif
                                <button class="size-7 md:size-8 flex items-center justify-center rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition-colors" title="Detail Booking">
                                    <i data-lucide="eye" class="size-3.5 md:size-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="calendar-x" class="size-10 text-muted mb-2"></i>
                                <p class="text-xs font-semibold text-foreground">Tidak ada data booking</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($bookings) && method_exists($bookings, 'links'))
        <div class="px-5 py-3 border-t border-border bg-white flex flex-col md:flex-row items-center justify-between gap-3">
            <p class="text-[10px] md:text-xs text-secondary font-medium text-center md:text-left">
                Menampilkan {{ $bookings->firstItem() ?? 0 }} - {{ $bookings->lastItem() ?? 0 }} dari {{ $bookings->total() ?? 0 }}
            </p>
            <div class="flex gap-2">
                @if(!$bookings->onFirstPage())
                <a href="{{ $bookings->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-border text-[10px] md:text-xs font-semibold text-foreground hover:bg-muted transition-colors">Prev</a>
                @endif
                @if($bookings->hasMorePages())
                <a href="{{ $bookings->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-border text-[10px] md:text-xs font-semibold text-foreground hover:bg-muted transition-colors">Next</a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection