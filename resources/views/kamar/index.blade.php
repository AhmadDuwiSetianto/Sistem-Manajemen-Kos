@extends('layouts.app')

@section('title', 'Katalog Semua Kamar | MyKos')

@section('content')

{{-- ================= ALERT SECTION ================= --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
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
    
    <div class="bg-white border-b border-slate-200 pb-12 pt-8 mb-8 md:mb-12 shadow-sm relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-1.5 text-brand-600 font-bold tracking-widest uppercase text-[10px] sm:text-xs bg-brand-50 border border-brand-100 px-4 py-1.5 rounded-full mb-4">
                <i data-lucide="layout-grid" class="size-3.5"></i> Daftar Keseluruhan
            </span>
            <h1 class="text-4xl lg:text-5xl font-black text-slate-800 mb-4 tracking-tight">Cek Ketersediaan Kamar</h1>
            <p class="text-base md:text-lg text-slate-500 font-medium max-w-2xl mx-auto">
                Lihat daftar seluruh kamar Inna Kos Pekalongan yang siap huni maupun yang sedang terisi saat ini.
            </p>

            <div class="flex justify-center gap-4 sm:gap-6 mt-8">
                <div class="flex items-center gap-2 text-xs sm:text-sm font-bold text-slate-600 bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-200 shadow-sm">
                    <span class="w-3 h-3 rounded-full bg-success"></span>
                    {{ $semuaKamar->where('status', 'tersedia')->count() }} Kamar Tersedia
                </div>
                <div class="flex items-center gap-2 text-xs sm:text-sm font-bold text-slate-600 bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-200 shadow-sm">
                    <span class="w-3 h-3 rounded-full bg-error"></span>
                    {{ $semuaKamar->where('status', 'terisi')->count() }} Kamar Terisi
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($semuaKamar->count() > 0)
            
            <div class="flex overflow-x-auto snap-x snap-mandatory gap-6 pb-8 -mx-4 px-4 md:mx-0 md:px-0 md:grid md:grid-cols-2 lg:grid-cols-3 md:overflow-visible hide-scrollbar">
                
                @foreach($semuaKamar as $kamar)
                <div class="snap-center shrink-0 w-[85%] sm:w-[350px] md:w-auto bg-white rounded-[2rem] overflow-hidden border border-slate-200/80 shadow-soft hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full group relative">
                    
                    <div class="relative h-60 overflow-hidden bg-slate-100 p-2">
                        <div class="w-full h-full rounded-[1.5rem] overflow-hidden relative">
                            @if($kamar->gambar)
                                <img src="{{ asset('storage/' . $kamar->gambar) }}" 
                                     alt="Kamar {{ $kamar->nomor_kamar }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            @else
                                <img src="https://images.unsplash.com/photo-1522771753035-4a5046216955?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition duration-700 grayscale-[20%]">
                            @endif
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-transparent to-transparent opacity-60 group-hover:opacity-90 transition-opacity duration-300"></div>
                            
                            @if($kamar->status == 'terisi')
                            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px] z-10 flex flex-col items-center justify-center pointer-events-none">
                                <div class="bg-black/60 px-4 py-2 rounded-xl backdrop-blur-md border border-white/10">
                                    <span class="text-white font-black tracking-widest uppercase text-sm flex items-center gap-2"><i data-lucide="lock" class="size-4"></i> Sedang Terisi</span>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <div class="absolute top-5 left-5 z-20 {{ $kamar->status == 'tersedia' ? 'bg-success/90 border-success text-white' : 'bg-slate-800/90 border-slate-600 text-slate-200' }} px-3 py-1.5 rounded-xl text-[10px] font-black shadow-lg flex items-center gap-1.5 backdrop-blur-sm border uppercase tracking-wider">
                            @if($kamar->status == 'tersedia')
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> TERSA
                            @else
                                PENUH
                            @endif
                        </div>
                        
                        <div class="absolute bottom-5 right-5 z-20 bg-white/95 backdrop-blur-md text-slate-800 px-3 py-1.5 rounded-xl text-[10px] font-bold shadow-md flex items-center gap-2">
                            <span class="text-brand-600 border-r border-slate-300 pr-2">TIPE {{ strtoupper($kamar->tipe_kamar) }}</span>
                            <span class="flex items-center gap-1"><i data-lucide="layers" class="size-3 text-slate-400"></i> LT. {{ $kamar->lantai }}</span>
                        </div>
                    </div>

                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex justify-between items-start mb-5">
                            <div>
                                <p class="text-[10px] text-brand-600 font-bold uppercase tracking-widest mb-1">No. Kamar</p>
                                <h3 class="text-2xl font-black text-slate-800 group-hover:text-brand-600 transition-colors">{{ $kamar->nomor_kamar }}</h3>
                            </div>
                            <div class="text-right bg-slate-50 px-3 py-2 rounded-xl border border-slate-100">
                                <p class="text-lg font-black text-brand-600">Rp {{ number_format($kamar->harga / 1000, 0) }}k</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">/ Bulan</p>
                            </div>
                        </div>

                        <div class="space-y-2 mb-6 pt-5 border-t border-slate-100 flex-grow">
                            @php
                                $fasilitasArr = [];
                                if(is_string($kamar->fasilitas)) {
                                    $fasilitasArr = array_map('trim', explode(',', $kamar->fasilitas));
                                } elseif(is_array($kamar->fasilitas)) {
                                    $fasilitasArr = $kamar->fasilitas;
                                } elseif(is_string($kamar->fasilitas) && (str_starts_with($kamar->fasilitas, '[') || str_starts_with($kamar->fasilitas, '{'))) {
                                     $decoded = json_decode($kamar->fasilitas, true);
                                     if(json_last_error() === JSON_ERROR_NONE) { $fasilitasArr = $decoded; }
                                }
                            @endphp

                            @if(count($fasilitasArr) > 0)
                                @foreach(array_slice($fasilitasArr, 0, 3) as $fas)
                                <div class="flex items-center gap-3 text-sm font-medium text-slate-600">
                                    <div class="size-5 rounded-full bg-brand-50 flex items-center justify-center shrink-0">
                                        <i data-lucide="check" class="size-3 text-brand-600 stroke-[3]"></i>
                                    </div>
                                    <span class="line-clamp-1">{{ is_object($fas) ? ($fas->nama_fasilitas ?? $fas->nama) : $fas }}</span>
                                </div>
                                @endforeach
                            @else
                                <p class="text-sm font-medium text-slate-400 italic flex items-center gap-2">
                                    <i data-lucide="info" class="size-4"></i> Fasilitas standar kos tersedia
                                </p>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-3 mt-auto pt-2">
                            <a href="{{ route('kamar.show', $kamar->id) }}" 
                               class="flex items-center justify-center gap-2 py-3.5 rounded-xl border-2 border-slate-100 text-slate-600 font-bold hover:bg-slate-50 hover:text-slate-900 transition-colors text-sm">
                                <i data-lucide="eye" class="size-4"></i> Info Detail
                            </a>
                            
                            @if($kamar->status == 'terisi')
                                <button disabled class="flex items-center justify-center gap-2 py-3.5 rounded-xl bg-slate-100 text-slate-400 font-bold cursor-not-allowed text-sm border border-transparent">
                                    <i data-lucide="lock" class="size-4"></i> Diisi
                                </button>
                            @elseif(Auth::check() && Auth::user()->hasActiveBooking())
                                <button disabled class="flex items-center justify-center gap-1.5 py-3.5 rounded-xl bg-slate-100 text-slate-400 font-bold cursor-not-allowed text-[11px] sm:text-xs border border-transparent leading-tight" title="Anda sudah punya kamar aktif">
                                    <i data-lucide="shield-alert" class="size-3.5 shrink-0"></i> Booking Aktif
                                </button>
                            @else
                                <a href="{{ route('booking.create', $kamar->id) }}" 
                                   class="flex items-center justify-center gap-2 py-3.5 rounded-xl bg-brand-600 text-white font-bold hover:bg-brand-700 transition-colors shadow-lg shadow-brand-600/20 text-sm">
                                    <i data-lucide="bookmark" class="size-4"></i> Pesan
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
                <p class="text-slate-500 font-medium">Sistem saat ini belum memiliki daftar kamar untuk ditampilkan.</p>
            </div>
        @endif
    </div>
</div>

<style>
    /* Sembunyikan scrollbar bawaan browser tapi tetap bisa digeser */
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection