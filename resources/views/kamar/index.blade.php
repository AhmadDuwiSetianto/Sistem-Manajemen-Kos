@extends('layouts.app')

@section('title', 'Semua Kamar Tersedia | MyKos')

@section('content')

{{-- 🔥 ADDED: Alert Section to Display Controller Messages 🔥 --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Berhasil!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Gagal!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if(session('warning'))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Perhatian!</strong>
            <span class="block sm:inline">{{ session('warning') }}</span>
        </div>
    @endif

    @if(session('info'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Info:</strong>
            <span class="block sm:inline">{{ session('info') }}</span>
        </div>
    @endif
    
    {{-- Validation Errors (if any) --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
{{-- 🔥 END ALERT SECTION 🔥 --}}

<div class="bg-slate-50 min-h-screen pb-20">
    
    <div class="bg-white border-b border-slate-200 pb-10 pt-6 mb-10 shadow-sm relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-brand-600 font-bold tracking-wider uppercase text-[10px] lg:text-xs bg-brand-50 px-3 py-1 rounded-full mb-3 inline-block">
                Katalog Lengkap
            </span>
            <h1 class="text-3xl lg:text-4xl font-extrabold text-slate-900 mb-3 tracking-tight">Temukan Kamar Idealmu</h1>
            <p class="text-base text-slate-500 max-w-xl mx-auto">
                Jelajahi pilihan kamar kos premium dengan fasilitas lengkap dan lingkungan yang nyaman.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($semuaKamar->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            @foreach($semuaKamar as $kamar)
            <div class="bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-brand-600/10 hover:-translate-y-1 transition duration-300 flex flex-col h-full group">
                
                <div class="relative h-56 overflow-hidden">
                    @if($kamar->gambar)
                        <img src="{{ asset('storage/' . $kamar->gambar) }}" 
                             alt="Kamar {{ $kamar->nomor_kamar }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <img src="https://images.unsplash.com/photo-1522771753035-4a5046216955?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @endif
                    
                    <div class="absolute top-3 left-3 {{ $kamar->status == 'tersedia' ? 'bg-green-500' : 'bg-red-500' }} text-white px-2.5 py-1 rounded-md text-[10px] font-bold shadow-lg uppercase tracking-wide">
                        {{ $kamar->status == 'tersedia' ? 'Tersedia' : 'Penuh' }}
                    </div>
                    <div class="absolute top-3 right-3 bg-white/95 backdrop-blur text-slate-800 px-2.5 py-1 rounded-md text-[10px] font-bold shadow-sm uppercase tracking-wide">
                        {{ $kamar->tipe_kamar }}
                    </div>
                </div>

                <div class="p-5 flex flex-col flex-grow">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="text-[10px] text-brand-600 font-bold uppercase tracking-wider mb-1 bg-brand-50 inline-block px-2 py-0.5 rounded">
                                Lantai {{ $kamar->lantai }}
                            </p>
                            <h3 class="text-lg font-bold text-slate-800 mt-0.5">Kamar {{ $kamar->nomor_kamar }}</h3>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-brand-600">Rp {{ number_format($kamar->harga / 1000, 0) }}k</p>
                            <p class="text-[10px] text-slate-400">/bulan</p>
                        </div>
                    </div>

                    <div class="space-y-1.5 mb-5 pt-3 border-t border-slate-50">
                        @php
                            $fasilitasArr = [];
                            if(is_string($kamar->fasilitas)) {
                                $fasilitasArr = array_map('trim', explode(',', $kamar->fasilitas));
                            } elseif(is_array($kamar->fasilitas)) {
                                $fasilitasArr = $kamar->fasilitas;
                            }
                            // JSON decoding logic if stored as JSON string in DB
                            elseif(is_string($kamar->fasilitas) && (str_starts_with($kamar->fasilitas, '[') || str_starts_with($kamar->fasilitas, '{'))) {
                                 $decoded = json_decode($kamar->fasilitas, true);
                                 if(json_last_error() === JSON_ERROR_NONE) {
                                     $fasilitasArr = $decoded;
                                 }
                            }
                        @endphp

                        @if(count($fasilitasArr) > 0)
                            @foreach(array_slice($fasilitasArr, 0, 3) as $fas)
                            <div class="flex items-center gap-2 text-xs text-slate-600">
                                <i class="fas fa-check-circle text-brand-400 text-[10px] flex-shrink-0"></i>
                                <span class="line-clamp-1">{{ is_object($fas) ? ($fas->nama_fasilitas ?? $fas->nama) : $fas }}</span>
                            </div>
                            @endforeach
                        @else
                            <p class="text-xs text-slate-400 italic">Fasilitas standar tersedia</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-2 mt-auto">
                        <a href="{{ route('kamar.show', $kamar->id) }}" 
                           class="flex items-center justify-center py-2 rounded-lg border border-slate-200 text-slate-600 font-bold hover:border-brand-600 hover:text-brand-600 transition text-xs lg:text-sm">
                            Detail
                        </a>
                        
                        {{-- Booking Button --}}
                        <a href="{{ route('booking.create', $kamar->id) }}" 
                               class="flex items-center justify-center py-3 rounded-xl bg-brand-600 text-white font-bold hover:bg-brand-700 transition shadow-lg shadow-brand-600/20">
                                Pesan
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-slate-300">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-search text-slate-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800">Tidak ada kamar ditemukan</h3>
            <p class="text-sm text-slate-500 mt-1">Coba hubungi admin untuk ketersediaan kamar terbaru.</p>
        </div>
        @endif
    </div>
</div>
@endsection