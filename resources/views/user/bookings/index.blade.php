@extends('layouts.user')

@section('title', 'Riwayat Booking Saya')

@section('content')
<div class="w-full max-w-6xl mx-auto pb-10">
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-foreground tracking-tight">Riwayat Booking</h1>
        <p class="text-secondary mt-1 font-medium">Daftar semua riwayat penyewaan kamar Anda di Inna Kos.</p>
    </div>

    <!-- ========================================================== -->
    <!-- LOGIKA BANNER PERINGATAN H-2 JATUH TEMPO -->
    <!-- ========================================================== -->
    @php
        // Cari booking aktif yang masa berlakunya tinggal 2 hari atau kurang
        $h2Booking = $bookings->filter(function($booking) {
            return in_array($booking->status, ['confirmed', 'checked_in']);
        })->first(function($booking) {
            $jatuhTempo = \Carbon\Carbon::parse($booking->tanggal_keluar)->startOfDay();
            $sekarang = \Carbon\Carbon::now()->startOfDay();
            $selisihHari = $sekarang->diffInDays($jatuhTempo, false); // false agar bisa melihat minus jika sudah lewat

            // Peringatkan jika sisa hari antara 0 sampai 2 hari
            return $selisihHari >= 0 && $selisihHari <= 2;
        });
    @endphp

    @if($h2Booking)
    <div class="bg-warning-light border border-warning text-warning-dark p-4 md:p-5 rounded-2xl mb-6 md:mb-8 flex items-start gap-3 md:gap-4 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 w-2 h-full bg-warning"></div>
        <div class="size-10 bg-warning/20 rounded-full flex items-center justify-center shrink-0">
            <i data-lucide="alert-triangle" class="size-5 text-warning-dark"></i>
        </div>
        <div>
            <h3 class="font-bold text-sm md:text-base">Peringatan Jatuh Tempo!</h3>
            @php
                $sisaHari = \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($h2Booking->tanggal_keluar)->startOfDay(), false);
            @endphp
            <p class="text-xs md:text-sm mt-1 mb-3 leading-relaxed">
                Masa sewa <strong>Kamar {{ $h2Booking->kamar->nomor_kamar ?? '-' }}</strong> Anda akan habis pada <strong>{{ \Carbon\Carbon::parse($h2Booking->tanggal_keluar)->translatedFormat('d M Y') }}</strong> 
                @if($sisaHari == 0)
                    (<span class="text-error font-bold">Hari Ini!</span>).
                @else
                    (Sisa <strong>{{ $sisaHari }} hari</strong>). 
                @endif
                Segera lakukan perpanjangan agar sistem tidak melepas kamar Anda ke publik.
            </p>
            <a href="{{ route('booking.extendForm', $h2Booking->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-warning-dark text-white text-xs font-bold rounded-lg hover:bg-yellow-600 transition-colors">
                <i data-lucide="calendar-plus" class="size-3.5"></i> Perpanjang Sekarang
            </a>
        </div>
    </div>
    @endif
    <!-- ========================================================== -->

    <div class="bg-white rounded-3xl shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[10px] uppercase tracking-widest text-secondary font-bold">
                        <th class="p-5 border-b border-border">ID Pesanan</th>
                        <th class="p-5 border-b border-border">Kamar</th>
                        <th class="p-5 border-b border-border">Periode Inap</th>
                        <th class="p-5 border-b border-border">Tagihan</th>
                        <th class="p-5 border-b border-border">Status</th>
                        <th class="p-5 border-b border-border text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($bookings as $item)
                        <tr class="hover:bg-muted/50 transition-colors">
                            <td class="p-5 border-b border-border font-mono text-xs text-secondary">
                                #{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="p-5 border-b border-border">
                                <p class="font-bold text-foreground">Kamar {{ $item->kamar->nomor_kamar ?? '-' }}</p>
                                <p class="text-xs text-secondary mt-0.5">{{ $item->durasi }} Bulan</p>
                            </td>
                            <td class="p-5 border-b border-border">
                                <p class="font-semibold text-foreground">{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d M Y') }}</p>
                                <p class="text-[10px] text-secondary">s/d {{ \Carbon\Carbon::parse($item->tanggal_keluar)->format('d M Y') }}</p>
                            </td>
                            <td class="p-5 border-b border-border font-bold text-foreground">
                                Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="p-5 border-b border-border">
                                <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md {{ $item->status_badge_class }}">
                                    {{ $item->status_display }}
                                </span>
                            </td>
                            <td class="p-5 border-b border-border text-center">
                                <a href="{{ route('user.bookings.show', $item->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-border text-secondary hover:text-primary hover:border-primary/50 text-xs font-bold rounded-lg transition-colors">
                                    <i data-lucide="eye" class="size-3.5"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center text-secondary">
                                <i data-lucide="inbox" class="size-10 mx-auto opacity-50 mb-3"></i>
                                <p class="font-bold">Belum ada riwayat pesanan kamar.</p>
                                <a href="{{ route('home') }}#kamar" class="text-primary hover:underline text-sm font-medium mt-1 inline-block">Mulai cari kamar sekarang</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-6">
        {{ $bookings->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection