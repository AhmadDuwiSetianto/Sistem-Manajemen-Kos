@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<style type="text/tailwindcss">
    :root {
        --primary: #165DFF;
        --primary-hover: #0E4BD9;
        --foreground: #080C1A;
        --secondary: #6A7686;
        --muted: #EFF2F7;
        --border: #F3F4F3;
        --success: #30B22D;
        --success-light: #DCFCE7;
        --error: #ED6B60;
        --error-light: #FEE2E2;
        --warning: #FED71F;
        --warning-light: #FEF9C3;
        --font-sans: 'Lexend Deca', sans-serif;
    }
    @theme inline {
        --color-primary: var(--primary);
        --color-primary-hover: var(--primary-hover);
        --color-foreground: var(--foreground);
        --color-secondary: var(--secondary);
        --color-muted: var(--muted);
        --color-border: var(--border);
        --color-success: var(--success);
        --color-success-light: var(--success-light);
        --color-error: var(--error);
        --color-error-light: var(--error-light);
        --color-warning: var(--warning);
        --color-warning-light: var(--warning-light);
        --font-sans: var(--font-sans);
    }
    body { font-family: var(--font-sans); }
</style>
@endpush

@section('content')
<div class="flex-1 p-5 md:p-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-foreground">Dashboard Overview</h1>
            <p class="text-secondary mt-1">Statistik real-time sistem manajemen Inna Kos</p>
        </div>
        <div class="flex items-center gap-3 bg-white p-3 rounded-2xl border border-border shadow-sm">
            <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center">
                <i data-lucide="clock" class="size-5 text-primary"></i>
            </div>
            <div class="text-right">
                <p class="text-xs text-secondary font-medium uppercase tracking-wider">Update Terakhir</p>
                <p class="text-sm font-bold text-foreground">{{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white hover:ring-1 hover:ring-primary transition-all duration-300">
            <div class="flex items-center gap-3">
                <div class="size-11 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="home" class="size-6 text-primary"></i>
                </div>
                <p class="font-medium text-secondary">Total Kamar</p>
            </div>
            <p class="font-bold text-3xl text-foreground">{{ $totalKamar ?? 0 }}</p>
        </div>

        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white hover:ring-1 hover:ring-success transition-all duration-300">
            <div class="flex items-center gap-3">
                <div class="size-11 bg-success-light rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="check-circle" class="size-6 text-success"></i>
                </div>
                <p class="font-medium text-secondary">Tersedia</p>
            </div>
            <p class="font-bold text-3xl text-foreground">{{ $kamarTersedia ?? 0 }}</p>
        </div>

        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white hover:ring-1 hover:ring-primary transition-all duration-300">
            <div class="flex items-center gap-3">
                <div class="size-11 bg-blue-50 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="users" class="size-6 text-blue-600"></i>
                </div>
                <p class="font-medium text-secondary">Penghuni</p>
            </div>
            <p class="font-bold text-3xl text-foreground">{{ $totalPenghuni ?? 0 }}</p>
        </div>

        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white hover:ring-1 hover:ring-warning transition-all duration-300">
            <div class="flex items-center gap-3">
                <div class="size-11 bg-warning-light rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="timer" class="size-6 text-orange-500"></i>
                </div>
                <p class="font-medium text-secondary">Pending Bookings</p>
            </div>
            <p class="font-bold text-3xl text-foreground">{{ $pendingBookingsCount ?? 0 }}</p>
        </div>

        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white hover:ring-1 hover:ring-primary transition-all duration-300">
            <div class="flex items-center gap-3">
                <div class="size-11 bg-muted rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="bed" class="size-6 text-foreground"></i>
                </div>
                <p class="font-medium text-secondary">Kamar Terisi</p>
            </div>
            <p class="font-bold text-3xl text-foreground">{{ $kamarTerisi ?? 0 }}</p>
        </div>

        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white hover:ring-1 hover:ring-success transition-all duration-300">
            <div class="flex items-center gap-3">
                <div class="size-11 bg-success-light rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="calendar-check" class="size-6 text-success"></i>
                </div>
                <p class="font-medium text-secondary">Confirmed</p>
            </div>
            <p class="font-bold text-3xl text-foreground">{{ $confirmedBookingsCount ?? 0 }}</p>
        </div>

        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white hover:ring-1 hover:ring-primary transition-all duration-300">
            <div class="flex items-center gap-3">
                <div class="size-11 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="key" class="size-6 text-primary"></i>
                </div>
                <p class="font-medium text-secondary">Checked In</p>
            </div>
            <p class="font-bold text-3xl text-foreground">{{ $checkedInBookingsCount ?? 0 }}</p>
        </div>

        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white hover:ring-1 hover:ring-error transition-all duration-300">
            <div class="flex items-center gap-3">
                <div class="size-11 bg-error-light rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="credit-card" class="size-6 text-error"></i>
                </div>
                <p class="font-medium text-secondary">Pending Pay</p>
            </div>
            <p class="font-bold text-3xl text-foreground">{{ $pendingPaymentsCount ?? 0 }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 flex flex-col rounded-2xl border border-border p-6 bg-white shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-bold text-lg text-foreground">Booking Terbaru</h3>
                    <p class="text-sm text-secondary">Aktivitas pemesanan kamar terkini</p>
                </div>
                <a href="{{ route('admin.booking.index') }}" class="px-4 py-2 rounded-xl bg-muted text-primary font-semibold text-sm hover:bg-primary hover:text-white transition-all">Lihat Semua</a>
            </div>
            
            <div class="flex flex-col gap-1">
                @forelse($recentBookings ?? [] as $booking)
                <div class="flex items-center gap-4 py-4 border-b border-border last:border-0 hover:bg-muted/30 -mx-2 px-2 rounded-xl transition-all">
                    <div class="size-11 bg-muted rounded-full flex items-center justify-center shrink-0 ring-1 ring-border text-secondary">
                        <i data-lucide="calendar"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <p class="font-semibold text-foreground text-sm truncate">{{ $booking->user->name ?? 'N/A' }}</p>
                            <span class="text-xs text-secondary shrink-0">{{ $booking->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-secondary">Kamar {{ $booking->kamar->nomor_kamar ?? 'N/A' }} • {{ $booking->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="shrink-0">
                        @php
                            $statusMap = [
                                'pending' => ['bg' => 'bg-warning-light', 'text' => 'text-orange-600'],
                                'confirmed' => ['bg' => 'bg-success-light', 'text' => 'text-success'],
                                'checked_in' => ['bg' => 'bg-primary/10', 'text' => 'text-primary'],
                                'completed' => ['bg' => 'bg-muted', 'text' => 'text-secondary'],
                                'cancelled' => ['bg' => 'bg-error-light', 'text' => 'text-error'],
                            ];
                            $currStatus = $statusMap[$booking->status] ?? ['bg' => 'bg-muted', 'text' => 'text-secondary'];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $currStatus['bg'] }} {{ $currStatus['text'] }}">
                            {{ str_replace('_', ' ', $booking->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-10">
                    <i data-lucide="calendar-x" class="size-10 text-muted mx-auto mb-3"></i>
                    <p class="text-secondary">Tidak ada data booking</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="flex flex-col rounded-2xl border border-border p-6 bg-white shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-lg text-foreground">User Baru</h3>
                <a href="{{ route('admin.user.index') }}" class="size-9 flex items-center justify-center rounded-xl hover:bg-muted transition-colors">
                    <i data-lucide="external-link" class="size-5 text-secondary"></i>
                </a>
            </div>
            
            <div class="flex flex-col gap-4">
                @forelse($recentUsers ?? [] as $user)
                <div class="flex items-center gap-3 p-3 rounded-2xl border border-border hover:border-primary/30 transition-all">
                    <div class="size-10 bg-primary/10 rounded-full flex items-center justify-center shrink-0 font-bold text-primary">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm text-foreground truncate">{{ $user->name }}</p>
                        <p class="text-xs text-secondary truncate">{{ $user->email }}</p>
                    </div>
                    @php
                        $roleMap = [
                            'admin' => 'bg-purple-100 text-purple-700',
                            'penghuni' => 'bg-green-100 text-green-700',
                            'calon_penghuni' => 'bg-blue-100 text-blue-700',
                        ];
                    @endphp
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $roleMap[$user->role] ?? 'bg-muted text-secondary' }}">
                        {{ $user->role == 'penghuni' ? 'Warga' : ($user->role == 'admin' ? 'Admin' : 'Calon') }}
                    </span>
                </div>
                @empty
                <p class="text-center text-secondary text-sm">Belum ada user.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="group bg-white rounded-3xl p-1 border border-border hover:shadow-xl transition-all duration-300">
            <div class="bg-primary rounded-[22px] p-6 text-white h-full relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="home" class="size-32"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Kelola Kamar</h3>
                <p class="text-white/80 text-sm mb-6 pr-10">Atur ketersediaan, harga, dan fasilitas kamar kos.</p>
                <a href="{{ route('admin.kamar.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-primary font-bold rounded-xl hover:bg-muted transition-colors shadow-lg shadow-primary/20">
                    <i data-lucide="door-open" class="size-4"></i> Masuk Menu
                </a>
            </div>
        </div>

        <div class="group bg-white rounded-3xl p-1 border border-border hover:shadow-xl transition-all duration-300">
            <div class="bg-success rounded-[22px] p-6 text-white h-full relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="bar-chart-3" class="size-32"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Laporan</h3>
                <p class="text-white/80 text-sm mb-6 pr-10">Pantau arus kas dan statistik penghuni bulanan.</p>
                <a href="{{ route('admin.laporan.keuangan') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-success font-bold rounded-xl hover:bg-muted transition-colors shadow-lg shadow-success/20">
                    <i data-lucide="trending-up" class="size-4"></i> Buka Laporan
                </a>
            </div>
        </div>

        <div class="group bg-white rounded-3xl p-1 border border-border hover:shadow-xl transition-all duration-300">
            <div class="bg-foreground rounded-[22px] p-6 text-white h-full relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="user-cog" class="size-32"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Manajemen User</h3>
                <p class="text-white/80 text-sm mb-6 pr-10">Kelola hak akses admin dan verifikasi calon penghuni.</p>
                <a href="{{ route('admin.user.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-foreground font-bold rounded-xl hover:bg-muted transition-colors shadow-lg">
                    <i data-lucide="users" class="size-4"></i> Kelola User
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    // Method untuk menampilkan halaman profil admin
    public function profile()
    {
        return view('admin.profile'); // Pastikan kamu membuat file resources/views/admin/profile.blade.php
    }

    // Method untuk mengupdate data profil admin (opsional jika ada form update)
    public function updateProfile(\Illuminate\Http\Request $request)
    {
        // Logika update profil (validasi & simpan)
        // ...
        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    // Method untuk melihat semua halaman notifikasi
    public function notifications()
    {
        $notifications = auth()->user()->notifications()->paginate(10);
        return view('admin.notifications.index', compact('notifications')); 
    }

    // Method untuk menandai semua notifikasi sudah dibaca
    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    // Method API untuk AJAX Polling Notifikasi
    public function latestNotifications()
    {
        $user = auth()->user();
        $unreadCount = $user->unreadNotifications->count();
        
        // Opsional: Mereturn HTML list notifikasi untuk di-replace secara otomatis (agar dinamis)
        $html = '';
        if($unreadCount > 0) {
            foreach($user->unreadNotifications->take(5) as $notification) {
                $typeClass = (isset($notification->data['type']) && $notification->data['type'] == 'payment') ? 'bg-success-light' : 'bg-primary/10';
                $iconClass = (isset($notification->data['type']) && $notification->data['type'] == 'payment') ? 'text-success' : 'text-primary';
                $icon = $notification->data['icon'] ?? 'bell';
                $title = $notification->data['title'] ?? 'Notifikasi Baru';
                $message = $notification->data['message'] ?? 'Ada pembaruan sistem.';
                $time = $notification->created_at->diffForHumans();
                $url = $notification->data['url'] ?? '#';

                $html .= '
                <a href="'.$url.'" class="flex gap-3 p-4 hover:bg-muted/50 border-b border-border transition-colors bg-primary/5">
                    <div class="size-9 rounded-full '.$typeClass.' flex items-center justify-center shrink-0">
                        <i data-lucide="'.$icon.'" class="size-4 '.$iconClass.'"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-foreground">'.$title.'</p>
                        <p class="text-xs text-secondary mt-0.5">'.$message.'</p>
                        <p class="text-[10px] text-secondary mt-1">'.$time.'</p>
                    </div>
                </a>';
            }
        } else {
            $html = '
            <div class="p-6 flex flex-col items-center justify-center text-center">
                <i data-lucide="bell-off" class="size-8 text-secondary/50 mb-2"></i>
                <p class="text-sm font-medium text-secondary">Belum ada notifikasi</p>
            </div>';
        }

        return response()->json([
            'unread_count' => $unreadCount,
            'html' => $html
        ]);
    }
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        
        // Stagger animation for cards
        const observerOptions = {
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.flex.flex-col.rounded-2xl').forEach((card) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.5s ease-out';
            observer.observe(card);
        });
    });
    
</script>
@endpush