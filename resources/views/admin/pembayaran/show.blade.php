@extends('layouts.admin')

@section('title', 'Detail Transaksi #' . $pembayaran->kode_pembayaran)

@push('styles')
<style>
    @media print {
        body * { visibility: hidden; }
        #invoice-area, #invoice-area * { visibility: visible; }
        #invoice-area { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none !important; border: none !important; }
        .no-print { display: none !important; }
    }
</style>
@endpush

@section('content')
<div class="flex-1 p-4 md:p-8 max-w-5xl mx-auto w-full">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4 no-print">
        <a href="{{ route('admin.pembayaran.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-border text-foreground font-semibold text-sm rounded-xl hover:bg-muted transition-colors shadow-sm w-max">
            <i data-lucide="arrow-left" class="size-4 mr-2 text-secondary"></i> Kembali
        </a>
        <div class="flex gap-2 w-full md:w-auto">
            <button onclick="window.print()" class="flex-1 md:flex-none inline-flex items-center justify-center px-4 py-2 bg-primary text-white font-bold text-sm rounded-xl hover:bg-primary-hover transition-colors shadow-sm shadow-primary/30">
                <i data-lucide="printer" class="size-4 mr-2"></i> Cetak Invoice
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-success-light border border-success/20 p-4 mb-6 rounded-xl flex items-center gap-3 no-print">
        <i data-lucide="check-circle" class="size-5 text-success shrink-0"></i>
        <p class="text-success text-sm font-medium">{{ session('success') }}</p>
    </div>
    @endif

    <div id="invoice-area" class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-border overflow-hidden">
        
        <div class="p-6 md:p-10 border-b border-border bg-slate-50/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-foreground tracking-tight mb-1">INVOICE</h1>
                <p class="text-sm font-mono font-bold text-secondary">{{ $pembayaran->kode_pembayaran }}</p>
                <p class="text-xs text-secondary mt-1">{{ $pembayaran->created_at->format('d F Y, H:i') }} WIB</p>
            </div>
            
            <div class="text-left md:text-right w-full md:w-auto">
                @php
                    $statusMap = [
                        'pending' => ['bg' => 'bg-warning-light', 'text' => 'text-warning-dark', 'label' => 'Menunggu Pembayaran'],
                        'paid' => ['bg' => 'bg-success-light', 'text' => 'text-success', 'label' => 'Lunas'],
                        'expired' => ['bg' => 'bg-error-light', 'text' => 'text-error', 'label' => 'Kedaluwarsa'],
                        'cancelled' => ['bg' => 'bg-error-light', 'text' => 'text-error', 'label' => 'Dibatalkan']
                    ];
                    $currStatus = $statusMap[$pembayaran->status] ?? ['bg' => 'bg-muted', 'text' => 'text-secondary', 'label' => ucfirst($pembayaran->status)];
                @endphp
                <p class="text-xs font-bold text-secondary uppercase tracking-widest mb-1.5">Status Transaksi</p>
                <span class="inline-flex px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider border {{ $currStatus['bg'] }} {{ $currStatus['text'] }} border-current">
                    {{ $currStatus['label'] }}
                </span>
            </div>
        </div>

        <div class="p-6 md:p-10 grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 border-b border-border">
            
            <div>
                <p class="text-[10px] md:text-xs font-bold text-secondary uppercase tracking-widest mb-3 flex items-center gap-2">
                    <i data-lucide="user" class="size-4"></i> Ditagihkan Kepada:
                </p>
                <h3 class="text-base md:text-lg font-bold text-foreground mb-1">{{ $pembayaran->user->name ?? 'User Tidak Ditemukan' }}</h3>
                <p class="text-sm text-secondary mb-0.5">{{ $pembayaran->user->email ?? '-' }}</p>
                <p class="text-sm text-secondary mb-0.5">{{ $pembayaran->user->phone ?? '-' }}</p>
                <p class="text-sm text-secondary">{{ $pembayaran->user->address ?? '-' }}</p>
            </div>

            <div>
                <p class="text-[10px] md:text-xs font-bold text-secondary uppercase tracking-widest mb-3 flex items-center gap-2">
                    <i data-lucide="home" class="size-4"></i> Detail Sewa Kamar:
                </p>
                <div class="bg-muted/30 rounded-xl p-4 border border-border">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-secondary">Nomor Kamar</span>
                        <span class="text-sm font-bold text-foreground">Kamar {{ $pembayaran->booking->kamar->nomor_kamar ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-secondary">Tipe Kamar</span>
                        <span class="text-sm font-semibold text-foreground">{{ ucfirst($pembayaran->booking->kamar->tipe_kamar ?? '-') }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-secondary">Periode Sewa</span>
                        <span class="text-sm font-semibold text-foreground">{{ $pembayaran->booking->durasi ?? 0 }} Bulan</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-secondary">Tanggal Masuk</span>
                        <span class="text-sm font-semibold text-foreground">{{ \Carbon\Carbon::parse($pembayaran->booking->tanggal_masuk)->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 md:p-10">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-border">
                        <th class="py-3 text-xs md:text-sm font-bold text-foreground">Deskripsi Layanan</th>
                        <th class="py-3 text-right text-xs md:text-sm font-bold text-foreground">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr class="border-b border-border/50">
                        <td class="py-4">
                            <p class="font-bold text-foreground">Sewa Kamar {{ $pembayaran->booking->kamar->nomor_kamar ?? '-' }} ({{ $pembayaran->booking->durasi ?? 0 }} Bulan)</p>
                            <p class="text-xs text-secondary mt-1">Biaya per bulan: Rp {{ number_format($pembayaran->booking->kamar->harga ?? 0, 0, ',', '.') }}</p>
                        </td>
                        <td class="py-4 text-right font-bold text-foreground">
                            Rp {{ number_format(($pembayaran->booking->kamar->harga ?? 0) * ($pembayaran->booking->durasi ?? 0), 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="border-b border-border/50">
                        <td class="py-4">
                            <p class="font-bold text-foreground">Biaya Layanan / Admin Aplikasi</p>
                        </td>
                        <td class="py-4 text-right font-bold text-success uppercase text-xs">
                            Gratis
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="flex justify-end mt-6">
                <div class="w-full md:w-1/2 lg:w-1/3 space-y-3">
                    <div class="flex justify-between items-center border-t-2 border-foreground pt-3">
                        <span class="font-bold text-foreground">TOTAL TAGIHAN</span>
                        <span class="text-xl md:text-2xl font-black text-primary">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($pembayaran->status == 'paid')
                    <div class="flex justify-between items-center text-sm">
                        <span class="font-medium text-secondary">Metode Pembayaran</span>
                        <span class="font-bold text-foreground">{{ strtoupper(str_replace('_', ' ', $pembayaran->metode_pembayaran ?? $pembayaran->metode ?? 'Online')) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="font-medium text-secondary">Waktu Lunas</span>
                        <span class="font-bold text-foreground">{{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y, H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    @if($pembayaran->status == 'pending')
    <div class="mt-6 md:mt-8 p-6 bg-warning-light/30 border border-warning/30 rounded-2xl md:rounded-3xl shadow-sm no-print">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-base md:text-lg font-bold text-warning-dark mb-1 flex items-center gap-2">
                    <i data-lucide="settings-2" class="size-5"></i> Tindakan Manual Admin
                </h3>
                <p class="text-xs md:text-sm text-secondary">Pilih aksi di bawah jika pengguna membayar tunai (Cash) secara langsung kepada Anda, atau jika Anda ingin membatalkan transaksi ini.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 md:gap-3 shrink-0">
                <form action="{{ route('admin.pembayaran.reject', $pembayaran->id) }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Yakin ingin membatalkan transaksi ini? Kamar akan dikosongkan kembali.');">
                    @csrf
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-error-light text-error font-bold text-sm rounded-xl hover:bg-error hover:text-white transition-colors cursor-pointer">
                        <i data-lucide="x" class="size-4 mr-1.5"></i> Tolak / Batalkan
                    </button>
                </form>
                
                <form action="{{ route('admin.pembayaran.verify', $pembayaran->id) }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Konfirmasi bahwa uang telah Anda terima? Status akan menjadi LUNAS.');">
                    @csrf
                    <button type="submit" class="w-full inline-flex justify-center items-center px-5 py-2.5 bg-success text-white font-bold text-sm rounded-xl hover:bg-green-600 transition-colors shadow-sm shadow-success/30 cursor-pointer">
                        <i data-lucide="check-check" class="size-4 mr-1.5"></i> Tandai Lunas
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection