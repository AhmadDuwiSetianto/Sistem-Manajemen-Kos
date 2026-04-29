@extends('layouts.app')

@section('title', 'Bukti Pembayaran #' . $pembayaran->kode_pembayaran)

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<style>
    @media print {
        body * { visibility: hidden; }
        #printable-area, #printable-area * { visibility: visible; }
        #printable-area { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none !important; border: none !important; }
        .no-print { display: none !important; }
    }
    .receipt-edge {
        background-image: radial-gradient(#F8FAFC 4px, transparent 4px);
        background-size: 16px 16px;
        background-position: -8px -8px;
        height: 8px;
        width: 100%;
        position: relative;
        z-index: 10;
        margin-top: -4px;
    }
</style>

<div class="max-w-2xl mx-auto px-4 pt-28 pb-10">
    
    <div class="flex items-center justify-between mb-6 no-print">
        <a href="{{ route('user.dashboard') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
            <i data-lucide="arrow-left" class="size-5"></i>
        </a>
        <div class="flex gap-2">
            <button onclick="window.print()" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                <i data-lucide="printer" class="size-4"></i>
            </button>
            <button onclick="downloadPDF()" id="btn-download" class="px-4 h-10 bg-brand-600 text-white text-xs font-bold rounded-full flex items-center gap-2 hover:bg-brand-700 transition-colors shadow-md shadow-brand-600/20">
                <i data-lucide="download" class="size-4"></i> Unduh
            </button>
        </div>
    </div>

    <div id="printable-area" class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden">
        
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-[0.05] pointer-events-none -rotate-12 z-0">
            <span class="text-8xl font-black text-green-600 border-8 border-green-600 px-8 py-2 rounded-3xl tracking-widest">LUNAS</span>
        </div>

        <div class="bg-slate-800 text-white p-6 relative z-10 text-center">
            <div class="w-16 h-16 bg-brand-600 rounded-2xl flex items-center justify-center shadow-inner mx-auto mb-4 border-2 border-slate-700">
                <i data-lucide="check" class="size-8 text-white"></i>
            </div>
            <h1 class="text-xl font-black tracking-widest mb-1">PEMBAYARAN BERHASIL</h1>
            <p class="text-brand-300 font-mono text-xs">INV-{{ $pembayaran->kode_pembayaran }}</p>
        </div>

        <div class="receipt-edge no-print"></div>

        <div class="p-6 md:p-8 relative z-10">
            
            <div class="text-center mb-8">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Pembayaran</p>
                <h2 class="text-4xl font-black text-brand-600 tracking-tight">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</h2>
            </div>

            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 space-y-4 mb-8">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500 font-medium">Tanggal Bayar</span>
                    <span class="text-slate-800 font-bold">{{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->translatedFormat('d M Y, H:i') }}</span>
                </div>
                <div class="border-t border-dashed border-slate-200"></div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500 font-medium">Metode</span>
                    <span class="text-slate-800 font-bold text-right max-w-[60%]">
                        @php
                            $method = strtolower($pembayaran->metode_pembayaran ?? $pembayaran->metode ?? 'online');
                            if (str_contains($method, 'transfer')) echo 'Transfer / VA';
                            elseif (str_contains($method, 'gopay') || str_contains($method, 'qris')) echo 'QRIS / E-Wallet';
                            else echo ucwords(str_replace('_', ' ', $method));
                        @endphp
                    </span>
                </div>
                <div class="border-t border-dashed border-slate-200"></div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500 font-medium">Penyewa</span>
                    <span class="text-slate-800 font-bold">{{ $pembayaran->user->name }}</span>
                </div>
            </div>

            <div class="mb-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Detail Layanan</p>
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="font-bold text-slate-800 text-sm">Sewa Kamar {{ $pembayaran->booking->kamar->nomor_kamar }}</p>
                        <p class="text-xs text-slate-500 mt-1">Durasi: {{ $pembayaran->booking->durasi }} Bulan</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-slate-800 text-sm">Rp {{ number_format($pembayaran->booking->kamar->harga * $pembayaran->booking->durasi, 0, ',', '.') }}</p>
                        <p class="text-[10px] text-slate-400 mt-1">Rp {{ number_format($pembayaran->booking->kamar->harga, 0, ',', '.') }}/bln</p>
                    </div>
                </div>
            </div>

            <div class="border-t-2 border-dashed border-slate-200 my-6"></div>

            <div class="text-center space-y-2">
                <p class="text-[10px] text-slate-500 font-medium">
                    Dokumen ini sah diterbitkan oleh sistem <strong>Inna Kos</strong>.
                </p>
                <div class="flex items-center justify-center gap-2 text-brand-600 font-bold text-sm">
                    <i data-lucide="shield-check" class="size-4"></i> Transaksi Aman
                </div>
            </div>

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
        
        btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Loading';
        btn.disabled = true;
        lucide.createIcons();

        var opt = {
            margin: [5, 5, 5, 5],
            filename: 'Kuitansi-InnaKos-{{ $pembayaran->kode_pembayaran }}.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true, backgroundColor: '#ffffff' }, 
            jsPDF: { unit: 'mm', format: 'a5', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).save().then(function(){
            btn.innerHTML = originalHTML;
            btn.disabled = false;
            lucide.createIcons();
        });
    }
</script>
@endsection