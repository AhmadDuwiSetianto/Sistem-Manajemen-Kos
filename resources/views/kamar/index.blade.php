@extends('layouts.app')

@section('title', 'Katalog Semua Kamar | Inna Kos')

@section('content')

{{-- ================= CSS OVERRIDE UNTUK HEADER ================= --}}
<style>
    /* Memaksa Header menjadi Solid (Putih) khusus di halaman ini */
    #main-header {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(12px) !important;
        border-bottom: 1px solid rgba(226, 232, 240, 0.6) !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
    }
    
    /* Memaksa teks header menjadi gelap/biru, mengabaikan class transparent */
    #main-header .dynamic-logo { color: #1e293b !important; }
    #main-header .dynamic-brand { color: #165DFF !important; }
    #main-header .dynamic-text { color: #64748b !important; }
    #main-header .dynamic-text:hover { color: #165DFF !important; }
    #main-header .dynamic-btn { background-color: #165DFF !important; color: #ffffff !important; }
    #main-header .dynamic-user { color: #1e293b !important; }
    #main-header .dynamic-role { color: #94a3b8 !important; }
    #main-header .dynamic-icon { color: #94a3b8 !important; }
    #main-header .nav-link.active { color: #165DFF !important; font-weight: 800 !important; }
    #main-header .nav-link.active::after { background-color: #165DFF !important; }

    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

{{-- ================= Jarak agar tidak tertutup header ================= --}}
<div class="pt-24 bg-white"></div>

