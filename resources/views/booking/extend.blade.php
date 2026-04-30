@extends('layouts.app')

@section('title', 'Perpanjang Sewa Kamar')

@section('content')
<div class="max-w-xl mx-auto px-4 pt-20 md:pt-28 pb-10">
    
    <!-- Header Section -->
    <div class="mb-6 text-center">
        <div class="size-12 md:size-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-3 text-blue-600">
            <i data-lucide="calendar-plus" class="size-6 md:size-8"></i>
        </div>
        <h1 class="text-xl md:text-2xl font-black text-foreground tracking-tight">Perpanjang Sewa</h1>
        <p class="text-secondary mt-1 text-xs md:text-sm font-medium">
            Kamar {{ $booking->kamar->nomor_kamar ?? '-' }} • Berakhir {{ \Carbon\Carbon::parse($booking->tanggal_keluar)->translatedFormat('d M Y') }}
        </p>
    </div>

    <form action="{{ route('booking.process-extend', $booking->id) }}" method="POST" class="bg-white rounded-2xl md:rounded-3xl p-5 md:p-8 shadow-sm border border-border">
        @csrf
        
        <!-- Info Box -->
        <div class="mb-6 bg-amber-50 border border-amber-100 p-3 md:p-4 rounded-xl flex items-start gap-3">
            <i data-lucide="info" class="size-4 md:size-5 text-amber-600 shrink-0 mt-0.5"></i>
            <p class="text-[11px] md:text-xs text-amber-800 font-medium leading-relaxed">
                Masa sewa baru otomatis dimulai setelah tanggal <strong>{{ \Carbon\Carbon::parse($booking->tanggal_keluar)->translatedFormat('d M Y') }}</strong>.
            </p>
        </div>

        <!-- Input Section -->
        <div class="mb-6">
            <label for="durasi" class="block text-xs md:text-sm font-bold text-foreground mb-2 md:mb-3">Durasi Perpanjangan <span class="text-red-500">*</span></label>
            <div class="relative">
                <i data-lucide="hourglass" class="absolute left-4 top-1/2 -translate-y-1/2 size-4 md:size-5 text-secondary pointer-events-none z-10"></i>
                <select id="durasi" name="durasi" class="w-full pl-10 md:pl-12 pr-10 py-3 md:py-4 rounded-xl md:rounded-2xl border border-border focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all appearance-none cursor-pointer bg-white text-foreground font-bold text-sm md:text-base" required onchange="calculateTotal()">
                    <option value="" disabled selected>Pilih durasi...</option>
                    <option value="1">1 Bulan</option>
                    <option value="3">3 Bulan</option>
                    <option value="6">6 Bulan</option>
                    <option value="12">1 Tahun (12 Bulan)</option>
                </select>
                <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 size-4 md:size-5 text-secondary pointer-events-none"></i>
            </div>
        </div>

        <!-- Price Summary Box -->
        <div class="bg-slate-50 rounded-2xl p-4 mb-8 border border-slate-100">
            <div class="flex justify-between items-center mb-1">
                <span class="text-[10px] font-bold text-secondary uppercase tracking-widest">Harga per Bulan</span>
                <span class="text-xs font-bold text-foreground">Rp {{ number_format($booking->kamar->harga, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center pt-2 border-t border-slate-200">
                <span class="text-[10px] font-bold text-secondary uppercase tracking-widest">Total Tagihan</span>
                <span class="font-black text-lg md:text-xl text-blue-600 tracking-tight" id="summary-total">Rp 0</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col-reverse md:flex-row gap-3">
            <a href="{{ route('user.dashboard') }}" class="w-full md:w-1/3 py-3 md:py-4 bg-slate-100 text-slate-600 font-bold rounded-xl md:rounded-2xl hover:bg-slate-200 transition-colors text-center text-sm md:text-base">
                Batal
            </a>
            <button type="submit" id="btn-submit" disabled class="w-full md:w-2/3 py-3 md:py-4 bg-blue-600 text-white rounded-xl md:rounded-2xl font-bold transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2 hover:bg-blue-700 shadow-lg shadow-blue-600/20 text-sm md:text-base">
                Lanjut Pembayaran <i data-lucide="arrow-right" class="size-4"></i>
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });

    const hargaPerBulan = {{ $booking->kamar->harga }};
    const btnSubmit = document.getElementById('btn-submit');
    const summaryTotal = document.getElementById('summary-total');

    function calculateTotal() {
        const durasi = document.getElementById('durasi').value;
        if (durasi) {
            const total = hargaPerBulan * parseInt(durasi);
            summaryTotal.innerText = "Rp " + new Intl.NumberFormat('id-ID').format(total);
            btnSubmit.disabled = false;
        }
    }
</script>
@endsection