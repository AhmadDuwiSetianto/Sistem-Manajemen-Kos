@extends('layouts.app')

@section('title', 'Inna Kos Pekalongan')

@section('content')

<style>
    .elite-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 24px;
    }

    /* Hero Section dengan Background Custom */
    .elite-hero {
        background: linear-gradient(rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.8)), url('{{ asset('images/hero.png') }}') center/cover no-repeat;
        border-radius: 0 0 24px 24px;
        margin-bottom: 2rem;
        padding: 10rem 0 8rem 0; /* Padding disesuaikan */
        color: white;
    }
    .elite-hero-content {
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
    }
    .elite-hero h1 {
        font-size: clamp(2.2rem, 5vw, 3.5rem);
        font-weight: 800;
        margin-bottom: 1.5rem;
        line-height: 1.2;
        text-shadow: 0 2px 5px rgba(0,0,0,0.3);
    }
    .elite-hero-tagline {
        font-size: 1.2rem;
        color: #e2e8f0;
        opacity: 0.95;
    }

    /* Stats Banner - Tetap 4 Kolom Menyamping di Semua Layar */
    .elite-excellence-banner {
        background: #ffffff;
        border-radius: 30px;
        border: 1px solid #e2e8f0;
        padding: 2rem 1rem;
        margin: -4rem auto 3rem auto;
        display: grid;
        grid-template-columns: repeat(4, 1fr); /* Mengunci 4 kolom */
        gap: 1rem;
        text-align: center;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        position: relative;
        z-index: 10;
    }
    .elite-stat-item h3 {
        font-size: 1.8rem;
        color: #165DFF;
        font-weight: 800;
    }
    .elite-stat-item p {
        color: #64748b;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
    }

    /* Services / Fasilitas */
    .elite-section-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }
    .elite-section-header h2 {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }
    .elite-section-subhead {
        font-size: 1.125rem;
        color: #64748b;
        max-width: 700px;
        margin: 0 auto;
    }
    .elite-services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }
    .elite-service-card {
        text-align: center;
        padding: 2rem 1.5rem;
        background: #ffffff;
        border-radius: 30px;
        transition: all 0.2s;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .elite-service-card:hover {
        border-color: #93c5fd;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    }
    .elite-service-card i {
        font-size: 2.2rem;
        color: #165DFF;
        margin-bottom: 1rem;
        background: #eff6ff;
        padding: 1rem;
        border-radius: 30px;
    }
    .elite-service-card h4 {
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
        font-weight: 700;
        color: #0f172a;
    }
    .elite-service-card p {
        color: #64748b;
        font-size: 0.9rem;
    }

    /* App Promo / Contact */
    .elite-app-promo {
        background: linear-gradient(110deg, #0f172a 0%, #1e3a8a 100%);
        border-radius: 30px;
        padding: 3rem;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        gap: 3rem;
        color: white;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2);
    }
    .elite-app-text h3 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: white;
        font-weight: 800;
    }

    /* Media Queries untuk Mobile */
    @media (max-width: 768px) {
        .elite-hero { padding: 8rem 0 6rem 0; }
        
        .elite-stat-item h3 { 
            font-size: 1rem; /* Angka diperkecil */
            margin-bottom: 0.2rem;
        }
        .elite-stat-item p { 
            font-size: 0.45rem; /* Teks deskripsi sangat diperkecil */
            letter-spacing: -0.2px;
            line-height: 1.2;
        }
        
        .elite-app-promo { text-align: center; justify-content: center; }
    }
     .elite-app-map-container {
        flex-shrink: 0; 
        width: 100%;
        max-width: 400px; 
        height: 250px;
        background: white;
        border-radius: 30px;
        overflow: hidden;
        border: 4px solid rgba(255,255,255,0.1);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.3);
    }

    /* Media Queries untuk Mobile */
    @media (max-width: 768px) {
        .elite-stat-item h3 { font-size: 1.2rem; }
        .elite-stat-item p { font-size: 0.55rem; letter-spacing: -0.5px; }
        
        /* Promo / Peta turun ke bawah di HP */
        .elite-app-promo { 
            flex-direction: column; 
            text-align: center; 
            padding: 2rem;
            gap: 2rem;
        }
        .elite-app-text { max-width: 100%; }
        .elite-app-map-container { max-width: 100%; height: 220px; }
    }

    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="elite-hero" id="home">
    <div class="elite-container">
        <div class="elite-hero-content">
            <h1>
                <span style="color: #3b82f6;">Segarkan</span> Pikiran 
                <span style="color: #3b82f6;">&</span> Perkaya 
                <span style="color: #3b82f6;">Jiwa</span> Anda
            </h1>
            <p class="elite-hero-tagline">Tingkatkan kualitas hidup Anda dengan hunian kos bernuansa modern dan fasilitas lengkap setara hotel.</p>
        </div>
    </div>
</div>

