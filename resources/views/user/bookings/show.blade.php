@extends('layouts.user')

@section('title', 'Detail Booking')

@section('content')
<div class="w-full max-w-4xl mx-auto pb-10">
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('user.bookings') }}" class="size-10 bg-white border border-border rounded-full flex items-center justify-center text-secondary hover:bg-muted transition-colors">
            <i data-lucide="arrow-left" class="size-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-foreground tracking-tight">Detail Pesanan</h1>
            <p class="text-secondary text-sm font-medium mt-0.5">Info lengkap mengenai sewa kamar Anda.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-border overflow-hidden mb-6">
        <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Info Kamar & Status -->
            <div>
                <span class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider rounded-md {{ $booking->status_badge_class }} mb-4 inline-block">
                    Status: {{ $booking->status_display }}
                </span>
                
                <div class="flex gap-4 items-center mb-6">
                    <div class="size-20 rounded-2xl bg-muted overflow-hidden ring-1 ring-border shrink-0">
                        @if($booking->kamar && $booking->kamar->gambar)
                            <img src="{{ \Illuminate\Support\Str::startsWith($theKamar->gambar, ['http://', 'https://']) ? $theKamar->gambar : asset('storage/' . $theKamar->gambar) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center"><i data-lucide="image-off" class="text-secondary"></i></div>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-foreground">Kamar {{ $booking->kamar->nomor_kamar ?? 'N/A' }}</h2>
                        <p class="text-sm font-bold text-primary mt-1">Tipe {{ ucfirst($booking->kamar->tipe_kamar ?? '-') }}</p>
                    </div>
                </div>

                <div class="space-y-4 text-sm bg-slate-50 p-5 rounded-2xl border border-slate-100">
                    <div class="flex justify-between border-b border-border pb-3">
                        <span class="text-secondary">Durasi Sewa</span>
                        <span class="font-bold text-foreground">{{ $booking->durasi }} Bulan</span>
                    </div>
                    <div class="flex justify-between border-b border-border pb-3">
                        <span class="text-secondary">Tanggal Masuk</span>
                        <span class="font-bold text-foreground">{{ \Carbon\Carbon::parse($booking->tanggal_masuk)->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-secondary">Tanggal Keluar</span>
                        <span class="font-bold text-foreground">{{ \Carbon\Carbon::parse($booking->tanggal_keluar)->translatedFormat('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Pembayaran & Aksi -->
            <div class="flex flex-col">
                <h3 class="font-bold text-foreground mb-4 flex items-center gap-2"><i data-lucide="receipt" class="size-4 text-primary"></i> Detail Tagihan</h3>
                
                <div class="bg-primary/5 p-5 rounded-2xl border border-primary/20 flex-1">
                    <div class="flex justify-between items-center mb-3 text-sm">
                        <span class="text-secondary">Harga per Bulan</span>
                        <span class="font-semibold text-foreground">Rp {{ number_format($booking->kamar->harga ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-3 text-sm">
                        <span class="text-secondary">Biaya Admin</span>
                        <span class="font-semibold text-success">Gratis</span>
                    </div>
                    <div class="border-t border-primary/20 my-4"></div>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-primary uppercase tracking-widest text-xs">Total Pembayaran</span>
                        <span class="font-black text-xl text-primary">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-6 flex flex-col gap-3">
                    @if($booking->isPending() && $booking->pembayaran)
                        <a href="{{ route('booking.payment', $booking->pembayaran->id) }}" class="w-full py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl text-center shadow-lg shadow-orange-500/30 transition-colors flex justify-center gap-2 items-center">
                            <i data-lucide="credit-card" class="size-4"></i> Lanjut Bayar Sekarang
                        </a>
                    @elseif($booking->pembayaran && $booking->pembayaran->status === 'paid')
                        <a href="{{ route('booking.receipt', $booking->pembayaran->id) }}" class="w-full py-3.5 bg-white border border-border text-foreground font-bold rounded-xl hover:bg-muted text-center transition-colors flex justify-center gap-2 items-center">
                            <i data-lucide="printer" class="size-4 text-secondary"></i> Cetak Invoice
                        </a>
                    @endif
                </div>
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