{{-- ================= ALERT SECTION ================= --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 bg-white">
    @if(session('success'))
        <div class="bg-success-light/50 border border-success/30 text-success px-5 py-4 rounded-2xl relative mb-6 flex items-center gap-3">
            <i data-lucide="check-circle-2" class="size-5 shrink-0"></i>
            <div>
                <strong class="font-bold block text-sm">Berhasil!</strong>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-error-light/50 border border-error/30 text-error px-5 py-4 rounded-2xl relative mb-6 flex items-center gap-3">
            <i data-lucide="alert-circle" class="size-5 shrink-0"></i>
            <div>
                <strong class="font-bold block text-sm">Gagal!</strong>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-error-light/50 border border-error/30 text-error px-5 py-4 rounded-2xl relative mb-6 flex items-start gap-3">
            <i data-lucide="x-octagon" class="size-5 shrink-0 mt-0.5"></i>
            <div>
                <strong class="font-bold block text-sm mb-1">Terdapat Kesalahan:</strong>
                <ul class="text-sm font-medium list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
</div>
{{-- ================= END ALERT SECTION ================= --}}


<div class="bg-slate-50 min-h-screen pb-24">
    
    <div class="bg-white border-b border-slate-200 pb-12 pt-4 mb-8 md:mb-12 shadow-sm relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-1.5 text-brand-600 font-bold tracking-widest uppercase text-[10px] sm:text-xs bg-brand-50 border border-brand-100 px-4 py-1.5 rounded-full mb-4">
                <i data-lucide="layout-grid" class="size-3.5"></i> Daftar Keseluruhan
            </span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 mb-4 tracking-tight">Katalog Pilihan Kamar</h1>
            <p class="text-sm sm:text-base md:text-lg text-slate-500 font-medium max-w-2xl mx-auto">
                Lihat daftar seluruh kamar Inna Kos Pekalongan yang siap huni maupun yang sedang terisi saat ini.
            </p>

            <div class="flex flex-wrap justify-center gap-3 sm:gap-6 mt-8">
                <div class="flex items-center gap-2 text-xs sm:text-sm font-bold text-slate-600 bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-200 shadow-sm">
                    <span class="size-2.5 sm:size-3 rounded-full bg-success"></span>
                    {{ $semuaKamar->where('status', 'tersedia')->count() }} Kamar Tersedia
                </div>
                <div class="flex items-center gap-2 text-xs sm:text-sm font-bold text-slate-600 bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-200 shadow-sm">
                    <span class="size-2.5 sm:size-3 rounded-full bg-error"></span>
                    {{ $semuaKamar->where('status', 'terisi')->count() }} Kamar Terisi
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($semuaKamar->count() > 0)
            
            <div class="flex overflow-x-auto snap-x snap-mandatory gap-6 pb-8 -mx-4 px-4 md:mx-0 md:px-0 md:grid md:grid-cols-2 lg:grid-cols-3 md:overflow-visible hide-scrollbar">
                
                @foreach($semuaKamar as $kamar)
                <div class="snap-center shrink-0 w-[85%] sm:w-[350px] md:w-auto bg-white rounded-[1.5rem] lg:rounded-[2rem] overflow-hidden border border-slate-200/60 shadow-soft hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full group relative p-2">
                    
                    <div class="relative h-48 lg:h-56 overflow-hidden rounded-xl lg:rounded-[1.5rem] bg-slate-200">
                        @if($kamar->gambar)
                            <img src="{{ asset('storage/' . $kamar->gambar) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <img src="https://images.unsplash.com/photo-1522771753035-4a5046216955?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover grayscale-[20%] group-hover:scale-105 transition-transform duration-700">
                        @endif
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        @if($kamar->status == 'terisi')
                            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px] flex items-center justify-center z-10">
                                <span class="bg-black/70 text-white font-black px-4 py-2 rounded-xl flex items-center gap-2 text-xs lg:text-sm tracking-wider uppercase border border-white/10">
                                    <i data-lucide="lock" class="size-4"></i> Kamar Terisi
                                </span>
                            </div>
                        @endif

                        <div class="absolute top-3 left-3 lg:top-4 lg:left-4 z-20 {{ $kamar->status == 'tersedia' ? 'bg-white text-success' : 'bg-slate-800 text-slate-200 border-slate-600' }} px-2.5 py-1 lg:px-3 lg:py-1.5 rounded-lg text-[9px] lg:text-[10px] font-black shadow-md flex items-center gap-1.5 uppercase tracking-widest border">
                            @if($kamar->status == 'tersedia')
                                <span class="size-1.5 rounded-full bg-success animate-pulse"></span> TERSEDIA
                            @else
                                PENUH
                            @endif
                        </div>
                    </div>

                    <div class="p-4 lg:p-5 flex flex-col flex-grow">
                        
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">Nomor Kamar</p>
                                <h3 class="text-xl lg:text-2xl font-black text-slate-900 leading-none mt-1">{{ $kamar->nomor_kamar }}</h3>
                            </div>
                            <div class="text-right">
                                <p class="text-lg lg:text-xl font-black text-brand-600 leading-none">Rp {{ number_format($kamar->harga / 1000, 0) }}k</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">/ Bulan</p>
                            </div>
                        </div>

                        <div class="flex gap-2 mb-4">
                            <span class="bg-slate-50 border border-slate-100 text-slate-600 text-[9px] lg:text-[10px] font-bold px-2 py-1 rounded flex items-center gap-1">
                                <i data-lucide="tag" class="size-3"></i> Tipe {{ $kamar->tipe_kamar }}
                            </span>
                            <span class="bg-slate-50 border border-slate-100 text-slate-600 text-[9px] lg:text-[10px] font-bold px-2 py-1 rounded flex items-center gap-1">
                                <i data-lucide="layers" class="size-3"></i> Lantai {{ $kamar->lantai }}
                            </span>
                        </div>

                        <div class="space-y-1.5 mb-4 lg:mb-5 flex-grow">
                            @php
                                $rawFasilitas = is_array($kamar->fasilitas) ? implode(',', $kamar->fasilitas) : ($kamar->fasilitas ?? '');
                                $cleanFasilitas = str_replace(['[', ']', '"', '\\'], '', $rawFasilitas);
                                $fasilitasArr = array_filter(array_map('trim', explode(',', $cleanFasilitas)));
                            @endphp

                            @if(count($fasilitasArr) > 0)
                                @foreach(array_slice($fasilitasArr, 0, 3) as $fas)
                                <div class="flex items-center gap-2 text-xs font-medium text-slate-600">
                                    <div class="size-4 rounded-full bg-brand-50 flex items-center justify-center shrink-0">
                                        <i data-lucide="check" class="size-2.5 text-brand-600 stroke-[3]"></i>
                                    </div>
                                    <span class="truncate">{{ $fas }}</span>
                                </div>
                                @endforeach
                                
                                @if(count($fasilitasArr) > 3)
                                    <div class="text-[10px] text-brand-600 font-bold pl-6 mt-1">
                                        +{{ count($fasilitasArr) - 3 }} fasilitas lainnya
                                    </div>
                                @endif
                            @else
                                <p class="text-xs font-medium text-slate-400 italic flex items-center gap-1.5">
                                    <i data-lucide="info" class="size-3"></i> Fasilitas standar tersedia
                                </p>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-2 mt-auto pt-4 border-t border-slate-200/60">
                            <a href="{{ route('kamar.show', $kamar->id) }}" class="flex justify-center items-center py-2.5 lg:py-3 bg-slate-50 border border-slate-200 text-slate-700 font-bold rounded-xl text-xs hover:bg-slate-100 transition">
                                Lihat Detail
                            </a>
                            
                            @if($kamar->status == 'terisi')
                                <button disabled class="flex justify-center items-center py-2.5 lg:py-3 bg-slate-200 text-slate-400 font-bold rounded-xl text-xs cursor-not-allowed">
                                    Diisi
                                </button>
                            @elseif(Auth::check() && Auth::user()->hasActiveBooking())
                                <button disabled class="flex items-center justify-center gap-1.5 py-2.5 lg:py-3 rounded-xl bg-slate-100 text-slate-400 font-bold cursor-not-allowed text-[10px] sm:text-xs border border-transparent leading-tight" title="Anda sudah punya pesanan aktif">
                                    <i data-lucide="shield-alert" class="size-3.5 shrink-0"></i> Booking Aktif
                                </button>
                            @else
                                <a href="{{ route('booking.create', $kamar->id) }}" class="flex justify-center items-center py-2.5 lg:py-3 bg-brand-600 text-white font-bold rounded-xl text-xs hover:bg-brand-700 shadow-md shadow-brand-600/20 transition">
                                    Pesan
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
                @endforeach

            </div>
            
            <div class="flex md:hidden items-center justify-center gap-2 mt-2 text-slate-400 font-medium text-xs">
                <i data-lucide="arrow-left-right" class="size-4"></i> Geser untuk melihat lainnya
            </div>

        @else
            <div class="text-center py-24 bg-white rounded-[3rem] border border-dashed border-slate-300 shadow-sm max-w-3xl mx-auto">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-5 border border-slate-100">
                    <i data-lucide="search-x" class="size-10 text-slate-300"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 mb-2">Belum Ada Data Kamar</h3>
                <p class="text-slate-500 font-medium">Admin belum menambahkan kamar atau semua kamar sedang ditutup sementara.</p>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection