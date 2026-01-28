@extends('layouts.app')

@section('title', 'Detail Kamar ' . $kamar->nomor_kamar . ' - MyKos')

@section('content')
<div class="bg-slate-50 min-h-screen pt-8 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <nav class="flex mb-6 text-sm font-medium text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-brand-600 transition">Beranda</a>
            <span class="mx-2 text-slate-300">/</span>
            <a href="{{ route('kamar.index') }}" class="hover:text-brand-600 transition">Kamar</a>
            <span class="mx-2 text-slate-300">/</span>
            <span class="text-brand-600 font-bold">Detail Kamar {{ $kamar->nomor_kamar }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-2xl p-2 shadow-sm border border-slate-100">
                    <div class="relative h-[300px] lg:h-[400px] rounded-xl overflow-hidden group">
                        @if($kamar->gambar)
                            <img src="{{ asset('storage/' . $kamar->gambar) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                        @else
                            <img src="https://images.unsplash.com/photo-1522771753035-4a5046216955?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                        @endif
                        <div class="absolute bottom-4 right-4 bg-black/50 backdrop-blur px-3 py-1.5 rounded-lg text-xs font-medium text-white shadow-lg">
                            <i class="fas fa-expand mr-1"></i> Klik untuk perbesar
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 lg:p-8 shadow-sm border border-slate-100">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 pb-6 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-brand-600 font-bold tracking-wide text-[10px] uppercase bg-brand-50 px-2 py-0.5 rounded">
                                    {{ $kamar->tipe_kamar }}
                                </span>
                                <span class="text-slate-500 font-bold tracking-wide text-[10px] uppercase bg-slate-100 px-2 py-0.5 rounded">
                                    Lantai {{ $kamar->lantai }}
                                </span>
                            </div>
                            <h1 class="text-2xl lg:text-3xl font-extrabold text-slate-900">Kamar Nomor {{ $kamar->nomor_kamar }}</h1>
                            <p class="text-slate-500 mt-1 text-sm">
                                Luas kamar {{ $kamar->ukuran ?? 'Standar' }} m² • Sirkulasi udara baik
                            </p>
                        </div>
                        <div class="mt-4 md:mt-0 text-right">
                             <div class="text-2xl lg:text-3xl font-bold text-brand-600">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</div>
                             <div class="text-xs text-slate-400 font-medium">/ bulan</div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-slate-900 mb-3">Deskripsi Kamar</h3>
                        <div class="text-slate-600 leading-relaxed text-sm bg-slate-50 p-4 rounded-xl border border-slate-100">
                            {{ $kamar->deskripsi ?? 'Kamar ini didesain untuk kenyamanan maksimal dengan pencahayaan alami yang baik dan sirkulasi udara yang lancar. Cocok untuk mahasiswa maupun karyawan.' }}
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-slate-900 mb-4">Fasilitas Termasuk</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @php
                                $fasilitasDetail = [];
                                if(is_string($kamar->fasilitas)) {
                                    $fasilitasDetail = array_map('trim', explode(',', $kamar->fasilitas));
                                } elseif(is_array($kamar->fasilitas)) {
                                    $fasilitasDetail = $kamar->fasilitas;
                                }
                            @endphp

                            @if(count($fasilitasDetail) > 0)
                                @foreach($fasilitasDetail as $item)
                                <div class="flex items-center p-3 rounded-xl bg-white border border-slate-200">
                                    <div class="w-8 h-8 rounded-full bg-brand-50 flex items-center justify-center text-brand-600 shadow-sm mr-3">
                                        <i class="fas fa-check text-xs"></i>
                                    </div>
                                    <span class="font-medium text-slate-700 text-sm">
                                        {{ is_object($item) ? ($item->nama_fasilitas ?? $item->nama) : $item }}
                                    </span>
                                </div>
                                @endforeach
                            @else
                                <p class="text-slate-400 italic text-sm">Tidak ada data fasilitas spesifik.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    <div class="bg-white rounded-2xl p-6 shadow-xl shadow-brand-900/5 border border-slate-100">
                        <div class="text-center mb-6 pb-6 border-b border-slate-50">
                            <p class="text-slate-400 text-xs uppercase tracking-wider font-bold mb-1">Total Sewa</p>
                            <h3 class="text-3xl font-bold text-slate-900">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</h3>
                            <span class="inline-block mt-2 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $kamar->status == 'tersedia' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $kamar->status == 'tersedia' ? 'Tersedia Sekarang' : 'Sudah Terisi' }}
                            </span>
                        </div>

                        <div class="space-y-3 mb-6 text-sm">
                            <div class="flex justify-between text-slate-500">
                                <span>Deposit Awal</span>
                                <span class="font-bold text-slate-900">Rp 500.000</span>
                            </div>
                            <div class="flex justify-between text-slate-500">
                                <span>Minimal Sewa</span>
                                <span class="font-bold text-slate-900">3 Bulan</span>
                            </div>
                            <div class="flex justify-between text-slate-500">
                                <span>Biaya Listrik</span>
                                <span class="font-bold text-slate-900">Token Sendiri</span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @if($kamar->status == 'tersedia')
                                @auth
                                    @if(Auth::user()->isCalonPenghuni())
                                    <a href="{{ route('booking.create', $kamar->id) }}" 
                                       class="w-full py-3.5 bg-brand-600 text-white rounded-xl font-bold hover:bg-brand-700 transition shadow-lg shadow-brand-600/30 flex items-center justify-center gap-2 transform active:scale-95">
                                        Booking Sekarang
                                    </a>
                                    @endif
                                @else
                                <a href="{{ route('login') }}" 
                                   class="w-full py-3.5 bg-brand-600 text-white rounded-xl font-bold hover:bg-brand-700 transition shadow-lg shadow-brand-600/30 flex items-center justify-center gap-2">
                                    Login untuk Booking
                                </a>
                                @endauth
                            @endif
                            
                            <a href="https://wa.me/6281234567890?text=Halo,%20saya%20tertarik%20dengan%20Kamar%20{{ $kamar->nomor_kamar }}" target="_blank"
                               class="w-full py-3.5 bg-white border border-green-500 text-green-600 rounded-xl font-bold hover:bg-green-50 transition flex items-center justify-center gap-2">
                                <i class="fab fa-whatsapp text-lg"></i> Chat Admin
                            </a>
                        </div>
                    </div>

                    <div class="bg-brand-900 rounded-2xl p-6 text-white text-center relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-white opacity-5 rounded-full -mr-10 -mt-10"></div>
                        <i class="fas fa-headset text-2xl mb-3 opacity-80"></i>
                        <h4 class="font-bold text-base mb-1">Butuh Bantuan?</h4>
                        <p class="text-brand-100 text-xs mb-3">Tim kami siap membantu Anda 24/7 jika ada pertanyaan.</p>
                        <a href="#" class="text-xs font-bold text-white underline hover:text-brand-200">Hubungi CS</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection