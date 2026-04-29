@extends('layouts.user')

@section('title', 'Dashboard Saya')

@section('content')
<div class="w-full max-w-6xl mx-auto pb-10">
    
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-foreground tracking-tight">Halo, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
        <p class="text-secondary mt-1 font-medium">Selamat datang di panel pengelola akun Inna Kos Anda.</p>
    </div>

    @php
        $pendingPayment = auth()->user()->pembayarans()->where('status', 'pending')->first();
        $activeBooking = Auth::user()->getActiveBooking();
    @endphp

    @if($pendingPayment)
        @php
            $dueDate = \Carbon\Carbon::parse($pendingPayment->tanggal_jatuh_tempo);
            $now = \Carbon\Carbon::now();
            $diffHours = $now->diffInHours($dueDate, false);
        @endphp

        @if($diffHours > 0 && $diffHours <= 48)
            <div class="mb-6 p-5 rounded-2xl bg-orange-50 border border-orange-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-start gap-4">
                    <div class="size-12 rounded-full bg-orange-100 flex items-center justify-center shrink-0">
                        <i data-lucide="alert-triangle" class="size-6 text-orange-600"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-orange-800">Peringatan Pembayaran!</h3>
                        <p class="text-sm text-orange-700 mt-1 font-medium">
                            Batas waktu pembayaran kamar Anda tersisa <strong>{{ ceil($diffHours) }} jam</strong> lagi.<br>
                            <span class="text-xs opacity-80">(Jatuh tempo: {{ $dueDate->translatedFormat('d M Y, H:i') }} WIB)</span>
                        </p>
                    </div>
                </div>
                <a href="{{ route('booking.payment', $pendingPayment->id) }}" class="inline-flex items-center justify-center px-6 py-2.5 bg-orange-600 text-white font-bold rounded-xl hover:bg-orange-700 transition-colors shrink-0 shadow-lg shadow-orange-600/20">
                    Selesaikan Pembayaran
                </a>
            </div>
        
        @elseif($diffHours <= 0)
            <div class="mb-6 p-5 rounded-2xl bg-red-50 border border-red-200 flex items-start gap-4 shadow-sm">
                <div class="size-12 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                    <i data-lucide="x-octagon" class="size-6 text-red-600"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-red-800">Pembayaran Melewati Batas Waktu</h3>
                    <p class="text-sm text-red-700 mt-1 font-medium">
                        Waktu pembayaran telah habis. Sistem sedang memproses pembatalan pemesanan kamar Anda dan penonaktifan akun akan segera dilakukan.
                    </p>
                </div>
            </div>
        @endif
    @endif

    @if($pendingPayment || $activeBooking)
        @php
            $isPending = !is_null($pendingPayment);
            $theBooking = $isPending ? $pendingPayment->booking : $activeBooking;
            $theKamar = $theBooking->kamar;
        @endphp
        
        <div class="bg-white rounded-3xl shadow-sm border border-border overflow-hidden mb-8">
            <div class="p-4 md:p-5 border-b border-border bg-muted/30 flex justify-between items-center">
                <h2 class="font-bold text-foreground text-sm flex items-center gap-2">
                    <i data-lucide="info" class="size-5 text-primary"></i> 
                    Detail {{ $isPending ? 'Pesanan (Menunggu Pembayaran)' : 'Kamar Saat Ini' }}
                </h2>
                @if($isPending)
                    <span class="px-2.5 py-1 bg-warning-light text-orange-600 text-[10px] font-bold uppercase tracking-wider rounded-md border border-warning/30">Pending</span>
                @else
                    <span class="px-2.5 py-1 bg-success-light text-success text-[10px] font-bold uppercase tracking-wider rounded-md border border-success/30">Aktif</span>
                @endif
            </div>
            
            <div class="p-5 md:p-6 grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-4 lg:col-span-3">
                    <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-muted ring-1 ring-border relative group">
                        @if($theKamar->gambar)
                            <img src="{{ asset('storage/' . $theKamar->gambar) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-secondary">
                                <i data-lucide="image-off" class="size-8 opacity-50 mb-2"></i>
                                <span class="text-xs font-medium">Tanpa Foto</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="md:col-span-8 lg:col-span-9 flex flex-col justify-center space-y-5">
                    <div>
                        <h3 class="font-black text-2xl text-foreground tracking-tight">Kamar {{ $theKamar->nomor_kamar }}</h3>
                        <p class="text-sm font-bold text-primary mt-1 bg-primary/10 inline-block px-2.5 py-1 rounded-md">Tipe {{ ucfirst($theKamar->tipe_kamar) }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                        <div class="bg-slate-50 p-3.5 md:p-4 rounded-xl border border-border">
                            <p class="text-[10px] font-bold text-secondary uppercase tracking-widest mb-1.5 flex items-center gap-1.5"><i data-lucide="clock-4" class="size-3"></i> Waktu Booking</p>
                            <p class="text-sm font-semibold text-foreground">{{ \Carbon\Carbon::parse($theBooking->created_at)->translatedFormat('d M Y, H:i') }} WIB</p>
                        </div>
                        
                        @if($isPending)
                        <div class="bg-orange-50 p-3.5 md:p-4 rounded-xl border border-orange-200">
                            <p class="text-[10px] font-bold text-orange-600 uppercase tracking-widest mb-1.5 flex items-center gap-1.5"><i data-lucide="alert-circle" class="size-3"></i> Jatuh Tempo</p>
                            <p class="text-sm font-bold text-orange-700">{{ \Carbon\Carbon::parse($pendingPayment->tanggal_jatuh_tempo)->translatedFormat('d M Y, H:i') }} WIB</p>
                        </div>
                        @else
                        <div class="bg-success-light/50 p-3.5 md:p-4 rounded-xl border border-success/20">
                            <p class="text-[10px] font-bold text-success uppercase tracking-widest mb-1.5 flex items-center gap-1.5"><i data-lucide="calendar-check" class="size-3"></i> Masa Sewa Berakhir</p>
                            <p class="text-sm font-bold text-success">{{ \Carbon\Carbon::parse($theBooking->tanggal_keluar)->translatedFormat('d F Y') }}</p>
                        </div>
                        @endif

                        <div class="bg-slate-50 p-3.5 md:p-4 rounded-xl border border-border sm:col-span-2">
                            <p class="text-[10px] font-bold text-secondary uppercase tracking-widest mb-2">Periode Inap (Durasi: {{ $theBooking->durasi }} Bulan)</p>
                            <div class="flex items-center gap-3 text-sm font-semibold text-foreground bg-white p-2.5 rounded-lg border border-border/50 w-max">
                                <span class="flex items-center gap-1.5"><i data-lucide="log-in" class="size-4 text-primary"></i> {{ \Carbon\Carbon::parse($theBooking->tanggal_masuk)->translatedFormat('d M Y') }}</span>
                                <i data-lucide="arrow-right" class="size-3 text-secondary mx-1"></i>
                                <span class="flex items-center gap-1.5"><i data-lucide="log-out" class="size-4 text-error"></i> {{ \Carbon\Carbon::parse($theBooking->tanggal_keluar)->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        
        <div class="flex flex-col rounded-3xl border border-border p-6 bg-white shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                    <i data-lucide="shield-check" class="size-6"></i>
                </div>
                <span class="px-3 py-1 bg-success-light text-success text-[10px] font-bold uppercase tracking-wider rounded-md border border-success/20">
                    Aktif
                </span>
            </div>
            <h3 class="text-secondary text-sm font-medium">Status Akun</h3>
            <p class="text-2xl font-black text-foreground mt-1 tracking-tight">
                {{ Auth::user()->isPenghuni() ? 'Penghuni' : 'Calon Penghuni' }}
            </p>
        </div>

        <div class="flex flex-col rounded-3xl border border-border p-6 bg-white shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="door-open" class="size-6"></i>
                </div>
            </div>
            <h3 class="text-secondary text-sm font-medium">Kamar Anda</h3>
            @if($activeBooking)
                <p class="text-2xl font-black text-foreground mt-1 tracking-tight">Kamar {{ $activeBooking->kamar->nomor_kamar ?? '-' }}</p>
                <p class="text-xs text-secondary mt-1 font-medium">Tipe {{ ucfirst($activeBooking->kamar->tipe_kamar) }}</p>
            @else
                <p class="text-lg font-black text-foreground mt-1 tracking-tight">Belum Ada Kamar</p>
                <a href="{{ route('home') }}#kamar" class="text-xs text-primary font-bold hover:underline mt-1">Pesan Kamar Sekarang &rarr;</a>
            @endif
        </div>

        <div class="flex flex-col rounded-3xl border border-border p-6 bg-white shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="size-12 bg-warning-light rounded-2xl flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform">
                    <i data-lucide="receipt" class="size-6"></i>
                </div>
            </div>
            <h3 class="text-secondary text-sm font-medium">Tagihan Belum Dibayar</h3>
            @if($pendingPayment)
                <p class="text-2xl font-black text-foreground mt-1 tracking-tight">Rp {{ number_format($pendingPayment->jumlah, 0, ',', '.') }}</p>
                <a href="{{ route('booking.payment', $pendingPayment->id) }}" class="text-xs text-orange-600 font-bold hover:underline mt-1">Bayar Tagihan &rarr;</a>
            @else
                <p class="text-2xl font-black text-foreground mt-1 tracking-tight">Rp 0</p>
                <p class="text-xs text-success font-bold mt-1 flex items-center gap-1"><i data-lucide="check-circle-2" class="size-3.5"></i> Semua tagihan lunas.</p>
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