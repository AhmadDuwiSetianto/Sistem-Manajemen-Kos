@extends('layouts.admin')

@section('title', 'Data Pembayaran')

@section('content')
<div class="flex-1 p-4 md:p-8">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-foreground">Transaksi Pembayaran</h1>
            <p class="text-secondary mt-1 text-sm md:text-base">Verifikasi bukti transfer dan kelola tagihan</p>
        </div>
        <div class="flex">
            <a href="{{ route('admin.laporan.keuangan') }}" class="inline-flex items-center px-4 py-2 bg-white border border-border text-foreground font-semibold text-sm rounded-xl hover:bg-muted transition-colors shadow-sm">
                <i data-lucide="printer" class="size-4 mr-2 text-secondary"></i> Cetak Laporan
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-success-light border border-success/20 p-3 md:p-4 mb-6 rounded-xl flex items-center gap-3">
        <div class="size-8 bg-success/20 rounded-full flex items-center justify-center shrink-0">
            <i data-lucide="check-circle" class="size-5 text-success"></i>
        </div>
        <p class="text-success text-sm font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-error-light border border-error/20 p-3 md:p-4 mb-6 rounded-xl flex items-center gap-3">
        <div class="size-8 bg-error/20 rounded-full flex items-center justify-center shrink-0">
            <i data-lucide="alert-circle" class="size-5 text-error"></i>
        </div>
        <p class="text-error text-sm font-medium">{{ session('error') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-8">
        <div class="flex flex-col justify-between rounded-xl border border-border p-4 bg-white shadow-sm hover:ring-1 hover:ring-primary transition-all">
            <div class="flex items-center gap-2 mb-2">
                <div class="size-8 md:size-10 bg-primary/10 rounded-lg flex items-center justify-center shrink-0">
                    <i data-lucide="wallet" class="size-4 text-primary"></i>
                </div>
                <p class="font-medium text-secondary text-[10px] md:text-xs leading-tight">Total<br>Pemasukan</p>
            </div>
            <p class="font-black text-base md:text-2xl text-foreground">Rp {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}</p>
        </div>

        <div class="flex flex-col justify-between rounded-xl border border-border p-4 bg-white shadow-sm hover:ring-1 hover:ring-warning transition-all relative overflow-hidden">
            <div class="flex items-center gap-2 mb-2">
                <div class="size-8 md:size-10 bg-warning-light rounded-lg flex items-center justify-center shrink-0">
                    <i data-lucide="hourglass" class="size-4 text-warning-dark"></i>
                </div>
                <p class="font-medium text-secondary text-[10px] md:text-xs leading-tight">Menunggu<br>Cek</p>
            </div>
            <p class="font-black text-xl md:text-3xl text-warning-dark">{{ $pendingPembayaran ?? 0 }} <span class="text-xs font-medium text-secondary">trx</span></p>
            @if(($pendingPembayaran ?? 0) > 0)
            <div class="absolute top-0 right-0 w-1.5 h-full bg-warning"></div>
            @endif
        </div>

        <div class="flex flex-col justify-between rounded-xl border border-border p-4 bg-white shadow-sm hover:ring-1 hover:ring-success transition-all">
            <div class="flex items-center gap-2 mb-2">
                <div class="size-8 md:size-10 bg-success-light rounded-lg flex items-center justify-center shrink-0">
                    <i data-lucide="check-circle" class="size-4 text-success"></i>
                </div>
                <p class="font-medium text-secondary text-[10px] md:text-xs leading-tight">Pembayaran<br>Lunas</p>
            </div>
            <p class="font-black text-xl md:text-3xl text-success">{{ $suksesPembayaran ?? 0 }}</p>
        </div>

        <div class="flex flex-col justify-between rounded-xl border border-border p-4 bg-white shadow-sm hover:ring-1 hover:ring-error transition-all">
            <div class="flex items-center gap-2 mb-2">
                <div class="size-8 md:size-10 bg-error-light rounded-lg flex items-center justify-center shrink-0">
                    <i data-lucide="alert-circle" class="size-4 text-error"></i>
                </div>
                <p class="font-medium text-secondary text-[10px] md:text-xs leading-tight">Ditolak /<br>Gagal</p>
            </div>
            <p class="font-black text-xl md:text-3xl text-error">{{ $gagalPembayaran ?? 0 }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden">
        <div class="px-5 py-4 border-b border-border flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="text-base md:text-lg font-bold text-foreground">Riwayat Transaksi</h2>
            <form method="GET" action="{{ route('admin.pembayaran.index') }}" class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                <div class="relative w-full sm:w-40">
                    <i data-lucide="filter" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                    <select name="status" onchange="this.form.submit()" class="w-full pl-9 pr-8 py-2 bg-muted border-none rounded-xl text-xs outline-none appearance-none text-foreground cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Cek</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Batal/Kedaluwarsa</option>
                    </select>
                    <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                </div>
                <div class="relative w-full sm:w-56">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/kamar..." class="w-full pl-9 pr-4 py-2 bg-muted border-none rounded-xl text-xs outline-none placeholder:text-secondary text-foreground">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                    <button type="submit" class="hidden"></button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-muted/50">
                    <tr>
                        <th class="px-5 py-3 text-left text-[10px] md:text-xs font-semibold text-secondary uppercase">ID Transaksi</th>
                        <th class="px-5 py-3 text-left text-[10px] md:text-xs font-semibold text-secondary uppercase">Booking Ref</th>
                        <th class="px-5 py-3 text-left text-[10px] md:text-xs font-semibold text-secondary uppercase">Metode</th>
                        <th class="px-5 py-3 text-right text-[10px] md:text-xs font-semibold text-secondary uppercase">Total (Rp)</th>
                        <th class="px-5 py-3 text-center text-[10px] md:text-xs font-semibold text-secondary uppercase">Status</th>
                        <th class="px-5 py-3 text-right text-[10px] md:text-xs font-semibold text-secondary uppercase">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse($pembayarans ?? [] as $payment)
                    <tr class="hover:bg-muted/30 transition-colors">
                        <td class="px-5 py-3 whitespace-nowrap">
                            <p class="text-[11px] md:text-xs font-mono font-bold text-foreground">{{ $payment->kode_pembayaran }}</p>
                            <p class="text-[10px] text-secondary mt-0.5">{{ $payment->created_at->format('d M Y, H:i') }}</p>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <p class="text-xs md:text-sm font-semibold text-primary">#BK-{{ str_pad($payment->booking_id, 5, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-[10px] md:text-[11px] text-secondary mt-0.5">{{ $payment->user->name ?? 'User' }} • Kmr {{ $payment->booking->kamar->nomor_kamar ?? '-' }}</p>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <span class="text-xs font-medium text-foreground">{{ ucfirst($payment->metode_pembayaran ?? $payment->metode ?? 'Transfer Bank') }}</span>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-right">
                            <p class="text-xs md:text-sm font-bold text-foreground">{{ number_format($payment->jumlah, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-center">
                            @php
                                $statusMap = [
                                    'pending' => ['bg' => 'bg-warning-light', 'text' => 'text-warning-dark', 'label' => 'Menunggu Cek'],
                                    'paid' => ['bg' => 'bg-success-light', 'text' => 'text-success', 'label' => 'Lunas'],
                                    'expired' => ['bg' => 'bg-error-light', 'text' => 'text-error', 'label' => 'Kedaluwarsa'],
                                    'cancelled' => ['bg' => 'bg-error-light', 'text' => 'text-error', 'label' => 'Dibatalkan']
                                ];
                                $currStatus = $statusMap[$payment->status] ?? ['bg' => 'bg-muted', 'text' => 'text-secondary', 'label' => ucfirst($payment->status)];
                            @endphp
                            <span class="inline-flex px-2 py-1 rounded-full text-[9px] md:text-[10px] font-bold uppercase tracking-wider {{ $currStatus['bg'] }} {{ $currStatus['text'] }}">
                                {{ $currStatus['label'] }}
                            </span>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-right">
    <div class="flex items-center justify-end gap-2">
        
        <a href="/admin/pembayaran/{{ $payment->id }}" class="size-7 md:size-8 flex items-center justify-center rounded-lg border border-blue-100 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors" title="Lihat Detail & Invoice">
            <i data-lucide="file-text" class="size-3.5 md:size-4"></i>
        </a>

        @if($payment->status == 'pending')
        <button onclick="openVerifyModal({{ $payment->id }}, '{{ $payment->user->name ?? 'User' }}', '{{ number_format($payment->jumlah, 0, ',', '.') }}')" class="px-3 py-1.5 rounded-lg bg-warning/10 text-warning-dark text-[10px] md:text-xs font-bold hover:bg-warning hover:text-white transition-colors cursor-pointer flex items-center gap-1.5" title="Aksi Manual">
            <i data-lucide="settings-2" class="size-3 md:size-3.5"></i> Kelola
        </button>
        @endif

    </div>
</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="wallet" class="size-10 text-muted mb-2"></i>
                                <p class="text-xs font-semibold text-foreground">Tidak ada transaksi</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($pembayarans) && method_exists($pembayarans, 'links'))
        <div class="px-5 py-3 border-t border-border bg-white flex flex-col md:flex-row items-center justify-between gap-3">
            <p class="text-[10px] md:text-xs text-secondary font-medium text-center md:text-left">
                Menampilkan {{ $pembayarans->firstItem() ?? 0 }} - {{ $pembayarans->lastItem() ?? 0 }} dari {{ $pembayarans->total() ?? 0 }}
            </p>
            <div class="flex gap-2">
                @if(!$pembayarans->onFirstPage())
                <a href="{{ $pembayarans->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-border text-[10px] md:text-xs font-semibold text-foreground hover:bg-muted transition-colors">Prev</a>
                @endif
                @if($pembayarans->hasMorePages())
                <a href="{{ $pembayarans->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-border text-[10px] md:text-xs font-semibold text-foreground hover:bg-muted transition-colors">Next</a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<div id="imageModal" class="fixed inset-0 bg-black/80 z-[100] hidden items-center justify-center p-4 backdrop-blur-sm transition-opacity">
    <div class="relative max-w-2xl w-full">
        <button onclick="closeImageModal()" class="absolute -top-10 right-0 size-8 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center transition-colors cursor-pointer">
            <i data-lucide="x" class="size-4"></i>
        </button>
        <img id="modalImage" src="" class="w-full max-h-[75vh] object-contain rounded-xl shadow-2xl" alt="Bukti Pembayaran">
    </div>
</div>

<div id="verifyModal" class="fixed inset-0 bg-black/60 z-[100] hidden items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden">
        <div class="p-4 border-b border-border flex items-center justify-between">
            <h3 class="font-bold text-base text-foreground">Verifikasi Pembayaran</h3>
            <button onclick="closeVerifyModal()" class="text-secondary hover:text-foreground transition-colors cursor-pointer">
                <i data-lucide="x" class="size-4"></i>
            </button>
        </div>
        <div class="p-5">
            <p class="text-xs text-secondary mb-4">Setujui pembayaran ini untuk mengaktifkan status booking kamar.</p>
            
            <div class="bg-muted/50 rounded-xl p-3 mb-5">
                <div class="flex justify-between mb-1">
                    <span class="text-[10px] text-secondary">Penyewa</span>
                    <span id="verifyUserName" class="text-xs font-semibold text-foreground"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[10px] text-secondary">Total Dibayar</span>
                    <span id="verifyAmount" class="text-xs font-bold text-primary"></span>
                </div>
            </div>

            <div class="flex gap-2">
                <form id="rejectForm" method="POST" action="" class="w-full">
                    @csrf
                    <button type="submit" class="w-full py-2 bg-error-light text-error text-xs font-bold rounded-lg hover:bg-error hover:text-white transition-colors cursor-pointer">Tolak</button>
                </form>
                <form id="approveForm" method="POST" action="" class="w-full">
                    @csrf
                    <button type="submit" class="w-full py-2 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary-hover transition-colors cursor-pointer shadow-sm">Setujui</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });

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

    function openVerifyModal(paymentId, userName, amount) {
        document.getElementById('verifyUserName').textContent = userName;
        document.getElementById('verifyAmount').textContent = 'Rp ' + amount;
        document.getElementById('approveForm').action = `/admin/pembayaran/${paymentId}/verify`;
        document.getElementById('rejectForm').action = `/admin/pembayaran/${paymentId}/reject`;

        const modal = document.getElementById('verifyModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeVerifyModal() {
        const modal = document.getElementById('verifyModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.addEventListener('keydown', function(e) {
        if(e.key === 'Escape') {
            closeImageModal();
            closeVerifyModal();
        }
    });
</script>
@endsection