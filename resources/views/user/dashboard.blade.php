@extends('layouts.user')

@section('title', 'Dashboard Penghuni')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-foreground tracking-tight">Dashboard Penghuni</h1>
                <p class="text-secondary mt-1 font-medium">Selamat datang kembali, <span class="font-bold text-primary">{{ Auth::user()->name }}</span>!</p>
            </div>
            <div class="inline-flex items-center gap-2.5 text-sm font-bold text-secondary bg-white px-4 py-2.5 rounded-xl shadow-sm border border-border">
                <i data-lucide="calendar" class="size-4 text-primary"></i> 
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
            
            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-white rounded-[2rem] shadow-sm border border-border overflow-hidden">
                    <div class="h-24 bg-gradient-to-r from-primary to-blue-400 relative">
                        <div class="absolute inset-0 bg-white/20" style="clip-path: polygon(0 0, 100% 0, 100% 100%, 0 75%);"></div>
                    </div>
                    
                    <div class="px-6 pb-6 relative flex flex-col items-center">
                        
                        <div class="w-24 h-24 rounded-full bg-white p-1 absolute -top-12 shadow-md ring-1 ring-border">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile" class="w-full h-full rounded-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=165DFF&color=fff&size=150&bold=true" alt="Profile" class="w-full h-full rounded-full object-cover">
                            @endif
                        </div>
                        
                        <div class="mt-14 text-center w-full">
                            <h3 class="font-black text-xl text-foreground truncate">{{ Auth::user()->name }}</h3>
                            <span class="inline-flex items-center justify-center mt-1.5 px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-md {{ Auth::user()->role == 'penghuni' ? 'bg-success-light text-success' : 'bg-warning-light text-warning-dark' }}">
                                {{ str_replace('_', ' ', Auth::user()->role) }}
                            </span>
                        </div>
                        
                        <div class="w-full mt-6 space-y-3">
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <div class="size-8 rounded-lg bg-white flex items-center justify-center shrink-0 shadow-sm">
                                    <i data-lucide="mail" class="size-4 text-secondary"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-[10px] font-bold text-secondary uppercase tracking-widest mb-0.5">Email</p>
                                    <p class="text-sm font-bold text-foreground truncate">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <div class="size-8 rounded-lg bg-white flex items-center justify-center shrink-0 shadow-sm">
                                    <i data-lucide="phone" class="size-4 text-secondary"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-[10px] font-bold text-secondary uppercase tracking-widest mb-0.5">Telepon</p>
                                    <p class="text-sm font-bold text-foreground truncate">{{ Auth::user()->phone ?? 'Belum diisi' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="w-full mt-6 pt-6 border-t border-border">
                            <a href="{{ route('user.profile') }}" class="group flex items-center justify-center gap-2 w-full py-3 text-sm font-bold text-primary bg-primary/5 border border-primary/20 rounded-xl hover:bg-primary hover:text-white transition-all duration-300">
                                <i data-lucide="user-cog" class="size-4"></i> Edit Profil
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] shadow-sm border border-border p-6 md:p-8">
                    <h4 class="font-black text-foreground mb-4 text-xs uppercase tracking-widest flex items-center gap-2">
                        <i data-lucide="help-circle" class="size-4 text-primary"></i> Pusat Bantuan
                    </h4>
                    <ul class="space-y-3">
                        <li>
                            <a href="https://wa.me/6281234567890" target="_blank" class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-success-light hover:text-success transition-colors text-sm font-bold text-secondary group">
                                <div class="size-8 rounded-lg bg-success/10 text-success flex items-center justify-center group-hover:bg-success group-hover:text-white transition-all">
                                    <i data-lucide="message-circle" class="size-4"></i>
                                </div>
                                Hubungi Admin
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-primary/10 hover:text-primary transition-colors text-sm font-bold text-secondary group">
                                <div class="size-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all">
                                    <i data-lucide="book-open" class="size-4"></i>
                                </div>
                                Tata Tertib Kos
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="lg:col-span-3 space-y-6 lg:space-y-8">

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
                            <p class="text-sm text-error/80 mt-1 font-medium">Masa pembayaran Kamar {{ $activeBooking->kamar->nomor_kamar }} telah habis pada <b>{{ $jatuhTempo->format('d M Y, H:i') }}</b>. Segera selesaikan pembayaran Anda.</p>
                        </div>
                        <a href="{{ route('booking.retry-payment', $pembayaran->id) }}" class="shrink-0 w-full sm:w-auto px-6 py-3 bg-error text-white text-sm font-bold rounded-xl hover:bg-error/90 transition-colors shadow-lg shadow-error/30 text-center">
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
                            <p class="text-sm text-warning-dark/80 mt-1 font-medium">Batas pembayaran Anda berakhir dalam <b>{{ \Carbon\Carbon::now()->diffInDays($jatuhTempo) }} hari</b> lagi ({{ $jatuhTempo->format('d M Y') }}).</p>
                        </div>
                    </div>
                    @endif

                    <div class="bg-white rounded-[2rem] shadow-sm border border-border overflow-hidden relative">
                        
                        <div class="p-6 border-b border-border flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 bg-slate-50/50">
                            <h2 class="font-black text-lg text-foreground flex items-center gap-2">
                                <i data-lucide="key" class="size-5 text-primary"></i> Kamar Aktif Anda
                            </h2>
                            <span class="inline-flex w-max px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $activeBooking->status == 'confirmed' ? 'bg-success-light border border-success/20 text-success' : 'bg-primary/10 border border-primary/20 text-primary' }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $activeBooking->status == 'confirmed' ? 'bg-success' : 'bg-primary' }}"></span>
                                {{ $activeBooking->status == 'confirmed' ? 'Status Aktif' : ucfirst($activeBooking->status) }}
                            </span>
                        </div>
                        
                        <div class="p-6 md:p-8">
                            <div class="flex flex-col md:flex-row gap-8">
                                
                                <div class="w-full md:w-1/3 shrink-0">
                                    <div class="aspect-[4/3] rounded-[1.5rem] overflow-hidden bg-slate-100 relative border border-slate-200 shadow-inner group">
                                        @if($activeBooking->kamar->gambar)
                                            <img src="{{ asset('storage/' . $activeBooking->kamar->gambar) }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" alt="Kamar">
                                        @else
                                            <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-400">
                                                <i data-lucide="bed-double" class="size-12 opacity-50 mb-2"></i>
                                                <span class="text-xs font-bold uppercase tracking-wider">No Image</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="w-full md:w-2/3 flex flex-col">
                                    
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-6">
                                        <div>
                                            <p class="text-[10px] font-bold text-secondary uppercase tracking-widest mb-1">Nomor Kamar</p>
                                            <h3 class="text-3xl font-black text-foreground">{{ $activeBooking->kamar->nomor_kamar }}</h3>
                                            <span class="inline-block mt-2 px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-widest rounded-md border border-slate-200">
                                                Tipe {{ $activeBooking->kamar->tipe_kamar }}
                                            </span>
                                        </div>
                                        <div class="sm:text-right bg-brand-50 px-4 py-3 rounded-xl border border-brand-100 shrink-0">
                                            <p class="text-[10px] font-bold text-brand-600 uppercase tracking-widest mb-0.5">Tagihan Bulanan</p>
                                            <p class="text-xl font-black text-brand-600">Rp {{ number_format($activeBooking->kamar->harga, 0, ',', '.') }}</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-8">
                                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex flex-col justify-center">
                                            <p class="text-[10px] font-bold text-secondary uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                                <i data-lucide="calendar-check" class="size-3 text-primary"></i> Tanggal Masuk
                                            </p>
                                            <p class="font-bold text-sm sm:text-base text-foreground">
                                                {{ $activeBooking->tanggal_masuk->translatedFormat('d M Y') }}
                                            </p>
                                        </div>
                                        
                                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex flex-col justify-center">
                                            <p class="text-[10px] font-bold text-secondary uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                                                <i data-lucide="hourglass" class="size-3 text-warning-dark"></i> Durasi Sewa
                                            </p>
                                            <p class="font-bold text-sm sm:text-base text-foreground">
                                                {{ $activeBooking->durasi }} Bulan
                                            </p>
                                        </div>
                                    </div>

                                    <div class="mt-auto pt-6 border-t border-dashed border-border flex flex-col sm:flex-row items-center gap-3">
                                        @if($isPaid)
                                            <div class="w-full sm:w-auto inline-flex justify-center items-center gap-2 text-success font-black text-sm uppercase tracking-wider bg-success-light px-6 py-3 rounded-xl border border-success/30">
                                                <i data-lucide="check-circle-2" class="size-4"></i> LUNAS
                                            </div>
                                            <a href="{{ route('booking.receipt', $pembayaran->id) }}" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-border font-bold text-sm rounded-xl text-foreground bg-white hover:bg-slate-50 transition-colors shadow-sm">
                                                <i data-lucide="printer" class="size-4 mr-2"></i> Cetak Struk
                                            </a>
                                        @elseif($isOverdue)
                                            <a href="{{ route('booking.retry-payment', $pembayaran->id) }}" class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3.5 bg-error text-white font-bold rounded-xl hover:bg-error/90 shadow-lg shadow-error/30 transition-all text-sm">
                                                <i data-lucide="wallet" class="size-4 mr-2"></i> Bayar Tunggakan
                                            </a>
                                        @else
                                            <a href="{{ route('booking.payment', $pembayaran->id) }}" class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3.5 bg-primary text-white font-bold rounded-xl hover:bg-primary-hover shadow-lg shadow-primary/30 transition-all text-sm">
                                                <i data-lucide="credit-card" class="size-4 mr-2"></i> Bayar Sekarang
                                            </a>
                                        @endif
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    <div class="bg-white rounded-[2rem] shadow-sm border border-dashed border-slate-300 p-12 text-center flex flex-col items-center justify-center min-h-[400px]">
                        <div class="size-24 bg-slate-50 rounded-full flex items-center justify-center mb-6 border border-slate-100">
                            <i data-lucide="home" class="size-10 text-slate-300"></i>
                        </div>
                        <h2 class="text-2xl font-black text-slate-800 mb-2">Belum Ada Kamar Aktif</h2>
                        <p class="text-slate-500 font-medium max-w-sm mx-auto mb-8">Anda belum memiliki pesanan kamar yang aktif saat ini. Jelajahi katalog kamar kami untuk mulai menyewa.</p>
                        <a href="{{ route('home') }}#kamar" class="inline-flex items-center px-8 py-4 bg-brand-600 text-white font-bold rounded-xl hover:bg-brand-700 shadow-lg shadow-brand-600/30 transition-all transform active:scale-95">
                            <i data-lucide="search" class="size-5 mr-2"></i> Cari Kamar Kos
                        </a>
                    </div>
                @endif

                <div class="bg-white rounded-[2rem] shadow-sm border border-border overflow-hidden">
                    <div class="p-6 border-b border-border bg-slate-50/50">
                        <h2 class="font-black text-lg text-foreground flex items-center gap-2">
                            <i data-lucide="history" class="size-5 text-secondary"></i> Riwayat Pesanan
                        </h2>
                    </div>
                    
                    @if(isset($bookingHistory) && $bookingHistory->count() > 0)
                        <div class="overflow-x-auto hide-scrollbar">
                            <table class="min-w-full divide-y divide-border">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-[10px] font-bold text-secondary uppercase tracking-widest whitespace-nowrap">ID Ref</th>
                                        <th class="px-6 py-4 text-left text-[10px] font-bold text-secondary uppercase tracking-widest whitespace-nowrap">Kamar</th>
                                        <th class="px-6 py-4 text-left text-[10px] font-bold text-secondary uppercase tracking-widest whitespace-nowrap">Tgl. Dibuat</th>
                                        <th class="px-6 py-4 text-left text-[10px] font-bold text-secondary uppercase tracking-widest whitespace-nowrap">Status</th>
                                        <th class="px-6 py-4 text-right text-[10px] font-bold text-secondary uppercase tracking-widest whitespace-nowrap">Total</th>
                                        <th class="px-6 py-4 text-center text-[10px] font-bold text-secondary uppercase tracking-widest whitespace-nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border bg-white">
                                    @foreach($bookingHistory as $booking)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-xs font-mono font-bold text-slate-700">
                                            #{{ $booking->pembayaran->kode_pembayaran ?? str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="font-black text-slate-800 text-sm">Kamar {{ $booking->kamar->nomor_kamar }}</p>
                                            <p class="text-[10px] font-bold text-secondary uppercase tracking-widest mt-0.5">{{ $booking->kamar->tipe_kamar }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-slate-600">
                                            {{ $booking->created_at->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $status = $booking->status;
                                                $badges = [
                                                    'pending' => 'bg-warning-light text-warning-dark border-warning/20',
                                                    'confirmed' => 'bg-success-light text-success border-success/20',
                                                    'checked_in' => 'bg-primary/10 text-primary border-primary/20',
                                                    'cancelled' => 'bg-error-light text-error border-error/20',
                                                ];
                                                $badgeClass = $badges[$status] ?? 'bg-slate-100 text-slate-500 border-slate-200';
                                            @endphp
                                            <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-widest border {{ $badgeClass }}">
                                                {{ str_replace('_', ' ', $status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right font-black text-slate-800 text-sm">
                                            Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($booking->pembayaran && $booking->pembayaran->status == 'paid')
                                                <a href="{{ route('booking.receipt', $booking->pembayaran->id) }}" class="inline-flex items-center justify-center size-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-primary hover:text-white transition-colors" title="Lihat Struk">
                                                    <i data-lucide="printer" class="size-4"></i>
                                                </a>
                                            @else
                                                <span class="text-slate-300"><i data-lucide="minus" class="size-4 mx-auto"></i></span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-12 text-center flex flex-col items-center">
                            <i data-lucide="file-clock" class="size-12 text-slate-300 mb-3"></i>
                            <p class="font-black text-slate-800">Belum Ada Riwayat Transaksi</p>
                            <p class="text-sm text-slate-500 font-medium mt-1">Transaksi masa lalu Anda akan muncul di sini.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    /* Menyembunyikan scrollbar bawaan di tabel riwayat untuk tampilan mobile yang bersih */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection