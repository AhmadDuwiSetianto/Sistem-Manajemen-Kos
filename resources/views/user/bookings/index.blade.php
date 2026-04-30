@extends('layouts.user')

@section('title', 'Riwayat Booking Saya')

@section('content')
<div class="w-full max-w-6xl mx-auto pb-10">
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-foreground tracking-tight">Riwayat Booking</h1>
        <p class="text-secondary mt-1 font-medium">Daftar semua riwayat penyewaan kamar Anda di Inna Kos.</p>
    </div>

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