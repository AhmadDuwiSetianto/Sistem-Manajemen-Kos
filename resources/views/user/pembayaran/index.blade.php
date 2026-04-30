@extends('layouts.user')

@section('title', 'Riwayat Tagihan')

@section('content')
<div class="w-full max-w-6xl mx-auto pb-10">
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-foreground tracking-tight">Riwayat Tagihan</h1>
        <p class="text-secondary mt-1 font-medium">Pantau semua status pembayaran sewa kamar Anda di sini.</p>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[10px] uppercase tracking-widest text-secondary font-bold">
                        <th class="p-5 border-b border-border">No. Tagihan</th>
                        <th class="p-5 border-b border-border">Kamar / Layanan</th>
                        <th class="p-5 border-b border-border">Tanggal Bayar</th>
                        <th class="p-5 border-b border-border">Total Dibayar</th>
                        <th class="p-5 border-b border-border text-center">Status</th>
                        <th class="p-5 border-b border-border text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($payments as $pay)
                        <tr class="hover:bg-muted/50 transition-colors">
                            <td class="p-5 border-b border-border">
                                <span class="font-mono text-xs font-bold text-primary bg-primary/10 px-2 py-1 rounded">
                                    {{ $pay->kode_pembayaran }}
                                </span>
                            </td>
                            <td class="p-5 border-b border-border">
                                <p class="font-bold text-foreground">Kamar {{ $pay->booking->kamar->nomor_kamar ?? '-' }}</p>
                                <p class="text-xs text-secondary mt-0.5">Sewa {{ $pay->booking->durasi ?? 0 }} Bulan</p>
                            </td>
                            <td class="p-5 border-b border-border text-secondary">
                                {{ $pay->tanggal_bayar ? \Carbon\Carbon::parse($pay->tanggal_bayar)->translatedFormat('d M Y, H:i') : '-' }}
                            </td>
                            <td class="p-5 border-b border-border font-black text-foreground">
                                Rp {{ number_format($pay->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="p-5 border-b border-border text-center">
                                @if($pay->status === 'paid')
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-success-light text-success border border-success/30">Lunas</span>
                                @elseif($pay->status === 'pending')
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-warning-light text-orange-600 border border-warning/30">Pending</span>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-error-light text-error border border-error/30">Gagal</span>
                                @endif
                            </td>
                            <td class="p-5 border-b border-border text-center">
                                @if($pay->status === 'paid')
                                    <a href="{{ route('booking.receipt', $pay->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-border text-secondary hover:text-primary hover:border-primary/50 text-xs font-bold rounded-lg transition-colors">
                                        <i data-lucide="receipt" class="size-3.5"></i> Struk
                                    </a>
                                @elseif($pay->status === 'pending')
                                    <!-- CLASS WARNA TOMBOL SUDAH DIPERBAIKI DI BAWAH INI -->
                                    <a href="{{ route('booking.payment', $pay->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-orange-500 text-white hover:bg-orange-600 text-xs font-bold rounded-lg transition-colors shadow-sm shadow-orange-500/30">
                                        <i data-lucide="credit-card" class="size-3.5"></i> Bayar
                                    </a>
                                @else
                                    <span class="text-xs text-secondary italic">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center text-secondary">
                                <i data-lucide="receipt" class="size-10 mx-auto opacity-50 mb-3"></i>
                                <p class="font-bold">Belum ada riwayat tagihan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-6">
        {{ $payments->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection