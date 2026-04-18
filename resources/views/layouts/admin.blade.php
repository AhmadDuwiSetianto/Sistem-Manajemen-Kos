<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Inna Kos</title>
    <link rel="shortcut icon" href="{{ asset('images/Inna Kos.png') }}" type="image/png">
    
   <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
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
                <a href="{{ route('admin.dashboard') ?? '#' }}" class="flex items-center gap-3">
                    <div class="w-11 h-9 bg-primary rounded-xl flex items-center justify-center shadow-sm shadow-primary/30">
                        <i data-lucide="home" class="size-5 text-white"></i>
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
                                <i data-lucide="users" class="size-5 text-secondary group-[.active]:text-primary group-hover:text-primary transition-colors"></i>
                                <span class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Kelola Booking</span>
                            </div>
                        </a>
                        <a href="{{ route('admin.pembayaran.index') ?? '#' }}" class="group {{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                            <div class="flex items-center rounded-xl p-3.5 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all">
                                <i data-lucide="users" class="size-5 text-secondary group-[.active]:text-primary group-hover:text-primary transition-colors"></i>
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

                <div class="flex flex-col gap-4">
                    <h3 class="font-medium text-xs text-secondary uppercase tracking-wider">Pengaturan</h3>
                    <div class="flex flex-col gap-1">
                        <a href="{{ route('admin.settings.index') ?? '#' }}" class="group {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                            <div class="flex items-center rounded-xl p-3.5 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all">
                                <i data-lucide="settings" class="size-5 text-secondary group-hover:text-primary transition-colors"></i>
                                <span class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Sistem</span>
                            </div>
                        </a>
                        <a href="#" class="group">
                            <div class="flex items-center rounded-xl p-3.5 gap-3 bg-white hover:bg-muted transition-all">
                                <i data-lucide="help-circle" class="size-5 text-secondary group-hover:text-primary transition-colors"></i>
                                <span class="font-medium text-secondary group-hover:text-foreground">Bantuan</span>
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
        <button onclick="toggleDropdown('notificationDropdown')" class="relative size-11 flex items-center justify-center rounded-xl border border-border hover:bg-muted transition-colors">
            <i data-lucide="bell" class="size-5 text-secondary"></i>
            @if(Auth::check() && Auth::user()->unreadNotifications->count() > 0)
                <span id="notif-badge" class="absolute top-2 right-2.5 size-2 bg-error rounded-full ring-2 ring-white"></span>
            @endif
        </button>

        <button onclick="toggleDropdown('userDropdown')" class="hidden md:flex items-center gap-3 pl-3 ml-2 border-l border-border hover:opacity-80 transition-opacity">
            <div class="text-right">
                <p class="font-semibold text-foreground text-sm">{{ Auth::user()->name ?? 'Administrator' }}</p>
                <p class="text-secondary text-xs">Super Admin</p>
            </div>
            <div class="size-10 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold">
                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
            </div>
            <i data-lucide="chevron-down" class="size-4 text-secondary"></i>
        </button>

        <button onclick="toggleDropdown('userDropdown')" class="md:hidden size-11 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold">
            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
        </button>

        <div id="notificationDropdown" class="hidden absolute top-14 right-14 w-80 bg-white border border-border rounded-2xl shadow-xl z-50 overflow-hidden">
            <div class="p-4 border-b border-border flex justify-between items-center">
                <h3 class="font-bold text-sm text-foreground">Notifikasi</h3>
                @if(Auth::check() && Auth::user()->unreadNotifications->count() > 0)
                    <form action="{{ route('admin.notifications.markAllRead') ?? '#' }}" method="POST" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="text-xs text-primary font-semibold cursor-pointer hover:underline">Tandai dibaca</button>
                    </form>
                @endif
            </div>
            <div class="max-h-[300px] overflow-y-auto" id="notification-list-container">
                @if(Auth::check() && Auth::user()->notifications->count() > 0)
                    @foreach(Auth::user()->unreadNotifications->take(5) as $notification)
                        <a href="{{ $notification->data['url'] ?? '#' }}" class="flex gap-3 p-4 hover:bg-muted/50 border-b border-border transition-colors {{ is_null($notification->read_at) ? 'bg-primary/5' : '' }}">
                            <div class="size-9 rounded-full {{ isset($notification->data['type']) && $notification->data['type'] == 'payment' ? 'bg-success-light' : 'bg-primary/10' }} flex items-center justify-center shrink-0">
                                <i data-lucide="{{ isset($notification->data['icon']) ? $notification->data['icon'] : 'bell' }}" class="size-4 {{ isset($notification->data['type']) && $notification->data['type'] == 'payment' ? 'text-success' : 'text-primary' }}"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-foreground">{{ $notification->data['title'] ?? 'Notifikasi Baru' }}</p>
                                <p class="text-xs text-secondary mt-0.5">{{ $notification->data['message'] ?? 'Ada pembaruan sistem.' }}</p>
                                <p class="text-[10px] text-secondary mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div class="p-6 flex flex-col items-center justify-center text-center">
                        <i data-lucide="bell-off" class="size-8 text-secondary/50 mb-2"></i>
                        <p class="text-sm font-medium text-secondary">Belum ada notifikasi</p>
                    </div>
                @endif
            </div>
            <a href="{{ route('admin.notifications.index') ?? '#' }}" class="block p-3 text-center text-sm font-semibold text-primary hover:bg-muted transition-colors">Lihat Semua</a>
        </div>

        <div id="userDropdown" class="hidden absolute top-14 right-0 w-56 bg-white border border-border rounded-2xl shadow-xl z-50 overflow-hidden py-2">
            <div class="px-4 py-2 mb-2 border-b border-border md:hidden">
                <p class="font-bold text-sm text-foreground">{{ Auth::user()->name ?? 'Administrator' }}</p>
                <p class="text-xs text-secondary">Super Admin</p>
            </div>
            <a href="{{ route('admin.profile') ?? '#' }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-muted transition-colors">
                <i data-lucide="user" class="size-4 text-secondary"></i>
                <span class="text-sm font-medium text-foreground">Profil Saya</span>
            </a>
            <a href="{{ route('admin.settings.index') ?? '#' }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-muted transition-colors">
                <i data-lucide="settings" class="size-4 text-secondary"></i>
                <span class="text-sm font-medium text-foreground">Pengaturan</span>
            </a>
            <div class="my-1 border-t border-border"></div>
            <form method="POST" action="{{ route('logout') ?? '#' }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-error-light transition-colors text-error cursor-pointer">
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
            // Initialize Lucide Icons
            lucide.createIcons();

            // Handle Dropdown close when clicking outside
            document.addEventListener('click', function(event) {
                const userDropdown = document.getElementById('userDropdown');
                const notifDropdown = document.getElementById('notificationDropdown');
                
                if (!event.target.closest('#userDropdown') && !event.target.closest('[onclick="toggleDropdown(\'userDropdown\')"]')) {
                    userDropdown.classList.add('hidden');
                }
                
                if (!event.target.closest('#notificationDropdown') && !event.target.closest('[onclick="toggleDropdown(\'notificationDropdown\')"]')) {
                    notifDropdown.classList.add('hidden');
                }
            });
        });

        // Toggle Sidebar Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            document.body.classList.toggle('overflow-hidden'); // Prevent scrolling when sidebar open
        }

        // Toggle Dropdowns
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            const allDropdowns = ['userDropdown', 'notificationDropdown'];
            
            // Close other dropdowns
            allDropdowns.forEach(d => {
                if (d !== id) document.getElementById(d).classList.add('hidden');
            });
            
            // Toggle target dropdown
            dropdown.classList.toggle('hidden');
        }
    </script>
    
    @stack('scripts')
</body>
</html>