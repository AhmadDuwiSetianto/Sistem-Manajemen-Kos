@extends('layouts.app')

@section('title', 'Inna Kos Pekalongan - Kos Premium')

@section('content')

<section id="home" class="pt-4 pb-10 lg:pt-8 lg:pb-16 bg-white">
    <div class="w-full max-w-[1600px] mx-auto px-2 sm:px-4 lg:px-6">
        
        <div class="relative w-full min-h-[450px] lg:min-h-[600px] rounded-[2rem] lg:rounded-[3rem] overflow-hidden flex items-center shadow-2xl">
            
            <img src="https://images.unsplash.com/photo-1522771753035-4a5046216955?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
                 alt="Main Hero Image" 
                 class="absolute inset-0 w-full h-full object-cover">
            
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/95 via-slate-900/70 to-transparent"></div>

            <div class="relative z-10 p-6 sm:p-10 lg:p-20 max-w-4xl text-left w-full">

                <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white leading-[1.2] tracking-tight mb-4 lg:mb-6">
                    Segarkan Pikiran <br>
                    <span class="text-brand-400">& Perkaya Jiwa Anda</span> <br>
                    Setiap Hari.
                </h1>

                <p class="text-sm md:text-lg text-slate-300 leading-relaxed font-medium mb-8 lg:mb-10 max-w-2xl">
                    Tingkatkan kualitas hidup Anda dengan hunian kos bernuansa modern, fasilitas lengkap setara hotel, dan lingkungan yang sangat mendukung produktivitas.
                </p>

                <div class="flex flex-col sm:flex-row gap-3 lg:gap-4">
                    <a href="#kamar" class="px-6 py-3.5 lg:px-8 lg:py-5 bg-brand-600 text-white rounded-xl font-bold shadow-lg shadow-brand-600/30 hover:bg-brand-500 transition-colors flex items-center justify-center gap-2 text-sm lg:text-base">
                        Pesan Sekarang <i data-lucide="arrow-right" class="size-4 lg:size-5"></i>
                    </a>
                    <a href="#fasilitas" class="px-6 py-3.5 lg:px-8 lg:py-5 bg-white/10 backdrop-blur-md text-white border border-white/20 rounded-xl font-bold hover:bg-white/20 transition-colors flex items-center justify-center gap-2 text-sm lg:text-base">
                        Lihat Fasilitas
                    </a>
                </div>
            </div>

            <div class="hidden lg:flex absolute bottom-10 right-10 bg-white/10 backdrop-blur-md border border-white/20 p-5 rounded-2xl items-center gap-5 z-10 shadow-2xl">
                <div class="size-14 bg-white text-brand-600 rounded-full flex items-center justify-center shrink-0 shadow-lg">
                    <i data-lucide="shield-check" class="size-7"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-300 font-bold uppercase tracking-widest mb-0.5">Keamanan</p>
                    <p class="text-base font-black text-white">CCTV 24 Jam</p>
                </div>
            </div>

        </div>
    </div>
</section>

