@extends('layouts.app')

@section('title', 'Inna Kos Pekalongan - Solusi Kos Modern')

@section('content')

<section id="home" class="relative pt-24 pb-20 lg:pt-32 lg:pb-32 overflow-hidden bg-white">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] z-0"></div>
    <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3 w-[800px] h-[800px] bg-brand-50 rounded-full blur-[100px] opacity-70 z-0"></div>
    <div class="absolute bottom-0 left-0 translate-y-1/3 -translate-x-1/3 w-[600px] h-[600px] bg-blue-50/50 rounded-full blur-[80px] opacity-70 z-0"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
            
            <div class="w-full lg:w-[55%] text-center lg:text-left space-y-8">
                
                <div class="inline-flex items-center gap-2.5 px-4 py-2 bg-white/80 backdrop-blur-md border border-brand-100/50 rounded-full shadow-soft">
                    <span class="relative flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-success opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-success"></span>
                    </span>
                    <span class="text-xs font-extrabold text-brand-600 tracking-wider uppercase">Tersedia Kamar Kosong</span>
                </div>
                
                <h1 class="text-5xl lg:text-[4rem] font-black text-slate-800 leading-[1.1] tracking-tight">
                    Temukan Tempat <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 to-brand-400 relative inline-block pb-2">
                        Tinggal Impianmu
                        <svg class="absolute w-full h-4 -bottom-1 left-0 text-brand-200/60 -z-10" viewBox="0 0 100 10" preserveAspectRatio="none">
                            <path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="8" fill="none" stroke-linecap="round"/>
                        </svg>
                    </span>
                </h1>
                
                <p class="text-lg text-slate-500 leading-relaxed max-w-lg mx-auto lg:mx-0 font-medium">
                    Sistem manajemen kos modern yang memberikan kemudahan. Nikmati fasilitas lengkap, keamanan terjamin, dan lingkungan yang nyaman untuk produktivitasmu.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-2">
                    <a href="#kamar" class="group px-8 py-4 bg-brand-600 text-white rounded-2xl font-bold shadow-lg shadow-brand-600/25 hover:bg-brand-700 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2">
                        <i data-lucide="search" class="w-5 h-5"></i> Cari Kamar Sekarang
                    </a>
                    
                    <a href="#fasilitas" class="px-8 py-4 bg-white text-slate-700 border border-slate-200 rounded-2xl font-bold hover:bg-slate-50 hover:text-brand-600 transition-all duration-300 flex items-center justify-center gap-2 shadow-soft">
                        <i data-lucide="play-circle" class="w-5 h-5 text-brand-500"></i> Lihat Fasilitas
                    </a>
                </div>

                <div class="pt-6 flex flex-wrap justify-center lg:justify-start items-center gap-6 gap-y-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-6 h-6 rounded-full bg-success-light text-success flex items-center justify-center">
                            <i data-lucide="check" class="w-3.5 h-3.5 stroke-[3]"></i>
                        </div>
                        <span class="text-sm font-semibold text-slate-600">Lokasi Strategis</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-6 h-6 rounded-full bg-success-light text-success flex items-center justify-center">
                            <i data-lucide="check" class="w-3.5 h-3.5 stroke-[3]"></i>
                        </div>
                        <span class="text-sm font-semibold text-slate-600">Terverifikasi</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-6 h-6 rounded-full bg-success-light text-success flex items-center justify-center">
                            <i data-lucide="check" class="w-3.5 h-3.5 stroke-[3]"></i>
                        </div>
                        <span class="text-sm font-semibold text-slate-600">Bebas Jam Malam</span>
                    </div>
                </div>
            </div>
            
            <div class="w-full lg:w-[45%] relative mt-10 lg:mt-0 flex justify-center lg:justify-end">
                <div class="relative w-full max-w-[500px] aspect-[4/3] sm:aspect-square lg:aspect-[4/5] rounded-[2.5rem] bg-gradient-to-br from-brand-50 to-white border-2 border-white shadow-2xl shadow-brand-900/10 overflow-hidden flex items-center justify-center p-12">
                    
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-[150%] h-[150%] bg-brand-100/30 rounded-full border border-brand-200/50"></div>
                        <div class="absolute w-[100%] h-[100%] bg-white/50 rounded-full border border-brand-100 shadow-sm backdrop-blur-sm"></div>
                    </div>

                    <div class="relative z-10 w-full h-full flex flex-col items-center justify-center transform transition-transform duration-700 hover:scale-105">
                        @if(file_exists(public_path('images/mykos.png')))
                            <img src="{{ asset('images/mykos.png') }}" alt="MyKos Logo" class="w-48 md:w-64 h-auto object-contain drop-shadow-lg">
                        @elseif(file_exists(public_path('images/logo.svg')))
                            <img src="{{ asset('images/logo.svg') }}" alt="MyKos Logo" class="w-48 md:w-64 h-auto object-contain drop-shadow-lg">
                        @else
                            <div class="w-32 h-32 md:w-40 md:h-40 bg-brand-600 rounded-[2rem] flex items-center justify-center shadow-lg shadow-brand-600/30 mb-6 rotate-[-10deg] hover:rotate-0 transition-transform duration-500">
                                <i data-lucide="home" class="w-16 h-16 md:w-20 md:h-20 text-white"></i>
                            </div>
                            <h2 class="text-4xl md:text-5xl font-black text-slate-800 tracking-tight">My<span class="text-brand-600">Kos</span></h2>
                        @endif
                    </div>

                    <div class="absolute bottom-8 left-8 bg-white/90 backdrop-blur-md p-4 rounded-2xl shadow-lg border border-white flex items-center gap-4 z-20 animate-bounce" style="animation-duration: 3s;">
                        <div class="w-12 h-12 bg-success-light text-success rounded-full flex items-center justify-center">
                            <i data-lucide="shield-check" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Keamanan</p>
                            <p class="text-sm font-bold text-slate-800">100% Terjamin</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<section id="kamar" class="py-24 bg-slate-50 relative">
    <div class="container mx-auto px-6">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
            <div class="max-w-xl">
                <span class="inline-block py-1 px-3 rounded-full bg-brand-100 text-brand-700 font-bold text-xs uppercase tracking-widest mb-3">
                    Ketersediaan Kamar
                </span>
                <h2 class="text-3xl lg:text-4xl font-black text-slate-800 tracking-tight">Pilih Kamar Favoritmu</h2>
                <p class="text-slate-500 mt-3 text-lg font-medium">Hanya menampilkan kamar dengan kualitas terbaik yang siap Anda huni hari ini juga.</p>
            </div>
            
            <a href="{{ route('kamar.index') }}" class="hidden md:flex items-center gap-2 text-brand-600 font-bold hover:text-brand-700 hover:bg-brand-100 transition-colors bg-brand-50 px-6 py-3 rounded-xl">
                Lihat Semua Kamar <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        @if($kamarTersedia->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($kamarTersedia->take(3) as $kamar)
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-soft hover:shadow-xl hover:-translate-y-2 transition-all duration-300 flex flex-col h-full group">
                
                <div class="relative h-60 overflow-hidden bg-slate-100">
                    <img src="{{ $kamar->gambar_url ?? 'https://images.unsplash.com/photo-1522771753035-4a5046216955?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}" 
                         alt="Kamar {{ $kamar->nomor_kamar }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <div class="absolute top-4 left-4 {{ $kamar->status == 'tersedia' ? 'bg-success text-white' : 'bg-error text-white' }} px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm flex items-center gap-1.5 backdrop-blur-sm">
                        @if($kamar->status == 'tersedia')
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> Tersedia
                        @else
                            <i data-lucide="x-circle" class="w-3.5 h-3.5"></i> Penuh
                        @endif
                    </div>

                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm text-slate-700 px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm flex items-center gap-1.5">
                        <i data-lucide="layers" class="w-3.5 h-3.5 text-brand-600"></i> Lt. {{ $kamar->lantai }}
                    </div>
                </div>

                <div class="p-6 flex flex-col flex-grow">
                    <div class="flex justify-between items-start mb-5">
                        <div>
                            <h3 class="text-2xl font-bold text-slate-800 group-hover:text-brand-600 transition-colors">Kamar {{ $kamar->nomor_kamar }}</h3>
                            <p class="text-sm font-medium text-slate-500 mt-1 flex items-center gap-1.5">
                                <i data-lucide="tag" class="w-3.5 h-3.5 text-brand-400"></i> Tipe {{ ucfirst($kamar->tipe_kamar) }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-black text-brand-600">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Per Bulan</p>
                        </div>
                    </div>

                    <div class="space-y-2.5 mb-8 pt-5 border-t border-slate-100 flex-grow">
                        @php
                            $listFasilitas = [];
                            if (!empty($kamar->fasilitas)) {
                                if (is_string($kamar->fasilitas)) {
                                    $listFasilitas = array_map('trim', explode(',', $kamar->fasilitas));
                                } elseif (is_array($kamar->fasilitas)) {
                                    $listFasilitas = $kamar->fasilitas;
                                }
                            }
                        @endphp

                        @if(count($listFasilitas) > 0)
                            @foreach(array_slice($listFasilitas, 0, 3) as $item)
                                <div class="flex items-center gap-3 text-sm text-slate-600 font-medium">
                                    <div class="w-5 h-5 rounded-full bg-brand-50 flex items-center justify-center shrink-0">
                                        <i data-lucide="check" class="w-3 h-3 text-brand-600 stroke-[3]"></i>
                                    </div>
                                    <span class="line-clamp-1">{{ $item }}</span>
                                </div>
                            @endforeach
                            
                            @if(count($listFasilitas) > 3)
                                <div class="text-xs text-brand-600 font-bold pl-8 pt-1">
                                    +{{ count($listFasilitas) - 3 }} fasilitas menarik lainnya
                                </div>
                            @endif
                        @else
                            <p class="text-sm text-slate-400 italic flex items-center gap-2">
                                <i data-lucide="info" class="w-4 h-4"></i> Fasilitas standar kos tersedia.
                            </p>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-auto">
                        <a href="{{ route('kamar.show', $kamar->id) }}" 
                           class="flex items-center justify-center gap-2 py-3.5 rounded-xl bg-slate-50 text-slate-600 font-bold hover:bg-slate-100 hover:text-slate-800 transition-colors">
                            <i data-lucide="eye" class="w-4 h-4"></i> Detail
                        </a>
                        
                        @if(Auth::check() && Auth::user()->hasActiveBooking())
                             <button disabled class="flex items-center justify-center gap-2 py-3.5 rounded-xl bg-slate-100 text-slate-400 font-bold cursor-not-allowed">
                                <i data-lucide="lock" class="w-4 h-4"></i> Aktif
                            </button>
                        @else
                            <a href="{{ route('booking.create', $kamar) }}" 
                               class="flex items-center justify-center gap-2 py-3.5 rounded-xl bg-brand-600 text-white font-bold hover:bg-brand-700 transition-colors shadow-md shadow-brand-600/20">
                                <i data-lucide="bookmark" class="w-4 h-4"></i> Pesan
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-12 text-center md:hidden">
            <a href="{{ route('kamar.index') }}" class="inline-flex items-center justify-center gap-2 w-full px-8 py-4 bg-brand-50 text-brand-600 rounded-xl font-bold hover:bg-brand-100 transition-colors">
                Lihat Semua Kamar <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
        
        @else
        <div class="text-center py-20 bg-white rounded-[2.5rem] border border-dashed border-slate-200 shadow-sm">
            <div class="w-20 h-20 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                <i data-lucide="bed-double" class="w-10 h-10 text-slate-300"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Kamar Tersedia</h3>
            <p class="text-slate-500 font-medium max-w-md mx-auto">Saat ini semua kamar sedang terisi atau belum ada data kamar yang ditambahkan oleh Admin.</p>
        </div>
        @endif
    </div>
</section>

<section id="fasilitas" class="py-24 bg-white relative border-t border-slate-100">
    <div class="container mx-auto px-6">
        <div class="text-center max-w-2xl mx-auto mb-20">
            <span class="inline-block py-1 px-3 rounded-full bg-brand-100 text-brand-700 font-bold text-xs uppercase tracking-widest mb-3">
                Layanan Unggulan
            </span>
            <h2 class="text-3xl lg:text-4xl font-black text-slate-800 tracking-tight">Fasilitas Berstandar Hotel</h2>
            <p class="text-slate-500 mt-4 text-lg font-medium">Kami menjamin kenyamanan Anda dengan fasilitas super lengkap tanpa biaya tambahan tersembunyi.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 hover:bg-white hover:border-brand-200 hover:shadow-xl hover:shadow-brand-600/5 transition-all duration-300 text-center group">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm group-hover:bg-brand-600 group-hover:text-white transition-colors duration-300">
                    <i data-lucide="wifi" class="w-7 h-7 text-brand-600 group-hover:text-white"></i>
                </div>
                <h3 class="font-bold text-xl text-slate-800 mb-3">Internet Fiber</h3>
                <p class="text-slate-500 font-medium text-sm leading-relaxed">Koneksi WiFi berkecepatan tinggi yang dedicated untuk menunjang aktivitas Anda.</p>
            </div>
            
            <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 hover:bg-white hover:border-brand-200 hover:shadow-xl hover:shadow-brand-600/5 transition-all duration-300 text-center group">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm group-hover:bg-brand-600 group-hover:text-white transition-colors duration-300">
                    <i data-lucide="shield-check" class="w-7 h-7 text-brand-600 group-hover:text-white"></i>
                </div>
                <h3 class="font-bold text-xl text-slate-800 mb-3">Aman 24 Jam</h3>
                <p class="text-slate-500 font-medium text-sm leading-relaxed">Pengawasan CCTV di berbagai sudut dan akses kunci pintar (smart lock).</p>
            </div>

            <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 hover:bg-white hover:border-brand-200 hover:shadow-xl hover:shadow-brand-600/5 transition-all duration-300 text-center group">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm group-hover:bg-brand-600 group-hover:text-white transition-colors duration-300">
                    <i data-lucide="sparkles" class="w-7 h-7 text-brand-600 group-hover:text-white"></i>
                </div>
                <h3 class="font-bold text-xl text-slate-800 mb-3">Layanan Kebersihan</h3>
                <p class="text-slate-500 font-medium text-sm leading-relaxed">Pembersihan area komunal setiap hari agar lingkungan tetap segar dan higienis.</p>
            </div>

            <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 hover:bg-white hover:border-brand-200 hover:shadow-xl hover:shadow-brand-600/5 transition-all duration-300 text-center group">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm group-hover:bg-brand-600 group-hover:text-white transition-colors duration-300">
                    <i data-lucide="coffee" class="w-7 h-7 text-brand-600 group-hover:text-white"></i>
                </div>
                <h3 class="font-bold text-xl text-slate-800 mb-3">Area Komunal</h3>
                <p class="text-slate-500 font-medium text-sm leading-relaxed">Ruang bersantai dan dapur bersama yang luas, lengkap dengan perabotan modern.</p>
            </div>
        </div>
    </div>
</section>

<section id="lokasi" class="py-24 bg-slate-50">
    <div class="container mx-auto px-6">
        <div class="bg-slate-850 rounded-[3rem] overflow-hidden shadow-2xl flex flex-col lg:flex-row relative">
            
            <div class="absolute top-0 right-0 w-96 h-96 bg-brand-600 opacity-20 rounded-full blur-[80px] -mr-20 -mt-20 pointer-events-none"></div>
            
            <div class="lg:w-1/2 p-10 lg:p-20 flex flex-col justify-center relative z-10">
                <span class="inline-block py-1 px-3 w-max rounded-full bg-slate-700/50 text-brand-300 font-bold text-xs uppercase tracking-widest mb-4 border border-slate-600">
                    Lokasi Premium
                </span>
                <h2 class="text-3xl lg:text-5xl font-black text-white mb-6 leading-tight">Dekat Dengan <br>Segala Aktivitasmu</h2>
                
                <div class="space-y-8 mt-4 mb-12">
                    <div class="flex items-start gap-5">
                        <div class="w-12 h-12 rounded-2xl bg-slate-800 border border-slate-700 flex items-center justify-center shrink-0">
                            <i data-lucide="graduation-cap" class="w-5 h-5 text-brand-400"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-lg mb-1">Kampus & Perkantoran</h4>
                            <p class="text-sm text-slate-400 font-medium leading-relaxed">Hanya 5-10 menit berkendara menuju universitas ternama dan distrik bisnis.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-5">
                        <div class="w-12 h-12 rounded-2xl bg-slate-800 border border-slate-700 flex items-center justify-center shrink-0">
                            <i data-lucide="bus" class="w-5 h-5 text-brand-400"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-lg mb-1">Akses Transportasi</h4>
                            <p class="text-sm text-slate-400 font-medium leading-relaxed">Berjalan kaki ke halte bus terdekat dan stasiun KRL/MRT.</p>
                        </div>
                    </div>
                </div>
                
                <a href="https://maps.google.com" target="_blank" class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-brand-600 text-white font-bold rounded-xl hover:bg-brand-500 transition-colors shadow-lg shadow-brand-600/20 w-full sm:w-max">
                    <i data-lucide="map-pinned" class="w-5 h-5"></i> Buka Google Maps
                </a>
            </div>
            
            <div class="lg:w-1/2 min-h-[400px] lg:min-h-full bg-slate-200 relative border-l-8 border-slate-850">
                <iframe src="https://www.google.com/maps/place/INNA+Kost/@-7.046949,109.5612765,25726m/data=!3m1!1e3!4m16!1m9!4m8!1m0!1m6!1m2!1s0x2e702191df1b9a5b:0x992f1f5ab81a56c4!2sINNA+Kost,+Jl.+Perum+Sinar+Muncar,+Perum+Villa+Pisma+Asri,+Podo,+Kec.+Kedungwuni,+Kabupaten+Pekalongan,+Jawa+Tengah+51173!2m2!1d109.6544513!2d-6.9626482!3m5!1s0x2e702191df1b9a5b:0x992f1f5ab81a56c4!8m2!3d-6.9626482!4d109.6544513!16s%2Fg%2F11vqhpzhgr?entry=ttu&g_ep=EgoyMDI2MDMwNC4xIKXMDSoASAFQAw%3D%3D" 
                         width="100%" height="100%" style="border:0; position:absolute; inset:0;" 
                         allowfullscreen="" loading="lazy" class="grayscale-[20%] contrast-[110%]"></iframe>
            </div>
            
        </div>
    </div>
</section>

<div id="cancelModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm items-center justify-center z-[100] hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-[2rem] p-8 max-w-md w-full mx-4 shadow-2xl transform scale-95 transition-transform duration-300">
        <div class="flex items-center mb-6">
            <div class="w-14 h-14 bg-error-light rounded-2xl flex items-center justify-center shrink-0 mr-4 border border-error/20">
                <i data-lucide="alert-octagon" class="w-7 h-7 text-error"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-slate-800">Batalkan Pesanan?</h3>
                <p class="text-slate-500 text-sm mt-0.5 font-medium">Tindakan ini permanen.</p>
            </div>
        </div>
        
        <p class="text-slate-600 mb-6 font-medium leading-relaxed">
            Apakah Anda yakin ingin membatalkan booking kamar ini? Anda mungkin harus memesan ulang dari awal jika berubah pikiran.
        </p>
        
        <form id="cancelForm" method="POST" class="space-y-4">
            @csrf
            @method('DELETE')
            <div>
                <label for="alasan" class="block text-sm font-bold text-slate-700 mb-2">Alasan Pembatalan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <textarea id="alasan" name="alasan" rows="3" 
                          class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-error/20 focus:border-error transition-all resize-none"
                          placeholder="Ceritakan alasan Anda batal menyewa..."></textarea>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="hideCancelModal()" 
                        class="flex-1 bg-white border border-slate-200 text-slate-600 py-3.5 px-4 rounded-xl font-bold hover:bg-slate-50 transition-colors">
                    Kembali
                </button>
                <button type="submit" 
                        class="flex-1 bg-error text-white py-3.5 px-4 rounded-xl font-bold hover:bg-error/90 transition-colors shadow-lg shadow-error/20">
                    Ya, Batalkan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Pastikan Icon di-render
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });

    // Fungsi Modal Batal dengan Animasi
    let currentBookingId = null;
    const modal = document.getElementById('cancelModal');
    const modalContent = modal.querySelector('div');

    function showCancelModal(bookingId) {
        currentBookingId = bookingId;
        const form = document.getElementById('cancelForm');
        form.action = `/booking/${bookingId}/cancel`;
        
        modal.classList.remove('hidden');
        // Trigger reflow
        void modal.offsetWidth;
        
        modal.classList.add('flex');
        modal.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
        document.body.style.overflow = 'hidden'; // Kunci scroll layar
    }

    function hideCancelModal() {
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Buka scroll layar
            currentBookingId = null;
        }, 300); // Sesuaikan dengan duration animasi
    }

    // Tutup jika klik area gelap di luar kotak putih
    modal.addEventListener('click', function(e) {
        if (e.target === this) hideCancelModal();
    });
</script>
@endsection