@extends('layouts.app')

@section('title', 'Booking Kamar - Inna Kos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 md:pt-28 pb-10">
        
    <nav class="flex items-center mb-6 md:mb-8 text-xs md:text-sm font-medium text-slate-500 overflow-x-auto whitespace-nowrap scrollbar-hide pb-2">
        <a href="{{ route('user.dashboard') }}" class="hover:text-brand-600 transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="size-3 md:size-4 mx-1.5 md:mx-2 text-slate-400 shrink-0"></i>
        <a href="{{ route('kamar.index') }}" class="hover:text-brand-600 transition-colors">Cari Kamar</a>
        <i data-lucide="chevron-right" class="size-3 md:size-4 mx-1.5 md:mx-2 text-slate-400 shrink-0"></i>
        <span class="text-brand-600 font-bold">Form Pemesanan</span>
    </nav>

    @if (session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 md:p-5 rounded-r-2xl shadow-sm flex items-start gap-3 md:gap-4 animate-pulse">
        <div class="size-8 md:size-10 bg-red-100 rounded-full flex items-center justify-center shrink-0 mt-0.5">
            <i data-lucide="x-circle" class="size-4 md:size-5 text-red-500"></i>
        </div>
        <div>
            <h3 class="text-sm md:text-base font-bold text-red-600">Gagal Memproses Booking</h3>
            <p class="text-xs md:text-sm text-red-500 mt-1">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if ($errors->any())
    <div class="mb-6 md:mb-8 bg-amber-50 border-l-4 border-amber-500 p-4 md:p-5 rounded-r-2xl shadow-sm flex items-start gap-3 md:gap-4">
        <div class="size-8 md:size-10 bg-amber-100 rounded-full flex items-center justify-center shrink-0 mt-0.5">
            <i data-lucide="alert-triangle" class="size-4 md:size-5 text-amber-600"></i>
        </div>
        <div>
            <h3 class="text-xs md:text-sm font-bold text-amber-700">Periksa kembali input Anda:</h3>
            <ul class="mt-1 md:mt-2 text-[11px] md:text-sm text-amber-600 list-disc list-inside font-medium space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="mb-6 md:mb-10">
        <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Selesaikan Pesanan</h1>
        <p class="text-xs md:text-sm text-slate-500 mt-1.5 md:mt-2 font-medium">Lengkapi data diri dan detail sewa untuk mengamankan kamar ini.</p>
    </div>

    <form id="booking-form" action="{{ route('booking.store', $kamar->id) }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 items-start">
            
            <!-- Form Kiri -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Verifikasi Data Diri -->
                <div class="bg-white rounded-2xl md:rounded-3xl p-5 md:p-8 shadow-sm border border-slate-200">
                    <div class="flex items-center gap-3 md:gap-4 mb-5 md:mb-6 pb-5 md:pb-6 border-b border-slate-200 bg-slate-50 -mx-5 md:-mx-8 -mt-5 md:-mt-8 p-5 md:p-8 rounded-t-2xl md:rounded-t-3xl">
                        <div class="size-10 md:size-12 bg-brand-50 rounded-lg md:rounded-xl flex items-center justify-center shrink-0">
                            <i data-lucide="user-check" class="size-5 md:size-6 text-brand-600"></i>
                        </div>
                        <div>
                            <h3 class="text-base md:text-lg font-bold text-slate-800">1. Verifikasi Data Diri</h3>
                            <p class="text-[11px] md:text-sm text-slate-500 font-medium mt-0.5">Data dibutuhkan untuk validasi penyewa.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <div>
                            <label class="block text-xs md:text-sm font-bold text-slate-800 mb-1.5 md:mb-2">Nama Lengkap</label>
                            <input type="text" value="{{ Auth::user()->name }}" class="w-full px-3.5 py-3 md:py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-500 font-medium text-sm focus:outline-none cursor-not-allowed" readonly>
                        </div>
                        <div>
                            <label class="block text-xs md:text-sm font-bold text-slate-800 mb-1.5 md:mb-2">Alamat Email</label>
                            <input type="email" value="{{ Auth::user()->email }}" class="w-full px-3.5 py-3 md:py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-500 font-medium text-sm focus:outline-none cursor-not-allowed" readonly>
                        </div>
                        <div>
                            <label class="block text-xs md:text-sm font-bold text-slate-800 mb-1.5 md:mb-2">No. WhatsApp <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <i data-lucide="phone" class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-slate-400"></i>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', Auth::user()->phone) }}" class="w-full pl-10 pr-4 py-3 md:py-3.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20 outline-none transition-all" required placeholder="0812...">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs md:text-sm font-bold text-slate-800 mb-1.5 md:mb-2">No. KTP (NIK) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <i data-lucide="credit-card" class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-slate-400"></i>
                                <input type="number" name="identity_number" id="identity_number" value="{{ old('identity_number', Auth::user()->identity_number) }}" class="w-full pl-10 pr-4 py-3 md:py-3.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20 outline-none transition-all" required placeholder="16 digit NIK">
                            </div>
                            <p class="text-[10px] md:text-[11px] font-semibold text-slate-500 mt-1"><i data-lucide="info" class="inline size-3 mr-1"></i>Hanya angka tanpa spasi.</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs md:text-sm font-bold text-slate-800 mb-1.5 md:mb-2">Alamat Asal (Sesuai KTP) <span class="text-red-500">*</span></label>
                            <textarea name="address" id="address" rows="2" class="w-full p-3.5 md:p-4 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20 outline-none transition-all resize-y" required placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota...">{{ old('address', Auth::user()->address) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Atur Waktu Sewa -->
                <div class="bg-white rounded-2xl md:rounded-3xl p-5 md:p-8 shadow-sm border border-slate-200">
                    <div class="flex items-center gap-3 md:gap-4 mb-5 md:mb-6 pb-5 md:pb-6 border-b border-slate-200 bg-slate-50 -mx-5 md:-mx-8 -mt-5 md:-mt-8 p-5 md:p-8 rounded-t-2xl md:rounded-t-3xl">
                        <div class="size-10 md:size-12 bg-amber-50 rounded-lg md:rounded-xl flex items-center justify-center shrink-0">
                            <i data-lucide="calendar-clock" class="size-5 md:size-6 text-amber-600"></i>
                        </div>
                        <div>
                            <h3 class="text-base md:text-lg font-bold text-slate-800">2. Atur Waktu Sewa</h3>
                            <p class="text-[11px] md:text-sm text-slate-500 font-medium mt-0.5">Tentukan durasi dan tanggal masuk.</p>
                        </div>
                    </div>

                    <div class="space-y-4 md:space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                            <div>
                                <label for="durasi" class="block text-xs md:text-sm font-bold text-slate-800 mb-1.5 md:mb-2">Durasi Sewa <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i data-lucide="hourglass" class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-slate-400 pointer-events-none z-10"></i>
                                    <select id="durasi" name="durasi" class="w-full pl-10 pr-10 py-3 md:py-3.5 rounded-xl border border-slate-200 focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20 outline-none transition-all appearance-none cursor-pointer bg-white text-sm text-slate-800 font-medium" required onchange="calculateTotal()">
                                        <option value="" disabled {{ old('durasi') ? '' : 'selected' }}>Pilih durasi...</option>
                                        <option value="1" {{ old('durasi') == '1' ? 'selected' : '' }}>1 Bulan</option>
                                        <option value="3" {{ old('durasi') == '3' ? 'selected' : '' }}>3 Bulan</option>
                                        <option value="6" {{ old('durasi') == '6' ? 'selected' : '' }}>6 Bulan</option>
                                        <option value="12" {{ old('durasi') == '12' ? 'selected' : '' }}>1 Tahun (12 Bln)</option>
                                    </select>
                                    <i data-lucide="chevron-down" class="absolute right-3.5 top-1/2 -translate-y-1/2 size-4 text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>
                            <div>
                                <label for="tanggal_masuk" class="block text-xs md:text-sm font-bold text-slate-800 mb-1.5 md:mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i data-lucide="calendar-check" class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-slate-400 pointer-events-none"></i>
                                    <input type="date" id="tanggal_masuk" name="tanggal_masuk" 
                                           value="{{ old('tanggal_masuk', date('Y-m-d')) }}"
                                           min="{{ date('Y-m-d') }}"
                                           class="w-full pl-10 pr-4 py-3 md:py-3.5 rounded-xl border border-slate-200 focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20 outline-none transition-all cursor-pointer text-sm font-medium text-slate-800" required>
                                </div>
                                <p class="text-[10px] md:text-[11px] font-semibold text-slate-500 mt-1.5">Paling cepat hari ini.</p>
                            </div>
                        </div>

                        <div>
                            <label for="catatan" class="block text-xs md:text-sm font-bold text-slate-800 mb-1.5 md:mb-2">Catatan Khusus <span class="text-slate-500 font-normal">(Opsional)</span></label>
                            <textarea id="catatan" name="catatan" rows="3" class="w-full p-3.5 md:p-4 rounded-xl border border-slate-200 focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20 outline-none transition-all resize-y text-sm" placeholder="Contoh: Butuh parkir motor, dll.">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Pesanan (Kanan/Bawah) -->
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-[100px] bg-white rounded-2xl md:rounded-3xl p-5 md:p-8 shadow-sm md:shadow-lg md:shadow-slate-200/40 border border-slate-200">
                    <h3 class="text-base md:text-lg font-black text-slate-800 mb-4 md:mb-5 flex items-center gap-2">
                        <i data-lucide="receipt" class="size-4 md:size-5 text-brand-600"></i> Ringkasan
                    </h3>
                    
                    <div class="flex items-center gap-3 md:gap-4 mb-5 md:mb-6 bg-slate-50 p-3 md:p-3.5 rounded-xl md:rounded-2xl border border-slate-200">
                        @if($kamar->gambar)
                            <img src="{{ \Illuminate\Support\Str::startsWith($theKamar->gambar, ['http://', 'https://']) ? $theKamar->gambar : asset('storage/' . $theKamar->gambar) }}" class="size-14 md:size-16 rounded-lg md:rounded-xl object-cover ring-1 ring-slate-200 shrink-0">
                        @else
                            <div class="size-14 md:size-16 rounded-lg md:rounded-xl bg-slate-100 flex items-center justify-center ring-1 ring-slate-200 shrink-0">
                                <i data-lucide="bed-double" class="size-5 md:size-6 text-slate-400"></i>
                            </div>
                        @endif
                        <div class="flex flex-col justify-center min-w-0">
                            <h4 class="font-bold text-slate-800 text-xs md:text-sm truncate">Kamar {{ $kamar->nomor_kamar }}</h4>
                            <p class="text-[10px] md:text-xs font-semibold text-slate-500 mt-0.5">Tipe {{ ucfirst($kamar->tipe_kamar) }}</p>
                            <p class="text-[11px] md:text-xs font-bold text-brand-600 mt-1">{{ $kamar->harga_formatted }} <span class="text-slate-500 font-medium text-[9px] md:text-[10px]">/ bln</span></p>
                        </div>
                    </div>

                    <div class="space-y-3 text-xs md:text-sm text-slate-500 font-medium mb-5 md:mb-6 pb-5 md:pb-6 border-b border-slate-200 border-dashed">
                        <div class="flex justify-between items-center">
                            <span>Sewa Bulanan</span>
                            <span class="text-slate-800 font-bold">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Durasi Dipilih</span>
                            <span id="summary-durasi" class="font-bold text-slate-800 bg-slate-100 px-2 py-0.5 md:py-1 rounded">-</span>
                        </div>
                        <div class="flex justify-between items-center text-green-600">
                            <span>Biaya Layanan</span>
                            <span class="font-bold bg-green-50 px-2 py-0.5 md:py-1 rounded uppercase text-[10px] md:text-xs">Gratis</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-6 md:mb-8">
                        <span class="font-bold text-slate-800 text-sm md:text-base">Total Tagihan</span>
                        <span class="font-black text-xl md:text-2xl text-brand-600 tracking-tight" id="summary-total">Rp 0</span>
                    </div>

                    <div class="mb-5 md:mb-6 flex items-start gap-2.5 md:gap-3 bg-brand-50 p-3 md:p-4 rounded-xl border border-brand-100">
                        <input type="checkbox" id="terms" name="terms" value="1" class="mt-0.5 md:mt-1 size-3.5 md:size-4 text-brand-600 rounded border-slate-300 focus:ring-brand-600 cursor-pointer accent-brand-600 shrink-0" required>
                        <label for="terms" class="text-[10px] md:text-xs text-slate-800 font-medium cursor-pointer leading-relaxed">
                            Saya setuju <a href="#" class="text-brand-600 font-bold hover:underline">S&K Kos</a>, dan menjamin data diri adalah benar.
                        </label>
                    </div>

                    <button type="submit" id="btn-submit" disabled class="group w-full py-3.5 md:py-4 bg-brand-600 text-white rounded-xl font-bold transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 text-sm md:text-base">
                        <span>Lanjut Bayar</span>
                        <i data-lucide="arrow-right" class="size-4 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                    <p class="text-[9px] md:text-[10px] text-center text-slate-500 font-medium mt-3"><i data-lucide="lock" class="inline size-3 md:size-3.5 mr-1 mb-0.5"></i>Transaksi dijamin aman & terenkripsi.</p>
                </div>
            </div>
            
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });

    const hargaPerBulan = {{ $kamar->harga }};

    function calculateTotal() {
        const durasi = document.getElementById('durasi').value;
        const summaryDurasi = document.getElementById('summary-durasi');
        const summaryTotal = document.getElementById('summary-total');
        
        checkFormValidity();

        if (durasi) {
            const total = hargaPerBulan * durasi;
            summaryDurasi.innerText = durasi + " Bln";
            summaryTotal.innerText = "Rp " + new Intl.NumberFormat('id-ID').format(total);
        } else {
            summaryDurasi.innerText = "-";
            summaryTotal.innerText = "Rp 0";
        }
    }

    function checkFormValidity() {
        const durasi = document.getElementById('durasi').value;
        const tanggalMasuk = document.getElementById('tanggal_masuk').value;
        const phone = document.getElementById('phone').value;
        const nik = document.getElementById('identity_number').value;
        const address = document.getElementById('address').value;
        const terms = document.getElementById('terms').checked;
        
        const btnSubmit = document.getElementById('btn-submit');
        const isValid = durasi && tanggalMasuk && phone && nik && address && terms;

        if (isValid) {
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
            btnSubmit.classList.add('hover:bg-brand-700', 'shadow-lg', 'shadow-brand-600/30');
        } else {
            btnSubmit.disabled = true;
            btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
            btnSubmit.classList.remove('hover:bg-brand-700', 'shadow-lg', 'shadow-brand-600/30');
        }
    }

    const inputs = ['durasi', 'tanggal_masuk', 'phone', 'identity_number', 'address', 'terms'];
    inputs.forEach(id => {
        const el = document.getElementById(id);
        if(el) {
            el.addEventListener('change', () => { calculateTotal(); checkFormValidity(); });
            el.addEventListener('keyup', checkFormValidity);
        }
    });

    window.addEventListener('DOMContentLoaded', () => {
        calculateTotal();
        checkFormValidity();
    });
</script>
@endsection