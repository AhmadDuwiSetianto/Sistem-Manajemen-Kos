@extends('layouts.user')

@section('title', 'Menunggu Pembayaran - MyKos')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <div class="bg-white rounded-3xl shadow-sm border border-warning/30 p-6 md:p-8 mb-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-warning-light rounded-full blur-2xl opacity-50 pointer-events-none"></div>

        <div class="flex items-center gap-5 relative z-10">
            <div class="w-14 h-14 rounded-2xl bg-warning-light flex items-center justify-center animate-pulse border border-warning/30 shrink-0">
                <i data-lucide="clock" class="size-7 text-warning-dark"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-foreground tracking-tight">Selesaikan Pembayaran</h1>
                <p class="text-sm font-medium text-secondary mt-1">
                    Batas waktu: <span class="font-bold text-warning-dark bg-warning/10 px-2 py-0.5 rounded-md">{{ \Carbon\Carbon::parse($pembayaran->tanggal_jatuh_tempo)->translatedFormat('d M Y, H:i') }} WIB</span>
                </p>
            </div>
        </div>

        <div class="bg-warning-light/50 px-5 py-3 rounded-2xl border border-warning/20 flex flex-col md:items-end w-full md:w-auto relative z-10">
            <span class="text-xs font-bold text-warning-dark uppercase tracking-widest mb-1">Sisa Waktu Membayar</span>
            <span id="countdown" class="font-mono font-black text-3xl text-warning-dark tracking-tight">--:--:--</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-3xl shadow-sm border border-border overflow-hidden">
                <div class="p-6 md:p-8 border-b border-border bg-muted/30">
                    <h2 class="font-bold text-foreground text-lg flex items-center gap-2">
                        <i data-lucide="shopping-bag" class="size-5 text-primary"></i> Rincian Pesanan
                    </h2>
                </div>
                
                <div class="p-6 md:p-8">
                    <div class="flex flex-col sm:flex-row gap-6 mb-8">
                        <div class="w-full sm:w-40 aspect-[4/3] sm:aspect-square shrink-0 bg-muted rounded-2xl overflow-hidden relative ring-1 ring-border group">
                             @if($pembayaran->booking->kamar->gambar)
                                <img src="{{ asset('storage/' . $pembayaran->booking->kamar->gambar) }}" alt="Kamar" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-secondary">
                                    <i data-lucide="image-off" class="size-8 opacity-50 mb-2"></i>
                                    <span class="text-xs font-medium">No Image</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex-1 flex flex-col justify-center">
                            <span class="inline-block px-3 py-1 bg-brand-50 text-brand-600 text-[10px] font-bold uppercase tracking-widest rounded-lg mb-2 w-max border border-brand-100">
                                Tipe {{ ucfirst($pembayaran->booking->kamar->tipe_kamar) }}
                            </span>
                            <h3 class="font-black text-2xl text-foreground mb-4">
                                Kamar {{ $pembayaran->booking->kamar->nomor_kamar }}
                            </h3>
                            
                            <div class="grid grid-cols-2 gap-4 bg-muted/40 p-4 rounded-2xl border border-border/60">
                                <div>
                                    <p class="text-[11px] font-bold text-secondary uppercase tracking-widest mb-1">Tanggal Masuk</p>
                                    <p class="font-semibold text-foreground flex items-center gap-1.5">
                                        <i data-lucide="calendar-check" class="size-3.5 text-primary"></i> 
                                        {{ \Carbon\Carbon::parse($pembayaran->booking->tanggal_masuk)->translatedFormat('d M Y') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[11px] font-bold text-secondary uppercase tracking-widest mb-1">Durasi Sewa</p>
                                    <p class="font-semibold text-foreground flex items-center gap-1.5">
                                        <i data-lucide="hourglass" class="size-3.5 text-primary"></i> 
                                        {{ $pembayaran->booking->durasi }} Bulan
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-dashed border-border">
                        <h4 class="text-[11px] font-bold text-secondary uppercase tracking-widest mb-4">Data Identitas Penyewa</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <p class="text-[10px] font-bold text-secondary uppercase tracking-wider mb-1">Nama Lengkap</p>
                                <p class="font-bold text-foreground text-sm truncate">{{ $pembayaran->user->name }}</p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <p class="text-[10px] font-bold text-secondary uppercase tracking-wider mb-1">Alamat Email</p>
                                <p class="font-bold text-foreground text-sm truncate">{{ $pembayaran->user->email }}</p>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <p class="text-[10px] font-bold text-secondary uppercase tracking-wider mb-1">No. WhatsApp</p>
                                <p class="font-bold text-foreground text-sm truncate">{{ $pembayaran->user->phone ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/40 border border-border p-6 md:p-8 sticky top-28">
                
                <div class="mb-6">
                    <p class="text-xs font-bold text-secondary uppercase tracking-widest mb-2">Kode Referensi Tagihan</p>
                    <div class="flex items-center justify-between bg-muted/50 p-3.5 rounded-xl border border-border group hover:border-primary/30 transition-colors cursor-pointer" onclick="copyToClipboard()">
                        <span class="font-mono font-black text-foreground tracking-wider select-all" id="kode-bayar">{{ $pembayaran->kode_pembayaran }}</span>
                        <button class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-secondary group-hover:text-primary group-hover:bg-primary/10 transition-colors shadow-sm">
                            <i data-lucide="copy" class="size-4"></i>
                        </button>
                    </div>
                </div>

                <div class="border-t border-dashed border-border my-6"></div>

                <div class="space-y-3.5 mb-8">
                    <div class="flex justify-between items-center text-sm font-medium">
                        <span class="text-secondary">Subtotal ({{ $pembayaran->booking->durasi }} Bln)</span>
                        <span class="text-foreground">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm font-medium">
                        <span class="text-secondary">Biaya Admin Platform</span>
                        <span class="text-success bg-success-light px-2 py-0.5 rounded text-xs font-bold">Gratis</span>
                    </div>
                    <div class="bg-brand-50 p-4 rounded-xl flex justify-between items-center mt-4 border border-brand-100">
                        <span class="text-brand-700 font-bold text-sm uppercase tracking-wider">Total Tagihan</span>
                        <span class="text-brand-600 font-black text-2xl tracking-tight">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <button id="pay-button" class="w-full py-4 bg-primary hover:bg-primary-hover text-white font-bold rounded-xl shadow-lg shadow-primary/30 transition-all transform active:scale-[0.98] flex justify-center items-center gap-2 group">
                        <i data-lucide="credit-card" class="size-5 group-hover:-rotate-12 transition-transform"></i> Bayar Tagihan Sekarang
                    </button>

                    <a href="{{ route('booking.retry-payment', $pembayaran->id) }}" class="w-full py-3.5 bg-white text-slate-700 border border-slate-200 font-bold rounded-xl hover:bg-slate-50 transition-colors flex justify-center items-center gap-2 text-sm">
                        <i data-lucide="refresh-cw" class="size-4"></i> Muat Ulang Token
                    </a>

                    <form id="cancel-form" action="{{ route('payment.cancel', $pembayaran->id) }}" method="POST" class="pt-2">
                        @csrf
                        <button type="button" onclick="confirmCancel()" class="w-full py-3 text-error font-bold rounded-xl hover:bg-error-light transition-colors flex justify-center items-center gap-2 text-sm border border-transparent hover:border-error/20">
                            <i data-lucide="x-circle" class="size-4"></i> Batalkan Pesanan
                        </button>
                    </form>
                </div>
                
                <p class="text-center text-[10px] text-secondary font-medium mt-6 pt-4 border-t border-border">
                    <i data-lucide="shield-check" class="inline size-3 text-success mr-1"></i> Pembayaran aman & terenkripsi
                </p>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
    // Init Lucide Icons
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });

    // 1. Copy Code dengan SweetAlert Toast (UI diperbarui)
    function copyToClipboard() {
        var copyText = document.getElementById("kode-bayar").innerText;
        navigator.clipboard.writeText(copyText).then(() => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                customClass: {
                    popup: 'rounded-xl shadow-lg border border-border'
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'success',
                title: 'Kode Referensi Disalin!'
            });
        });
    }

    // 2. Konfirmasi Pembatalan dengan SweetAlert Modern
    function confirmCancel() {
        Swal.fire({
            title: 'Batalkan Pesanan?',
            text: "Kamar ini akan dilepas kembali ke publik. Anda harus mengulang proses booking dari awal jika berubah pikiran.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ED6B60', // Warna error
            cancelButtonColor: '#6A7686',  // Warna secondary
            confirmButtonText: 'Ya, Batalkan Pesanan',
            cancelButtonText: 'Tutup',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-[2rem]',
                confirmButton: 'rounded-xl font-bold px-6 py-3',
                cancelButton: 'rounded-xl font-bold px-6 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang membatalkan pesanan Anda.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-2xl' },
                    didOpen: () => { Swal.showLoading() }
                });
                document.getElementById('cancel-form').submit();
            }
        });
    }

    // 3. Logic Countdown Timer
    const expiredTime = new Date("{{ \Carbon\Carbon::parse($pembayaran->tanggal_jatuh_tempo)->format('Y-m-d H:i:s') }}").getTime();

    const timer = setInterval(function() {
        const now = new Date().getTime();
        const distance = expiredTime - now;

        if (distance < 0) {
            clearInterval(timer);
            const cdEl = document.getElementById("countdown");
            cdEl.innerHTML = "KADALUARSA";
            cdEl.classList.replace('text-warning-dark', 'text-error');
            
            const btn = document.getElementById("pay-button");
            btn.disabled = true;
            btn.classList.add('bg-secondary', 'cursor-not-allowed');
            btn.classList.remove('bg-primary', 'hover:bg-primary-hover', 'shadow-primary/30');
            btn.innerHTML = '<i data-lucide="clock-4" class="size-5"></i> Waktu Habis';
            lucide.createIcons();
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

    // 4. INTEGRASI MIDTRANS SNAP (TIDAK ADA PERUBAHAN FUNGSI)
    const payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function () {
        const snapToken = '{{ $pembayaran->snap_token }}';
        
        if(!snapToken) {
            Swal.fire({
                icon: 'error',
                title: 'Token Tidak Valid',
                text: 'Sesi pembayaran telah habis. Silakan klik tombol "Muat Ulang Token".',
                customClass: { popup: 'rounded-2xl', confirmButton: 'bg-primary rounded-xl px-6' }
            });
            return;
        }

        snap.pay(snapToken, {
            onSuccess: function(result){
                Swal.fire({
                    title: 'Verifikasi Pembayaran',
                    text: 'Mohon tunggu, sistem sedang mengkonfirmasi pembayaran Anda...',
                    icon: 'success',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    customClass: { popup: 'rounded-2xl' },
                    didOpen: () => { Swal.showLoading(); }
                });
                window.location.href = "{{ route('booking.check-status', $pembayaran->id) }}";
            },
            onPending: function(result){
                Swal.fire({
                    title: 'Menunggu Pembayaran',
                    text: 'Silakan selesaikan pembayaran Anda menggunakan metode yang dipilih.',
                    icon: 'info',
                    customClass: { popup: 'rounded-2xl', confirmButton: 'bg-primary rounded-xl px-6' }
                }).then(() => {
                   window.location.href = "{{ route('booking.check-status', $pembayaran->id) }}";
                });
            },
            onError: function(result){
                Swal.fire({
                    icon: 'error',
                    title: 'Transaksi Gagal',
                    text: 'Terjadi masalah teknis saat memproses pembayaran. Silakan coba lagi.',
                    customClass: { popup: 'rounded-2xl', confirmButton: 'bg-error rounded-xl px-6' }
                }).then(() => {
                    location.reload();
                });
            },
            onClose: function(){
                Swal.fire({
                    title: 'Mengecek Status...',
                    text: 'Menyinkronkan data dengan server',
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-2xl' },
                    didOpen: () => { Swal.showLoading() }
                }).then(() => {
                    window.location.href = "{{ route('booking.check-status', $pembayaran->id) }}";
                });
            }
        });
    });
</script>
@endsection