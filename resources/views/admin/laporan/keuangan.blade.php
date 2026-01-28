@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
    <div class="mb-4 md:mb-0">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Laporan Keuangan</h1>
        <p class="text-gray-600 mt-2">Analisis keuangan dan pendapatan KOSTKU</p>
    </div>
    <div class="flex space-x-3">
        <!-- Export Buttons -->
        <form action="{{ route('admin.laporan.export-excel') }}" method="GET" class="inline">
            <input type="hidden" name="bulan" value="{{ $filterBulan }}">
            <input type="hidden" name="tahun" value="{{ $filterTahun }}">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                <i class="fas fa-file-excel mr-2 text-green-600"></i>Export Excel
            </button>
        </form>
        <form action="{{ route('admin.laporan.export-pdf') }}" method="GET" class="inline" target="_blank">
            <input type="hidden" name="bulan" value="{{ $filterBulan }}">
            <input type="hidden" name="tahun" value="{{ $filterTahun }}">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-medium rounded-lg shadow-md transition-all duration-200">
                <i class="fas fa-file-pdf mr-2"></i>Export PDF
            </button>
        </form>
    </div>
</div>

<!-- Filter Section -->
<div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Laporan</h3>
    <form action="{{ route('admin.laporan.keuangan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
            <input type="month" name="bulan" value="{{ $filterBulan }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
            <select name="tahun" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                @for($year = date('Y'); $year >= 2020; $year--)
                    <option value="{{ $year }}" {{ $filterTahun == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endfor
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-filter mr-2"></i>Terapkan Filter
            </button>
        </div>
    </form>
</div>

<!-- Financial Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium opacity-90">Pendapatan Bulan Ini</p>
                <p class="text-2xl font-bold mt-1">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
            </div>
            <div class="p-3 rounded-full bg-white bg-opacity-20">
                <i class="fas fa-money-bill-wave text-xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm opacity-90">
            @if($persentasePendapatan > 0)
            <i class="fas fa-arrow-up mr-1"></i>
            <span>{{ number_format($persentasePendapatan, 1) }}% dari bulan lalu</span>
            @elseif($persentasePendapatan < 0)
            <i class="fas fa-arrow-down mr-1"></i>
            <span>{{ number_format(abs($persentasePendapatan), 1) }}% dari bulan lalu</span>
            @else
            <span>Tidak ada perubahan</span>
            @endif
        </div>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium opacity-90">Total Pendapatan</p>
                <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
            <div class="p-3 rounded-full bg-white bg-opacity-20">
                <i class="fas fa-chart-line text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium opacity-90">Rata-rata per Kamar</p>
                <p class="text-2xl font-bold mt-1">Rp {{ number_format($rataRataKamar, 0, ',', '.') }}</p>
            </div>
            <div class="p-3 rounded-full bg-white bg-opacity-20">
                <i class="fas fa-home text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium opacity-90">Tunggakan</p>
                <p class="text-2xl font-bold mt-1">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</p>
            </div>
            <div class="p-3 rounded-full bg-white bg-opacity-20">
                <i class="fas fa-clock text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Reports -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Monthly Revenue Chart -->
    <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Pendapatan Bulanan ({{ $filterTahun }})</h3>
        <div class="h-64">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Revenue by Room Type -->
    <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Pendapatan per Tipe Kamar</h3>
        <div class="space-y-4">
            @foreach($pendapatanPerTipe as $tipe)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-home text-primary-600"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700">{{ $tipe['tipe'] }}</span>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($tipe['pendapatan'], 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">{{ $tipe['persentase'] }}%</p>
                </div>
            </div>
            @endforeach
            @if($pendapatanPerTipe->isEmpty())
            <div class="text-center text-gray-500 py-4">
                <i class="fas fa-chart-pie text-2xl mb-2"></i>
                <p>Tidak ada data pendapatan</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="card bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Transaksi Sukses Terbaru</h2>
            <p class="text-sm text-gray-600 mt-1 sm:mt-0">
                Menampilkan {{ $transaksiTerbaru->count() }} transaksi lunas
            </p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Transaksi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kamar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($transaksiTerbaru as $transaksi)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-mono font-semibold text-gray-900">#{{ $transaksi->id }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $transaksi->user->name ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-500">{{ $transaksi->user->email ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">Kamar {{ $transaksi->kamar->nomor_kamar ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ $transaksi->kamar->tipe_kamar ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $transaksi->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-gray-900">
                            Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-1"></i>Lunas
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <i class="fas fa-receipt text-4xl mb-3"></i>
                            <p class="text-lg font-medium text-gray-500">Belum ada transaksi</p>
                            <p class="text-sm mt-1 text-gray-400">Tidak ada transaksi lunas pada periode yang dipilih</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
    <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Tahun {{ $filterTahun }}</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Pendapatan Tertinggi</span>
                <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($pendapatanTertinggi, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Bulan Terbaik</span>
                <span class="text-sm font-semibold text-gray-900">{{ $bulanTerbaik }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Rata-rata Bulanan</span>
                <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($rataRataBulanan, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Kinerja Kamar</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Occupancy Rate</span>
                <span class="text-sm font-semibold text-gray-900">{{ $occupancyRate }}%</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Kamar Terisi</span>
                <span class="text-sm font-semibold text-gray-900">{{ $kamarTerisi }}/{{ $totalKamar }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Kamar Tersedia</span>
                <span class="text-sm font-semibold text-gray-900">{{ $kamarTersedia }}</span>
            </div>
        </div>
    </div>

    <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Pembayaran</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Pembayaran Lunas</span>
                <span class="text-sm font-semibold text-gray-900">{{ $pembayaranLunas }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Menunggu Pembayaran</span>
                <span class="text-sm font-semibold text-gray-900">{{ $menungguPembayaran }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Pembayaran Gagal</span>
                <span class="text-sm font-semibold text-gray-900">{{ $pembayaranGagal }}</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const chartData = @json($chartData);
        
        const revenueChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.map(item => item.bulan),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: chartData.map(item => item.pendapatan),
                    backgroundColor: 'rgba(14, 165, 233, 0.6)',
                    borderColor: 'rgba(14, 165, 233, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
</style>
@endsection