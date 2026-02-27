@extends('layouts.admin')

@section('title', 'Data Pembayaran')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-foreground">Transaksi Pembayaran</h1>
        <p class="text-secondary mt-1">Verifikasi bukti transfer dan kelola tagihan</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.laporan.keuangan') }}" class="inline-flex items-center px-4 py-2.5 bg-white border border-border text-foreground font-semibold rounded-xl hover:bg-muted transition-colors shadow-sm">
            <i data-lucide="printer" class="size-4 mr-2 text-secondary"></i> Cetak Laporan
        </a>
    </div>
</div>

@if(session('success'))
<div class="bg-success-light border border-success/20 p-4 mb-6 rounded-2xl flex items-center gap-3">
    <div class="size-8 bg-success/20 rounded-full flex items-center justify-center shrink-0">
        <i data-lucide="check-circle" class="size-5 text-success"></i>
    </div>
    <p class="text-success font-medium">{{ session('success') }}</p>
</div>
@endif

@if(session('error'))
<div class="bg-error-light border border-error/20 p-4 mb-6 rounded-2xl flex items-center gap-3">
    <div class="size-8 bg-error/20 rounded-full flex items-center justify-center shrink-0">
        <i data-lucide="alert-circle" class="size-5 text-error"></i>
    </div>
    <p class="text-error font-medium">{{ session('error') }}</p>
