@extends('layouts.user')

@section('title', 'Booking Kamar - MyKos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        
    <nav class="flex items-center mb-8 text-sm font-medium text-secondary">
        <a href="{{ route('user.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
        <i data-lucide="chevron-right" class="size-4 mx-2 text-secondary/50"></i>
        <a href="{{ route('kamar.index') }}" class="hover:text-primary transition-colors">Cari Kamar</a>
        <i data-lucide="chevron-right" class="size-4 mx-2 text-secondary/50"></i>
        <span class="text-primary font-bold">Form Pemesanan</span>
    </nav>

    @if (session('error'))
    <div class="mb-6 bg-error-light border-l-4 border-error p-5 rounded-r-2xl shadow-sm flex items-start gap-4 animate-pulse">
        <div class="size-10 bg-error/20 rounded-full flex items-center justify-center shrink-0 mt-0.5">
            <i data-lucide="x-circle" class="size-5 text-error"></i>
        </div>
        <div>
            <h3 class="text-base font-bold text-error">Gagal Memproses Booking</h3>
            <p class="text-sm text-error/80 mt-1">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if ($errors->any())
    <div class="mb-8 bg-warning-light border-l-4 border-warning p-5 rounded-r-2xl shadow-sm flex items-start gap-4">
        <div class="size-10 bg-warning/20 rounded-full flex items-center justify-center shrink-0 mt-0.5">
            <i data-lucide="alert-triangle" class="size-5 text-warning-dark"></i>
        </div>
        <div>
            <h3 class="text-sm font-bold text-warning-dark">Periksa kembali input Anda:</h3>
            <ul class="mt-2 text-sm text-warning-dark/80 list-disc list-inside font-medium space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="mb-10">
        <h1 class="text-3xl font-black text-foreground tracking-tight">Selesaikan Pesanan Anda</h1>
        <p class="text-secondary mt-2 font-medium">Lengkapi data diri dan detail sewa untuk mengamankan kamar ini.</p>
    </div>

    <form id="booking-form" action="{{ route('booking.store', $kamar->id) }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-border">
                    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-border bg-muted/30 -mx-6 -mt-6 p-6 rounded-t-3xl">
                        <div class="size-12 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                            <i data-lucide="user-check" class="size-6 text-primary"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-foreground">1. Verifikasi Data Diri</h3>
                            <p class="text-sm text-secondary font-medium mt-0.5">Data ini dibutuhkan untuk validasi penyewa.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-foreground mb-2">Nama Lengkap</label>
                            <input type="text" value="{{ Auth::user()->name }}" class="w-full px-4 py-3.5 bg-muted/50 border border-border rounded-xl text-secondary font-medium focus:outline-none cursor-not-allowed" readonly>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-foreground mb-2">Alamat Email</label>
                            <input type="email" value="{{ Auth::user()->email }}" class="w-full px-4 py-3.5 bg-muted/50 border border-border rounded-xl text-secondary font-medium focus:outline-none cursor-not-allowed" readonly>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-foreground mb-2">No. WhatsApp <span class="text-error">*</span></label>
                            <div class="relative">
                                <i data-lucide="phone" class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', Auth::user()->phone) }}" class="w-full pl-11 pr-4 py-3.5 bg-white border border-border rounded-xl text-foreground focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" required placeholder="0812...">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-foreground mb-2">No. KTP (NIK) <span class="text-error">*</span></label>
                            <div class="relative">
                                <i data-lucide="credit-card" class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                                <input type="number" name="identity_number" id="identity_number" value="{{ old('identity_number', Auth::user()->identity_number) }}" class="w-full pl-11 pr-4 py-3.5 bg-white border border-border rounded-xl text-foreground focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all" required placeholder="16 digit NIK KTP">
                            </div>
                            <p class="text-[11px] font-semibold text-secondary mt-1.5"><i data-lucide="info" class="inline size-3 mr-1"></i>Hanya angka tanpa spasi.</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-foreground mb-2">Alamat Asal (Sesuai KTP) <span class="text-error">*</span></label>
                            <textarea name="address" id="address" rows="2" class="w-full p-4 bg-white border border-border rounded-xl text-foreground focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-y" required placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota...">{{ old('address', Auth::user()->address) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-border">
                    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-border bg-muted/30 -mx-6 -mt-6 p-6 rounded-t-3xl">
                        <div class="size-12 bg-warning-light rounded-xl flex items-center justify-center shrink-0">
                            <i data-lucide="calendar-clock" class="size-6 text-warning-dark"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-foreground">2. Atur Waktu Sewa</h3>
                            <p class="text-sm text-secondary font-medium mt-0.5">Tentukan durasi dan tanggal mulai masuk.</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <label for="durasi" class="block text-sm font-bold text-foreground mb-2">Durasi Sewa <span class="text-error">*</span></label>
                                <div class="relative">
                                    <i data-lucide="hourglass" class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none z-10"></i>
                                    <select id="durasi" name="durasi" class="w-full pl-11 pr-10 py-3.5 rounded-xl border border-border focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all appearance-none cursor-pointer bg-white text-foreground font-medium" required onchange="calculateTotal()">
                                        <option value="" disabled {{ old('durasi') ? '' : 'selected' }}>Pilih durasi...</option>
                                        <option value="1" {{ old('durasi') == '1' ? 'selected' : '' }}>1 Bulan</option>
                                        <option value="3" {{ old('durasi') == '3' ? 'selected' : '' }}>3 Bulan</option>
                                        <option value="6" {{ old('durasi') == '6' ? 'selected' : '' }}>6 Bulan</option>
                                        <option value="12" {{ old('durasi') == '12' ? 'selected' : '' }}>1 Tahun (12 Bulan)</option>
                                    </select>
                                    <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                                </div>
                            </div>
                            
                            <div>
                                <label for="tanggal_masuk" class="block text-sm font-bold text-foreground mb-2">Tanggal Mulai Ngekos <span class="text-error">*</span></label>
                                <div class="relative">
                                    <i data-lucide="calendar-check" class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                                    <input type="date" id="tanggal_masuk" name="tanggal_masuk" 
                                           value="{{ old('tanggal_masuk', date('Y-m-d')) }}"
                                           min="{{ date('Y-m-d') }}"
                                           class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-border focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all cursor-pointer font-medium text-foreground" required>
                                </div>
                                <p class="text-[11px] font-semibold text-secondary mt-1.5">Paling cepat hari ini.</p>
                            </div>
                        </div>

                        <div>
                            <label for="catatan" class="block text-sm font-bold text-foreground mb-2">Catatan Khusus <span class="text-secondary font-normal">(Opsional)</span></label>
                            <textarea id="catatan" name="catatan" rows="3" class="w-full p-4 rounded-xl border border-border focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-y" placeholder="Contoh: Saya akan membawa motor, butuh slot parkir, atau estimasi jam kedatangan.">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="sticky top-[100px] bg-white rounded-3xl p-6 md:p-8 shadow-lg shadow-slate-200/40 border border-border">
                    <h3 class="text-lg font-black text-foreground mb-5 flex items-center gap-2">
                        <i data-lucide="receipt" class="size-5 text-primary"></i> Ringkasan Pesanan
                    </h3>
                    
                    <div class="flex gap-4 mb-6 bg-muted/40 p-3.5 rounded-2xl border border-border/60">
                        @if($kamar->gambar)
                            <img src="{{ asset('storage/' . $kamar->gambar) }}" class="size-16 rounded-xl object-cover ring-1 ring-border">
                        @else
                            <div class="size-16 rounded-xl bg-muted flex items-center justify-center ring-1 ring-border">
                                <i data-lucide="bed-double" class="size-6 text-secondary"></i>
                            </div>
                        @endif
                        <div class="flex flex-col justify-center">
                            <h4 class="font-bold text-foreground text-sm line-clamp-1">Kamar {{ $kamar->nomor_kamar }}</h4>
                            <p class="text-xs font-semibold text-secondary mt-0.5">Tipe {{ ucfirst($kamar->tipe_kamar) }}</p>
                            <p class="text-xs font-bold text-primary mt-1.5">{{ $kamar->harga_formatted }} <span class="text-secondary font-medium">/ bln</span></p>
                        </div>
                    </div>

                    <div class="space-y-3.5 text-sm text-secondary font-medium mb-6 pb-6 border-b border-border border-dashed">
                        <div class="flex justify-between items-center">
                            <span>Harga Sewa Bulanan</span>
                            <span class="text-foreground font-bold">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Durasi Dipilih</span>
                            <span id="summary-durasi" class="font-bold text-foreground bg-muted px-2.5 py-1 rounded-md">-</span>
                        </div>
                        <div class="flex justify-between items-center text-success">
                            <span>Biaya Layanan Aplikasi</span>
                            <span class="font-bold bg-success-light px-2.5 py-1 rounded-md">Gratis</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-8">
                        <span class="font-bold text-foreground">Total Tagihan</span>
                        <span class="font-black text-2xl text-primary tracking-tight" id="summary-total">Rp 0</span>
                    </div>

                    <div class="mb-6 flex items-start gap-3 bg-primary/5 p-4 rounded-xl border border-primary/10">
                        <input type="checkbox" id="terms" name="terms" value="1" class="mt-0.5 size-4 text-primary rounded border-border focus:ring-primary cursor-pointer accent-primary" required>
                        <label for="terms" class="text-xs text-foreground font-medium cursor-pointer leading-relaxed">
                            Saya menyetujui <a href="#" class="text-primary font-bold hover:underline">Syarat & Ketentuan</a> Kos, dan menjamin seluruh data diri di atas adalah benar.
                        </label>
                    </div>

                    <button type="submit" id="btn-submit" disabled class="group w-full py-4 bg-primary text-white rounded-xl font-bold transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <span>Lanjut Pembayaran</span>
                        <i data-lucide="arrow-right" class="size-4 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                    <p class="text-[10px] text-center text-secondary font-medium mt-3"><i data-lucide="lock" class="inline size-3 mr-1"></i>Transaksi Anda dijamin aman & terenkripsi.</p>
                </div>
            </div>
            
        </div>
    </form>
</div>

<script>
    // Init Lucide Icons
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
            summaryDurasi.innerText = durasi + " Bulan";
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
            btnSubmit.classList.add('hover:bg-primary-hover', 'shadow-lg', 'shadow-primary/30');
        } else {
            btnSubmit.disabled = true;
            btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
            btnSubmit.classList.remove('hover:bg-primary-hover', 'shadow-lg', 'shadow-primary/30');
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