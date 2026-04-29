@extends('layouts.app')

@section('title', 'Menunggu Pembayaran - Inna Kos')

@section('content')
<div class="max-w-2xl mx-auto px-4 pt-28 pb-24 lg:pb-10">
    
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-3xl shadow-lg shadow-orange-500/30 p-6 md:p-8 mb-6 text-white relative overflow-hidden">
        <div class="absolute -right-6 -top-6 w-32 h-32 bg-white rounded-full opacity-10 blur-xl"></div>
        <div class="absolute -left-6 -bottom-6 w-24 h-24 bg-black rounded-full opacity-10 blur-xl"></div>
        
        <div class="relative z-10 flex flex-col items-center text-center">
            <span class="text-orange-100 text-xs font-bold uppercase tracking-widest mb-2">Sisa Waktu Pembayaran</span>
            <span id="countdown" class="font-mono font-black text-4xl md:text-5xl tracking-tight mb-4 drop-shadow-md">--:--:--</span>
            <p class="text-sm font-medium text-orange-50 bg-black/10 px-4 py-1.5 rounded-full backdrop-blur-sm">
                Jatuh Tempo: {{ \Carbon\Carbon::parse($pembayaran->tanggal_jatuh_tempo)->translatedFormat('d M Y, H:i') }} WIB
            </p>
        </div>
    </div>

    <div class="space-y-4">
        
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i data-lucide="store" class="size-4 text-brand-600"></i> Detail Pesanan
                </h2>
                <span class="px-2.5 py-1 bg-brand-50 text-brand-600 text-[10px] font-bold uppercase rounded-md border border-brand-100">
                    Tipe {{ ucfirst($pembayaran->booking->kamar->tipe_kamar) }}
                </span>
            </div>
            
            <div class="p-5">
                <div class="flex gap-4 mb-4">
                    <div class="w-20 h-20 shrink-0 bg-slate-100 rounded-2xl overflow-hidden ring-1 ring-slate-200">
                         @if($pembayaran->booking->kamar->gambar)
                            <img src="{{ asset('storage/' . $pembayaran->booking->kamar->gambar) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i data-lucide="image-off" class="size-6 text-slate-400"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex-1 flex flex-col justify-center">
                        <h3 class="font-black text-lg text-slate-800 mb-1">Kamar {{ $pembayaran->booking->kamar->nomor_kamar }}</h3>
                        <p class="text-xs text-slate-500 font-medium">Durasi: {{ $pembayaran->booking->durasi }} Bulan</p>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">Check-in: {{ \Carbon\Carbon::parse($pembayaran->booking->tanggal_masuk)->translatedFormat('d M Y') }}</p>
                    </div>
                </div>

                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100 space-y-2">
                    <div class="flex justify-between items-center text-xs font-medium">
                        <span class="text-slate-500">Nama Penyewa</span>
                        <span class="text-slate-800">{{ $pembayaran->user->name }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs font-medium">
                        <span class="text-slate-500">No. WhatsApp</span>
                        <span class="text-slate-800">{{ $pembayaran->user->phone ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <i data-lucide="receipt" class="size-4 text-brand-600"></i> Rincian Tagihan
                </h2>
                <div class="flex items-center gap-2 bg-slate-50 px-2.5 py-1 rounded-md border border-slate-200 cursor-pointer hover:bg-slate-100" onclick="copyToClipboard()">
                    <span class="font-mono text-[10px] font-bold text-slate-600" id="kode-bayar">{{ $pembayaran->kode_pembayaran }}</span>
                    <i data-lucide="copy" class="size-3 text-slate-400"></i>
                </div>
            </div>

            <div class="p-5">
                <div class="space-y-3 mb-5">
                    <div class="flex justify-between items-center text-sm font-medium">
                        <span class="text-slate-500">Subtotal Sewa</span>
                        <span class="text-slate-800">Rp {{ number_format($pembayaran->booking->kamar->harga * $pembayaran->booking->durasi, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm font-medium">
                        <span class="text-slate-500">Biaya Aplikasi</span>
                        <span class="text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded text-[10px] uppercase">Gratis</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-dashed border-slate-200 flex justify-between items-center">
                    <span class="font-bold text-slate-800 text-sm">Total Pembayaran</span>
                    <span class="font-black text-2xl text-brand-600 tracking-tight">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <form id="cancel-form" action="{{ route('payment.cancel', $pembayaran->id) }}" method="POST" class="flex-1">
                @csrf
                <button type="button" onclick="confirmCancel()" class="w-full py-3.5 bg-red-50 text-red-600 font-bold rounded-2xl hover:bg-red-100 transition-colors flex justify-center items-center gap-2 text-xs border border-red-100">
                    Batalkan
                </button>
            </form>
            <a href="{{ route('booking.retry-payment', $pembayaran->id) }}" class="flex-1 py-3.5 bg-white text-slate-600 border border-slate-200 font-bold rounded-2xl hover:bg-slate-50 transition-colors flex justify-center items-center gap-2 text-xs">
                 Muat Ulang
            </a>
        </div>

    </div>

    <div class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-slate-200 z-[40] lg:static lg:bg-transparent lg:border-none lg:p-0 lg:mt-6 pb-safe">
        <button id="pay-button" class="w-full py-4 bg-brand-600 hover:bg-brand-700 text-white font-bold text-base rounded-2xl shadow-xl shadow-brand-600/30 transition-all transform active:scale-[0.98] flex justify-center items-center gap-2">
            Pilih Metode Pembayaran <i data-lucide="arrow-right" class="size-4"></i>
        </button>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });

    function copyToClipboard() {
        var copyText = document.getElementById("kode-bayar").innerText;
        navigator.clipboard.writeText(copyText).then(() => {
            Swal.fire({
                toast: true, position: 'top', showConfirmButton: false, timer: 2000,
                icon: 'success', title: 'Kode disalin!',
                customClass: { popup: 'rounded-xl shadow-md border border-slate-100 mt-4' }
            });
        });
    }

    function confirmCancel() {
        Swal.fire({
            title: 'Batalkan Pesanan?',
            text: "Kamar ini akan dilepas kembali ke publik.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444', cancelButtonColor: '#6A7686',
            confirmButtonText: 'Ya, Batalkan', cancelButtonText: 'Tutup',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-3xl',
                confirmButton: 'rounded-xl font-bold px-6 py-3',
                cancelButton: 'rounded-xl font-bold px-6 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Memproses...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => { Swal.showLoading() } });
                document.getElementById('cancel-form').submit();
            }
        });
    }

    const expiredTime = new Date("{{ \Carbon\Carbon::parse($pembayaran->tanggal_jatuh_tempo)->format('Y-m-d H:i:s') }}").getTime();

    const timer = setInterval(function() {
        const now = new Date().getTime();
        const distance = expiredTime - now;

        if (distance < 0) {
            clearInterval(timer);
            const cdEl = document.getElementById("countdown");
            cdEl.innerHTML = "WAKTU HABIS";
            cdEl.classList.replace('text-4xl', 'text-2xl');
            
            const btn = document.getElementById("pay-button");
            btn.disabled = true;
            btn.classList.add('bg-slate-300', 'cursor-not-allowed', 'shadow-none');
            btn.classList.remove('bg-brand-600', 'hover:bg-brand-700', 'shadow-brand-600/30');
            btn.innerHTML = 'Pembayaran Dibatalkan';
            return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("countdown").innerHTML = 
            (hours < 10 ? "0" + hours : hours) + ":" + 
            (minutes < 10 ? "0" + minutes : minutes) + ":" + 
            (seconds < 10 ? "0" + seconds : seconds);
    }, 1000);

    const payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function () {
        const snapToken = '{{ $pembayaran->snap_token }}';
        
        if(!snapToken) {
            Swal.fire({ icon: 'error', title: 'Sesi Habis', text: 'Silakan klik Muat Ulang Token.', customClass: { popup: 'rounded-3xl', confirmButton: 'bg-brand-600 rounded-xl px-6' }});
            return;
        }

        snap.pay(snapToken, {
            onSuccess: function(result){
                Swal.fire({ title: 'Berhasil!', text: 'Mengarahkan ke invoice...', icon: 'success', showConfirmButton: false, allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});
                window.location.href = "{{ route('booking.check-status', $pembayaran->id) }}";
            },
            onPending: function(result){
                window.location.href = "{{ route('booking.check-status', $pembayaran->id) }}";
            },
            onError: function(result){
                location.reload();
            },
            onClose: function(){
                window.location.href = "{{ route('booking.check-status', $pembayaran->id) }}";
            }
        });
    });
</script>
@endsection