@extends('layouts.user')

@section('title', 'Dashboard Penghuni')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-foreground">Dashboard Penghuni</h1>
                <p class="text-secondary mt-1">Selamat datang kembali, <span class="font-bold text-primary">{{ Auth::user()->name }}</span>!</p>
            </div>
            <div class="inline-flex items-center gap-2 text-sm font-semibold text-secondary bg-white px-4 py-2.5 rounded-xl shadow-sm border border-border">
                <i data-lucide="calendar" class="size-4 text-primary"></i> 
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-white rounded-3xl shadow-sm border border-border overflow-hidden">
                    <div class="h-24 bg-gradient-to-r from-primary to-blue-400 relative">
                        <div class="absolute inset-0 bg-white/20" style="clip-path: polygon(0 0, 100% 0, 100% 100%, 0 75%);"></div>
                    </div>
                    
                    <div class="px-6 pb-6 relative flex flex-col items-center">
                        
                        <div class="w-24 h-24 rounded-full bg-white p-1.5 absolute -top-12 shadow-md ring-1 ring-border">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile" class="w-full h-full rounded-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=165DFF&color=fff&size=150&bold=true" alt="Profile" class="w-full h-full rounded-full object-cover">
                            @endif
                        </div>
                        
                        <div class="mt-14 text-center w-full">
                            <h3 class="font-bold text-xl text-foreground truncate">{{ Auth::user()->name }}</h3>
                            <span class="inline-flex items-center justify-center mt-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md {{ Auth::user()->role == 'penghuni' ? 'bg-success-light text-success' : 'bg-warning-light text-warning-dark' }}">
                                {{ str_replace('_', ' ', Auth::user()->role) }}
                            </span>
                        </div>
                        
                        <div class="w-full mt-6 space-y-3">
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-muted/40 border border-border/60">
                                <div class="size-8 rounded-lg bg-white flex items-center justify-center shrink-0 shadow-sm">
                                    <i data-lucide="mail" class="size-4 text-secondary"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-[10px] font-bold text-secondary uppercase tracking-wider mb-0.5">Email</p>
                                    <p class="text-sm font-medium text-foreground truncate">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-muted/40 border border-border/60">
                                <div class="size-8 rounded-lg bg-white flex items-center justify-center shrink-0 shadow-sm">
                                    <i data-lucide="phone" class="size-4 text-secondary"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-[10px] font-bold text-secondary uppercase tracking-wider mb-0.5">Telepon</p>
                                    <p class="text-sm font-medium text-foreground truncate">{{ Auth::user()->phone ?? 'Belum diisi' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="w-full mt-6 pt-6 border-t border-border">
                            <a href="{{ route('user.profile') }}" class="group flex items-center justify-center gap-2 w-full py-2.5 text-sm font-bold text-primary bg-primary/5 border border-primary/20 rounded-xl hover:bg-primary hover:text-white transition-all duration-300">
                                <i data-lucide="user-cog" class="size-4"></i> Edit Profil
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-border p-6">
                    <h4 class="font-bold text-foreground mb-4 text-sm uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="help-circle" class="size-4 text-primary"></i> Pusat Bantuan
                    </h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="#" class="flex items-center gap-3 p-3 rounded-xl bg-muted/30 hover:bg-success-light hover:text-success transition-colors text-sm font-semibold text-secondary group">
                                <div class="size-8 rounded-lg bg-success/10 text-success flex items-center justify-center group-hover:bg-success group-hover:text-white transition-all">
                                    <i data-lucide="message-circle" class="size-4"></i>
                                </div>
                                Hubungi Admin
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 p-3 rounded-xl bg-muted/30 hover:bg-primary/10 hover:text-primary transition-colors text-sm font-semibold text-secondary group">
                                <div class="size-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all">
                                    <i data-lucide="book-open" class="size-4"></i>
                                </div>
                                Tata Tertib Kos
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="lg:col-span-3 space-y-6">

                @if($activeBooking)
                    @php
                        $pembayaran = $activeBooking->pembayaran;
                        $isOverdue = $pembayaran ? $pembayaran->isOverdue() : false;
                        $isPaid = $pembayaran && $pembayaran->status == 'paid';
                        $jatuhTempo = $pembayaran ? \Carbon\Carbon::parse($pembayaran->tanggal_jatuh_tempo) : null;
                        
                        $showWarning = false;
                        if($jatuhTempo && !$isPaid && !$isOverdue) {
                            $diffDays = \Carbon\Carbon::now()->diffInDays($jatuhTempo, false);
                            if ($diffDays >= 0 && $diffDays <= 3) {
                                $showWarning = true;
                            }
                        }
                    @endphp

                    @if($isOverdue && !$isPaid)
                    <div class="bg-error-light border border-error/30 p-5 rounded-2xl shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-4 animate-pulse">
                        <div class="size-12 bg-error/20 rounded-full flex items-center justify-center shrink-0">
                            <i data-lucide="alert-triangle" class="size-6 text-error"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-bold text-error">Tagihan Jatuh Tempo!</h3>
                            <p class="text-sm text-error/80 mt-1">Masa pembayaran Kamar {{ $activeBooking->kamar->nomor_kamar }} telah habis pada <b>{{ $jatuhTempo->format('d M Y, H:i') }}</b>. Segera selesaikan pembayaran Anda.</p>
                        </div>
                        <a href="{{ route('booking.retry-payment', $pembayaran->id) }}" class="shrink-0 px-5 py-2.5 bg-error text-white text-sm font-bold rounded-xl hover:bg-error/90 transition-colors shadow-lg shadow-error/30">
                            Bayar Sekarang
                        </a>
                    </div>
                    @endif

                    @if($showWarning)
                    <div class="bg-warning-light border border-warning/40 p-5 rounded-2xl shadow-sm flex items-start gap-4">
                        <div class="size-10 bg-warning/20 rounded-full flex items-center justify-center shrink-0">
                            <i data-lucide="clock" class="size-5 text-warning-dark"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-warning-dark">Pengingat Pembayaran</h3>
                            <p class="text-sm text-warning-dark/80 mt-1">Batas pembayaran Anda berakhir dalam <b>{{ \Carbon\Carbon::now()->diffInDays($jatuhTempo) }} hari</b> lagi ({{ $jatuhTempo->format('d M Y') }}).</p>
                        </div>
                    </div>
                    @endif

                    <div class="bg-white rounded-3xl shadow-sm border border-border overflow-hidden">
                        <div class="p-5 md:p-6 border-b border-border flex justify-between items-center bg-muted/30">
                            <h2 class="font-bold text-foreground flex items-center gap-2">
                                <i data-lucide="key" class="size-5 text-primary"></i> Kamar Aktif Anda
                            </h2>
                            <span class="px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $activeBooking->status == 'confirmed' ? 'bg-success-light text-success' : 'bg-primary/10 text-primary' }}">
                                {{ $activeBooking->status == 'confirmed' ? 'Aktif' : ucfirst($activeBooking->status) }}
                            </span>
                        </div>
                        
                        <div class="p-5 md:p-8">
                            <div class="flex flex-col md:flex-row gap-6 md:gap-8">
                                <div class="w-full md:w-1/3 shrink-0">
                                    <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-muted relative ring-1 ring-border">
                                        @if($activeBooking->kamar->gambar)
                                            <img src="{{ asset('storage/' . $activeBooking->kamar->gambar) }}" class="object-cover w-full h-full" alt="Kamar">
                                        @else
                                            <div class="absolute inset-0 flex flex-col items-center justify-center text-secondary">
                                                <i data-lucide="bed-double" class="size-12 opacity-50 mb-2"></i>
                                                <span class="text-xs font-medium">No Image</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="w-full md:w-2/3 flex flex-col justify-between">
                                    <div>
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                                            <div>
                                                <h3 class="text-3xl font-bold text-foreground">Kamar {{ $activeBooking->kamar->nomor_kamar }}</h3>
                                                <p class="text-secondary font-medium mt-1">Tipe {{ ucfirst($activeBooking->kamar->tipe_kamar) }}</p>
                                            </div>
                                            <div class="sm:text-right bg-primary/5 p-3 rounded-xl border border-primary/10 inline-block">
                                                <p class="text-xs font-bold text-primary uppercase tracking-wider mb-1">Tagihan Bulanan</p>
                                                <p class="text-xl font-bold text-foreground">Rp {{ number_format($activeBooking->kamar->harga, 0, ',', '.') }}</p>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 mt-6">
                                            <div class="bg-muted/50 p-4 rounded-xl border border-border">
                                                <p class="text-xs font-semibold text-secondary uppercase tracking-wider mb-1">Tanggal Masuk</p>
                                                <p class="font-bold text-foreground flex items-center gap-2">
                                                    <i data-lucide="calendar-check" class="size-4 text-primary"></i> 
                                                    {{ $activeBooking->tanggal_masuk->format('d M Y') }}
                                                </p>
                                            </div>
                                            <div class="bg-muted/50 p-4 rounded-xl border border-border">
                                                <p class="text-xs font-semibold text-secondary uppercase tracking-wider mb-1">Durasi Sewa</p>
                                                <p class="font-bold text-foreground flex items-center gap-2">
                                                    <i data-lucide="hourglass" class="size-4 text-warning-dark"></i> 
                                                    {{ $activeBooking->durasi }} Bulan
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6 pt-6 border-t border-border flex flex-wrap items-center gap-3">
                                        @if($isPaid)
                                            <div class="inline-flex items-center gap-2 text-success font-bold bg-success-light px-5 py-2.5 rounded-xl border border-success/20">
                                                <i data-lucide="check-circle-2" class="size-5"></i> Lunas
                                            </div>
                                            <a href="{{ route('booking.receipt', $pembayaran->id) }}" class="inline-flex justify-center items-center px-5 py-2.5 border border-border font-bold rounded-xl text-foreground bg-white hover:bg-muted transition-colors shadow-sm">
                                                <i data-lucide="printer" class="size-4 mr-2"></i> Cetak Struk
                                            </a>
                                        @elseif($isOverdue)
                                            <a href="{{ route('booking.retry-payment', $pembayaran->id) }}" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-2.5 bg-error text-white font-bold rounded-xl hover:bg-error/90 shadow-lg shadow-error/30 transition-all">
                                                <i data-lucide="wallet" class="size-4 mr-2"></i> Bayar Tunggakan
                                            </a>
                                        @else
                                            <a href="{{ route('booking.payment', $pembayaran->id) }}" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-primary-hover shadow-lg shadow-primary/30 transition-all">
                                                <i data-lucide="credit-card" class="size-4 mr-2"></i> Bayar Sekarang
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    <div class="bg-white rounded-3xl shadow-sm border border-border p-12 text-center flex flex-col items-center justify-center">
                        <div class="size-24 bg-primary/10 rounded-full flex items-center justify-center mb-5">
                            <i data-lucide="home" class="size-12 text-primary"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-foreground mb-2">Belum Ada Kamar Aktif</h2>
                        <p class="text-secondary max-w-md mx-auto mb-8">Anda belum memiliki pesanan kamar yang aktif. Silakan lihat daftar kamar yang tersedia dan lakukan pemesanan.</p>
                        <a href="{{ route('home') }}#kamar" class="inline-flex items-center px-8 py-3.5 bg-primary text-white font-bold rounded-xl hover:bg-primary-hover shadow-lg shadow-primary/30 transition-all">
                            <i data-lucide="search" class="size-5 mr-2"></i> Cari Kamar Kos
                        </a>
                    </div>
                @endif

                <div class="bg-white rounded-3xl shadow-sm border border-border overflow-hidden">
                    <div class="p-6 border-b border-border">
                        <h2 class="font-bold text-foreground flex items-center gap-2">
                            <i data-lucide="history" class="size-5 text-secondary"></i> Riwayat Pesanan
                        </h2>
                    </div>
                    
                    @if(isset($bookingHistory) && $bookingHistory->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-border">
                                <thead class="bg-muted/50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">ID Ref</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Kamar</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-right text-xs font-semibold text-secondary uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-4 text-center text-xs font-semibold text-secondary uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border bg-white">
                                    @foreach($bookingHistory as $booking)
                                    <tr class="hover:bg-muted/30 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-xs font-mono font-bold text-foreground">
                                            #{{ $booking->pembayaran->kode_pembayaran ?? str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="font-bold text-foreground text-sm">Kamar {{ $booking->kamar->nomor_kamar }}</p>
                                            <p class="text-[11px] text-secondary mt-0.5">{{ ucfirst($booking->kamar->tipe_kamar) }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-foreground">
                                            {{ $booking->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $status = $booking->status;
                                                $badges = [
                                                    'pending' => 'bg-warning-light text-warning-dark',
                                                    'confirmed' => 'bg-success-light text-success',
                                                    'checked_in' => 'bg-primary/10 text-primary',
                                                    'cancelled' => 'bg-error-light text-error',
                                                ];
                                                $badgeClass = $badges[$status] ?? 'bg-muted text-secondary';
                                            @endphp
                                            <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                                                {{ $status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-foreground">
                                            Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($booking->pembayaran && $booking->pembayaran->status == 'paid')
                                                <a href="{{ route('booking.receipt', $booking->pembayaran->id) }}" class="inline-flex items-center justify-center p-2 rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition-colors" title="Lihat Struk">
                                                    <i data-lucide="receipt" class="size-4"></i>
                                                </a>
                                            @else
                                                <span class="text-secondary opacity-50"><i data-lucide="minus" class="size-4 mx-auto"></i></span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-10 text-center flex flex-col items-center">
                            <i data-lucide="history" class="size-12 text-muted mb-3"></i>
                            <p class="font-semibold text-foreground">Riwayat Kosong</p>
                            <p class="text-sm text-secondary mt-1">Belum ada riwayat transaksi masa lalu.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection