@extends('layouts.app')

@section('title', 'Dashboard Penghuni')

@section('content')
<div class="min-h-screen bg-slate-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-800">
                    Dashboard Penghuni
                </h1>
                <p class="text-slate-500 mt-1">
                    Selamat datang kembali, <span class="font-semibold text-brand-600">{{ Auth::user()->name }}</span>!
                </p>
            </div>
            <div class="text-sm text-slate-500 bg-white px-4 py-2 rounded-full shadow-sm border border-slate-200">
                <i class="far fa-calendar-alt mr-2"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden relative">
                    <div class="h-24 bg-gradient-to-r from-brand-600 to-blue-500"></div>
                    <div class="px-6 pb-6 relative">
                        <div class="w-20 h-20 rounded-full bg-white p-1 absolute -top-10 left-1/2 transform -translate-x-1/2 shadow-md">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff" 
                                 alt="Profile" 
                                 class="w-full h-full rounded-full object-cover">
                        </div>
                        <div class="mt-12 text-center">
                            <h3 class="font-bold text-lg text-slate-800">{{ Auth::user()->name }}</h3>
                            <span class="inline-block px-3 py-1 mt-2 text-xs font-semibold rounded-full 
                                {{ Auth::user()->role == 'penghuni' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                            </span>
                        </div>
                        
                        <div class="mt-6 space-y-3">
                            <div class="flex items-center text-sm text-slate-600">
                                <i class="fas fa-envelope w-6 text-slate-400"></i>
                                <span class="truncate">{{ Auth::user()->email }}</span>
                            </div>
                            <div class="flex items-center text-sm text-slate-600">
                                <i class="fas fa-phone w-6 text-slate-400"></i>
                                <span>{{ Auth::user()->phone ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-slate-100">
                            <a href="{{ route('user.profile') }}" class="block w-full py-2 text-center text-sm font-semibold text-brand-600 border border-brand-600 rounded-lg hover:bg-brand-50 transition">
                                <i class="fas fa-edit mr-1"></i> Edit Profil
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h4 class="font-bold text-slate-800 mb-4 text-sm uppercase tracking-wide">Pusat Bantuan</h4>
                    <ul class="space-y-3 text-sm">
                        <li>
                            <a href="#" class="flex items-center text-slate-600 hover:text-brand-600 transition">
                                <i class="fab fa-whatsapp w-6 text-green-500 text-lg"></i> Hubungi Admin
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center text-slate-600 hover:text-brand-600 transition">
                                <i class="fas fa-book w-6 text-blue-400"></i> Tata Tertib Kos
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
                        
                        // Logic Warning H-3
                        $showWarning = false;
                        if($jatuhTempo && !$isPaid && !$isOverdue) {
                            $diffDays = \Carbon\Carbon::now()->diffInDays($jatuhTempo, false);
                            // Jika selisih positif dan kurang dari 3 hari
                            if ($diffDays >= 0 && $diffDays <= 3) {
                                $showWarning = true;
                            }
                        }
                    @endphp

                    @if($isOverdue && !$isPaid)
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm animate-pulse">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                            </div>
                            <div class="ml-3 w-full">
                                <h3 class="text-sm font-bold text-red-800">PEMBAYARAN KEDALUWARSA / JATUH TEMPO</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>Masa pembayaran untuk Kamar {{ $activeBooking->kamar->nomor_kamar }} telah habis pada <b>{{ $jatuhTempo->format('d M Y H:i') }}</b>.</p>
                                    <p class="mt-1">Silakan lakukan pembayaran segera atau hubungi admin jika ada kendala.</p>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('booking.retry-payment', $pembayaran->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        Bayar Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($showWarning)
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <span class="font-bold">Pengingat:</span> Batas pembayaran Anda berakhir dalam {{ \Carbon\Carbon::now()->diffInDays($jatuhTempo) }} hari lagi ({{ $jatuhTempo->format('d M Y') }}).
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                            <h2 class="font-bold text-slate-800 flex items-center gap-2">
                                <i class="fas fa-key text-brand-600"></i> Kamar Aktif Saat Ini
                            </h2>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                                {{ $activeBooking->status == 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $activeBooking->status == 'confirmed' ? 'Aktif' : ucfirst($activeBooking->status) }}
                            </span>
                        </div>
                        
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row gap-6">
                                <div class="w-full md:w-1/3">
                                    <div class="aspect-w-16 aspect-h-9 rounded-xl overflow-hidden bg-slate-100 relative">
                                        @if($activeBooking->kamar->gambar)
                                            <img src="{{ asset('storage/' . $activeBooking->kamar->gambar) }}" class="object-cover w-full h-full" alt="Kamar">
                                        @else
                                            <div class="flex items-center justify-center h-48 bg-slate-200 text-slate-400">
                                                <i class="fas fa-bed text-4xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="w-full md:w-2/3 flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="text-2xl font-bold text-slate-800">Kamar {{ $activeBooking->kamar->nomor_kamar }}</h3>
                                                <p class="text-slate-500 text-sm">Tipe {{ ucfirst($activeBooking->kamar->tipe_kamar) }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs text-slate-400 uppercase">Harga Sewa</p>
                                                <p class="text-lg font-bold text-brand-600">Rp {{ number_format($activeBooking->kamar->harga, 0, ',', '.') }}<span class="text-xs font-normal text-slate-500">/bln</span></p>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 mt-6">
                                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                                <p class="text-xs text-slate-400 mb-1">Tanggal Masuk</p>
                                                <p class="font-semibold text-slate-700">{{ $activeBooking->tanggal_masuk->format('d M Y') }}</p>
                                            </div>
                                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                                <p class="text-xs text-slate-400 mb-1">Durasi Sewa</p>
                                                <p class="font-semibold text-slate-700">{{ $activeBooking->durasi }} Bulan</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6 pt-4 border-t border-slate-100 flex flex-wrap gap-3">
                                        @if($isPaid)
                                            <div class="flex items-center gap-2 text-green-600 font-bold bg-green-50 px-4 py-2 rounded-lg border border-green-100">
                                                <i class="fas fa-check-circle"></i> Pembayaran Lunas
                                            </div>
                                            <a href="{{ route('booking.receipt', $pembayaran->id) }}" class="inline-flex items-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition">
                                                <i class="fas fa-print mr-2"></i> Cetak Struk
                                            </a>
                                        @elseif($isOverdue)
                                            <a href="{{ route('booking.retry-payment', $pembayaran->id) }}" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-2.5 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 shadow-lg shadow-red-500/30 transition animate-bounce">
                                                <i class="fas fa-wallet mr-2"></i> Bayar Tagihan (Overdue)
                                            </a>
                                        @else
                                            <a href="{{ route('booking.payment', $pembayaran->id) }}" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-2.5 bg-brand-600 text-white font-bold rounded-lg hover:bg-brand-700 shadow-lg shadow-brand-600/30 transition">
                                                <i class="fas fa-wallet mr-2"></i> Bayar Sekarang
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-10 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-home text-slate-300 text-4xl"></i>
                        </div>
                        <h2 class="text-xl font-bold text-slate-800 mb-2">Anda Belum Menyewa Kamar</h2>
                        <p class="text-slate-500 mb-6 max-w-md mx-auto">Silakan cari kamar yang sesuai dengan keinginan Anda dan lakukan pemesanan sekarang.</p>
                        <a href="{{ route('home') }}#kamar" class="inline-flex items-center px-6 py-3 bg-brand-600 text-white font-bold rounded-xl hover:bg-brand-700 shadow-lg transition">
                            <i class="fas fa-search mr-2"></i> Cari Kamar Kos
                        </a>
                    </div>
                @endif

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 border-b border-slate-100">
                        <h2 class="font-bold text-slate-800">Riwayat Pesanan</h2>
                    </div>
                    
                    @if($bookingHistory->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-slate-50 text-slate-500 font-bold border-b border-slate-100">
                                    <tr>
                                        <th class="px-6 py-4">ID Pesanan</th>
                                        <th class="px-6 py-4">Kamar</th>
                                        <th class="px-6 py-4">Tanggal</th>
                                        <th class="px-6 py-4">Status</th>
                                        <th class="px-6 py-4 text-right">Total</th>
                                        <th class="px-6 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($bookingHistory as $booking)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                            #{{ $booking->pembayaran->kode_pembayaran ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-800">Kamar {{ $booking->kamar->nomor_kamar }}</div>
                                            <div class="text-xs text-slate-500">{{ ucfirst($booking->kamar->tipe_kamar) }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">
                                            {{ $booking->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $status = $booking->status;
                                                $badges = [
                                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                                    'confirmed' => 'bg-green-100 text-green-700',
                                                    'checked_in' => 'bg-blue-100 text-blue-700',
                                                    'cancelled' => 'bg-red-100 text-red-700',
                                                ];
                                                $badgeClass = $badges[$status] ?? 'bg-gray-100 text-gray-700';
                                            @endphp
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $badgeClass }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-slate-700">
                                            Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($booking->pembayaran && $booking->pembayaran->status == 'paid')
                                                <a href="{{ route('booking.receipt', $booking->pembayaran->id) }}" class="text-brand-600 hover:text-brand-800 text-xs font-bold border border-brand-200 px-3 py-1 rounded hover:bg-brand-50 transition">
                                                    Lihat Struk
                                                </a>
                                            @else
                                                <span class="text-slate-400 text-xs">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-8 text-center text-slate-500">
                            <i class="fas fa-history text-3xl mb-3 text-slate-300"></i>
                            <p>Belum ada riwayat pesanan.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection