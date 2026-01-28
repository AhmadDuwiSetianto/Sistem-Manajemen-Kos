@extends('layouts.app')

@section('title', 'Menunggu Pembayaran - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6 flex flex-col md:flex-row items-center justify-between gap-4 transition-all hover:shadow-md">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center animate-pulse">
                    <i class="fas fa-clock text-orange-600 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Menunggu Pembayaran</h1>
                    <p class="text-sm text-gray-500">Selesaikan sebelum <span class="font-semibold text-orange-600">{{ \Carbon\Carbon::parse($pembayaran->tanggal_jatuh_tempo)->translatedFormat('d F Y, H:i') }}</span></p>
                </div>
            </div>
            <div class="bg-orange-50 px-4 py-2 rounded-lg border border-orange-100">
                <span class="text-sm text-gray-600 mr-2">Sisa Waktu:</span>
                <span id="countdown" class="font-mono font-bold text-xl text-orange-600">--:--:--</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="font-bold text-gray-800 text-lg">Rincian Pesanan</h2>
                    </div>
                    
                    <div class="p-6 flex flex-col sm:flex-row gap-6">
                        <div class="w-full sm:w-32 h-32 flex-shrink-0 bg-gray-200 rounded-lg overflow-hidden relative group">
                             @if($pembayaran->booking->kamar->gambar)
                                <img src="{{ asset('storage/' . $pembayaran->booking->kamar->gambar) }}" alt="Kamar" class="w-full h-full object-cover transition duration-300 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                                    <i class="fas fa-bed text-3xl"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex-1">
                            <h3 class="font-bold text-xl text-gray-900 mb-1">
                                Kamar No. {{ $pembayaran->booking->kamar->nomor_kamar }}
                            </h3>
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full mb-3">
                                Tipe {{ ucfirst($pembayaran->booking->kamar->tipe_kamar) }}
                            </span>
                            
                            <div class="grid grid-cols-2 gap-4 text-sm mt-2 bg-gray-50 p-3 rounded-lg">
                                <div>
                                    <p class="text-gray-500 text-xs uppercase tracking-wide">Check-in</p>
                                    <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($pembayaran->booking->tanggal_masuk)->translatedFormat('d M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs uppercase tracking-wide">Durasi</p>
                                    <p class="font-semibold text-gray-800">{{ $pembayaran->booking->durasi }} Bulan</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Data Penyewa</h4>
                        <div class="flex flex-col sm:flex-row justify-between text-sm gap-2">
                            <div>
                                <p class="text-gray-500">Nama</p>
                                <p class="font-medium text-gray-900">{{ $pembayaran->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Email</p>
                                <p class="font-medium text-gray-900">{{ $pembayaran->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Telepon</p>
                                <p class="font-medium text-gray-900">{{ $pembayaran->user->phone ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-8">
                    <p class="text-sm text-gray-500 mb-1">Kode Tagihan</p>
                    <div class="flex items-center justify-between mb-6 bg-gray-50 p-3 rounded-lg border border-gray-200 group hover:border-blue-300 transition-colors cursor-pointer" onclick="copyToClipboard()">
                        <span class="font-mono font-bold text-gray-700 select-all" id="kode-bayar">{{ $pembayaran->kode_pembayaran }}</span>
                        <button class="text-gray-400 group-hover:text-blue-600 transition-colors text-sm">
                            <i class="far fa-copy"></i>
                        </button>
                    </div>

                    <div class="border-t border-dashed border-gray-200 my-4"></div>

                    <div class="space-y-2 mb-6">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-gray-900 font-medium">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">Biaya Layanan</span>
                            <span class="text-green-600 font-medium">Gratis</span>
                        </div>
                        <div class="bg-blue-50 p-3 rounded-xl flex justify-between items-center mt-2">
                            <span class="text-blue-700 font-semibold text-sm">Total Bayar</span>
                            <span class="text-blue-700 font-bold text-lg">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <button id="pay-button" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all transform active:scale-[0.98] flex justify-center items-center gap-2">
                            <i class="fas fa-lock"></i> Bayar Sekarang
                        </button>

                        <a href="{{ route('booking.retry-payment', $pembayaran->id) }}" class="w-full py-3.5 bg-white text-gray-700 border border-gray-300 font-semibold rounded-xl hover:bg-gray-50 transition-all flex justify-center items-center gap-2">
                            <i class="fas fa-sync-alt"></i> Refresh Token
                        </a>

                        <form id="cancel-form" action="{{ route('payment.cancel', $pembayaran->id) }}" method="POST">
                            @csrf
                            <button type="button" onclick="confirmCancel()" class="w-full py-3.5 text-red-600 font-semibold rounded-xl hover:bg-red-50 transition-all flex justify-center items-center gap-2 text-sm border border-transparent hover:border-red-100">
                                <i class="fas fa-times-circle"></i> Batalkan Pesanan
                            </button>
                        </form>

                        <a href="{{ route('home') }}" class="block text-center text-xs text-gray-400 hover:text-gray-600 mt-4">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
    // 1. Copy Code dengan SweetAlert Toast
    function copyToClipboard() {
        var copyText = document.getElementById("kode-bayar").innerText;
        navigator.clipboard.writeText(copyText).then(() => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: false,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'success',
                title: 'Kode berhasil disalin!'
            });
        });
    }

    // 2. Konfirmasi Pembatalan dengan SweetAlert
    function confirmCancel() {
        Swal.fire({
            title: 'Batalkan Pesanan?',
            text: "Kamar akan dilepas dan Anda harus memesan ulang jika berubah pikiran.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Kembali',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang membatalkan pesanan',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
                document.getElementById('cancel-form').submit();
            }
        });
    }

    // 3. Logic Countdown
    const expiredTime = new Date("{{ \Carbon\Carbon::parse($pembayaran->tanggal_jatuh_tempo)->format('Y-m-d H:i:s') }}").getTime();

    const timer = setInterval(function() {
        const now = new Date().getTime();
        const distance = expiredTime - now;

        if (distance < 0) {
            clearInterval(timer);
            document.getElementById("countdown").innerHTML = "EXPIRED";
            document.getElementById("countdown").classList.replace('text-orange-600', 'text-red-600');
            
            const btn = document.getElementById("pay-button");
            btn.disabled = true;
            btn.classList.add('bg-gray-400', 'cursor-not-allowed');
            btn.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'shadow-blue-500/30');
            btn.innerHTML = "Waktu Habis";
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

    // 4. 🔥 INTEGRASI MIDTRANS SNAP (UPDATED) 🔥
    const payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function () {
        const snapToken = '{{ $pembayaran->snap_token }}';
        
        if(!snapToken) {
            Swal.fire({
                icon: 'error',
                title: 'Token Kadaluwarsa',
                text: 'Silakan klik tombol "Refresh Token" terlebih dahulu.',
            });
            return;
        }

        snap.pay(snapToken, {
            // SAAT SUKSES BAYAR DI POPUP:
            onSuccess: function(result){
                // Tampilkan loading screen agar user tahu sistem sedang bekerja
                Swal.fire({
                    title: 'Memverifikasi Pembayaran',
                    text: 'Mohon tunggu sebentar, kami sedang mengecek status pembayaran Anda...',
                    icon: 'success',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // REDIRECT KE CONTROLLER 'CHECK STATUS' (JEMPUT BOLA)
                // Ini kuncinya agar localhost bisa update status tanpa ngrok
                window.location.href = "{{ route('booking.check-status', $pembayaran->id) }}";
            },

            // SAAT PENDING (User tutup popup tapi belum bayar, atau pilih ATM)
            onPending: function(result){
                Swal.fire({
                    title: 'Menunggu Pembayaran',
                    text: 'Silakan selesaikan pembayaran Anda sesuai instruksi.',
                    icon: 'info'
                }).then(() => {
                   // Redirect ke check status juga untuk update status jadi pending (jika perlu)
                   // atau reload saja
                   window.location.href = "{{ route('booking.check-status', $pembayaran->id) }}";
                });
            },

            // SAAT ERROR
            onError: function(result){
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Gagal',
                    text: 'Terjadi kesalahan saat memproses pembayaran.',
                }).then(() => {
                    location.reload();
                });
            },

            // SAAT POPUP DITUTUP (CLOSE BUTTON)
            onClose: function(){
                // Cek status ke server, siapa tahu user sudah bayar di tab lain
                // atau hanya sekedar menutup popup
                Swal.fire({
                    title: 'Mengecek Status...',
                    text: 'Sedang memuat ulang status pembayaran',
                    timer: 1000,
                    showConfirmButton: false,
                    didOpen: () => { Swal.showLoading() }
                }).then(() => {
                    window.location.href = "{{ route('booking.check-status', $pembayaran->id) }}";
                });
            }
        });
    });
</script>
@endsection