<div class="elite-container">
    <div class="elite-excellence-banner">
        <div class="elite-stat-item"><h3>24/7</h3><p>Keamanan CCTV</p></div>
        <div class="elite-stat-item"><h3>100%</h3><p>Privasi Terjaga</p></div>
        <div class="elite-stat-item"><h3>3+</h3><p>Tipe Kamar Premium</p></div>
        <div class="elite-stat-item"><h3>Strategis</h3><p>Akses Pusat Kota</p></div>
    </div>
</div>

<div class="elite-container" id="fasilitas" style="margin: 5rem auto;">
    <div class="elite-section-header">
        <h2>Kenyamanan Maksimal Fasilitas Kami</h2>
        <p class="elite-section-subhead">Didesain khusus untuk memenuhi standar gaya hidup modern Anda.</p>
    </div>
    <div class="elite-services-grid">
        <div class="elite-service-card">
            <i class="fas fa-wifi"></i>
            <h4>Internet Cepat</h4>
            <p>Koneksi stabil 24 jam untuk menunjang tugas kuliah atau pekerjaan remote Anda.</p>
        </div>
        <div class="elite-service-card">
            <i class="fas fa-shield-alt"></i>
            <h4>Keamanan Total</h4>
            <p>Dilengkapi kamera CCTV dan sistem kunci gerbang digital yang aman.</p>
        </div>
        <div class="elite-service-card">
            <i class="fas fa-broom"></i>
            <h4>Pembersihan Rutin</h4>
            <p>Pembersihan area publik setiap hari agar lingkungan tetap segar dan higienis.</p>
        </div>
        <div class="elite-service-card">
            <i class="fas fa-car"></i>
            <h4>Area Parkir</h4>
            <p>Tersedia area parkir kendaraan yang aman dan berkanopi teduh.</p>
        </div>
    </div>
</div>

<section id="kamar" class="py-12 lg:py-24 bg-slate-50 border-y border-slate-200">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-10 lg:mb-16">
            <span class="text-brand-600 font-bold tracking-widest text-[10px] uppercase bg-brand-50 px-3 py-1 rounded-md mb-2 lg:mb-3 inline-block">
                Katalog Pilihan
            </span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight">Kamar Favorit Kami</h2>
            <p class="text-slate-500 mt-2 text-sm lg:text-base font-medium">Berikut adalah kamar rekomendasi. Terisi maupun kosong, kualitas kami tetap nomor satu.</p>
        </div>

        @if(isset($semuaKamar) && $semuaKamar->count() > 0)
            <div class="flex overflow-x-auto snap-x snap-mandatory gap-5 pb-8 -mx-4 px-4 sm:mx-0 sm:px-0 sm:grid sm:grid-cols-2 lg:grid-cols-3 sm:overflow-visible hide-scrollbar">
                
                @foreach($semuaKamar->take(3) as $kamar)
                <div class="snap-center shrink-0 w-[85%] sm:w-auto bg-white rounded-[1.5rem] lg:rounded-[2rem] overflow-hidden border border-slate-200/60 shadow-soft hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full group p-2 relative">
                    
                    <div class="relative h-48 lg:h-56 overflow-hidden rounded-xl lg:rounded-[1.5rem] bg-slate-200">
                        @if($kamar->gambar)
                            <img src="{{ \Illuminate\Support\Str::startsWith($kamar->gambar, ['http://', 'https://']) ? $kamar->gambar : asset('storage/' . $kamar->gambar) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
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
                    Lihat Semua Kamar</i>
                </a>
            </div>
            <div class="hidden md:block mt-16 text-center">
                <a href="{{ route('kamar.index') }}" class="inline-flex items-center justify-center gap-2 px-10 py-4 bg-white border border-brand-200 text-brand-600 rounded-2xl font-bold hover:bg-brand-50 hover:border-brand-300 transition-colors shadow-sm text-base w-auto">
                    Lihat Semua Kamar</i>
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

<div class="elite-container" id="lokasi" style="margin: 5rem auto;">
    <div class="elite-app-promo">
        
        <div class="elite-app-text">
            <h3>Pesan Kamar Anda Hari Ini</h3>
            <p style="font-size:1.1rem; margin: 0.5rem 0; color: #cbd5e1; line-height: 1.6;">
                Konsultasi langsung dengan admin, lakukan survei lokasi, dan booking kamar dengan mudah melalui WhatsApp. Kami siap melayani Anda!
            </p>
            <div style="margin-top: 1.5rem;">
                <a href="https://wa.me/6281234567890" style="background: #165DFF; color: white; padding: 14px 28px; border-radius: 12px; text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s;">
                    <i class="fab fa-whatsapp" style="font-size: 1.2rem;"></i> Tanya Admin via WA
                </a>
            </div>
        </div>
        
        <div class="elite-app-map-container">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3501.4429411945093!2d109.65445129999999!3d-6.9626481999999985!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e702191df1b9a5b%3A0x992f1f5ab81a56c4!2sINNA%20Kost!5e1!3m2!1sid!2sid!4v1777388736326!5m2!1sid!2sid" 
                width="100%" 
                height="100%" 
                style="border:30px;" 
                allowfullscreen="" 
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

    </div>
</div>

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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>

@endsection