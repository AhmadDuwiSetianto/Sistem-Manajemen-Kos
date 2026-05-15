@extends('layouts.app')

@section('title', 'Struk Pembayaran #' . $pembayaran->kode_pembayaran)

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<style>
    @media print {
        body * { visibility: hidden; }
        #printable-area, #printable-area * { visibility: visible; }
        #printable-area { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none !important; border: none !important; background: white !important; padding: 15px !important; }
        .no-print { display: none !important; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    }
</style>

<div class="min-h-screen bg-slate-50 pt-24 md:pt-32 pb-16 px-4 flex flex-col items-center">
    
    <div class="w-full max-w-[340px] flex items-center justify-between mb-4 md:mb-6 no-print">
        <a href="{{ route('user.dashboard') }}" class="size-9 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
            <i data-lucide="arrow-left" class="size-4"></i>
        </a>
        <div class="flex gap-2">
            <button onclick="window.print()" class="size-9 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors" title="Cetak Struk">
                <i data-lucide="printer" class="size-3.5"></i>
            </button>
            <button onclick="downloadPDF()" id="btn-download" class="px-4 h-9 bg-slate-800 text-white text-[11px] font-bold rounded-full flex items-center gap-1.5 hover:bg-slate-900 transition-colors shadow-sm">
                <i data-lucide="download" class="size-3.5"></i> Unduh PDF
            </button>
        </div>
    </div>

    <div id="printable-area" class="w-full max-w-[340px] bg-white rounded-2xl shadow-xl shadow-slate-200/60 p-5 relative">
        
        <div class="flex flex-col items-center mb-5">
            <img src="{{ asset('images/innakos.png') }}" alt="Logo Inna Kos" class="h-7 md:h-8 object-contain">
            <span class="text-sm md:text-base font-black tracking-tight text-slate-800 mt-1">
                Inna<span class="text-blue-600">Kos</span>
            </span>
        </div>

        <div class="text-center mb-5">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Pembayaran</p>
            <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight mb-2">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</h2>
            <span class="inline-flex px-2.5 py-1 bg-green-50 text-green-600 text-[9px] font-bold uppercase tracking-widest rounded-full">
                Pembayaran Berhasil
            </span>
        </div>

        <div class="space-y-1.5 mb-4 border-b border-dashed border-slate-100 pb-4">
            <div class="flex justify-between items-center text-[11px] md:text-xs">
                <span class="text-slate-500">No. Invoice</span>
                <span class="font-bold text-slate-800">{{ $pembayaran->kode_pembayaran }}</span>
            </div>
            <div class="flex justify-between items-center text-[11px] md:text-xs">
                <span class="text-slate-500">Waktu Bayar</span>
                <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->translatedFormat('d M Y H:i') }}</span>
            </div>
            <div class="flex justify-between items-start text-[11px] md:text-xs">
                <span class="text-slate-500">Pelanggan</span>
                <span class="font-bold text-slate-800 text-right max-w-[60%]">{{ $pembayaran->user->name }}</span>
            </div>
            <div class="flex justify-between items-center text-[11px] md:text-xs">
                <span class="text-slate-500">Metode Bayar</span>
                <span class="font-bold text-slate-800 uppercase">
                    @php
                        $method = strtolower($pembayaran->metode_pembayaran ?? $pembayaran->metode ?? 'online');
                        if (str_contains($method, 'transfer')) echo 'Transfer / VA';
                        elseif (str_contains($method, 'gopay') || str_contains($method, 'qris')) echo 'QRIS';
                        else echo str_replace('_', ' ', $method);
                    @endphp
                </span>
            </div>
        </div>

        @if(!empty($pembayaran->booking->catatan))
        <div class="bg-slate-50 p-3 rounded-xl mb-4">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Catatan</p>
            <p class="text-[11px] font-bold text-slate-800 leading-snug">{{ $pembayaran->booking->catatan }}</p>
        </div>
        @endif

        <div class="mb-4">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2 border-b border-dashed border-slate-200 pb-1.5">Rincian Item (1 Produk)</p>
            
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="text-[11px] font-bold text-slate-800">Kamar {{ $pembayaran->booking->kamar->nomor_kamar }} ({{ ucfirst($pembayaran->booking->kamar->tipe_kamar) }})</p>
                    <p class="text-[10px] text-slate-500 mt-0.5">{{ $pembayaran->booking->durasi }} bln x Rp {{ number_format($pembayaran->booking->kamar->harga, 0, ',', '.') }}</p>
                </div>
                <span class="text-[11px] font-bold text-slate-800 shrink-0">Rp {{ number_format($pembayaran->booking->kamar->harga * $pembayaran->booking->durasi, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="border-t border-dashed border-slate-200 pt-3 space-y-1.5 mb-5">
            <div class="flex justify-between items-center text-[11px]">
                <span class="text-slate-500">Subtotal</span>
                <span class="font-bold text-slate-800">Rp {{ number_format($pembayaran->booking->kamar->harga * $pembayaran->booking->durasi, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center text-[11px]">
                <span class="text-slate-500">Layanan Sistem</span>
                <span class="font-bold text-slate-800">Rp 0</span>
            </div>
            <div class="flex justify-between items-center pt-1.5">
                <span class="text-xs font-bold text-slate-800">Total</span>
                <span class="text-sm font-black text-slate-900">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="text-center pt-4 border-t border-slate-100">
            <div class="inline-flex items-center justify-center gap-1 text-slate-400 mb-1">
                <i data-lucide="shield-check" class="size-3"></i>
                <span class="text-[9px] font-bold uppercase tracking-widest tracking-tighter">Inna Kos Management</span>
            </div>
            <p class="text-[9px] text-slate-400 leading-relaxed">
                Terima kasih atas kepercayaan Anda.
            </p>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });

    function downloadPDF() {
        var element = document.getElementById('printable-area');
        var btn = document.getElementById('btn-download');
        var originalHTML = btn.innerHTML;
        
        btn.innerHTML = '<i data-lucide="loader-2" class="size-3.5 animate-spin"></i>...';
        btn.disabled = true;
        lucide.createIcons();

        var elementHeightPx = element.offsetHeight;
        var elementHeightMm = (elementHeightPx * 0.264583) + 10; 

        var opt = {
            margin: [0, 0, 0, 0],
            filename: 'Struk-InnaKos-{{ $pembayaran->kode_pembayaran }}.pdf',
            image: { type: 'jpeg', quality: 1 },
            html2canvas: { scale: 2, useCORS: true, backgroundColor: '#ffffff' }, 
            jsPDF: { unit: 'mm', format: [80, elementHeightMm], orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).save().then(function(){
            btn.innerHTML = originalHTML;
            btn.disabled = false;
            lucide.createIcons();
        });
    }
</script>
@endsection