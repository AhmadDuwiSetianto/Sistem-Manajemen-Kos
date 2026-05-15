@extends('layouts.app')

@section('title', 'Verifikasi Email - Inna Kos')

@section('content')
<div class="min-h-screen flex items-center justify-center pt-32 pb-12 px-4 sm:px-6 relative bg-slate-50/50">
    
    <div class="w-full max-w-md bg-white rounded-[2rem] shadow-2xl shadow-blue-900/5 border border-slate-100 p-8 md:p-10 text-center relative overflow-hidden z-10">
        
        <div class="absolute -top-16 -right-16 w-48 h-48 bg-blue-100/50 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-amber-100/50 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-20 flex flex-col items-center">
            
            <div class="size-20 md:size-24 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-6 md:mb-8 ring-8 ring-blue-50/50">
                <i data-lucide="mail-check" class="size-10 md:size-12"></i>
            </div>

            <h1 class="text-2xl md:text-3xl font-black text-slate-800 mb-3 tracking-tight">Verifikasi Email Anda</h1>
            
            <p class="text-sm text-slate-500 font-medium mb-6 leading-relaxed">
                Terima kasih telah bergabung! Silakan klik tautan verifikasi yang baru saja kami kirimkan ke email Anda untuk melanjutkan pemesanan.
            </p>

            @if (session('message') == 'Verification link sent!')
                <div class="w-full mb-6 flex items-center justify-center gap-1.5 text-emerald-600">
                    <i data-lucide="check-circle-2" class="size-4"></i>
                    <span class="text-[13px] font-bold">Tautan baru berhasil dikirim.</span>
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full py-4 bg-blue-600 text-white text-sm font-bold rounded-2xl hover:bg-blue-700 active:scale-[0.98] transition-all shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2 mb-2 cursor-pointer">
                    Kirim Ulang Tautan <i data-lucide="send" class="size-4"></i>
                </button>
            </form>

            <div class="w-full mt-6 pt-6 border-t border-slate-100 flex flex-col gap-5">
                
                <p class="text-xs text-slate-400 font-medium leading-relaxed">
                    Tidak menerima email? <br class="hidden sm:block"> Periksa folder <strong class="text-slate-500">Spam</strong> atau <strong class="text-slate-500">Junk</strong> Anda.
                </p>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full py-3.5 bg-white border-2 border-slate-100 text-slate-600 text-sm font-bold rounded-2xl hover:bg-slate-50 hover:border-slate-200 hover:text-red-500 active:scale-[0.98] transition-all flex items-center justify-center gap-2 cursor-pointer group">
                        <i data-lucide="log-out" class="size-4 text-slate-400 group-hover:text-red-500 transition-colors"></i> 
                        Keluar dari Akun
                    </button>
                </form>

            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection