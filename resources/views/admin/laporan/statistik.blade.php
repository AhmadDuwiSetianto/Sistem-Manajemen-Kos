@extends('layouts.admin')

@section('title', 'Laporan Statistik')

@section('content')
<div class="flex-1 p-4 md:p-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 md:mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-foreground">Laporan Statistik</h1>
            <p class="text-secondary mt-1 text-sm md:text-base">Analisis performa, tren, dan data penghuni KOSTKU</p>
        </div>
        <div>
            <button class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-white border border-border text-foreground font-semibold text-xs md:text-sm rounded-xl hover:bg-muted transition-colors shadow-sm">
                <i data-lucide="calendar" class="size-4 mr-2 text-secondary"></i> Pilih Periode Analisis
            </button>
        </div>
    </div>

    <!-- Cards Statistik (Mobile: 2 Kolom, Desktop: 4 Kolom) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6 md:mb-8">
        <div class="flex flex-col justify-between rounded-xl border border-border p-4 bg-white shadow-sm hover:ring-1 hover:ring-primary transition-all">
            <div class="flex items-center gap-2 mb-2">
                <div class="size-8 md:size-10 bg-primary/10 rounded-lg flex items-center justify-center shrink-0">
                    <i data-lucide="users" class="size-4 md:size-5 text-primary"></i>
                </div>
            </div>
            <p class="font-medium text-secondary text-[10px] md:text-xs leading-tight">Total<br>Penghuni Aktif</p>
            <p class="font-black text-xl md:text-3xl text-foreground mt-1">{{ $totalPenghuni ?? 0 }}</p>
        </div>

        <div class="flex flex-col justify-between rounded-xl border border-border p-4 bg-white shadow-sm hover:ring-1 hover:ring-success transition-all relative overflow-hidden">
            <div class="flex items-center gap-2 mb-2">
                <div class="size-8 md:size-10 bg-success-light rounded-lg flex items-center justify-center shrink-0">
                    <i data-lucide="pie-chart" class="size-4 md:size-5 text-success"></i>
                </div>
            </div>
            <p class="font-medium text-secondary text-[10px] md:text-xs leading-tight">Occupancy<br>Rate</p>
            <div class="flex items-end gap-2 mt-1">
                <p class="font-black text-xl md:text-3xl text-foreground">{{ $occupancyRate ?? 0 }}%</p>
            </div>
            <div class="absolute bottom-0 left-0 h-1.5 bg-success/20 w-full">
                <div class="h-full bg-success" style="width: {{ $occupancyRate ?? 0 }}%"></div>
            </div>
        </div>

        <div class="flex flex-col justify-between rounded-xl border border-border p-4 bg-white shadow-sm hover:ring-1 hover:ring-purple-500 transition-all">
            <div class="flex items-center gap-2 mb-2">
                <div class="size-8 md:size-10 bg-purple-100 rounded-lg flex items-center justify-center shrink-0">
                    <i data-lucide="calendar-check" class="size-4 md:size-5 text-purple-600"></i>
                </div>
            </div>
            <p class="font-medium text-secondary text-[10px] md:text-xs leading-tight">Booking<br>Bulan Ini</p>
            <p class="font-black text-xl md:text-3xl text-foreground mt-1">{{ $bookingBulanIni ?? 0 }}</p>
        </div>

        <div class="flex flex-col justify-between rounded-xl border border-border p-4 bg-white shadow-sm hover:ring-1 hover:ring-warning transition-all">
            <div class="flex items-center gap-2 mb-2">
                <div class="size-8 md:size-10 bg-warning-light rounded-lg flex items-center justify-center shrink-0">
                    <i data-lucide="refresh-cw" class="size-4 md:size-5 text-warning-dark"></i>
                </div>
            </div>
            <p class="font-medium text-secondary text-[10px] md:text-xs leading-tight">Retention<br>Rate</p>
            <p class="font-black text-xl md:text-3xl text-foreground mt-1">{{ $retentionRate ?? 0 }}%</p>
        </div>
    </div>

    <!-- Section Demografi & Grafik -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-8">
        
        <!-- Kolom Kiri: Demografi & Kinerja -->
        <div class="lg:col-span-1 space-y-4 md:space-y-6">
            
            <!-- Demografi User -->
            <div class="bg-white rounded-2xl shadow-sm border border-border p-4 md:p-6">
                <h3 class="font-bold text-sm md:text-base text-foreground mb-4 flex items-center gap-2">
                    <i data-lucide="contact" class="size-4 md:size-5 text-primary"></i> Data Demografi User
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 md:p-3.5 bg-muted/50 rounded-xl border border-border">
                        <span class="text-xs md:text-sm font-semibold text-secondary">Total Seluruh User</span>
                        <span class="text-sm md:text-base font-bold text-foreground">{{ $totalUser ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 md:p-3.5 bg-muted/50 rounded-xl border border-border">
                        <span class="text-xs md:text-sm font-semibold text-secondary">Penyewa Aktif</span>
                        <span class="text-sm md:text-base font-bold text-primary">{{ $userAktif ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 md:p-3.5 bg-muted/50 rounded-xl border border-border">
                        <span class="text-xs md:text-sm font-semibold text-secondary">Pendaftar Baru (Bulan Ini)</span>
                        <span class="text-sm md:text-base font-bold text-success">+{{ $userBaru ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Analisis Kinerja -->
            <div class="bg-white rounded-2xl shadow-sm border border-border p-4 md:p-6">
                <h3 class="font-bold text-sm md:text-base text-foreground mb-4 flex items-center gap-2">
                    <i data-lucide="activity" class="size-4 md:size-5 text-warning-dark"></i> Analisis Kinerja
                </h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-xs md:text-sm mb-1.5">
                            <span class="font-medium text-secondary">Avg. Lama Menginap</span>
                            <span class="font-bold text-foreground">{{ $avgStay ?? 0 }} Bulan</span>
                        </div>
                        <div class="h-2 border border-border w-full bg-muted rounded-full overflow-hidden">
                            <div class="h-full bg-warning-dark rounded-full" style="width: 70%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs md:text-sm mb-1.5">
                            <span class="font-medium text-secondary">Booking Conversion</span>
                            <span class="font-bold text-success">{{ $conversionRate ?? 0 }}%</span>
                        </div>
                        <div class="h-2 border border-border w-full bg-muted rounded-full overflow-hidden">
                            <div class="h-full bg-success rounded-full" style="width: {{ $conversionRate ?? 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs md:text-sm mb-1.5">
                            <span class="font-medium text-secondary">Cancellation Rate</span>
                            <span class="font-bold text-error">{{ $cancellationRate ?? 0 }}%</span>
                        </div>
                        <div class="h-2 border border-border w-full bg-muted rounded-full overflow-hidden">
                            <div class="h-full bg-error rounded-full" style="width: {{ $cancellationRate ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Tempat Chart (Placeholder) -->
        <div class="lg:col-span-2 space-y-4 md:space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-border p-4 md:p-6 h-[250px] md:h-[300px] flex flex-col">
                <h3 class="font-bold text-sm md:text-base text-foreground mb-3 md:mb-4">Trend Pertumbuhan Booking</h3>
                <div class="flex-1 bg-muted/30 border border-dashed border-border rounded-xl flex flex-col items-center justify-center p-4 text-center">
                    <i data-lucide="line-chart" class="size-10 md:size-12 text-secondary/50 mb-2 md:mb-3"></i>
                    <p class="text-xs md:text-sm font-semibold text-secondary">Area Grafik Trend Booking</p>
                    <p class="text-[10px] md:text-xs text-secondary/70 mt-1">Implementasikan Chart.js di sini pada tahap lanjut.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-border p-4 md:p-6 h-[250px] md:h-[280px] flex flex-col">
                <h3 class="font-bold text-sm md:text-base text-foreground mb-3 md:mb-4">Distribusi Status Kamar Saat Ini</h3>
                <div class="flex-1 bg-muted/30 border border-dashed border-border rounded-xl flex flex-col items-center justify-center p-4 text-center">
                    <i data-lucide="pie-chart" class="size-10 md:size-12 text-secondary/50 mb-2 md:mb-3"></i>
                    <p class="text-xs md:text-sm font-semibold text-secondary">Area Grafik Pie/Doughnut</p>
                    <p class="text-[10px] md:text-xs text-secondary/70 mt-1">Perbandingan Terisi vs Tersedia vs Maintenance.</p>
                </div>
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