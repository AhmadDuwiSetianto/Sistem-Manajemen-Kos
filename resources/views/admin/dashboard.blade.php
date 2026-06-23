@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="flex-1 p-4 md:p-8">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-foreground">Dashboard Overview</h1>
            <p class="text-secondary mt-1 text-sm md:text-base">Statistik real-time manajemen Inna Kos</p>
        </div>
        <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-border shadow-sm w-max">
            <div class="size-10 bg-primary/10 rounded-lg flex items-center justify-center shrink-0">
                <i data-lucide="clock" class="size-5 text-primary"></i>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-secondary font-bold uppercase tracking-wider">Update Terakhir</p>
                <p class="text-xs font-bold text-foreground">{{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4 mb-8">
        
        <!-- Card 1: Total Kamar -->
        <div class="flex flex-col justify-between rounded-xl border border-border p-4 bg-white hover:ring-1 hover:ring-primary transition-all shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="size-9 bg-primary/10 rounded-lg flex items-center justify-center shrink-0">
                    <i data-lucide="home" class="size-4 text-primary"></i>
                </div>
                <p class="font-medium text-secondary text-xs leading-tight">Total<br>Kamar</p>
            </div>
            <p class="font-black text-2xl text-foreground">{{ $totalKamar ?? 0 }}</p>
        </div>

        <!-- Card 2: Tersedia -->
        <div class="flex flex-col justify-between rounded-xl border border-border p-4 bg-white hover:ring-1 hover:ring-success transition-all shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="size-9 bg-success-light rounded-lg flex items-center justify-center shrink-0">
                    <i data-lucide="check-circle" class="size-4 text-success"></i>
                </div>
                <p class="font-medium text-secondary text-xs leading-tight">Kamar<br>Tersedia</p>
            </div>
            <p class="font-black text-2xl text-foreground">{{ $kamarTersedia ?? 0 }}</p>
        </div>

        <!-- Card 3: Total Pendapatan -->
        <div class="flex flex-col justify-between rounded-xl border border-border p-4 bg-white hover:ring-1 hover:ring-primary transition-all shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="size-9 bg-blue-50 rounded-lg flex items-center justify-center shrink-0">
                    <i data-lucide="dollar-sign" class="size-4 text-blue-600"></i>
                </div>
                <p class="font-medium text-secondary text-xs leading-tight">Total<br>Pendapatan</p>
            </div>
            <p class="font-black text-xl text-foreground tracking-tight">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</p>
        </div>

        <!-- Card 4: Pending Bookings -->
        <div class="flex flex-col justify-between rounded-xl border border-border p-4 bg-white hover:ring-1 hover:ring-warning transition-all shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="size-9 bg-warning-light rounded-lg flex items-center justify-center shrink-0">
                    <i data-lucide="timer" class="size-4 text-orange-500"></i>
                </div>
                <p class="font-medium text-secondary text-xs leading-tight">Pending<br>Bookings</p>
            </div>
            <p class="font-black text-2xl text-foreground">{{ $pendingBookingsCount ?? 0 }}</p>
        </div>

        <!-- Card 5: Kamar Terisi -->
        <div class="flex flex-col justify-between rounded-xl border border-border p-4 bg-white hover:ring-1 hover:ring-foreground transition-all shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="size-9 bg-muted rounded-lg flex items-center justify-center shrink-0">
                    <i data-lucide="bed" class="size-4 text-foreground"></i>
                </div>
                <p class="font-medium text-secondary text-xs leading-tight">Kamar<br>Terisi</p>
            </div>
            <p class="font-black text-2xl text-foreground">{{ $kamarTerisi ?? 0 }}</p>
        </div>

        <!-- Card 6: Pending Pay -->
        <div class="flex flex-col justify-between rounded-xl border border-border p-4 bg-white hover:ring-1 hover:ring-error transition-all shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="size-9 bg-error-light rounded-lg flex items-center justify-center shrink-0">
                    <i data-lucide="credit-card" class="size-4 text-error"></i>
                </div>
                <p class="font-medium text-secondary text-xs leading-tight">Pending<br>Pay</p>
            </div>
            <p class="font-black text-2xl text-foreground">{{ $pendingPaymentsCount ?? 0 }}</p>
        </div>
    </div>

    <!-- Section: Chart & Booking Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Grafik Pendapatan -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-border p-5 shadow-sm overflow-hidden flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-base md:text-lg text-foreground">Grafik Pendapatan</h3>
                    <p class="text-xs text-secondary mt-1">Statistik pendapatan 6 bulan terakhir</p>
                </div>
                <a href="{{ route('admin.laporan.keuangan') ?? '#' }}" class="px-3 py-1.5 rounded-lg bg-muted text-primary font-semibold text-xs hover:bg-primary hover:text-white transition-all">
                    Lihat Laporan
                </a>
            </div>
            
            <!-- Canvas untuk Chart.js -->
            <div class="relative w-full flex-1 min-h-[250px]">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Booking Terbaru (Pindah ke Card Kanan) -->
        <div class="bg-white rounded-2xl border border-border p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-base md:text-lg text-foreground">Booking Terbaru</h3>
                <a href="{{ route('admin.booking.index') ?? '#' }}" class="text-secondary hover:text-primary transition-colors">
                    <i data-lucide="external-link" class="size-4"></i>
                </a>
            </div>
            
            <div class="flex flex-col gap-3">
                @forelse($recentBookings ?? [] as $booking)
                <div class="flex items-center gap-3 p-2.5 rounded-xl border border-border bg-slate-50/50">
                    <div class="size-9 bg-primary/10 rounded-full flex items-center justify-center shrink-0 text-primary">
                        <i data-lucide="calendar-check" class="size-4"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center mb-1">
                            <p class="font-bold text-xs text-foreground truncate">{{ $booking->user->name ?? 'N/A' }}</p>
                            <span class="text-[10px] text-secondary whitespace-nowrap">{{ $booking->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-[10px] text-secondary font-medium">Kamar {{ $booking->kamar->nomor_kamar ?? 'N/A' }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i data-lucide="inbox" class="size-8 text-slate-300 mx-auto mb-2"></i>
                    <p class="text-xs text-secondary">Belum ada booking baru.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Links Menu -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-primary rounded-2xl p-5 text-white relative overflow-hidden">
            <i data-lucide="home" class="absolute -right-4 -bottom-4 size-24 opacity-10"></i>
            <h3 class="text-lg font-bold mb-1">Kelola Kamar</h3>
            <p class="text-white/80 text-xs mb-4">Atur ketersediaan & harga.</p>
            <a href="{{ route('admin.kamar.index') ?? '#' }}" class="inline-flex items-center gap-2 px-3 py-2 bg-white text-primary text-xs font-bold rounded-lg hover:bg-muted transition-colors">
                <i data-lucide="door-open" class="size-3"></i> Masuk Menu
            </a>
        </div>

        <div class="bg-success rounded-2xl p-5 text-white relative overflow-hidden">
            <i data-lucide="bar-chart-3" class="absolute -right-4 -bottom-4 size-24 opacity-10"></i>
            <h3 class="text-lg font-bold mb-1">Laporan</h3>
            <p class="text-white/80 text-xs mb-4">Pantau arus kas Inna Kos.</p>
            <a href="{{ route('admin.laporan.keuangan') ?? '#' }}" class="inline-flex items-center gap-2 px-3 py-2 bg-white text-success text-xs font-bold rounded-lg hover:bg-muted transition-colors">
                <i data-lucide="trending-up" class="size-3"></i> Buka Laporan
            </a>
        </div>

        <div class="bg-foreground rounded-2xl p-5 text-white relative overflow-hidden">
            <i data-lucide="user-cog" class="absolute -right-4 -bottom-4 size-24 opacity-10"></i>
            <h3 class="text-lg font-bold mb-1">Manajemen User</h3>
            <p class="text-white/80 text-xs mb-4">Kelola data calon penghuni.</p>
            <a href="{{ route('admin.user.index') ?? '#' }}" class="inline-flex items-center gap-2 px-3 py-2 bg-white text-foreground text-xs font-bold rounded-lg hover:bg-muted transition-colors">
                <i data-lucide="users" class="size-3"></i> Kelola User
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Library Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();

        // --- Konfigurasi Grafik Pendapatan ---
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Gradient untuk memberikan efek warna elegan di bawah garis
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(22, 93, 255, 0.2)'); // Warna primary dengan opacity
        gradient.addColorStop(1, 'rgba(22, 93, 255, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                // DIKIRIM LANGSUNG DARI CONTROLLER
                labels: {!! json_encode($chartLabels ?? []) !!}, 
                datasets: [{
                    label: 'Pendapatan',
                    data: {!! json_encode($chartData ?? []) !!}, 
                    borderColor: '#165DFF', // Warna garis (--primary)
                    backgroundColor: gradient,
                    borderWidth: 3,
                    tension: 0.4, // Membuat garis melengkung (smooth)
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#165DFF',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Menyembunyikan legend karena hanya ada 1 dataset
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#F3F4F3', // Warna border/grid line
                            drawBorder: false,
                        },
                        ticks: {
                            color: '#6A7686', // Warna teks
                            font: {
                                size: 11,
                                family: "'Lexend Deca', sans-serif"
                            },
                            callback: function(value) {
                                // Memformat angka Y axis (Contoh: 5Jt, 10Jt)
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000) + ' Jt';
                                }
                                return value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false, // Menghilangkan garis vertikal
                            drawBorder: false,
                        },
                        ticks: {
                            color: '#6A7686',
                            font: {
                                size: 11,
                                family: "'Lexend Deca', sans-serif"
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush