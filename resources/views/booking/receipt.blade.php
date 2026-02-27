@extends('layouts.user')

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
</style>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4 no-print bg-white p-4 rounded-2xl shadow-sm border border-border">
        <a href="{{ route('user.dashboard') }}" class="inline-flex items-center text-sm font-semibold text-secondary hover:text-primary transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Kembali ke Dashboard
        </a>

        <div class="flex gap-3 w-full sm:w-auto">
            <button onclick="downloadPDF()" id="btn-download" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-hover transition-colors shadow-sm cursor-pointer">
                <i data-lucide="download" class="w-4 h-4"></i> Download PDF
            </button>
            <button onclick="window.print()" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-border text-foreground text-sm font-bold rounded-xl hover:bg-muted transition-colors shadow-sm cursor-pointer">
                <i data-lucide="printer" class="w-4 h-4 text-secondary"></i> Print
            </button>
        </div>
    </div>

    <div id="printable-area" class="bg-white rounded-3xl shadow-xl overflow-hidden border border-border relative">
        
        <div class="absolute top-24 right-8 md:right-16 opacity-[0.08] pointer-events-none transform -rotate-12 z-0">
            <span class="text-7xl md:text-8xl font-black text-success border-8 border-success px-8 py-2 rounded-2xl tracking-widest">LUNAS</span>
        </div>

        <div class="bg-foreground text-white p-8 md:p-10 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-start gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center shadow-sm">
                            <i data-lucide="home" class="w-6 h-6 text-white"></i>
                        </div>
                        <span class="text-3xl font-bold tracking-tight">MyKos</span>
                    </div>
                    <div class="text-white/70 text-sm space-y-1">
                        <p>Jl. Contoh No. 123, Pekalongan</p>
                        <p>Jawa Tengah, Indonesia</p>
                        <p class="flex items-center gap-2 mt-2 text-white/90">
                            <i data-lucide="mail" class="w-3.5 h-3.5"></i> support@mykos.com
                        </p>
                    </div>
                </div>
                
                <div class="md:text-right w-full md:w-auto border-t border-white/20 md:border-t-0 pt-6 md:pt-0">
                    <h1 class="text-4xl font-black tracking-wider mb-2 text-white">INVOICE</h1>
                    <p class="text-primary-hover font-mono text-lg bg-white/10 inline-block px-3 py-1 rounded-lg">#{{ $pembayaran->kode_pembayaran }}</p>
                    <div class="mt-5 inline-flex items-center gap-2 bg-success/20 text-success-light text-xs font-bold px-4 py-2 rounded-full uppercase tracking-widest border border-success/30">
                        <i data-lucide="check-circle-2" class="w-4 h-4"></i> STATUS: LUNAS
                    </div>
                </div>
            </div>
        </div>

        <div class="p-8 md:p-10 bg-white relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10 border-b border-border pb-10">
                <div>
                    <h3 class="text-[11px] font-bold text-secondary uppercase tracking-widest mb-3">Ditagihkan Kepada</h3>
                    <p class="text-lg font-bold text-foreground">{{ $pembayaran->user->name }}</p>
                    <p class="text-secondary text-sm mt-1">{{ $pembayaran->user->email }}</p>
                    <p class="text-secondary text-sm mt-1">{{ $pembayaran->user->no_telepon ?? $pembayaran->user->phone }}</p>
                </div>
                
                <div class="md:text-right">
                    <h3 class="text-[11px] font-bold text-secondary uppercase tracking-widest mb-3">Rincian Pembayaran</h3>
                    <div class="space-y-2">
                        <p class="text-sm">
                            <span class="text-secondary mr-2">Tanggal Bayar:</span> 
                            <span class="font-semibold text-foreground">{{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->translatedFormat('d F Y, H:i') }} WIB</span>
                        </p>
                        <p class="text-sm">
                            <span class="text-secondary mr-2">Metode:</span> 
                            <span class="font-bold text-primary">
                                @php
                                    $method = strtolower($pembayaran->metode_pembayaran ?? $pembayaran->metode ?? 'online');
                                    if (str_contains($method, 'bank_transfer') || str_contains($method, 'transfer')) echo 'Transfer Bank / VA';
                                    elseif (str_contains($method, 'bca')) echo 'BCA Virtual Account';
                                    elseif (str_contains($method, 'bni')) echo 'BNI Virtual Account';
                                    elseif (str_contains($method, 'bri')) echo 'BRI Virtual Account';
                                    elseif (str_contains($method, 'mandiri')) echo 'Mandiri Bill';
                                    elseif (str_contains($method, 'gopay') || str_contains($method, 'qris')) echo 'QRIS / E-Wallet';
                                    elseif (str_contains($method, 'shopeepay')) echo 'ShopeePay';
                                    else echo ucwords(str_replace('_', ' ', $method));
                                @endphp
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-10 overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr>
                            <th class="pb-4 text-[11px] font-bold text-secondary uppercase tracking-widest border-b border-border w-1/2">Deskripsi Layanan</th>
                            <th class="pb-4 text-[11px] font-bold text-secondary uppercase tracking-widest border-b border-border text-center">Durasi</th>
                            <th class="pb-4 text-[11px] font-bold text-secondary uppercase tracking-widest border-b border-border text-right">Harga / Bulan</th>
                            <th class="pb-4 text-[11px] font-bold text-secondary uppercase tracking-widest border-b border-border text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <tr>
                            <td class="py-5 border-b border-muted">
                                <p class="font-bold text-foreground text-base">Sewa Kamar {{ $pembayaran->booking->kamar->nomor_kamar }}</p>
                                <p class="text-xs font-semibold text-primary mt-1">Tipe {{ ucfirst($pembayaran->booking->kamar->tipe_kamar) }}</p>
                                <p class="text-xs text-secondary mt-1 flex items-center gap-1.5">
                                    <i data-lucide="calendar-check" class="w-3 h-3"></i> Check-in: {{ \Carbon\Carbon::parse($pembayaran->booking->tanggal_masuk)->translatedFormat('d M Y') }}
                                </p>
                            </td>
                            <td class="py-5 border-b border-muted text-center font-medium text-foreground">
                                {{ $pembayaran->booking->durasi }} Bulan
                            </td>
                            <td class="py-5 border-b border-muted text-right text-secondary">
                                Rp {{ number_format($pembayaran->booking->kamar->harga, 0, ',', '.') }}
                            </td>
                            <td class="py-5 border-b border-muted text-right font-bold text-foreground text-base">
                                Rp {{ number_format($pembayaran->booking->kamar->harga * $pembayaran->booking->durasi, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end mb-12">
                <div class="w-full sm:w-1/2 lg:w-2/5">
                    <div class="flex justify-between mb-3 text-sm">
                        <span class="text-secondary font-medium">Subtotal</span>
                        <span class="text-foreground font-semibold">Rp {{ number_format($pembayaran->booking->kamar->harga * $pembayaran->booking->durasi, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between mb-3 text-sm">
                        <span class="text-secondary font-medium">Biaya Admin / Gateway</span>
                        <span class="text-foreground font-semibold">Rp {{ number_format($pembayaran->jumlah - ($pembayaran->booking->kamar->harga * $pembayaran->booking->durasi), 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t-2 border-border mt-4 pt-4 flex justify-between items-center">
                        <span class="font-bold text-foreground text-sm uppercase tracking-widest">Total Dibayar</span>
                        <span class="font-black text-primary text-2xl">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-primary/5 rounded-2xl p-6 text-center border border-primary/10 flex flex-col items-center justify-center">
                <i data-lucide="heart-handshake" class="w-6 h-6 text-primary mb-3"></i>
                <p class="text-primary font-bold mb-1">Terima kasih atas pembayaran Anda!</p>
                <p class="text-xs text-secondary font-medium">Simpan dokumen ini sebagai bukti pembayaran yang sah dan valid.</p>
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
        
        var opt = {
            margin:       [10, 10, 10, 10], 
            filename:     'Invoice-{{ $pembayaran->kode_pembayaran }}.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true }, 
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        var btn = document.getElementById('btn-download');
        var originalText = btn.innerHTML;
        
        // Animasi loading
        btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Memproses...';
        btn.disabled = true;
        lucide.createIcons();

        // Download dieksekusi persis seperti aslimu
        html2pdf().set(opt).from(element).save().then(function(){
            btn.innerHTML = originalText;
            btn.disabled = false;
            lucide.createIcons();
        });
    }
</script>
@endsection