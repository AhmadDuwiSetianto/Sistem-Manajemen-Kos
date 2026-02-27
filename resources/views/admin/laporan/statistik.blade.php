@extends('layouts.admin')

@section('title', 'Laporan Statistik')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-foreground">Laporan Statistik</h1>
        <p class="text-secondary mt-1">Analisis performa, tren, dan data penghuni KOSTKU</p>
    </div>
    <div class="flex space-x-3">
        <button class="inline-flex items-center px-4 py-2.5 bg-white border border-border text-foreground font-semibold rounded-xl hover:bg-muted transition-colors shadow-sm">
            <i data-lucide="calendar" class="size-4 mr-2 text-secondary"></i> Pilih Periode Analisis
        </button>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="flex flex-col rounded-2xl border border-border p-6 bg-white shadow-sm">
        <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center mb-3">
            <i data-lucide="users" class="size-5 text-primary"></i>
        </div>
        <p class="font-medium text-sm text-secondary">Total Penghuni Aktif</p>
        <p class="font-bold text-3xl text-foreground mt-1">{{ $totalPenghuni ?? 0 }}</p>
    </div>

    <div class="flex flex-col rounded-2xl border border-border p-6 bg-white shadow-sm relative overflow-hidden">
        <div class="size-10 bg-success-light rounded-xl flex items-center justify-center mb-3">
            <i data-lucide="pie-chart" class="size-5 text-success"></i>
        </div>
        <p class="font-medium text-sm text-secondary">Occupancy Rate</p>
        <div class="flex items-end gap-2 mt-1">
            <p class="font-bold text-3xl text-foreground">{{ $occupancyRate ?? 0 }}%</p>
        </div>
        <div class="absolute bottom-0 left-0 h-1.5 bg-success/20 w-full">
            <div class="h-full bg-success" style="width: {{ $occupancyRate ?? 0 }}%"></div>
        </div>
    </div>

    <div class="flex flex-col rounded-2xl border border-border p-6 bg-white shadow-sm">
        <div class="size-10 bg-purple-100 rounded-xl flex items-center justify-center mb-3">
            <i data-lucide="calendar-check" class="size-5 text-purple-600"></i>
        </div>
        <p class="font-medium text-sm text-secondary">Booking Bulan Ini</p>
        <p class="font-bold text-3xl text-foreground mt-1">{{ $bookingBulanIni ?? 0 }}</p>
    </div>

    <div class="flex flex-col rounded-2xl border border-border p-6 bg-white shadow-sm">
        <div class="size-10 bg-warning-light rounded-xl flex items-center justify-center mb-3">
            <i data-lucide="refresh-cw" class="size-5 text-warning-dark"></i>
        </div>
        <p class="font-medium text-sm text-secondary">Retention Rate</p>
        <p class="font-bold text-3xl text-foreground mt-1">{{ $retentionRate ?? 0 }}%</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-1 space-y-6">
        
        <div class="bg-white rounded-2xl shadow-sm border border-border p-6">
            <h3 class="font-bold text-foreground mb-4 flex items-center gap-2">
                <i data-lucide="contact" class="size-5 text-primary"></i> Data Demografi User
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3.5 bg-muted/50 rounded-xl border border-border">
                    <span class="text-sm font-semibold text-secondary">Total Seluruh User</span>
                    <span class="text-base font-bold text-foreground">{{ $totalUser ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center p-3.5 bg-muted/50 rounded-xl border border-border">
                    <span class="text-sm font-semibold text-secondary">Penyewa Aktif</span>
                    <span class="text-base font-bold text-primary">{{ $userAktif ?? 0 }}</span>
                </div>
                <div class="flex justify-between items-center p-3.5 bg-muted/50 rounded-xl border border-border">
                    <span class="text-sm font-semibold text-secondary">Pendaftar Baru Bulan Ini</span>
                    <span class="text-base font-bold text-success">+{{ $userBaru ?? 0 }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-border p-6">
            <h3 class="font-bold text-foreground mb-4 flex items-center gap-2">
                <i data-lucide="activity" class="size-5 text-warning-dark"></i> Analisis Kinerja
            </h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-secondary">Avg. Lama Menginap</span>
                        <span class="font-bold text-foreground">{{ $avgStay ?? 0 }} Bulan</span>
                    </div>
                    <div class="h-2 w-full bg-muted rounded-full overflow-hidden">
                        <div class="h-full bg-warning-dark rounded-full" style="width: 70%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-secondary">Booking Conversion</span>
                        <span class="font-bold text-success">{{ $conversionRate ?? 0 }}%</span>
                    </div>
                    <div class="h-2 w-full bg-muted rounded-full overflow-hidden">
                        <div class="h-full bg-success rounded-full" style="width: {{ $conversionRate ?? 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-secondary">Cancellation Rate</span>
                        <span class="font-bold text-error">{{ $cancellationRate ?? 0 }}%</span>
                    </div>
                    <div class="h-2 w-full bg-muted rounded-full overflow-hidden">
                        <div class="h-full bg-error rounded-full" style="width: {{ $cancellationRate ?? 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-border p-6 h-[350px] flex flex-col">
            <h3 class="font-bold text-foreground mb-4">Trend Pertumbuhan Booking (6 Bulan Terakhir)</h3>
            <div class="flex-1 bg-muted/30 border border-dashed border-border rounded-xl flex flex-col items-center justify-center">
                <i data-lucide="line-chart" class="size-12 text-secondary/50 mb-3"></i>
                <p class="text-sm font-semibold text-secondary">Area Grafik Trend Booking</p>
                <p class="text-xs text-secondary/70 mt-1">Gunakan Chart.js untuk menampilkan visualisasi data di sini.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-border p-6 h-[320px] flex flex-col">
            <h3 class="font-bold text-foreground mb-4">Distribusi Status Kamar Saat Ini</h3>
            <div class="flex-1 bg-muted/30 border border-dashed border-border rounded-xl flex flex-col items-center justify-center">
                <i data-lucide="pie-chart" class="size-12 text-secondary/50 mb-3"></i>
                <p class="text-sm font-semibold text-secondary">Area Grafik Pie/Doughnut</p>
                <p class="text-xs text-secondary/70 mt-1">Perbandingan Terisi vs Tersedia vs Maintenance.</p>
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