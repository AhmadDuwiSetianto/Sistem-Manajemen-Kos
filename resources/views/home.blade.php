@extends('layouts.app')

@section('content')

<section id="home" class="relative bg-brand-50/50 pt-10 pb-20 lg:pt-20 lg:pb-32 overflow-hidden">
    <div class="container mx-auto px-6 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
            
            <div class="lg:w-1/2 text-center lg:text-left space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-white border border-brand-100 rounded-full shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-brand-600 tracking-wide uppercase">Tersedia Kamar Kosong</span>
                </div>
                
                <h1 class="text-4xl lg:text-6xl font-extrabold text-slate-900 leading-[1.15]">
                    Temukan Kamar <br>
                    <span class="text-brand-600 relative">
                        Impian Anda
                        <svg class="absolute w-full h-3 -bottom-1 left-0 text-brand-200 -z-10" viewBox="0 0 100 10" preserveAspectRatio="none">
                            <path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="8" fill="none" />
                        </svg>
                    </span>
                </h1>
                
                <p class="text-lg text-slate-500 leading-relaxed max-w-lg mx-auto lg:mx-0">
                    Sistem manajemen kos terbaik yang membantu Anda menemukan tempat tinggal nyaman dengan fasilitas lengkap yang dikelola profesional.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-4">
                    <a href="#kamar" class="px-8 py-4 bg-brand-600 text-white rounded-xl font-bold shadow-lg shadow-brand-600/30 hover:bg-brand-700 hover:-translate-y-1 transition duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-search"></i> Lihat Kamar
                    </a>
                    
                    <a href="#fasilitas" class="px-8 py-4 bg-white text-brand-600 border border-brand-200 rounded-xl font-bold hover:bg-brand-50 hover:border-brand-300 transition duration-300 flex items-center justify-center gap-2">
                        <i class="far fa-play-circle"></i> Info Fasilitas
                    </a>
                </div>

                <div class="pt-8 flex items-center justify-center lg:justify-start gap-6 text-slate-400">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-brand-600"></i>
                        <span class="text-sm font-medium text-slate-600">Terverifikasi</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-brand-600"></i>
                        <span class="text-sm font-medium text-slate-600">Lokasi Strategis</span>
                    </div>
                </div>
            </div>
            
            <div class="lg:w-1/2 relative">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-brand-100 rounded-full blur-3xl -z-10 opacity-60"></div>
                <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-brand-900/10 border-4 border-white transform rotate-2 hover:rotate-0 transition duration-500">
                     @if(file_exists(public_path('images/hero.jpg')))
                        <img src="{{ asset('images/hero.jpg') }}" alt="Interior Kamar" class="w-full h-auto object-cover">
                    @else
                        <img src="https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Interior Kamar" class="w-full h-auto object-cover">
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section id="kamar" class="py-24 bg-white">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
            <div>
                <span class="text-brand-600 font-bold tracking-wider uppercase text-sm">Pilihan Kamar</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mt-2">Kamar Kos Tersedia</h2>
                <p class="text-slate-500 mt-2 text-lg">Hanya menampilkan kamar terbaik yang siap huni.</p>
            </div>
            
            <a href="{{ route('kamar.index') }}" class="hidden md:flex items-center gap-2 text-brand-600 font-bold hover:text-brand-700 transition bg-brand-50 px-5 py-2.5 rounded-full">
                Lihat Semua Kamar <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        @if($kamarTersedia->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($kamarTersedia->take(3) as $kamar)
            <div class="bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-brand-600/10 hover:-translate-y-1 transition duration-300 flex flex-col h-full group">
                
                <div class="relative h-64 overflow-hidden">
                    <img src="{{ $kamar->gambar_url ?? 'https://images.unsplash.com/photo-1522771753035-4a5046216955?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}" 
                         alt="Kamar {{ $kamar->nomor_kamar }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    
                    <div class="absolute top-4 left-4 {{ $kamar->status == 'tersedia' ? 'bg-green-500' : 'bg-red-500' }} text-white px-3 py-1 rounded-lg text-xs font-bold shadow-lg">
                        {{ $kamar->status == 'tersedia' ? 'Tersedia' : 'Penuh' }}
                    </div>
                </div>

                <div class="p-6 flex flex-col flex-grow">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-xs text-brand-600 font-bold uppercase tracking-wider mb-1 bg-brand-50 inline-block px-2 py-1 rounded">
                                Lantai {{ $kamar->lantai }}
                            </p>
                            <h3 class="text-xl font-bold text-slate-800 mt-1">Kamar {{ $kamar->nomor_kamar }}</h3>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-bold text-brand-600">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</p>
                            <p class="text-xs text-slate-400">/bulan</p>
                        </div>
                    </div>

                    <div class="space-y-2 mb-6 pt-4 border-t border-slate-50">
                        @php
                            // Mengubah string fasilitas (misal: "AC, WiFi") menjadi array
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
                                <div class="flex items-center gap-3 text-sm text-slate-600">
                                    <i class="fas fa-check-circle text-brand-400 flex-shrink-0"></i>
                                    <span class="line-clamp-1">{{ $item }}</span>
                                </div>
                            @endforeach
                            
                            @if(count($listFasilitas) > 3)
                                <div class="text-xs text-brand-600 font-medium pl-7 pt-1">
                                    +{{ count($listFasilitas) - 3 }} fasilitas lainnya
                                </div>
                            @endif
                        @else
                            <p class="text-sm text-slate-400 italic pl-1">Fasilitas standar tersedia.</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-auto">
                        <a href="{{ route('kamar.show', $kamar->id) }}" 
                           class="flex items-center justify-center py-3 rounded-xl border-2 border-slate-100 text-slate-600 font-bold hover:border-brand-600 hover:text-brand-600 transition">
                            Detail
                        </a>
                        
                        @if(Auth::check() && Auth::user()->hasActiveBooking())
                             <button disabled class="flex items-center justify-center py-3 rounded-xl bg-slate-100 text-slate-400 font-bold cursor-not-allowed">
                                <i class="fas fa-check mr-2"></i> Aktif
                            </button>
                        @else
                            <a href="{{ route('booking.create', $kamar) }}" 
                               class="flex items-center justify-center py-3 rounded-xl bg-brand-600 text-white font-bold hover:bg-brand-700 transition shadow-lg shadow-brand-600/20">
                                Pesan
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-12 text-center md:hidden">
            <a href="{{ route('kamar.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-brand-50 text-brand-600 border border-brand-200 rounded-xl font-bold hover:bg-brand-100 transition">
                Lihat Semua Kamar
            </a>
        </div>
        
        @else
        <div class="text-center py-16 bg-slate-50 rounded-3xl border border-dashed border-slate-300">
            <div class="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-home text-slate-300 text-2xl"></i>
            </div>
            <p class="text-slate-500 font-medium">Belum ada kamar yang tersedia saat ini.</p>
        </div>
        @endif
    </div>
</section>

<section id="fasilitas" class="py-24 bg-brand-50/30">
    <div class="container mx-auto px-6">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-brand-600 font-bold tracking-wider uppercase text-sm">Kenapa MyKos?</span>
            <h2 class="text-3xl lg:text-4xl font-bold text-slate-900 mt-2">Fasilitas Standar Hotel</h2>
            <p class="text-slate-500 mt-4 text-lg">Kami memastikan pengalaman tinggal yang nyaman dan produktif untuk Anda.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition duration-300 text-center group">
                <div class="w-16 h-16 bg-blue-50 text-brand-600 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-6 group-hover:bg-brand-600 group-hover:text-white transition">
                    <i class="fas fa-wifi"></i>
                </div>
                <h3 class="font-bold text-xl text-slate-800 mb-2">Internet Cepat</h3>
                <p class="text-slate-500">Koneksi fiber optic dedicated.</p>
            </div>
            
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition duration-300 text-center group">
                <div class="w-16 h-16 bg-blue-50 text-brand-600 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-6 group-hover:bg-brand-600 group-hover:text-white transition">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="font-bold text-xl text-slate-800 mb-2">Aman 24 Jam</h3>
                <p class="text-slate-500">CCTV dan penjaga kos standby.</p>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition duration-300 text-center group">
                <div class="w-16 h-16 bg-blue-50 text-brand-600 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-6 group-hover:bg-brand-600 group-hover:text-white transition">
                    <i class="fas fa-broom"></i>
                </div>
                <h3 class="font-bold text-xl text-slate-800 mb-2">Bersih & Rapih</h3>
                <p class="text-slate-500">Layanan kebersihan area umum.</p>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-2 transition duration-300 text-center group">
                <div class="w-16 h-16 bg-blue-50 text-brand-600 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-6 group-hover:bg-brand-600 group-hover:text-white transition">
                    <i class="fas fa-couch"></i>
                </div>
                <h3 class="font-bold text-xl text-slate-800 mb-2">Ruang Santai</h3>
                <p class="text-slate-500">Area komunal nyaman untuk bersama.</p>
            </div>
        </div>
    </div>
</section>

<section id="lokasi" class="py-24 bg-white">
    <div class="container mx-auto px-6">
        <div class="bg-brand-900 rounded-[3rem] overflow-hidden shadow-2xl flex flex-col lg:flex-row relative">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
            
            <div class="lg:w-1/2 p-10 lg:p-20 flex flex-col justify-center relative z-10">
                <span class="text-brand-200 font-bold tracking-widest text-sm mb-2 uppercase">Lokasi Strategis</span>
                <h2 class="text-3xl lg:text-5xl font-bold text-white mb-6">Dekat Kemana Saja</h2>
                <div class="space-y-6 text-brand-100 mb-10">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-brand-800 flex items-center justify-center flex-shrink-0 text-white mt-1">1</div>
                        <div>
                            <h4 class="font-bold text-white text-lg">Universitas & Sekolah</h4>
                            <p class="text-sm opacity-80">Akses mudah ke area pendidikan.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-brand-800 flex items-center justify-center flex-shrink-0 text-white mt-1">2</div>
                        <div>
                            <h4 class="font-bold text-white text-lg">Pusat Transportasi</h4>
                            <p class="text-sm opacity-80">Dekat stasiun dan halte bus.</p>
                        </div>
                    </div>
                </div>
                <a href="https://maps.google.com" target="_blank" class="inline-flex items-center justify-center px-8 py-4 bg-white text-brand-900 font-bold rounded-xl hover:bg-brand-50 transition w-full sm:w-max">
                    <i class="fas fa-map-marker-alt mr-2 text-brand-600"></i> Lihat di Google Maps
                </a>
            </div>
            <div class="lg:w-1/2 min-h-[400px] bg-slate-200 relative">
                 <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.317239755703!2d106.82226931476915!3d-6.352966495404661!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69ec6b07b68ea5%3A0x17da46bdf9308386!2sSTT%20Terpadu%20Nurul%20Fikri%20-%20Kampus%20B!5e0!3m2!1sid!2sid!4v1647429535872!5m2!1sid!2sid" width="100%" height="100%" style="border:0; position:absolute; inset:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</section>

<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Batalkan Booking?</h3>
                <p class="text-gray-600 text-sm mt-1">Booking yang dibatalkan tidak dapat dikembalikan</p>
            </div>
        </div>
        
        <p class="text-gray-700 mb-6">
            Apakah Anda yakin ingin membatalkan booking ini?
        </p>
        
        <form id="cancelForm" method="POST" class="space-y-3">
            @csrf
            @method('DELETE')
            <div>
                <label for="alasan" class="block text-sm font-medium text-gray-700 mb-2">Alasan Pembatalan (Opsional)</label>
                <textarea id="alasan" name="alasan" rows="2" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                          placeholder="Masukkan alasan pembatalan..."></textarea>
            </div>
            
            <div class="flex space-x-3 pt-4">
                <button type="button" onclick="hideCancelModal()" 
                        class="flex-1 bg-gray-300 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-400 transition duration-300">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 bg-red-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-red-700 transition duration-300">
                    Ya, Batalkan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Cancel Modal Functions
let currentBookingId = null;

function showCancelModal(bookingId) {
    currentBookingId = bookingId;
    const modal = document.getElementById('cancelModal');
    const form = document.getElementById('cancelForm');
    form.action = `/booking/${bookingId}/cancel`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function hideCancelModal() {
    const modal = document.getElementById('cancelModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
    currentBookingId = null;
}

document.getElementById('cancelModal').addEventListener('click', function(e) {
    if (e.target === this) hideCancelModal();
});
</script>
@endsection