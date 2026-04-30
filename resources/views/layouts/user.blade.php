<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Dashboard') - Inna Kos</title>
    
    <!-- FAVICON UPDATE -->
    <link rel="shortcut icon" href="{{ asset('images/innakos.png') }}" type="image/png">
    
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script> 
    
    <!-- VITE UNTUK LARAVEL ECHO & REVERB -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
                <a href="{{ route('home') ?? '#' }}" class="flex items-center gap-2">
                    <!-- LOGO SIDEBAR TANPA BACKGROUND -->
                    <div class="w-12 h-10 shrink-0 flex items-center justify-center">
                        <img src="{{ asset('images/innakos.png') }}" alt="Inna Kos" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="font-bold text-xl leading-tight">Inna Kos</h1>
                        <p class="text-[10px] text-secondary font-medium uppercase tracking-wider">User Panel</p>
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
                        <a href="{{ route('user.dashboard') ?? '#' }}" class="group {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                            <div class="flex items-center rounded-xl p-3.5 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all">
                                <i data-lucide="layout-dashboard" class="size-5 text-secondary group-[.active]:text-primary group-hover:text-primary transition-colors"></i>
                                <span class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Dashboard</span>
                            </div>
                        </a>
                        <a href="{{ route('user.bookings') ?? '#' }}" class="group {{ request()->routeIs('user.bookings') ? 'active' : '' }}">
                            <div class="flex items-center rounded-xl p-3.5 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all">
                                <i data-lucide="calendar" class="size-5 text-secondary group-[.active]:text-primary group-hover:text-primary transition-colors"></i>
                                <span class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Booking Saya</span>
                            </div>
                        </a>
                        <a href="{{ route('user.pembayaran') ?? '#' }}" class="group {{ request()->routeIs('user.pembayaran') ? 'active' : '' }}">
                            <div class="flex items-center rounded-xl p-3.5 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all">
                                <i data-lucide="credit-card" class="size-5 text-secondary group-[.active]:text-primary group-hover:text-primary transition-colors"></i>
                                <span class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Riwayat Tagihan</span>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    <h3 class="font-medium text-xs text-secondary uppercase tracking-wider">Pengaturan</h3>
                    <div class="flex flex-col gap-1">
                        <a href="{{ route('user.profile') ?? '#' }}" class="group {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                            <div class="flex items-center rounded-xl p-3.5 gap-3 bg-white group-[.active]:bg-muted group-hover:bg-muted transition-all">
                                <i data-lucide="user" class="size-5 text-secondary group-[.active]:text-primary group-hover:text-primary transition-colors"></i>
                                <span class="font-medium text-secondary group-[.active]:font-semibold group-[.active]:text-primary group-hover:text-foreground">Profil Saya</span>
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
                </div>
                
                <div class="flex items-center gap-4 md:gap-5 relative">
                    
                    <!-- FITUR NOTIFIKASI BELL (REAL-TIME) -->
                    <div class="relative">
                        <button onclick="toggleDropdown('notificationDropdown')" class="relative size-10 flex items-center justify-center text-secondary hover:bg-muted rounded-full transition-colors">
                            <i data-lucide="bell" class="size-5"></i>
                            
                            <!-- Indikator Ping Real-Time -->
                            <span id="notif-indicator" class="{{ Auth::user()->unreadNotifications->count() > 0 ? 'flex' : 'hidden' }} absolute top-2 right-2 size-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-error opacity-75"></span>
                                <span class="relative inline-flex rounded-full size-2.5 bg-error border-2 border-white"></span>
                            </span>
                        </button>

                        <div id="notificationDropdown" class="hidden absolute top-14 right-0 w-[320px] bg-white border border-border rounded-2xl shadow-xl z-50 overflow-hidden">
                            <div class="px-4 py-3 border-b border-border flex justify-between items-center bg-slate-50">
                                <p class="font-bold text-sm text-foreground">Notifikasi</p>
                                <span class="text-[10px] font-bold text-primary" id="notif-count">{{ Auth::user()->unreadNotifications->count() }} Baru</span>
                            </div>
                            
                            <div id="notif-list" class="max-h-[300px] overflow-y-auto">
                                @forelse(Auth::user()->notifications()->take(5)->get() as $notification)
                                    <a href="{{ $notification->data['url'] ?? '#' }}" class="flex gap-3 px-4 py-3 hover:bg-muted/50 border-b border-border transition-colors {{ $notification->read_at ? 'opacity-70' : 'bg-primary/5' }}">
                                        <div class="size-8 rounded-full bg-{{ $notification->data['color'] ?? 'primary' }}-light text-{{ $notification->data['color'] ?? 'primary' }} flex items-center justify-center shrink-0">
                                            <i data-lucide="{{ $notification->data['icon'] ?? 'bell' }}" class="size-4"></i>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-foreground">{{ $notification->data['title'] }}</p>
                                            <p class="text-[11px] text-secondary mt-0.5 leading-snug">{{ $notification->data['message'] }}</p>
                                            <p class="text-[9px] text-secondary mt-1 font-medium">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <div id="empty-notif" class="p-6 text-center text-secondary">
                                        <i data-lucide="bell-off" class="size-6 mx-auto mb-2 opacity-50"></i>
                                        <p class="text-xs font-medium">Belum ada notifikasi.</p>
                                    </div>
                                @endforelse
                            </div>
                            <div class="p-2 border-t border-border bg-slate-50 text-center">
                                <a href="#" class="text-xs font-bold text-primary hover:underline">Lihat Semua Notifikasi</a>
                            </div>
                        </div>
                    </div>

                    <div class="h-8 w-px bg-border hidden md:block"></div>

                    <!-- DROPDOWN USER -->
                    <button onclick="toggleDropdown('userDropdown')" class="hidden md:flex items-center gap-3 hover:opacity-80 transition-opacity relative">
                        <div class="text-right">
                            <p class="font-semibold text-foreground text-sm">{{ Auth::user()->name ?? 'Penghuni' }}</p>
                            <p class="text-secondary text-xs">{{ Auth::user()->isPenghuni() ? 'Penghuni' : 'Calon Penghuni' }}</p>
                        </div>
                        <div class="size-10 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold shadow-sm shadow-primary/10">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </div>
                        <i data-lucide="chevron-down" class="size-4 text-secondary"></i>
                    </button>

                    <div id="userDropdown" class="hidden absolute top-14 right-0 w-56 bg-white border border-border rounded-2xl shadow-xl z-50 overflow-hidden py-2">
                        <a href="{{ route('home') ?? '#' }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-muted transition-colors">
                            <i data-lucide="home" class="size-4 text-secondary"></i>
                            <span class="text-sm font-medium text-foreground">Ke Landing Page</span>
                        </a>
                        <a href="{{ route('user.profile') ?? '#' }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-muted transition-colors">
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

            // LOGIKA LARAVEL ECHO (REVERB)
            const userId = {{ Auth::id() ?? 'null' }};
            if (userId && window.Echo) {
                window.Echo.private(`App.Models.User.${userId}`)
                    .notification((notification) => {
                        const indicator = document.getElementById('notif-indicator');
                        if (indicator) indicator.classList.replace('hidden', 'flex');

                        const emptyNotif = document.getElementById('empty-notif');
                        if (emptyNotif) emptyNotif.remove();

                        const countEl = document.getElementById('notif-count');
                        let count = parseInt(countEl.innerText) || 0;
                        countEl.innerText = (count + 1) + " Baru";

                        const notifList = document.getElementById('notif-list');
                        const newNotifHTML = `
                            <a href="${notification.url || '#'}" class="flex gap-3 px-4 py-3 hover:bg-muted/50 border-b border-border transition-colors bg-primary/5">
                                <div class="size-8 rounded-full bg-${notification.color}-light text-${notification.color} flex items-center justify-center shrink-0">
                                    <i data-lucide="${notification.icon}" class="size-4"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-foreground">${notification.title}</p>
                                    <p class="text-[11px] text-secondary mt-0.5 leading-snug">${notification.message}</p>
                                    <p class="text-[9px] text-primary mt-1 font-bold">Baru Saja</p>
                                </div>
                            </a>
                        `;
                        notifList.insertAdjacentHTML('afterbegin', newNotifHTML);
                        lucide.createIcons();
                    });
            }

            // Close dropdowns on outside click
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
            dropdowns.forEach(dropdownId => {
                const el = document.getElementById(dropdownId);
                if(dropdownId === id) {
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