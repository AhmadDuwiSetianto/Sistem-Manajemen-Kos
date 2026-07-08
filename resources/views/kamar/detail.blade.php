@extends('layouts.app')

@section('title', 'Detail Kamar ' . $kamar->nomor_kamar . ' - MyKos')

@section('content')
<div class="bg-slate-50 min-h-screen pt-6 pb-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <nav class="flex items-center gap-1.5 mb-6 text-[11px] sm:text-sm font-medium text-slate-500 overflow-x-auto whitespace-nowrap hide-scrollbar pb-1">
            <a href="{{ route('home') }}" class="hover:text-brand-600 transition-colors flex items-center gap-1.5 shrink-0">
                <i data-lucide="home" class="size-3.5 sm:size-4"></i> Beranda
            </a>
            <i data-lucide="chevron-right" class="size-3.5 text-slate-300 shrink-0"></i>
            <a href="{{ route('kamar.index') }}" class="hover:text-brand-600 transition-colors shrink-0">Katalog Kamar</a>
            <i data-lucide="chevron-right" class="size-3.5 text-slate-300 shrink-0"></i>
            <span class="text-brand-600 font-bold bg-brand-50 border border-brand-100 px-2 py-1 rounded-md shrink-0">Detail Kamar {{ $kamar->nomor_kamar }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 items-start">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-3xl p-2 shadow-sm border border-slate-200">
                    <div class="relative h-[250px] sm:h-[350px] lg:h-[400px] rounded-[1.25rem] overflow-hidden group bg-slate-100">
                        @if($kamar->gambar)
                            <img src="{{ \Illuminate\Support\Str::startsWith($kamar->gambar, ['http://', 'https://']) ? \Illuminate\Support\Str::replace('/upload/', '/upload/q_auto,f_auto,w_800/', $kamar->gambar) : asset('storage/' . $kamar->gambar) }}" 
     loading="lazy" 
     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <img src="https://images.unsplash.com/photo-1522771753035-4a5046216955?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" class="w-full h-full object-cover grayscale-[20%] group-hover:scale-105 transition-transform duration-700">
                        @endif
                        
                        <div class="absolute top-4 left-4 {{ $kamar->status == 'tersedia' ? 'bg-success/90 border-success text-white' : 'bg-slate-800/90 border-slate-600 text-slate-200' }} px-3 py-1.5 rounded-lg text-[10px] sm:text-[11px] font-bold shadow-lg flex items-center gap-1.5 backdrop-blur-sm border uppercase tracking-wider">
                            @if($kamar->status == 'tersedia')
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> TERSEDIA
                            @else
                                <i data-lucide="lock" class="size-3"></i> SEDANG TERISI
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 lg:p-8 shadow-sm border border-slate-200">
                    
                    <div class="flex flex-col md:flex-row justify-between items-start mb-6 pb-6 border-b border-slate-100">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-brand-600 font-bold tracking-wider text-[10px] uppercase bg-brand-50 border border-brand-100 px-2.5 py-1 rounded-md">
                                    Tipe {{ $kamar->tipe_kamar }}
                                </span>
                                <span class="text-slate-500 font-bold tracking-wider text-[10px] uppercase bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-md flex items-center gap-1.5">
                                    <i data-lucide="layers" class="size-3"></i> Lantai {{ $kamar->lantai }}
                                </span>
                            </div>
                            
                            <h1 class="text-2xl lg:text-3xl font-extrabold text-slate-800 tracking-tight mt-1">Kamar {{ $kamar->nomor_kamar }}</h1>
                            
                            <p class="text-slate-500 mt-2 text-xs sm:text-sm flex flex-wrap items-center gap-3 sm:gap-4">
                                <span class="flex items-center gap-1.5"><i data-lucide="ruler" class="size-4"></i> {{ $kamar->ukuran ?? 'Standar' }} m²</span>
                                <span class="flex items-center gap-1.5"><i data-lucide="wind" class="size-4"></i> Sirkulasi Baik</span>
                            </p>
                        </div>
                        
                        <div class="mt-5 md:hidden w-full bg-slate-50 p-4 rounded-xl border border-slate-200 flex justify-between items-center">
                             <div class="text-[11px] text-slate-500 font-bold uppercase tracking-widest">Sewa per Bulan</div>
                             <div class="text-xl font-black text-brand-600">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-base font-bold text-slate-800 mb-3 flex items-center gap-2">
                            <i data-lucide="file-text" class="size-4 text-brand-500"></i> Tentang Kamar Ini
                        </h3>
                        <div class="text-slate-600 leading-relaxed text-sm bg-slate-50 p-4 sm:p-5 rounded-xl border border-slate-100">
                            {{ $kamar->deskripsi ?? 'Kamar didesain untuk kenyamanan maksimal penghuninya. Memiliki pencahayaan alami yang baik serta sirkulasi udara yang lancar. Sangat ideal untuk mahasiswa maupun karyawan yang membutuhkan tempat istirahat tenang.' }}
                        </div>
                    </div>

                    <div>
                        <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <i data-lucide="sparkles" class="size-4 text-brand-500"></i> Fasilitas Termasuk
                        </h3>
                        
                        @php
                            $rawFasilitas = is_array($kamar->fasilitas) ? implode(',', $kamar->fasilitas) : ($kamar->fasilitas ?? '');
                            $cleanFasilitas = str_replace(['[', ']', '"', '\\'], '', $rawFasilitas);
                            $fasilitasDetail = array_filter(array_map('trim', explode(',', $cleanFasilitas)));
                        @endphp

                        @if(count($fasilitasDetail) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($fasilitasDetail as $item)
                                <div class="flex items-center p-3 rounded-xl bg-white border border-slate-200 shadow-sm">
                                    <div class="size-6 rounded-md bg-brand-50 flex items-center justify-center text-brand-600 shrink-0 mr-3">
                                        <i data-lucide="check" class="size-3.5 stroke-[3]"></i>
                                    </div>
                                    <span class="font-medium text-slate-600 text-sm">
                                        {{ $item }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-slate-50 p-5 rounded-xl border border-dashed border-slate-300 text-center">
                                <i data-lucide="info" class="size-6 text-slate-400 mx-auto mb-2"></i>
                                <p class="text-slate-500 text-sm">Kamar ini dilengkapi dengan fasilitas standar kos.</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            <div class="lg:col-span-1 hidden lg:block">
                <div class="sticky top-28 space-y-6">
                    <div class="bg-white rounded-3xl p-6 lg:p-8 shadow-lg shadow-slate-200/40 border border-slate-200">
                        
                        <div class="text-center mb-6 pb-6 border-b border-slate-100">
                            <p class="text-slate-400 text-[10px] uppercase tracking-widest font-bold mb-1">Total Sewa Bulanan</p>
                            <h3 class="text-3xl lg:text-4xl font-black text-brand-600 tracking-tight">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</h3>
                            
                            <div class="mt-3">
                                @if($kamar->status == 'tersedia')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-success-light/50 border border-success/30 text-success text-[10px] font-bold uppercase tracking-widest">
                                        Siap Dihuni
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-error-light/50 border border-error/30 text-error text-[10px] font-bold uppercase tracking-widest">
                                        Kamar Terisi
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- KODE BARU: Informasi Fasilitas Utama yang Lebih Profesional -->
<div class="grid grid-cols-1 gap-4 mb-8">
    <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
        <div class="size-10 rounded-xl bg-brand-50 flex items-center justify-center text-brand-600 shrink-0">
            <i data-lucide="shield-check" class="size-5"></i>
        </div>
        <div>
            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Keamanan</p>
            <p class="text-sm font-bold text-slate-800">Sistem Keamanan 24/7</p>
        </div>
    </div>
    <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
        <div class="size-10 rounded-xl bg-brand-50 flex items-center justify-center text-brand-600 shrink-0">
            <i data-lucide="zap" class="size-5"></i>
        </div>
        <div>
            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Listrik</p>
            <p class="text-sm font-bold text-slate-800">Sistem Token Mandiri</p>
        </div>
    </div>
</div>

                        <div class="space-y-3">
                            @if($kamar->status == 'tersedia')
                                @auth
                                    @if(Auth::user()->isCalonPenghuni() || Auth::user()->role == 'penghuni')
                                    <a href="{{ route('booking.create', $kamar->id) }}" 
                                       class="w-full py-3.5 bg-brand-600 text-white rounded-xl font-bold hover:bg-brand-700 transition-all shadow-md shadow-brand-600/20 flex items-center justify-center gap-2 transform active:scale-95 group text-sm">
                                        <i data-lucide="bookmark" class="size-4 group-hover:-rotate-12 transition-transform"></i> Pesan Kamar Ini
                                    </a>
                                    @endif
                                @else
                                <a href="{{ route('login') }}" 
                                   class="w-full py-3.5 bg-brand-600 text-white rounded-xl font-bold hover:bg-brand-700 transition-all shadow-md shadow-brand-600/20 flex items-center justify-center gap-2 text-sm transform active:scale-95">
                                   Login Untuk Pesan
                                </a>
                                @endauth
                            @else
                                <button disabled class="w-full py-3.5 bg-slate-100 border border-slate-200 text-slate-400 rounded-xl font-bold cursor-not-allowed flex items-center justify-center gap-2 text-sm">
                                    <i data-lucide="lock" class="size-4"></i> Tidak Tersedia
                                </button>
                            @endif
                            
                            <a href="https://wa.me/6281234567890?text=Halo,%20Admin%20Inna%20Kos.%20Saya%20tertarik%20dengan%20Kamar%20{{ $kamar->nomor_kamar }}" target="_blank"
                               class="w-full py-3 bg-white border border-success/30 text-success rounded-xl font-bold hover:bg-success-light/30 transition-colors flex items-center justify-center gap-2 text-sm">
                                <i class="fab fa-whatsapp text-base"></i> Chat Admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-4 shadow-[0_-10px_40px_-10px_rgba(0,0,0,0.08)] z-40 flex items-center justify-between gap-4">
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Total Sewa</p>
                    <p class="text-xl font-black text-brand-600 leading-none mt-0.5">Rp {{ number_format($kamar->harga / 1000, 0) }}k</p>
                </div>
                
                @if($kamar->status == 'tersedia')
                    @auth
                        <a href="{{ route('booking.create', $kamar->id) }}" class="flex-1 max-w-[220px] py-3.5 bg-brand-600 text-white rounded-xl font-bold text-sm text-center shadow-md shadow-brand-600/30 active:scale-95 transition-transform">Pesan Sekarang</a>
                    @else
                        <a href="{{ route('login') }}" class="flex-1 max-w-[220px] py-3.5 bg-slate-800 text-white rounded-xl font-bold text-sm text-center active:scale-95 transition-transform">Login & Pesan</a>
                    @endauth
                @else
                    <button disabled class="flex-1 max-w-[220px] py-3.5 bg-slate-100 text-slate-400 font-bold rounded-xl text-sm text-center">Sedang Penuh</button>
                @endif
            </div>

        </div>
    </div>
</div>

<style>
    /* Sembunyikan scrollbar pada nav breadcrumb agar terlihat rapi di HP */
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection