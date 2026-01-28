@extends('layouts.app')

@section('title', 'Booking Kamar - MyKos')

@section('content')
<div class="bg-slate-50 min-h-screen pt-8 pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <nav class="flex mb-8 text-sm font-medium text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-brand-600 transition">Beranda</a>
            <span class="mx-3 text-slate-300">/</span>
            <a href="{{ route('kamar.index') }}" class="hover:text-brand-600 transition">Kamar</a>
            <span class="mx-3 text-slate-300">/</span>
            <span class="text-brand-600 font-bold">Booking Form</span>
        </nav>

        @if (session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm animate-pulse">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-times-circle text-red-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-base font-bold text-red-800">Gagal Memproses Booking</h3>
                    <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-8 bg-orange-50 border-l-4 border-orange-500 p-4 rounded-r-xl shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-orange-500"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-orange-800">Periksa kembali input Anda:</h3>
                    <ul class="mt-2 text-sm text-orange-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-slate-900">Selesaikan Booking Anda</h1>
            <p class="text-slate-500 mt-2">Lengkapi data diri dan detail sewa untuk mengamankan kamar ini.</p>
        </div>

        <form id="booking-form" action="{{ route('booking.store', $kamar->id) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-50">
                            <div class="w-12 h-12 bg-brand-50 rounded-full flex items-center justify-center text-brand-600 text-xl">
                                <i class="far fa-user"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">1. Lengkapi Data Diri</h3>
                                <p class="text-sm text-slate-500">Data ini dibutuhkan untuk verifikasi penyewa</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                                <input type="text" value="{{ Auth::user()->name }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-600 focus:outline-none cursor-not-allowed" readonly>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                                <input type="email" value="{{ Auth::user()->email }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-600 focus:outline-none cursor-not-allowed" readonly>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">No. Telepon / WA <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', Auth::user()->phone) }}" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition" required placeholder="0812...">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">NIK / No. KTP <span class="text-red-500">*</span></label>
                                <input type="number" name="identity_number" id="identity_number" value="{{ old('identity_number', Auth::user()->identity_number) }}" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition" required placeholder="16 digit NIK">
                                <p class="text-xs text-slate-400 mt-1">Hanya angka.</p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Asal (Sesuai KTP) <span class="text-red-500">*</span></label>
                                <textarea name="address" id="address" rows="2" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition" required placeholder="Jalan, RT/RW, Kelurahan, Kecamatan...">{{ old('address', Auth::user()->address) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-50">
                            <div class="w-12 h-12 bg-brand-50 rounded-full flex items-center justify-center text-brand-600 text-xl">
                                <i class="far fa-calendar-alt"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">2. Detail Sewa</h3>
                                <p class="text-sm text-slate-500">Tentukan durasi dan tanggal mulai ngekos</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="durasi" class="block text-sm font-bold text-slate-700 mb-2">Durasi Sewa <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select id="durasi" name="durasi" class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition appearance-none cursor-pointer bg-white" required onchange="calculateTotal()">
                                            <option value="" disabled {{ old('durasi') ? '' : 'selected' }}>Pilih durasi...</option>
                                            <option value="1" {{ old('durasi') == '1' ? 'selected' : '' }}>1 Bulan</option>
                                            <option value="3" {{ old('durasi') == '3' ? 'selected' : '' }}>3 Bulan</option>
                                            <option value="6" {{ old('durasi') == '6' ? 'selected' : '' }}>6 Bulan</option>
                                            <option value="12" {{ old('durasi') == '12' ? 'selected' : '' }}>1 Tahun</option>
                                        </select>
                                        <i class="fas fa-clock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>
                                <div>
                                    <label for="tanggal_masuk" class="block text-sm font-bold text-slate-700 mb-2">Tanggal Masuk <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="date" id="tanggal_masuk" name="tanggal_masuk" 
                                               value="{{ old('tanggal_masuk', date('Y-m-d')) }}"
                                               min="{{ date('Y-m-d') }}"
                                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition cursor-pointer" required>
                                        <i class="fas fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-1">Minimal hari ini.</p>
                                </div>
                            </div>

                            <div>
                                <label for="catatan" class="block text-sm font-bold text-slate-700 mb-2">Catatan Tambahan (Opsional)</label>
                                <textarea id="catatan" name="catatan" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-100 transition" placeholder="Contoh: Saya akan membawa motor, butuh parkir.">{{ old('catatan') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="sticky top-28 bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 border border-slate-100">
                        <h3 class="text-lg font-bold text-slate-900 mb-4">Ringkasan Pesanan</h3>
                        
                        <div class="flex gap-4 mb-6 bg-slate-50 p-3 rounded-2xl border border-slate-200">
                            @if($kamar->gambar)
                                <img src="{{ asset('storage/' . $kamar->gambar) }}" class="w-16 h-16 rounded-xl object-cover bg-gray-200">
                            @else
                                <div class="w-16 h-16 rounded-xl bg-slate-200 flex items-center justify-center">
                                    <i class="fas fa-home text-slate-400"></i>
                                </div>
                            @endif
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm line-clamp-1">Kamar {{ $kamar->nomor_kamar }}</h4>
                                <p class="text-xs text-slate-500">{{ ucfirst($kamar->tipe_kamar) }}</p>
                                <p class="text-xs font-bold text-brand-600 mt-1">{{ $kamar->harga_formatted }}/bln</p>
                            </div>
                        </div>

                        <div class="space-y-3 text-sm text-slate-600 mb-6 pb-6 border-b border-slate-100">
                            <div class="flex justify-between">
                                <span>Harga Sewa</span>
                                <span>Rp {{ number_format($kamar->harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Durasi</span>
                                <span id="summary-durasi" class="font-medium text-slate-900">-</span>
                            </div>
                            <div class="flex justify-between text-green-600">
                                <span>Biaya Layanan</span>
                                <span>Gratis</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mb-8">
                            <span class="font-bold text-slate-800">Total Bayar</span>
                            <span class="font-extrabold text-2xl text-brand-600" id="summary-total">Rp 0</span>
                        </div>

                        <div class="mb-4 flex items-start gap-3">
                            <input type="checkbox" id="terms" name="terms" value="1" class="mt-1 w-4 h-4 text-brand-600 rounded border-gray-300 focus:ring-brand-500 cursor-pointer" required>
                            <label for="terms" class="text-xs text-slate-500 cursor-pointer">
                                Saya menyetujui <a href="#" class="text-brand-600 underline hover:text-brand-700">Syarat & Ketentuan</a> penyewaan kos, dan data diri saya sudah benar.
                            </label>
                        </div>

                        <button type="submit" id="btn-submit" disabled class="w-full py-4 bg-brand-600 text-white rounded-xl font-bold hover:bg-brand-700 transition shadow-lg shadow-brand-600/30 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                            <span>Lanjut Pembayaran</span>
                            <i class="fas fa-arrow-right text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const hargaPerBulan = {{ $kamar->harga }};

    function calculateTotal() {
        const durasi = document.getElementById('durasi').value;
        const summaryDurasi = document.getElementById('summary-durasi');
        const summaryTotal = document.getElementById('summary-total');
        
        // Panggil cek validasi setiap ada perubahan
        checkFormValidity();

        if (durasi) {
            const total = hargaPerBulan * durasi;
            summaryDurasi.innerText = durasi + " Bulan";
            summaryTotal.innerText = "Rp " + new Intl.NumberFormat('id-ID').format(total);
        } else {
            summaryDurasi.innerText = "-";
            summaryTotal.innerText = "Rp 0";
        }
    }

    function checkFormValidity() {
        // Ambil value dari semua input wajib
        const durasi = document.getElementById('durasi').value;
        const tanggalMasuk = document.getElementById('tanggal_masuk').value;
        const phone = document.getElementById('phone').value;
        const nik = document.getElementById('identity_number').value;
        const address = document.getElementById('address').value;
        const terms = document.getElementById('terms').checked;
        
        const btnSubmit = document.getElementById('btn-submit');

        // Cek apakah semuanya terisi (tidak kosong)
        const isValid = durasi && tanggalMasuk && phone && nik && address && terms;

        if (isValid) {
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
            btnSubmit.classList.add('hover:bg-brand-700', 'shadow-lg');
        } else {
            btnSubmit.disabled = true;
            btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
            btnSubmit.classList.remove('hover:bg-brand-700', 'shadow-lg');
        }
    }

    // Pasang Event Listener ke semua input wajib
    const inputs = ['durasi', 'tanggal_masuk', 'phone', 'identity_number', 'address', 'terms'];
    
    inputs.forEach(id => {
        const el = document.getElementById(id);
        if(el) {
            el.addEventListener('change', () => { calculateTotal(); checkFormValidity(); });
            el.addEventListener('keyup', checkFormValidity); // Untuk input text agar real-time saat ngetik
        }
    });

    // Initial check saat halaman dimuat
    window.addEventListener('DOMContentLoaded', () => {
        calculateTotal();
        checkFormValidity();
    });
</script>
@endsection