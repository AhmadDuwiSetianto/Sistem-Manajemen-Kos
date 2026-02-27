@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-foreground">Laporan Keuangan</h1>
        <p class="text-secondary mt-1">Analisis pendapatan dan transaksi KOSTKU</p>
    </div>
    <div class="flex items-center gap-3">
        <form action="{{ route('admin.laporan.export-excel') }}" method="GET" class="inline">
            <input type="hidden" name="bulan" value="{{ $filterBulan }}">
            <input type="hidden" name="tahun" value="{{ $filterTahun }}">
            <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-white border border-border text-foreground font-semibold rounded-xl hover:bg-success/10 hover:text-success hover:border-success/30 transition-all shadow-sm">
                <i data-lucide="file-spreadsheet" class="size-4 mr-2"></i> Export Excel
            </button>
        </form>
        <form action="{{ route('admin.laporan.export-pdf') }}" method="GET" class="inline" target="_blank">
            <input type="hidden" name="bulan" value="{{ $filterBulan }}">
            <input type="hidden" name="tahun" value="{{ $filterTahun }}">
            <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-error text-white font-bold rounded-xl hover:bg-error/90 transition-all shadow-sm shadow-error/30">
                <i data-lucide="file-text" class="size-4 mr-2"></i> Export PDF
            </button>
        </form>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-border p-6 mb-8">
    <div class="flex items-center gap-2 mb-4">
        <i data-lucide="filter" class="size-5 text-primary"></i>
        <h3 class="font-bold text-foreground">Filter Data Laporan</h3>
    </div>
    <form action="{{ route('admin.laporan.keuangan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label class="block text-xs font-semibold text-secondary uppercase tracking-wider mb-2">Pilih Bulan</label>
            <input type="month" name="bulan" value="{{ $filterBulan }}" class="w-full px-4 py-2.5 bg-muted border-none rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm text-foreground">
        </div>
        <div>
            <label class="block text-xs font-semibold text-secondary uppercase tracking-wider mb-2">Pilih Tahun</label>
            <div class="relative">
                <select name="tahun" class="w-full pl-4 pr-10 py-2.5 bg-muted border-none rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm appearance-none text-foreground">
                    @for($year = date('Y'); $year >= 2020; $year--)
                        <option value="{{ $year }}" {{ $filterTahun == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endfor
                </select>
                <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
            </div>
        </div>
        <div class="md:col-span-2 flex justify-end">
            <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-primary-hover transition-colors shadow-sm">
                Terapkan Filter
            </button>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="flex flex-col rounded-2xl border border-border p-6 bg-white shadow-sm">
        <div class="flex justify-between items-start mb-2">
            <div class="size-10 bg-success-light rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="trending-up" class="size-5 text-success"></i>
            </div>
            <span class="inline-flex items-center text-[10px] font-bold px-2 py-0.5 rounded-md {{ $persentasePendapatan >= 0 ? 'bg-success-light text-success' : 'bg-error-light text-error' }}">
                @if($persentasePendapatan > 0)<i data-lucide="arrow-up-right" class="size-3 mr-1"></i>@elseif($persentasePendapatan < 0)<i data-lucide="arrow-down-right" class="size-3 mr-1"></i>@endif
                {{ number_format(abs($persentasePendapatan), 1) }}%
            </span>
        </div>
        <p class="font-medium text-sm text-secondary mt-1">Pendapatan Bulan Ini</p>
        <p class="font-bold text-2xl text-foreground mt-0.5">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
    </div>

    <div class="flex flex-col rounded-2xl border border-border p-6 bg-white shadow-sm">
        <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center mb-2">
            <i data-lucide="wallet" class="size-5 text-primary"></i>
        </div>
        <p class="font-medium text-sm text-secondary mt-1">Total Pendapatan ({{ $filterTahun }})</p>
        <p class="font-bold text-2xl text-foreground mt-0.5">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
    </div>

    <div class="flex flex-col rounded-2xl border border-border p-6 bg-white shadow-sm">
        <div class="size-10 bg-purple-100 rounded-xl flex items-center justify-center mb-2">
            <i data-lucide="bar-chart" class="size-5 text-purple-600"></i>
        </div>
        <p class="font-medium text-sm text-secondary mt-1">Rata-rata per Kamar</p>
        <p class="font-bold text-2xl text-foreground mt-0.5">Rp {{ number_format($rataRataKamar, 0, ',', '.') }}</p>
    </div>

    <div class="flex flex-col rounded-2xl border border-border p-6 bg-white shadow-sm">
        <div class="size-10 bg-warning-light rounded-xl flex items-center justify-center mb-2">
            <i data-lucide="alert-triangle" class="size-5 text-warning-dark"></i>
        </div>
        <p class="font-medium text-sm text-secondary mt-1">Total Tunggakan</p>
        <p class="font-bold text-2xl text-error mt-0.5">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 flex flex-col rounded-2xl border border-border p-6 bg-white shadow-sm">
        <h3 class="font-bold text-lg text-foreground mb-4">Grafik Pendapatan Tahun {{ $filterTahun }}</h3>
        <div class="h-64 w-full">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="flex flex-col rounded-2xl border border-border p-6 bg-white shadow-sm">
        <h3 class="font-bold text-lg text-foreground mb-4">Distribusi Tipe Kamar</h3>
        <div class="flex flex-col gap-3 overflow-y-auto max-h-64 scrollbar-hide">
            @forelse($pendapatanPerTipe as $tipe)
            <div class="flex items-center justify-between p-3 rounded-xl border border-border hover:bg-muted/50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="size-10 bg-primary/10 rounded-lg flex items-center justify-center text-primary">
                        <i data-lucide="bed-double" class="size-5"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-foreground">{{ $tipe['tipe'] }}</p>
                        <p class="text-[10px] font-semibold text-secondary uppercase">{{ $tipe['persentase'] }}% dari total</p>
                    </div>
                </div>
                <p class="text-sm font-bold text-foreground text-right">Rp {{ number_format($tipe['pendapatan'], 0, ',', '.') }}</p>
            </div>
            @empty
            <div class="text-center py-8">
                <i data-lucide="pie-chart" class="size-10 text-muted mx-auto mb-2"></i>
                <p class="text-sm text-secondary">Tidak ada data distribusi</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div>
            <h2 class="text-lg font-bold text-foreground">Transaksi Sukses Terbaru</h2>
            <p class="text-xs text-secondary mt-0.5">Menampilkan {{ $transaksiTerbaru->count() }} transaksi lunas terakhir.</p>
        </div>
        <a href="#" class="text-sm font-semibold text-primary hover:underline">Lihat Semua Data</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-border">
            <thead class="bg-muted/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">ID TRX</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">User / Penyewa</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Kamar</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Waktu Pembayaran</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-secondary uppercase tracking-wider">Jumlah (Rp)</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-secondary uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-border">
                @forelse($transaksiTerbaru as $transaksi)
                <tr class="hover:bg-muted/30 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-mono font-bold text-foreground">#{{ $transaksi->id }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-semibold text-foreground">{{ $transaksi->user->name ?? 'N/A' }}</p>
                        <p class="text-[11px] text-secondary mt-0.5">{{ $transaksi->user->email ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-semibold text-foreground">Kamar {{ $transaksi->kamar->nomor_kamar ?? 'N/A' }}</p>
                        <p class="text-[11px] text-secondary mt-0.5">{{ $transaksi->kamar->tipe_kamar ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-foreground">{{ $transaksi->created_at->format('d M Y') }}</p>
                        <p class="text-[11px] text-secondary mt-0.5">{{ $transaksi->created_at->format('H:i') }} WIB</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <p class="text-sm font-bold text-foreground">Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-success-light text-success">
                            <i data-lucide="check" class="size-3"></i> Lunas
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <i data-lucide="receipt" class="size-10 text-muted mx-auto mb-2"></i>
                        <p class="text-sm font-semibold text-foreground">Belum ada transaksi</p>
                        <p class="text-xs text-secondary mt-1">Belum ada transaksi lunas di periode ini.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();

        // Chart.js Configuration
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const chartData = @json($chartData);
        
        // Buat efek gradient untuk bar chart
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, '#165DFF');
        gradient.addColorStop(1, 'rgba(22, 93, 255, 0.2)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.map(item => item.bulan),
                datasets: [{
                    label: 'Pendapatan',
                    data: chartData.map(item => item.pendapatan),
                    backgroundColor: gradient,
                    borderRadius: 6,
                    borderSkipped: false,
                    barThickness: 24,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#080C1A',
                        padding: 12,
                        titleFont: { family: "'Lexend Deca', sans-serif", size: 13 },
                        bodyFont: { family: "'Lexend Deca', sans-serif", size: 12 },
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#EFF2F7', drawBorder: false },
                        ticks: {
                            font: { family: "'Lexend Deca', sans-serif", size: 11 },
                            color: '#6A7686',
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000) + ' Jt';
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { font: { family: "'Lexend Deca', sans-serif", size: 11 }, color: '#6A7686' }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection