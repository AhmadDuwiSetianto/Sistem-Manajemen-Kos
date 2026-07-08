@extends('layouts.user')

@section('title', 'Dashboard Saya')

@section('content')
<div class="w-full max-w-6xl mx-auto px-4 md:px-0 pb-10">
    
    <div class="mb-6 md:mb-8 pt-6 md:pt-0">
        <h1 class="text-2xl md:text-3xl font-bold text-foreground tracking-tight">Halo, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
        <p class="text-xs md:text-sm text-secondary mt-1 font-medium">Selamat datang di panel pengelola akun Inna Kos Anda.</p>
    </div>

    @php
        $pendingPayment = auth()->user()->pembayarans()->where('status', 'pending')->latest()->first();
        
        $activeBooking = auth()->user()->bookings()
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->orderBy('tanggal_keluar', 'desc')
            ->first();

        $isPending = !is_null($pendingPayment);
        $theBooking = $isPending ? $pendingPayment->booking : $activeBooking;
        $theKamar = $theBooking ? $theBooking->kamar : null;

        $trueCheckInDate = $theBooking ? $theBooking->tanggal_masuk : null;
        $totalDurasi = $theBooking ? $theBooking->durasi : 0;

        if ($theKamar && $theBooking) {
            // 1. Cari tanggal Check-in paling pertama di kamar ini
            $firstBooking = auth()->user()->bookings()
                ->where('kamar_id', $theKamar->id)
                ->whereIn('status', ['confirmed', 'checked_in'])
                ->orderBy('tanggal_masuk', 'asc')
                ->first();
                
            if ($firstBooking) {
                $trueCheckInDate = $firstBooking->tanggal_masuk;
                
                // 2. Akumulasi total durasi dari semua perpanjangan
                $totalDurasi = auth()->user()->bookings()
                    ->where('kamar_id', $theKamar->id)
                    ->whereIn('status', ['confirmed', 'checked_in'])
                    ->sum('durasi');
                    
                // Jika sedang ada tagihan perpanjangan yang belum dibayar, tambahkan ke total
                if ($isPending && $pendingPayment->booking->status == 'pending') {
                    $totalDurasi += $pendingPayment->booking->durasi;
                }
            }
        }
    @endphp

    @if($pendingPayment)
        @php
            $dueDate = \Carbon\Carbon::parse($pendingPayment->tanggal_jatuh_tempo);
            $now = \Carbon\Carbon::now();
            $diffHours = $now->diffInHours($dueDate, false);
        @endphp

        @if($diffHours > 0 && $diffHours <= 48)
            <div class="mb-6 md:mb-8 p-4 md:p-5 rounded-2xl bg-orange-50 border border-orange-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-start gap-3 md:gap-4">
                    <div class="size-10 md:size-12 rounded-full bg-orange-100 flex items-center justify-center shrink-0">
                        <i data-lucide="alert-triangle" class="size-5 md:size-6 text-orange-600"></i>
                    </div>
                    <div>
                        <h3 class="text-sm md:text-base font-bold text-orange-800">Peringatan Pembayaran!</h3>
                        <p class="text-xs md:text-sm text-orange-700 mt-1 font-medium">
                            Batas waktu pembayaran kamar tersisa <strong>{{ ceil($diffHours) }} jam</strong>.<br>
                            <span class="text-[10px] md:text-xs opacity-80">(Jatuh tempo: {{ $dueDate->translatedFormat('d M Y, H:i') }} WIB)</span>
                        </p>
                    </div>
                </div>
                <a href="{{ route('booking.payment', $pendingPayment->id) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 sm:py-2.5 bg-orange-600 text-white text-sm font-bold rounded-xl hover:bg-orange-700 transition-colors shrink-0 shadow-lg shadow-orange-600/20">
                    Selesaikan Pembayaran
                </a>
            </div>
        @elseif($diffHours <= 0)
            <div class="mb-6 md:mb-8 p-4 md:p-5 rounded-2xl bg-red-50 border border-red-200 flex items-start gap-3 md:gap-4 shadow-sm">
                <div class="size-10 md:size-12 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                    <i data-lucide="x-octagon" class="size-5 md:size-6 text-red-600"></i>
                </div>
                <div>
                    <h3 class="text-sm md:text-base font-bold text-red-800">Pembayaran Melewati Batas Waktu</h3>
                    <p class="text-xs md:text-sm text-red-700 mt-1 font-medium leading-relaxed">
                        Waktu pembayaran telah habis. Sistem membatalkan pemesanan dan akun Anda dinonaktifkan sementara.
                    </p>
                </div>
            </div>
        @endif
    @endif

    @if($theBooking)
        <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-border overflow-hidden mb-6 md:mb-8">
            <div class="p-4 md:p-5 border-b border-border bg-muted/30 flex justify-between items-center">
                <h2 class="font-bold text-foreground text-xs md:text-sm flex items-center gap-1.5 md:gap-2">
                    <i data-lucide="info" class="size-4 md:size-5 text-primary"></i> 
                    Detail {{ $isPending ? 'Pesanan (Menunggu Pembayaran)' : 'Kamar Saat Ini' }}
                </h2>
                @if($isPending)
                    <span class="px-2 py-1 bg-warning-light text-orange-600 text-[9px] md:text-[10px] font-bold uppercase tracking-wider rounded border border-warning/30">Pending</span>
                @else
                    <span class="px-2 py-1 bg-success-light text-success text-[9px] md:text-[10px] font-bold uppercase tracking-wider rounded border border-success/30">Aktif</span>
                @endif
            </div>
            
            <div class="p-4 md:p-6 grid grid-cols-1 lg:grid-cols-12 gap-5 md:gap-6">
                <div class="lg:col-span-4 xl:col-span-3">
                    <div class="aspect-video md:aspect-[4/3] rounded-xl md:rounded-2xl overflow-hidden bg-muted ring-1 ring-border relative group">
                        @if($theKamar->gambar)
                            <img src="{{ \Illuminate\Support\Str::startsWith($theKamar->gambar, ['http://', 'https://']) ? $theKamar->gambar : asset('storage/' . $theKamar->gambar) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-secondary">
                                <i data-lucide="image-off" class="size-6 md:size-8 opacity-50 mb-1.5 md:mb-2"></i>
                                <span class="text-[10px] md:text-xs font-medium">Tanpa Foto</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="lg:col-span-8 xl:col-span-9 flex flex-col justify-center space-y-4 md:space-y-5">
                    <div>
                        <h3 class="font-black text-xl md:text-2xl text-foreground tracking-tight">Kamar {{ $theKamar->nomor_kamar }}</h3>
                        <p class="text-xs md:text-sm font-bold text-primary mt-1 bg-primary/10 inline-block px-2 py-0.5 md:px-2.5 md:py-1 rounded-md">Tipe {{ ucfirst($theKamar->tipe_kamar) }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                        <div class="bg-slate-50 p-3 md:p-4 rounded-xl border border-border">
                            <p class="text-[9px] md:text-[10px] font-bold text-secondary uppercase tracking-widest mb-1 md:mb-1.5 flex items-center gap-1 md:gap-1.5"><i data-lucide="clock-4" class="size-3 md:size-3.5"></i> Waktu Transaksi</p>
                            <p class="text-xs md:text-sm font-semibold text-foreground">{{ \Carbon\Carbon::parse($theBooking->created_at)->translatedFormat('d M Y, H:i') }} WIB</p>
                        </div>
                        
                        @if($isPending)
                        <div class="bg-orange-50 p-3 md:p-4 rounded-xl border border-orange-200">
                            <p class="text-[9px] md:text-[10px] font-bold text-orange-600 uppercase tracking-widest mb-1 md:mb-1.5 flex items-center gap-1 md:gap-1.5"><i data-lucide="alert-circle" class="size-3 md:size-3.5"></i> Jatuh Tempo</p>
                            <p class="text-xs md:text-sm font-bold text-orange-700">{{ \Carbon\Carbon::parse($pendingPayment->tanggal_jatuh_tempo)->translatedFormat('d M Y, H:i') }} WIB</p>
                        </div>
                        @else
                        <div class="bg-success-light/50 p-3 md:p-4 rounded-xl border border-success/20 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0">
                            <div>
                                <p class="text-[9px] md:text-[10px] font-bold text-success uppercase tracking-widest mb-1 md:mb-1.5 flex items-center gap-1 md:gap-1.5"><i data-lucide="calendar-check" class="size-3 md:size-3.5"></i> Berakhir Pada</p>
                                <p class="text-xs md:text-sm font-bold text-success">{{ \Carbon\Carbon::parse($theBooking->tanggal_keluar)->translatedFormat('d F Y') }}</p>
                            </div>
                            <a href="{{ route('booking.extend', $theBooking->id) }}" class="w-full sm:w-auto justify-center sm:justify-start px-3 py-2 sm:py-1.5 bg-success text-white text-[10px] md:text-xs font-bold rounded-lg hover:bg-green-600 transition-colors shadow-sm shadow-success/30 flex items-center gap-1.5">
                                <i data-lucide="calendar-plus" class="size-3.5"></i> Perpanjang
                            </a>
                        </div>
                        @endif

                        <div class="bg-slate-50 p-4 md:p-5 rounded-xl border border-border sm:col-span-2 relative">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-[10px] md:text-xs font-bold text-secondary uppercase tracking-widest">Detail Masa Sewa</span>
                                <span class="text-[10px] font-bold text-primary bg-primary/10 border border-primary/20 px-2.5 py-1 rounded-md shadow-sm">Total Inap: {{ $totalDurasi }} Bulan</span>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
                                <div class="flex items-center gap-3 flex-1">
                                    <div class="size-10 bg-white border border-border rounded-lg flex items-center justify-center shrink-0 shadow-sm">
                                        <i data-lucide="log-in" class="size-5 text-primary"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] md:text-xs text-secondary font-medium mb-0.5">Check-in Awal</p>
                                        <p class="text-sm md:text-base font-bold text-foreground">{{ \Carbon\Carbon::parse($trueCheckInDate)->translatedFormat('d M Y') }}</p>
                                    </div>
                                </div>

                                <div class="hidden sm:block w-px h-10 bg-border"></div>

                                <div class="flex items-center gap-3 flex-1">
                                    <div class="size-10 bg-white border border-border rounded-lg flex items-center justify-center shrink-0 shadow-sm">
                                        <i data-lucide="log-out" class="size-5 text-error"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] md:text-xs text-secondary font-medium mb-0.5">Check-out Akhir</p>
                                        <p class="text-sm md:text-base font-bold text-foreground">{{ \Carbon\Carbon::parse($theBooking->tanggal_keluar)->translatedFormat('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8 md:mb-10">
        <div class="flex flex-col rounded-2xl md:rounded-3xl border border-border p-5 md:p-6 bg-white shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <div class="size-10 md:size-12 bg-primary/10 rounded-xl md:rounded-2xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                    <i data-lucide="shield-check" class="size-5 md:size-6"></i>
                </div>
                <span class="px-2 md:px-3 py-1 bg-success-light text-success text-[9px] md:text-[10px] font-bold uppercase tracking-wider rounded border border-success/20">Aktif</span>
            </div>
            <h3 class="text-secondary text-xs md:text-sm font-medium">Status Akun</h3>
            <p class="text-xl md:text-2xl font-black text-foreground mt-0.5 md:mt-1 tracking-tight">{{ Auth::user()->isPenghuni() ? 'Penghuni' : 'Calon Penghuni' }}</p>
        </div>

        <div class="flex flex-col rounded-2xl md:rounded-3xl border border-border p-5 md:p-6 bg-white shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <div class="size-10 md:size-12 bg-blue-50 rounded-xl md:rounded-2xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="door-open" class="size-5 md:size-6"></i>
                </div>
            </div>
            <h3 class="text-secondary text-xs md:text-sm font-medium">Kamar Anda</h3>
            @if($activeBooking)
                <p class="text-xl md:text-2xl font-black text-foreground mt-0.5 md:mt-1 tracking-tight">Kamar {{ $activeBooking->kamar->nomor_kamar ?? '-' }}</p>
                <p class="text-[10px] md:text-xs text-secondary mt-0.5 md:mt-1 font-medium">Tipe {{ ucfirst($activeBooking->kamar->tipe_kamar) }}</p>
            @else
                <p class="text-base md:text-lg font-black text-foreground mt-0.5 md:mt-1 tracking-tight">Belum Ada Kamar</p>
                <a href="{{ route('home') }}#kamar" class="text-[10px] md:text-xs text-primary font-bold hover:underline mt-1">Pesan Kamar Sekarang &rarr;</a>
            @endif
        </div>

        <div class="flex flex-col rounded-2xl md:rounded-3xl border border-border p-5 md:p-6 bg-white shadow-sm hover:shadow-md transition-all group sm:col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between mb-3 md:mb-4">
                <div class="size-10 md:size-12 bg-warning-light rounded-xl md:rounded-2xl flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform">
                    <i data-lucide="receipt" class="size-5 md:size-6"></i>
                </div>
            </div>
            <h3 class="text-secondary text-xs md:text-sm font-medium">Tagihan Belum Dibayar</h3>
            @if($pendingPayment)
                <p class="text-xl md:text-2xl font-black text-foreground mt-0.5 md:mt-1 tracking-tight">Rp {{ number_format($pendingPayment->jumlah, 0, ',', '.') }}</p>
                <a href="{{ route('booking.payment', $pendingPayment->id) }}" class="text-[10px] md:text-xs text-orange-600 font-bold hover:underline mt-1">Bayar Tagihan &rarr;</a>
            @else
                <p class="text-xl md:text-2xl font-black text-foreground mt-0.5 md:mt-1 tracking-tight">Rp 0</p>
                <p class="text-[10px] md:text-xs text-success font-bold mt-1 flex items-center gap-1"><i data-lucide="check-circle-2" class="size-3 md:size-3.5"></i> Semua tagihan lunas.</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-border overflow-hidden">
        <div class="p-4 md:p-5 border-b border-border bg-muted/30 flex justify-between items-center">
            <h2 class="font-bold text-foreground text-xs md:text-sm flex items-center gap-1.5 md:gap-2">
                <i data-lucide="list" class="size-4 md:size-5 text-primary"></i> 
                Riwayat Pesanan Terakhir
            </h2>
            <a href="{{ route('user.bookings') }}" class="text-[10px] md:text-xs font-bold text-primary hover:underline">Lihat Semua</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead>
                    <tr class="bg-slate-50 text-[9px] md:text-[10px] uppercase tracking-widest text-secondary font-bold">
                        <th class="p-3 md:p-4 border-b border-border">Kamar</th>
                        <th class="p-3 md:p-4 border-b border-border">Periode Sewa</th>
                        <th class="p-3 md:p-4 border-b border-border">Total Pembayaran</th>
                        <th class="p-3 md:p-4 border-b border-border">Status</th>
                        <th class="p-3 md:p-4 border-b border-border text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs md:text-sm">
                    @forelse(auth()->user()->bookings()->latest()->take(5)->get() as $item)
                        <tr class="hover:bg-muted/50 transition-colors group">
                            <td class="p-3 md:p-4 border-b border-border">
                                <p class="font-bold text-foreground">Kamar {{ $item->kamar->nomor_kamar ?? 'N/A' }}</p>
                                <p class="text-[10px] md:text-xs text-secondary mt-0.5">Durasi: {{ $item->durasi }} Bln</p>
                            </td>
                            <td class="p-3 md:p-4 border-b border-border">
                                <p class="font-semibold text-foreground text-[11px] md:text-sm">{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d M Y') }}</p>
                                <p class="text-[9px] md:text-[10px] text-secondary">s/d {{ \Carbon\Carbon::parse($item->tanggal_keluar)->format('d M Y') }}</p>
                            </td>
                            <td class="p-3 md:p-4 border-b border-border font-bold text-foreground text-[11px] md:text-sm">
                                Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="p-3 md:p-4 border-b border-border">
                                <span class="px-2 md:px-2.5 py-1 text-[9px] md:text-[10px] font-bold uppercase tracking-wider rounded border border-current {{ str_contains($item->status_badge_class, 'success') ? 'bg-success-light text-success' : (str_contains($item->status_badge_class, 'warning') ? 'bg-warning-light text-warning-dark' : 'bg-muted text-secondary') }}">
                                    {{ $item->status_display }}
                                </span>
                            </td>
                            <td class="p-3 md:p-4 border-b border-border text-center">
                                <a href="{{ route('user.bookings.show', $item->id) }}" class="inline-flex items-center justify-center size-7 md:size-8 bg-white border border-border text-secondary hover:text-primary hover:border-primary/50 rounded-lg transition-colors" title="Lihat Detail">
                                    <i data-lucide="eye" class="size-3.5 md:size-4"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 md:p-8 text-center text-secondary">
                                <i data-lucide="inbox" class="size-6 md:size-8 mx-auto opacity-50 mb-2"></i>
                                <p class="font-medium text-xs md:text-sm">Belum ada riwayat pesanan kamar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection