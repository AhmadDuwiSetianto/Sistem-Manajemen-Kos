<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Penghuni') - InnaKos</title>
    <link rel="shortcut icon" href="{{ asset('images/innakos.png') }}" type="image/png">
    
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Lexend Deca"', 'sans-serif'] },
                    colors: {
                        primary: '#165DFF',
                        'primary-hover': '#0E4BD9',
                        foreground: '#080C1A',
                        secondary: '#6A7686',
                        muted: '#EFF2F7',
                        border: '#F3F4F3',
                        success: '#30B22D',
                        'success-light': '#DCFCE7',
                        error: '#ED6B60',
                        'error-light': '#FEE2E2',
                        warning: '#FED71F',
                        'warning-light': '#FEF9C3',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-muted/40 min-h-screen text-foreground antialiased flex flex-col">

    <nav class="bg-white border-b border-border sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-[72px]">
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shadow-sm shadow-primary/30">
                        <i data-lucide="home" class="w-5 h-5 text-white"></i>
                    </div>
                    <span class="font-bold text-xl tracking-tight hidden sm:block">Inna Kos <span class="text-primary">User</span></span>
                </a>

                <div class="flex items-center gap-4 h-full">
                    <a href="{{ route('home') }}" class="text-sm font-semibold text-secondary hover:text-primary transition-colors hidden md:flex items-center gap-2 h-full">
                        <i data-lucide="layout-template" class="w-4 h-4"></i> Ke Landing Page
                    </a>
                    
                    <div class="h-6 w-px bg-border hidden md:block"></div>
                    
                    <div class="relative group h-full flex items-center">
                        <button class="flex items-center gap-3 font-bold text-foreground hover:text-primary transition-colors focus:outline-none py-2 cursor-pointer">
                            <div class="text-right hidden sm:block leading-tight">
                                <div class="text-sm">{{ Auth::user()->name }}</div>
                                <div class="text-[10px] font-bold text-secondary uppercase tracking-wider mt-0.5">
                                    {{ str_replace('_', ' ', Auth::user()->role) }}
                                </div>
                            </div>
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=165DFF&color=fff" class="w-10 h-10 rounded-full ring-2 ring-white shadow-sm">
                            <i data-lucide="chevron-down" class="w-4 h-4 text-secondary group-hover:rotate-180 transition-transform duration-300"></i>
                        </button>
                        
                        <div class="absolute right-0 top-[80%] pt-4 w-60 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right z-50">
                            <div class="bg-white rounded-2xl shadow-xl border border-border overflow-hidden">
                                <div class="py-2">
                                    <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-5 py-2.5 text-sm font-semibold text-secondary hover:text-primary hover:bg-primary/5 transition-colors">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard Saya
                                    </a>
                                    <a href="{{ route('user.profile') }}" class="flex items-center gap-3 px-5 py-2.5 text-sm font-semibold text-secondary hover:text-primary hover:bg-primary/5 transition-colors">
                                        <i data-lucide="user" class="w-4 h-4"></i> Profil & Pengaturan
                                    </a>
                                </div>
                                <div class="border-t border-border my-1"></div>
                                <form action="{{ route('logout') }}" method="POST" class="py-1">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-5 py-2.5 text-sm font-bold text-error hover:bg-error-light transition-colors flex items-center gap-3 cursor-pointer">
                                        <i data-lucide="log-out" class="w-4 h-4"></i> Keluar Aplikasi
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow pt-8 pb-12">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-border py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-xs text-secondary font-medium">© {{ date('Y') }} MyKos Management. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>