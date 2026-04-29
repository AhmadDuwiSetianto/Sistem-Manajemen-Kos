<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Dashboard') - Inna Kos</title>
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
                <a href="{{ route('home') ?? '#' }}" class="flex items-center gap-3">
                    <div class="w-11 h-9 bg-primary rounded-xl flex items-center justify-center shadow-sm shadow-primary/30">
                        <i data-lucide="home" class="size-5 text-white"></i>
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
                        <a href="{{ route('home') ?? '#' }}" class="group">
                            <div class="flex items-center rounded-xl p-3.5 gap-3 bg-white hover:bg-muted transition-all">
                                <i data-lucide="search" class="size-5 text-secondary group-hover:text-primary transition-colors"></i>
                                <span class="font-medium text-secondary group-hover:text-foreground">Cari Kamar</span>
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
                
                <div class="flex items-center gap-3 relative">
                    <button onclick="toggleDropdown('userDropdown')" class="hidden md:flex items-center gap-3 pl-3 ml-2 border-l border-border hover:opacity-80 transition-opacity">
                        <div class="text-right">
                            <p class="font-semibold text-foreground text-sm">{{ Auth::user()->name ?? 'Penghuni' }}</p>
                            <p class="text-secondary text-xs">{{ Auth::user()->isPenghuni() ? 'Warga Kos' : 'Calon Penghuni' }}</p>
                        </div>
                        <div class="size-10 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </div>
                        <i data-lucide="chevron-down" class="size-4 text-secondary"></i>
                    </button>

                    <button onclick="toggleDropdown('userDropdown')" class="md:hidden size-11 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </button>

                    <div id="userDropdown" class="hidden absolute top-14 right-0 w-56 bg-white border border-border rounded-2xl shadow-xl z-50 overflow-hidden py-2">
                        <div class="px-4 py-2 mb-2 border-b border-border md:hidden">
                            <p class="font-bold text-sm text-foreground">{{ Auth::user()->name ?? 'Penghuni' }}</p>
                            <p class="text-xs text-secondary">{{ Auth::user()->isPenghuni() ? 'Warga Kos' : 'Calon Penghuni' }}</p>
                        </div>
                        <a href="{{ route('user.profile') ?? '#' }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-muted transition-colors">
                            <i data-lucide="user" class="size-4 text-secondary"></i>
                            <span class="text-sm font-medium text-foreground">Profil Saya</span>
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
            lucide.createIcons();

            document.addEventListener('click', function(event) {
                const userDropdown = document.getElementById('userDropdown');
                if (!event.target.closest('#userDropdown') && !event.target.closest('[onclick="toggleDropdown(\'userDropdown\')"]')) {
                    userDropdown.classList.add('hidden');
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
            document.getElementById(id).classList.toggle('hidden');
        }
    </script>
    
    @stack('scripts')
</body>
</html>