</div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="flex flex-col rounded-2xl border border-border p-5 bg-white shadow-sm">
        <div class="flex items-center gap-3 mb-2">
            <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center">
                <i data-lucide="wallet" class="size-5 text-primary"></i>
            </div>
            <p class="font-medium text-secondary">Total Pemasukan</p>
        </div>
        <p class="font-bold text-2xl text-foreground">Rp {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}</p>
    </div>
    <div class="flex flex-col rounded-2xl border border-border p-5 bg-white shadow-sm relative overflow-hidden">
        <div class="flex items-center gap-3 mb-2">
            <div class="size-10 bg-warning-light rounded-xl flex items-center justify-center">
                <i data-lucide="hourglass" class="size-5 text-warning-dark"></i>
            </div>
            <p class="font-medium text-secondary">Menunggu Cek</p>
        </div>
        <p class="font-bold text-3xl text-warning-dark">{{ $pendingPembayaran ?? 0 }} <span class="text-sm font-medium text-secondary">trx</span></p>
        @if(($pendingPembayaran ?? 0) > 0)
        <div class="absolute top-0 right-0 w-2 h-full bg-warning"></div>
        @endif
    </div>
    <div class="flex flex-col rounded-2xl border border-border p-5 bg-white shadow-sm">
        <div class="flex items-center gap-3 mb-2">
            <div class="size-10 bg-success-light rounded-xl flex items-center justify-center">
                <i data-lucide="check-circle" class="size-5 text-success"></i>
            </div>
            <p class="font-medium text-secondary">Lunas</p>
        </div>
        <p class="font-bold text-3xl text-success">{{ $suksesPembayaran ?? 0 }}</p>
    </div>
    <div class="flex flex-col rounded-2xl border border-border p-5 bg-white shadow-sm">
        <div class="flex items-center gap-3 mb-2">
            <div class="size-10 bg-error-light rounded-xl flex items-center justify-center">
                <i data-lucide="alert-circle" class="size-5 text-error"></i>
            </div>
            <p class="font-medium text-secondary">Ditolak / Gagal</p>
        </div>
        <p class="font-bold text-3xl text-error">{{ $gagalPembayaran ?? 0 }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden">
    <div class="px-6 py-5 border-b border-border flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h2 class="text-lg font-bold text-foreground">Riwayat Transaksi</h2>
        <form method="GET" action="{{ route('admin.pembayaran.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative w-full sm:w-48">
                <i data-lucide="filter" class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                <select name="status" onchange="this.form.submit()" class="w-full pl-10 pr-8 py-2.5 bg-muted border-none rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm appearance-none text-foreground cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Lunas</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <i data-lucide="chevron-down" class="absolute right-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
            </div>
            <div class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau nomor kamar..." class="w-full pl-10 pr-4 py-2.5 bg-muted border-none rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm placeholder:text-secondary text-foreground">
                <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                <button type="submit" class="hidden"></button>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-border">
            <thead class="bg-muted/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">ID Transaksi</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Booking Ref</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Metode Bayar</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-secondary uppercase tracking-wider">Total (Rp)</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-secondary uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-secondary uppercase tracking-wider">Tindakan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-border">
                @forelse($pembayarans ?? [] as $payment)
                <tr class="hover:bg-muted/30 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-bold text-foreground">#TRX-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-[11px] text-secondary mt-0.5">{{ $payment->created_at->format('d M Y, H:i') }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-semibold text-primary">#BK-{{ str_pad($payment->booking_id, 5, '0', STR_PAD_LEFT) }}</p>
                        <div class="flex items-center gap-1 mt-0.5">
                            <i data-lucide="user" class="size-3 text-secondary"></i>
                            <p class="text-[11px] text-secondary">{{ $payment->user->name ?? 'User' }}</p>
                        </div>
                        <div class="flex items-center gap-1 mt-0.5">
                            <i data-lucide="door-open" class="size-3 text-secondary"></i>
                            <p class="text-[11px] text-secondary">Kamar {{ $payment->booking->kamar->nomor_kamar ?? '-' }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <div class="size-6 bg-muted rounded flex items-center justify-center">
                                @if($payment->metode == 'transfer') <i data-lucide="building" class="size-3 text-secondary"></i>
                                @elseif($payment->metode == 'cash') <i data-lucide="banknote" class="size-3 text-secondary"></i>
                                @else <i data-lucide="credit-card" class="size-3 text-secondary"></i> @endif
                            </div>
                            <span class="text-sm font-medium text-foreground">{{ ucfirst($payment->metode ?? 'Transfer Bank') }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <p class="text-sm font-bold text-foreground">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @php
                            $statusMap = [
                                'pending' => ['bg' => 'bg-warning-light', 'text' => 'text-warning-dark', 'label' => 'Menunggu Cek'],
                                'success' => ['bg' => 'bg-success-light', 'text' => 'text-success', 'label' => 'Lunas'],
                                'failed' => ['bg' => 'bg-error-light', 'text' => 'text-error', 'label' => 'Ditolak']
                            ];
                            $currStatus = $statusMap[$payment->status] ?? ['bg' => 'bg-muted', 'text' => 'text-secondary', 'label' => 'Unknown'];
                        @endphp
                        <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $currStatus['bg'] }} {{ $currStatus['text'] }}">
                            {{ $currStatus['label'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-2">
                            
                            @if($payment->status == 'pending')
                            <button onclick="openVerifyModal({{ $payment->id }}, '{{ $payment->user->name }}', '{{ number_format($payment->jumlah, 0, ',', '.') }}')" class="px-3 py-1.5 rounded-lg bg-primary/10 text-primary text-xs font-bold hover:bg-primary hover:text-white transition-colors cursor-pointer">
                                Verifikasi
                            </button>
                            @else
                            <a href="#" class="size-8 flex items-center justify-center rounded-lg bg-muted text-secondary hover:text-foreground transition-colors" title="Lihat Invoice">
                                <i data-lucide="file-text" class="size-4"></i>
                            </a>
                            @endif
                            
                            <button onclick="openImageModal('{{ asset('images/default-bukti.jpg') }}')" class="size-8 flex items-center justify-center rounded-lg border border-border text-secondary hover:bg-muted transition-colors cursor-pointer" title="Lihat Bukti Bayar">
                                <i data-lucide="image" class="size-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i data-lucide="wallet" class="size-12 text-muted mb-3"></i>
                            <p class="text-sm font-semibold text-foreground">Tidak ada transaksi</p>
                            <p class="text-xs text-secondary mt-1">Data pembayaran akan muncul ketika user melakukan transaksi.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($pembayarans) && method_exists($pembayarans, 'links'))
    <div class="px-6 py-4 border-t border-border bg-white flex items-center justify-between">
        <p class="text-xs text-secondary font-medium">
            Menampilkan {{ $pembayarans->firstItem() ?? 0 }} - {{ $pembayarans->lastItem() ?? 0 }} dari {{ $pembayarans->total() ?? 0 }}
        </p>
        <div class="flex gap-2">
            @if(!$pembayarans->onFirstPage())
            <a href="{{ $pembayarans->previousPageUrl() . (request('status') ? '&status='.request('status') : '') . (request('search') ? '&search='.request('search') : '') }}" class="px-3 py-1.5 rounded-lg border border-border text-xs font-semibold text-foreground hover:bg-muted transition-colors">Prev</a>
            @endif
            
            @if($pembayarans->hasMorePages())
            <a href="{{ $pembayarans->nextPageUrl() . (request('status') ? '&status='.request('status') : '') . (request('search') ? '&search='.request('search') : '') }}" class="px-3 py-1.5 rounded-lg border border-border text-xs font-semibold text-foreground hover:bg-muted transition-colors">Next</a>
            @endif
        </div>
    </div>
    @endif
</div>

<div id="imageModal" class="fixed inset-0 bg-black/80 z-[100] hidden items-center justify-center p-4 backdrop-blur-sm transition-opacity">
    <div class="relative max-w-3xl w-full">
        <button onclick="closeImageModal()" class="absolute -top-12 right-0 size-10 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center transition-colors cursor-pointer">
            <i data-lucide="x" class="size-5"></i>
        </button>
        <img id="modalImage" src="" class="w-full max-h-[80vh] object-contain rounded-xl shadow-2xl" alt="Bukti Pembayaran">
    </div>
</div>

<div id="verifyModal" class="fixed inset-0 bg-black/60 z-[100] hidden items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="p-5 border-b border-border flex items-center justify-between">
            <h3 class="font-bold text-lg text-foreground">Verifikasi Pembayaran</h3>
            <button onclick="closeVerifyModal()" class="text-secondary hover:text-foreground transition-colors cursor-pointer">
                <i data-lucide="x" class="size-5"></i>
            </button>
        </div>
        <div class="p-6">
            <p class="text-sm text-secondary mb-4">Apakah Anda yakin ingin menyetujui pembayaran ini? Aksi ini akan mengubah status booking kamar terkait.</p>
            
            <div class="bg-muted/50 rounded-xl p-4 mb-6">
                <div class="flex justify-between mb-2">
                    <span class="text-xs text-secondary">Nama Penyewa</span>
                    <span id="verifyUserName" class="text-sm font-semibold text-foreground"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs text-secondary">Total Dibayar</span>
                    <span id="verifyAmount" class="text-sm font-bold text-primary"></span>
                </div>
            </div>

            <div class="flex gap-3">
                <form id="rejectForm" method="POST" action="" class="w-full">
                    @csrf
                    <button type="submit" class="w-full py-2.5 bg-error-light text-error font-bold rounded-xl hover:bg-error hover:text-white transition-colors cursor-pointer">Tolak</button>
                </form>
                
                <form id="approveForm" method="POST" action="" class="w-full">
                    @csrf
                    <button type="submit" class="w-full py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-primary-hover transition-colors cursor-pointer shadow-sm shadow-primary/30">Setujui Lunas</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });

    // --- Script Modal Gambar Bukti ---
    function openImageModal(imageUrl) {
        document.getElementById('modalImage').src = imageUrl;
        const modal = document.getElementById('imageModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // --- Script Modal Verifikasi ---
    function openVerifyModal(paymentId, userName, amount) {
        // Set info data
        document.getElementById('verifyUserName').textContent = userName;
        document.getElementById('verifyAmount').textContent = 'Rp ' + amount;
        
        // Set Action URL forms dynamically based on Route name structure
        document.getElementById('approveForm').action = `/admin/pembayaran/${paymentId}/verify`;
        document.getElementById('rejectForm').action = `/admin/pembayaran/${paymentId}/reject`;

        // Show Modal
        const modal = document.getElementById('verifyModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeVerifyModal() {
        const modal = document.getElementById('verifyModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Close Modals on Escape key press
    document.addEventListener('keydown', function(e) {
        if(e.key === 'Escape') {
            closeImageModal();
            closeVerifyModal();
        }
    });
</script>
@endsection