<section id="about" class="py-12 lg:py-24 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col-reverse lg:flex-row items-center justify-between gap-8 lg:gap-20">
            
            <div class="w-full lg:w-1/2 grid grid-cols-2 gap-3 lg:gap-4 relative">
                <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="rounded-[1.5rem] lg:rounded-[2rem] w-full h-36 sm:h-48 lg:h-64 object-cover shadow-lg mt-4 lg:mt-8" alt="Lounge Kos">
                <img src="https://images.unsplash.com/photo-1499955085172-a104c9463ece?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" class="rounded-[1.5rem] lg:rounded-[2rem] w-full h-36 sm:h-48 lg:h-64 object-cover shadow-lg" alt="Kamar Kos">
            </div>

            <div class="w-full lg:w-1/2 space-y-4 lg:space-y-6">
                <span class="text-brand-600 font-bold tracking-widest text-[10px] uppercase bg-brand-50 px-3 py-1 rounded-md inline-block">Tentang Inna Kos</span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight">Tempat Kenyamanan <br class="hidden lg:block">Bertemu Kedamaian</h2>
                <p class="text-sm lg:text-base text-slate-500 font-medium leading-relaxed">
                    Kami hadir dengan visi menciptakan lingkungan tempat tinggal yang tenang, eksklusif, dan mendukung perkembangan diri Anda. Didesain khusus untuk memenuhi standar gaya hidup modern.
                </p>
                <div class="grid grid-cols-2 gap-3 lg:gap-4 pt-2">
                    <div class="bg-slate-50 p-3 lg:p-4 rounded-2xl border border-slate-100 text-center">
                        <i data-lucide="lock" class="size-5 lg:size-6 text-brand-600 mx-auto mb-2"></i>
                        <h4 class="font-bold text-slate-800 text-xs lg:text-sm">Privasi Terjamin</h4>
                    </div>
                    <div class="bg-slate-50 p-3 lg:p-4 rounded-2xl border border-slate-100 text-center">
                        <i data-lucide="map" class="size-5 lg:size-6 text-brand-600 mx-auto mb-2"></i>
                        <h4 class="font-bold text-slate-800 text-xs lg:text-sm">Akses Strategis</h4>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section id="fasilitas" class="py-12 lg:py-24 bg-slate-50 border-y border-slate-100 overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-10 lg:mb-16">
            <span class="text-brand-600 font-bold tracking-widest text-[10px] uppercase bg-brand-50 px-3 py-1 rounded-md mb-2 lg:mb-3 inline-block">
                Layanan Unggulan
            </span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight">Temukan Kenyamanan Maksimal <br class="hidden md:block">dengan Fasilitas Kami</h2>
        </div>

        <div class="flex overflow-x-auto snap-x snap-mandatory gap-4 pb-6 -mx-4 px-4 sm:mx-0 sm:px-0 sm:grid sm:grid-cols-2 lg:grid-cols-4 sm:overflow-visible hide-scrollbar">
            
            <div class="snap-center shrink-0 w-[85%] sm:w-auto bg-white p-6 lg:p-8 rounded-[1.5rem] lg:rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-xl transition-shadow group text-center">
                <div class="size-12 lg:size-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 lg:mb-6 mx-auto group-hover:bg-brand-600 group-hover:text-white transition-colors">
                    <i data-lucide="wifi" class="size-6 lg:size-7 text-brand-600 group-hover:text-white"></i>
                </div>
                <h3 class="font-black text-base lg:text-lg text-slate-800 mb-2">Internet Cepat</h3>
                <p class="text-slate-500 text-xs lg:text-sm font-medium">Koneksi stabil 24 jam untuk menunjang tugas kuliah atau pekerjaan.</p>
            </div>
            
            <div class="snap-center shrink-0 w-[85%] sm:w-auto bg-white p-6 lg:p-8 rounded-[1.5rem] lg:rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-xl transition-shadow group text-center">
                <div class="size-12 lg:size-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 lg:mb-6 mx-auto group-hover:bg-brand-600 group-hover:text-white transition-colors">
                    <i data-lucide="shield-check" class="size-6 lg:size-7 text-brand-600 group-hover:text-white"></i>
                </div>
                <h3 class="font-black text-base lg:text-lg text-slate-800 mb-2">Keamanan Total</h3>
                <p class="text-slate-500 text-xs lg:text-sm font-medium">Dilengkapi kamera CCTV dan sistem kunci gerbang digital.</p>
            </div>

            <div class="snap-center shrink-0 w-[85%] sm:w-auto bg-white p-6 lg:p-8 rounded-[1.5rem] lg:rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-xl transition-shadow group text-center">
                <div class="size-12 lg:size-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 lg:mb-6 mx-auto group-hover:bg-brand-600 group-hover:text-white transition-colors">
                    <i data-lucide="sparkles" class="size-6 lg:size-7 text-brand-600 group-hover:text-white"></i>
                </div>
                <h3 class="font-black text-base lg:text-lg text-slate-800 mb-2">Pembersihan Rutin</h3>
                <p class="text-slate-500 text-xs lg:text-sm font-medium">Pembersihan area publik setiap hari agar lingkungan tetap segar.</p>
            </div>

            <div class="snap-center shrink-0 w-[85%] sm:w-auto bg-white p-6 lg:p-8 rounded-[1.5rem] lg:rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-xl transition-shadow group text-center">
                <div class="size-12 lg:size-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 lg:mb-6 mx-auto group-hover:bg-brand-600 group-hover:text-white transition-colors">
                    <i data-lucide="car-front" class="size-6 lg:size-7 text-brand-600 group-hover:text-white"></i>
                </div>
                <h3 class="font-black text-base lg:text-lg text-slate-800 mb-2">Area Parkir</h3>
                <p class="text-slate-500 text-xs lg:text-sm font-medium">Tersedia area parkir kendaraan yang aman dan teduh.</p>
            </div>

        </div>

        <div class="flex sm:hidden items-center justify-center gap-2 mt-2 text-slate-400 font-medium text-xs">
            <i data-lucide="arrow-left-right" class="size-4"></i> Geser untuk melihat layanan lainnya
        </div>
    </div>
