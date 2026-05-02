<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Inna Kos</title>
    
    <!-- FAVICON -->
    <link rel="shortcut icon" href="{{ asset('images/innakos.png') }}" type="image/png">
    
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <!-- VITE FOR REAL-TIME NOTIFICATIONS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/@tailwindcss/browser@4"></script> 
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
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        body { font-family: var(--font-sans); }
    </style>
    @stack('styles')
</head>
<body class="bg-muted min-h-screen overflow-x-hidden text-foreground">

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/80 z-40 hidden lg:hidden transition-opacity" onclick="toggleSidebar()"></div>

    <div class="flex h-screen max-h-screen flex-1 overflow-hidden">
        
        <aside id="sidebar" class="flex flex-col w-[280px] shrink-0 h-screen fixed inset-y-0 left-0 z-50 bg-white border-r border-border transform -translate-x-full lg:translate-x-0 transition-transform duration-300 shadow-xl lg:shadow-none">
            <div class="flex items-center justify-between border-b border-border h-[90px] px-5 gap-3 shrink-0">
                <a href="{{ route('admin.dashboard') ?? '#' }}" class="flex items-center gap-2">
                    <!-- LOGO SIDEBAR TANPA BACKGROUND -->
                    <div class="w-12 h-10 shrink-0 flex items-center justify-center">
                        <img src="{{ asset('images/innakos.png') }}" alt="Inna Kos" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="font-bold text-xl leading-tight">Inna Kos</h1>
                        <p class="text-[10px] text-secondary font-medium uppercase tracking-wider">Admin Panel</p>
                    </div>
                </a>
                <button onclick="toggleSidebar()" class="lg:hidden size-11 flex shrink-0 rounded-xl p-[10px] items-center justify-center hover:bg-muted transition-colors">
                    <i data-lucide="x" class="size-6 text-secondary"></i>
                </button>
            </div>

            <div class="flex flex-col p-5 pb-28 gap-6 overflow-y-auto flex-1 scrollbar-hide">
                <div class="flex flex-col gap-4">
                    <h3 class="font-medium text-xs text-secondary uppercase tracking-wider">Main Menu</h3>
                    <div class="flex flex-col gap-1">
                        <a href="{{ route('admin.dashboard') ?? '#' }}" class="group {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <div class="flex items-center rounded-xl p-3.5 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all">
                                <i data-lucide="layout-dashboard" class="size-5 text-secondary group-[.active]:text-primary group-hover:text-primary transition-colors"></i>
                                <span class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Dashboard</span>
                            </div>
                        </a>
                        <a href="{{ route('admin.kamar.index') ?? '#' }}" class="group {{ request()->routeIs('admin.kamar.*') ? 'active' : '' }}">
                            <div class="flex items-center rounded-xl p-3.5 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all">
                                <i data-lucide="bed" class="size-5 text-secondary group-[.active]:text-primary group-hover:text-primary transition-colors"></i>
                                <span class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Kelola Kamar</span>
                            </div>
                        </a>
                        <a href="{{ route('admin.user.index') ?? '#' }}" class="group {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                            <div class="flex items-center rounded-xl p-3.5 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all">
                                <i data-lucide="users" class="size-5 text-secondary group-[.active]:text-primary group-hover:text-primary transition-colors"></i>
                                <span class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Kelola User</span>
                            </div>
                        </a>
                        <a href="{{ route('admin.booking.index') ?? '#' }}" class="group {{ request()->routeIs('admin.booking.*') ? 'active' : '' }}">
                            <div class="flex items-center rounded-xl p-3.5 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all">
                                <i data-lucide="calendar" class="size-5 text-secondary group-[.active]:text-primary group-hover:text-primary transition-colors"></i>
                                <span class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Kelola Booking</span>
                            </div>
                        </a>
                        <a href="{{ route('admin.pembayaran.index') ?? '#' }}" class="group {{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                            <div class="flex items-center rounded-xl p-3.5 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all">
                                <i data-lucide="credit-card" class="size-5 text-secondary group-[.active]:text-primary group-hover:text-primary transition-colors"></i>
                                <span class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Pembayaran</span>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <h3 class="font-medium text-xs text-secondary uppercase tracking-wider">Laporan</h3>
                    <div class="flex flex-col gap-1">
                        <a href="{{ route('admin.laporan.keuangan') ?? '#' }}" class="group {{ request()->routeIs('admin.laporan.keuangan') ? 'active' : '' }}">
                            <div class="flex items-center rounded-xl p-3.5 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all">
                                <i data-lucide="pie-chart" class="size-5 text-secondary group-[.active]:text-primary group-hover:text-primary transition-colors"></i>
                                <span class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Keuangan</span>
                            </div>
                        </a>
                        <a href="{{ route('admin.laporan.statistik') ?? '#' }}" class="group {{ request()->routeIs('admin.laporan.statistik') ? 'active' : '' }}">
                            <div class="flex items-center rounded-xl p-3.5 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all">
                                <i data-lucide="bar-chart-2" class="size-5 text-secondary group-[.active]:text-primary group-hover:text-primary transition-colors"></i>
                                <span class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Statistik</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex-1 lg:ml-[280px] flex flex-col bg-white min-h-screen overflow-hidden transition-all duration-300">
            
            <header class="flex items-center justify-between w-full h-[90px] shrink-0 border-b border-border bg-white px-5 md:px-8 z-30">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="lg:hidden size-11 flex items-center justify-center rounded-xl border border-border hover:bg-muted transition-colors">
                        <i data-lucide="menu" class="size-5 text-foreground"></i>
                    </button>
                    <div class="hidden md:flex items-center gap-2 bg-muted rounded-xl px-4 py-2.5 w-72">
                        <i data-lucide="search" class="size-4 text-secondary"></i>
                        <input type="text" placeholder="Cari data..." class="bg-transparent border-none outline-none text-sm w-full placeholder:text-secondary text-foreground">
                    </div>
                </div>
                
                <div class="flex items-center gap-3 relative">
                    <!-- NOTIFICATION BELL (REAL-TIME) -->
                    <div class="relative">
                        <button onclick="toggleDropdown('notificationDropdown')" class="relative size-11 flex items-center justify-center rounded-xl border border-border hover:bg-muted transition-colors">
                            <i data-lucide="bell" class="size-5 text-secondary"></i>
                            <span id="notif-badge" class="{{ Auth::check() && Auth::user()->unreadNotifications->count() > 0 ? 'flex' : 'hidden' }} absolute top-2 right-2.5 size-2 bg-error rounded-full ring-2 ring-white"></span>
                        </button>

                        <div id="notificationDropdown" class="hidden absolute top-14 right-0 w-80 bg-white border border-border rounded-2xl shadow-xl z-50 overflow-hidden">
                            <div class="p-4 border-b border-border flex justify-between items-center bg-slate-50">
                                <h3 class="font-bold text-sm text-foreground">Notifikasi</h3>
                                <span class="text-[10px] font-bold text-primary" id="notif-count-text">{{ Auth::user()->unreadNotifications->count() }} Baru</span>
                            </div>
                            <div class="max-h-[300px] overflow-y-auto" id="notification-list">
                                @forelse(Auth::user()->unreadNotifications->take(5) as $notification)
                                    <a href="{{ $notification->data['url'] ?? '#' }}" class="flex gap-3 p-4 hover:bg-muted transition-colors border-b border-border bg-primary/5">
                                        <div class="size-9 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                            <i data-lucide="{{ $notification->data['icon'] ?? 'bell' }}" class="size-4 text-primary"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-foreground">{{ $notification->data['title'] ?? 'Pesanan Baru' }}</p>
                                            <p class="text-xs text-secondary mt-0.5 leading-snug">{{ $notification->data['message'] ?? 'Ada pesanan masuk.' }}</p>
                                            <p class="text-[10px] text-primary mt-1 font-semibold">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <div id="empty-notif" class="p-8 flex flex-col items-center justify-center text-center">
                                        <i data-lucide="bell-off" class="size-8 text-secondary/30 mb-2"></i>
                                        <p class="text-xs font-medium text-secondary">Belum ada notifikasi baru</p>
                                    </div>
                                @endforelse
                            </div>
                            <a href="{{ route('admin.notifications.index') ?? '#' }}" class="block p-3 text-center text-xs font-bold text-primary hover:bg-muted border-t border-border">Lihat Semua</a>
                        </div>
                    </div>

                    <button onclick="toggleDropdown('userDropdown')" class="hidden md:flex items-center gap-3 pl-3 ml-2 border-l border-border hover:opacity-80 transition-opacity relative">
                        <div class="text-right">
                            <p class="font-semibold text-foreground text-sm">{{ Auth::user()->name ?? 'Administrator' }}</p>
                            <p class="text-secondary text-xs">Super Admin</p>
                        </div>
                        <div class="size-10 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold shadow-sm shadow-primary/10">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <i data-lucide="chevron-down" class="size-4 text-secondary"></i>
                    </button>

                    <div id="userDropdown" class="hidden absolute top-14 right-0 w-56 bg-white border border-border rounded-2xl shadow-xl z-50 overflow-hidden py-2">
                        <a href="{{ route('admin.profile') ?? '#' }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-muted transition-colors">
                            <i data-lucide="user" class="size-4 text-secondary"></i>
                            <span class="text-sm font-medium text-foreground">Profil Saya</span>
                        </a>
                        <div class="my-1 border-t border-border"></div>
                        <form method="POST" action="{{ route('logout') ?? '#' }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-error-light transition-colors text-error cursor-pointer text-left">
                                <i data-lucide="log-out" class="size-4"></i>
                                <span class="text-sm font-medium">Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto bg-muted/30 p-4 md:p-6 lg:p-8">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();

            // === REAL-TIME NOTIFICATIONS (REVERB) ===
            const userId = {{ Auth::id() ?? 'null' }};
            if (userId && window.Echo) {
                window.Echo.private(`App.Models.User.${userId}`)
                    .notification((notification) => {
                        // 1. Show Red Badge
                        const badge = document.getElementById('notif-badge');
                        if (badge) badge.classList.remove('hidden');

                        // 2. Remove Empty State if exists
                        const emptyState = document.getElementById('empty-notif');
                        if (emptyState) emptyState.remove();

                        // 3. Update Text Count
                        const countText = document.getElementById('notif-count-text');
                        let currentCount = parseInt(countText.innerText) || 0;
                        countText.innerText = (currentCount + 1) + " Baru";

                        // 4. Prepend New Notification to List
                        const list = document.getElementById('notification-list');
                        const newNotifHTML = `
                            <a href="${notification.url || '#'}" class="flex gap-3 p-4 hover:bg-muted transition-colors border-b border-border bg-primary/5">
                                <div class="size-9 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                    <i data-lucide="${notification.icon || 'bell'}" class="size-4 text-primary"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-foreground">${notification.title}</p>
                                    <p class="text-xs text-secondary mt-0.5 leading-snug">${notification.message}</p>
                                    <p class="text-[10px] text-primary mt-1 font-bold">Baru Saja</p>
                                </div>
                            </a>
                        `;
                        list.insertAdjacentHTML('afterbegin', newNotifHTML);
                        lucide.createIcons(); // Refresh icons for new HTML
                    });
            }

            // Global Click to close dropdowns
            document.addEventListener('click', function(event) {
                const userDropdown = document.getElementById('userDropdown');
                const notifDropdown = document.getElementById('notificationDropdown');
                
                if (!event.target.closest('#userDropdown') && !event.target.closest('[onclick*="userDropdown"]')) {
                    if(userDropdown) userDropdown.classList.add('hidden');
                }
                if (!event.target.closest('#notificationDropdown') && !event.target.closest('[onclick*="notificationDropdown"]')) {
                    if(notifDropdown) notifDropdown.classList.add('hidden');
                }
            });
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden');
        }

        function toggleDropdown(id) {
            const dropdowns = ['userDropdown', 'notificationDropdown'];
            dropdowns.forEach(dId => {
                const el = document.getElementById(dId);
                if(dId === id) {
                    el.classList.toggle('hidden');
                } else {
                    el.classList.add('hidden');
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>