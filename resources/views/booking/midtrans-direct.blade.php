@extends('layouts.app')

@section('title', 'Memproses Pembayaran...')

@section('content')
<div class="pt-24 md:pt-28 pb-10 flex flex-col items-center justify-center min-h-[70vh] md:min-h-[80vh] relative overflow-hidden">
    <!-- Dekorasi Blur (Background) -->
    <div class="absolute -top-10 -right-10 w-48 md:w-64 h-48 md:h-64 bg-brand-500 rounded-full blur-[80px] md:blur-[100px] opacity-20 pointer-events-none"></div>

    <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-xl md:shadow-2xl shadow-slate-200/50 text-center max-w-[280px] md:max-w-[320px] w-full mx-4 border border-slate-100 relative z-10">
        
        <div class="relative w-16 md:w-20 h-16 md:h-20 mx-auto mb-5 md:mb-6">
            <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-brand-600 rounded-full border-t-transparent animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i data-lucide="shield-check" class="size-6 md:size-8 text-brand-600 animate-pulse"></i>
            </div>
        </div>
        
        <h2 class="text-lg md:text-xl font-black text-slate-800 tracking-tight mb-1.5 md:mb-2">Menyiapkan Sistem</h2>
        <p class="text-slate-500 text-[11px] md:text-sm font-medium mb-6 md:mb-8 leading-relaxed">Membuka gerbang pembayaran aman untuk Anda...</p>
        
        <button onclick="pay()" class="w-full py-3 bg-brand-50 text-brand-600 font-bold text-xs md:text-sm rounded-xl hover:bg-brand-100 transition-colors">
            Klik jika popup tidak muncul
        </button>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script type="text/javascript" src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });

    function pay() {
        var snapToken = '{{ $pembayaran->snap_token }}';
        
        if (!snapToken) {
            alert('Token pembayaran belum siap. Halaman akan dimuat ulang.');
            window.location.href = "{{ route('booking.payment', $pembayaran->id) }}";
            return;
        }

        snap.pay(snapToken, {
            onSuccess: function(result) {
                window.location.href = "{{ route('booking.receipt', $pembayaran->id) }}";
            },
            onPending: function(result) {
                window.location.href = "{{ route('booking.payment', $pembayaran->id) }}";
            },
            onError: function(result) {
                window.location.href = "{{ route('booking.payment', $pembayaran->id) }}?error=true";
            },
            onClose: function() {
                window.location.href = "{{ route('booking.payment', $pembayaran->id) }}";
            }
        });
    }
    
    // Auto trigger
    setTimeout(pay, 800);
</script>
@endsection