</section>

<section class="py-10 lg:py-16 bg-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-900 rounded-[2rem] lg:rounded-[2.5rem] p-8 lg:p-16 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6 lg:gap-8 shadow-2xl">
            <div class="absolute right-0 top-0 w-96 h-96 bg-brand-600 opacity-20 blur-[80px] rounded-full pointer-events-none"></div>
            
            <div class="relative z-10 max-w-2xl text-center md:text-left">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white mb-3 lg:mb-4">Kami Mewujudkan Kenyamanan Anda dengan Mudah</h2>
                <p class="text-sm lg:text-base text-slate-300 font-medium">Jangan ragu menghubungi kami jika Anda ingin melakukan survei lokasi secara langsung.</p>
            </div>
            
            <div class="relative z-10 shrink-0 w-full sm:w-auto mt-2 md:mt-0">
                <a href="https://wa.me/6281234567890" target="_blank" class="w-full sm:w-auto px-8 py-3.5 lg:py-4 bg-brand-600 text-white rounded-xl font-bold hover:bg-brand-500 transition-colors shadow-lg shadow-brand-600/30 flex items-center justify-center gap-2 text-sm lg:text-base">
                    <i class="fab fa-whatsapp text-lg"></i> Tanya Admin
                </a>
            </div>
        </div>
    </div>
</section>

<section id="kamar" class="py-12 lg:py-24 bg-slate-50 relative overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-10 lg:mb-16">
            <span class="text-brand-600 font-bold tracking-widest text-[10px] uppercase bg-brand-50 px-3 py-1 rounded-md mb-2 lg:mb-3 inline-block">
                Katalog Pilihan
            </span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight">Kamar Favorit Kami</h2>
            <p class="text-slate-500 mt-2 text-sm lg:text-base font-medium">Berikut adalah 3 kamar rekomendasi. Terisi maupun kosong, kualitas kami tetap nomor satu.</p>
        </div>

        @if(isset($semuaKamar) && $semuaKamar->count() > 0)
            <div class="flex overflow-x-auto snap-x snap-mandatory gap-5 pb-8 -mx-4 px-4 sm:mx-0 sm:px-0 sm:grid sm:grid-cols-2 lg:grid-cols-3 sm:overflow-visible hide-scrollbar">
                
                @foreach($semuaKamar->take(3) as $kamar)
                <div class="snap-center shrink-0 w-[85%] sm:w-auto bg-white rounded-[1.5rem] lg:rounded-[2rem] overflow-hidden border border-slate-200/60 shadow-soft hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full group p-2 relative">
                    
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
                                <p class="text-[9px] font-bold text-secondary uppercase tracking-widest mb-1">Nomor Kamar</p>
                                <h3 class="text-xl lg:text-2xl font-black text-slate-900 leading-none mt-1">{{ $kamar->nomor_kamar }}</h3>
                            </div>
                            <div class="text-right">
                                <p class="text-lg lg:text-xl font-black text-brand-600 leading-none">Rp {{ number_format($kamar->harga / 1000, 0) }}k</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">/ Bulan</p>
                            </div>
                        </div>

                        <div class="flex gap-2 mb-4 lg:mb-5">
                            <span class="bg-slate-50 border border-slate-100 text-slate-600 text-[9px] lg:text-[10px] font-bold px-2 py-1 rounded flex items-center gap-1">
                                <i data-lucide="tag" class="size-3"></i> Tipe {{ $kamar->tipe_kamar }}
                            </span>
                            <span class="bg-slate-50 border border-slate-100 text-slate-600 text-[9px] lg:text-[10px] font-bold px-2 py-1 rounded flex items-center gap-1">
                                <i data-lucide="layers" class="size-3"></i> Lantai {{ $kamar->lantai }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 mt-auto pt-4 border-t border-slate-200/60">
                            <a href="{{ route('kamar.show', $kamar->id) }}" class="flex justify-center items-center py-2.5 lg:py-3.5 bg-slate-50 border border-slate-200 text-slate-700 font-bold rounded-xl text-xs hover:bg-slate-100 transition">
                                Lihat Detail
                            </a>
                            
                            @if($kamar->status == 'terisi')
                                <button disabled class="flex justify-center items-center py-2.5 lg:py-3.5 bg-slate-200 text-slate-400 font-bold rounded-xl text-xs cursor-not-allowed">
                                    Diisi
                                </button>
                            @else
                                <a href="{{ route('booking.create', $kamar->id) }}" class="flex justify-center items-center py-2.5 lg:py-3.5 bg-brand-600 text-white font-bold rounded-xl text-xs hover:bg-brand-700 shadow-md shadow-brand-600/20 transition">
                                    Pesan
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach

            </div>

            <div class="flex sm:hidden items-center justify-center gap-2 mt-2 text-slate-400 font-medium text-xs">
                <i data-lucide="arrow-left-right" class="size-4"></i> Geser untuk melihat lainnya
            </div>

            <div class="mt-8 lg:mt-16 text-center md:hidden flex justify-center">
                <a href="{{ route('kamar.index') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-white border border-slate-200 text-slate-700 rounded-full font-bold shadow-sm hover:bg-slate-50 transition-colors text-sm w-[85%] max-w-[300px]">
                    Lihat Semua Kamar <i data-lucide="arrow-right" class="size-4"></i>
                </a>
            </div>
            <div class="hidden md:block mt-16 text-center">
                <a href="{{ route('kamar.index') }}" class="inline-flex items-center justify-center gap-2 px-10 py-4 bg-white border border-brand-200 text-brand-600 rounded-2xl font-bold hover:bg-brand-50 hover:border-brand-300 transition-colors shadow-sm text-base w-auto">
                    Lihat Semua Kamar <i data-lucide="arrow-right" class="size-4"></i>
                </a>
            </div>
            
        @else
            <div class="text-center py-12 lg:py-16 bg-white rounded-[2rem] border border-dashed border-slate-300">
                <i data-lucide="bed-double" class="size-10 lg:size-12 text-slate-300 mx-auto mb-3 lg:mb-4"></i>
                <h3 class="text-base lg:text-lg font-bold text-slate-800">Data Kamar Kosong</h3>
            </div>
        @endif
    </div>
</section>

<section class="py-12 lg:py-24 bg-white border-t border-slate-100">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-10 lg:mb-16">
            <span class="text-brand-600 font-bold tracking-widest text-[10px] uppercase bg-brand-50 px-3 py-1 rounded-md mb-2 lg:mb-3 inline-block">
                Testimoni
            </span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight">Testimoni Penghuni</h2>
            <p class="text-slate-500 mt-2 text-sm lg:text-base font-medium">Apa kata mereka yang sudah tinggal bersama kami?</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 lg:gap-6">
            <div class="bg-slate-50 p-6 lg:p-8 rounded-[1.5rem] lg:rounded-[2rem] shadow-sm border border-slate-100">
                <div class="flex items-center gap-1 text-warning mb-3 lg:mb-4">
                    <i data-lucide="star" class="size-3 lg:size-4 fill-warning"></i><i data-lucide="star" class="size-3 lg:size-4 fill-warning"></i><i data-lucide="star" class="size-3 lg:size-4 fill-warning"></i><i data-lucide="star" class="size-3 lg:size-4 fill-warning"></i><i data-lucide="star" class="size-3 lg:size-4 fill-warning"></i>
                </div>
                <p class="text-slate-600 font-medium mb-4 lg:mb-6 text-sm leading-relaxed">"Kos paling nyaman yang pernah saya tempati di Pekalongan. Keamanannya terjamin dan fasilitasnya persis seperti hotel."</p>
                <div class="flex items-center gap-3 border-t border-slate-200/60 pt-3 lg:pt-4">
                    <span class="font-bold text-slate-800 text-sm">- Sarah T.</span>
                </div>
            </div>

            <div class="bg-slate-50 p-6 lg:p-8 rounded-[1.5rem] lg:rounded-[2rem] shadow-sm border border-slate-100">
                <div class="flex items-center gap-1 text-warning mb-3 lg:mb-4">
                    <i data-lucide="star" class="size-3 lg:size-4 fill-warning"></i><i data-lucide="star" class="size-3 lg:size-4 fill-warning"></i><i data-lucide="star" class="size-3 lg:size-4 fill-warning"></i><i data-lucide="star" class="size-3 lg:size-4 fill-warning"></i><i data-lucide="star" class="size-3 lg:size-4 fill-warning"></i>
                </div>
                <p class="text-slate-600 font-medium mb-4 lg:mb-6 text-sm leading-relaxed">"Sirkulasi udara kamarnya sangat bagus, cocok banget buat saya yang sering kerja remote dari kosan."</p>
                <div class="flex items-center gap-3 border-t border-slate-200/60 pt-3 lg:pt-4">
                    <span class="font-bold text-slate-800 text-sm">- Budi S.</span>
                </div>
            </div>

            <div class="bg-slate-50 p-6 lg:p-8 rounded-[1.5rem] lg:rounded-[2rem] shadow-sm border border-slate-100">
                <div class="flex items-center gap-1 text-warning mb-3 lg:mb-4">
                    <i data-lucide="star" class="size-3 lg:size-4 fill-warning"></i><i data-lucide="star" class="size-3 lg:size-4 fill-warning"></i><i data-lucide="star" class="size-3 lg:size-4 fill-warning"></i><i data-lucide="star" class="size-3 lg:size-4 fill-warning"></i><i data-lucide="star" class="size-3 lg:size-4 text-slate-300 fill-slate-300"></i>
                </div>
                <p class="text-slate-600 font-medium mb-4 lg:mb-6 text-sm leading-relaxed">"Ibu kosnya ramah dan fast response kalau ada kendala fasilitas. Dekat dengan kampus juga."</p>
                <div class="flex items-center gap-3 border-t border-slate-200/60 pt-3 lg:pt-4">
                    <span class="font-bold text-slate-800 text-sm">- Maria K.</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="lokasi" class="py-12 lg:py-24 bg-slate-50 border-t border-slate-100">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-10 lg:mb-16">
            <div class="size-14 lg:size-16 bg-brand-50 text-brand-600 rounded-full flex items-center justify-center mx-auto mb-4 lg:mb-6">
                <i data-lucide="calendar-check" class="size-6 lg:size-8"></i>
            </div>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight mb-4 lg:mb-6">Pesan Kamar Anda Hari Ini</h2>
            <a href="https://wa.me/6281234567890" class="inline-flex items-center justify-center px-6 py-3.5 lg:px-8 lg:py-4 bg-brand-600 text-white font-bold rounded-xl lg:rounded-2xl hover:bg-brand-700 transition shadow-lg shadow-brand-600/30 text-sm lg:text-base">
                Konsultasi via WhatsApp
            </a>
        </div>

        <div class="bg-white p-2 sm:p-4 rounded-[1.5rem] lg:rounded-[2.5rem] mt-8 lg:mt-12 border border-slate-200 shadow-sm max-w-5xl mx-auto">
            <div class="w-full h-[300px] sm:h-[400px] lg:h-[500px] rounded-xl lg:rounded-[2rem] overflow-hidden bg-slate-200 relative border border-slate-100">
                <iframe 
                    src="https://www.google.com/maps/place/INNA+Kost/@-7.046949,109.5612765,25726m/data=!3m1!1e3!4m22!1m15!4m14!1m6!1m2!1s0x2e701c8847772095:0xbff0f80e20bac79d!2sLebakbarang,+Hutan,+Lebakbarang,+Kec.+Lebakbarang,+Kabupaten+Pekalongan,+Jawa+Tengah!2m2!1d109.65!2d-7.133333!1m6!1m2!1s0x2e702191df1b9a5b:0x992f1f5ab81a56c4!2sINNA+Kost,+Jl.+Perum+Sinar+Muncar,+Perum+Villa+Pisma+Asri,+Podo,+Kec.+Kedungwuni,+Kabupaten+Pekalongan,+Jawa+Tengah+51173!2m2!1d109.6544513!2d-6.9626482!3m5!1s0x2e702191df1b9a5b:0x992f1f5ab81a56c4!8m2!3d-6.9626482!4d109.6544513!16s%2Fg%2F11vqhpzhgr?entry=ttu&g_ep=EgoyMDI2MDMwNC4xIKXMDSoASAFQAw%3D%3D" 
                    width="100%" 
                    height="100%" 
                    style="border:0; position:absolute; inset:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    class="grayscale-[15%]">
                </iframe>
                
                <div class="absolute bottom-4 left-4 right-4 sm:bottom-6 sm:left-6 sm:right-auto bg-white p-4 lg:p-5 rounded-xl lg:rounded-2xl shadow-xl flex items-center gap-3 lg:gap-4 border border-slate-100 z-10 max-w-sm">
                    <div class="size-10 lg:size-12 bg-slate-50 text-slate-600 rounded-xl flex items-center justify-center shrink-0 border border-slate-100">
                        <i data-lucide="map-pin" class="size-4 lg:size-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-xs lg:text-sm">Pusat Kota Pekalongan</h4>
                        <p class="text-[10px] lg:text-xs text-slate-500 font-medium mt-0.5">Akses mudah ke kampus dan pusat perbelanjaan.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<footer class="bg-white border-t border-slate-200 pt-16 pb-8">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            
            <div class="col-span-1 md:col-span-2 shrink-0">
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex items-center justify-center">
                        @if(file_exists(public_path('images/innakos.png')))
                            <img src="{{ asset('images/innakos.png') }}" alt="Inna Kos Logo" class="h-10 w-auto object-contain">
                        @else
                            <i data-lucide="home" class="size-8 text-brand-600"></i>
                        @endif
                    </div>
                    <span class="text-2xl font-black text-slate-800 tracking-tight">Inna<span class="text-brand-600">Kos</span></span>
                </div>
                <p class="text-slate-500 leading-relaxed max-w-sm text-sm font-medium">
                    Platform penyewaan kos modern dengan fasilitas terlengkap, keamanan terjamin, dan pelayanan terbaik untuk kenyamanan tempat tinggal Anda di Pekalongan.
                </p>
            </div>
            
            <div>
                <h4 class="font-black text-slate-800 mb-5">Jelajahi</h4>
                <ul class="space-y-3 text-sm font-bold text-slate-500">
                    <li><a href="{{ route('kamar.index') }}" class="hover:text-brand-600 transition inline-flex items-center gap-2"><i data-lucide="chevron-right" class="size-3.5 text-brand-400"></i> Cari Kamar</a></li>
                    <li><a href="#fasilitas" class="hover:text-brand-600 transition inline-flex items-center gap-2"><i data-lucide="chevron-right" class="size-3.5 text-brand-400"></i> Fasilitas Kami</a></li>
                    <li><a href="#lokasi" class="hover:text-brand-600 transition inline-flex items-center gap-2"><i data-lucide="chevron-right" class="size-3.5 text-brand-400"></i> Peta Lokasi</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-black text-slate-800 mb-5">Hubungi Kami</h4>
                <ul class="space-y-4 text-sm font-bold text-slate-500">
                    <li class="flex items-start gap-3">
                        <div class="size-8 rounded-full bg-success-light text-success flex items-center justify-center shrink-0">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div>
                            <span class="text-slate-800 block mb-0.5">0812-3456-7890</span>
                            <span class="text-[10px] text-slate-400 uppercase tracking-widest">Layanan Chat 24/7</span>
                        </div>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="size-8 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                            <i data-lucide="mail" class="size-4"></i>
                        </div>
                        <span class="text-slate-600">hello@innakos.id</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-slate-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-center text-sm font-bold text-slate-400">
                © {{ date('Y') }} Inna Kos Management. Hak Cipta Dilindungi.
            </p>
            <div class="flex gap-3">
                <a href="#" class="size-10 rounded-full bg-slate-50 text-slate-400 hover:bg-brand-50 hover:text-brand-600 flex items-center justify-center transition-colors"><i class="fab fa-instagram text-lg"></i></a>
                <a href="#" class="size-10 rounded-full bg-slate-50 text-slate-400 hover:bg-brand-50 hover:text-brand-600 flex items-center justify-center transition-colors"><i class="fab fa-facebook-f text-lg"></i></a>
            </div>
        </div>
    </div>
</footer>

<style>
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