<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Inna Kos Premium Living')</title>
    
    <link rel="shortcut icon" href="{{ asset('images/innakos.png') }}" type="image/png">
    
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Lexend Deca"', 'sans-serif'], 
                    },
                    colors: {
                        brand: {
                            50: '#e8f0ff', 100: '#d1e0ff', 500: '#3b7dff', 600: '#165DFF', 700: '#0E4BD9', 800: '#0a36a3', 900: '#082570',
                        },
                        slate: {
                            50: '#EFF2F7', 100: '#F3F4F3', 200: '#e2e8f0', 300: '#cbd5e1', 400: '#94a3b8', 500: '#6A7686', 600: '#475569', 700: '#334155', 800: '#080C1A', 900: '#0f172a',
                        },
                        success: { DEFAULT: '#10B981', light: '#D1FAE5' },
                        warning: { DEFAULT: '#F59E0B', light: '#FEF3C7', dark: '#B45309' },
                        error: { DEFAULT: '#EF4444', light: '#FEE2E2' }
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(22, 93, 255, 0.12)',
                        'floating': '0 10px 40px -10px rgba(8, 12, 26, 0.08)',
                    }
                }
            }
        }
    </script>
    
    <style>
        body { 
            background-color: #F8FAFC; 
            color: #080C1A; 
            -webkit-font-smoothing: antialiased;
        }
        .nav-blur { 
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(12px); 
            -webkit-backdrop-filter: blur(12px);
        }
        .nav-link.active { 
            color: #165DFF; 
            font-weight: 800; 
        }
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background-color: #165DFF;
            border-radius: 4px;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen antialiased selection:bg-brand-600 selection:text-white">

    <header class="fixed w-full top-0 z-50 nav-blur border-b border-slate-200/60 transition-all duration-300">
        <div class="container mx-auto px-4 sm:px-6 h-20 flex justify-between items-center">
            
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="flex items-center justify-center transition-transform duration-300 group-hover:-translate-y-0.5">
                    @if(file_exists(public_path('images/innakos.png')))
                        <img src="{{ asset('images/innakos.png') }}" alt="Inna Kos Logo" class="h-10 w-auto object-contain">
                    @else
                        <i data-lucide="home" class="size-8 text-brand-600"></i>
                    @endif
                </div>
                <span class="text-2xl font-black text-slate-800 tracking-tight">Inna<span class="text-brand-600">Kos</span></span>
            </a>

            <nav class="hidden md:flex gap-10 text-sm font-bold text-slate-500">
                <a href="{{ route('home') }}#home" class="nav-link relative hover:text-brand-600 transition-colors py-2 {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ request()->routeIs('kamar.*') ? route('kamar.index') : route('home').'#kamar' }}" class="nav-link relative hover:text-brand-600 transition-colors py-2 {{ request()->routeIs('kamar.*') ? 'active' : '' }}">Kamar</a>
                <a href="{{ route('home') }}#fasilitas" class="nav-link relative hover:text-brand-600 transition-colors py-2">Fasilitas</a>
                <a href="{{ route('home') }}#lokasi" class="nav-link relative hover:text-brand-600 transition-colors py-2">Lokasi</a>
            </nav>

            <div class="flex items-center gap-5">
                @auth
                    <div class="relative group h-20 flex items-center">
                        <button class="flex items-center gap-3 font-medium text-slate-700 hover:text-brand-600 transition focus:outline-none py-2 cursor-pointer">
                            <div class="text-right hidden sm:block leading-tight">
                                <div class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">
                                    {{ Auth::user()->role == 'penghuni' ? 'Penghuni' : (Auth::user()->role == 'admin' ? 'Admin' : 'Member') }}
                                </div>
                            </div>
                            
                            <div class="relative shrink-0">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-sm border border-slate-100">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=165DFF&color=fff&bold=true" class="w-10 h-10 rounded-full ring-2 ring-white shadow-sm border border-slate-100">
                                @endif
                                <span class="absolute bottom-0 right-0 w-3 h-3 bg-success border-2 border-white rounded-full"></span>
                            </div>
                            
                            <i data-lucide="chevron-down" class="size-4 text-slate-400 group-hover:rotate-180 transition-transform duration-300"></i>
                        </button>
                        
                        <div class="absolute right-0 top-[75%] pt-4 w-64 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right z-50">
                            <div class="bg-white rounded-2xl shadow-floating border border-slate-100 overflow-hidden ring-1 ring-black ring-opacity-5">
                                
                                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 sm:hidden">
                                    <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs font-medium text-slate-500 truncate mt-0.5">{{ Auth::user()->email }}</p>
                                </div>

                                <div class="p-2 space-y-1">
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-slate-600 rounded-xl hover:bg-brand-50 hover:text-brand-600 transition-colors group">
                                            <div class="size-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-white transition-colors">
                                                <i data-lucide="shield" class="size-4"></i>
                                            </div>
                                            Panel Admin
                                        </a>
                                    @elseif(Auth::user()->role === 'penghuni' || Auth::user()->hasActiveBooking())
                                        <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-slate-600 rounded-xl hover:bg-brand-50 hover:text-brand-600 transition-colors group">
                                            <div class="size-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-white transition-colors">
                                                <i data-lucide="layout-dashboard" class="size-4"></i>
                                            </div>
                                            Dashboard Saya
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('home') }}#kamar" class="flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-slate-600 rounded-xl hover:bg-brand-50 hover:text-brand-600 transition-colors group">
                                        <div class="size-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-white transition-colors">
                                            <i data-lucide="search" class="size-4"></i>
                                        </div>
                                        Cari Kamar Baru
                                    </a>

                                    <a href="{{ route('user.profile') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-slate-600 rounded-xl hover:bg-brand-50 hover:text-brand-600 transition-colors group">
                                        <div class="size-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-white transition-colors">
                                            <i data-lucide="user-cog" class="size-4"></i>
                                        </div>
                                        Pengaturan Profil
                                    </a>
                                </div>

                                <div class="border-t border-slate-100 mx-2 my-1"></div>

                                <div class="p-2">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-error rounded-xl hover:bg-error-light transition-colors cursor-pointer">
                                            <div class="size-8 rounded-lg bg-error-light flex items-center justify-center text-error">
                                                <i data-lucide="log-out" class="size-4"></i>
                                            </div>
                                            Keluar Aplikasi
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:block text-slate-600 font-bold text-sm hover:text-brand-600 transition cursor-pointer">Masuk</a>
                    <a href="{{ route('register') }}" class="px-6 py-2.5 bg-brand-600 text-white text-sm font-bold rounded-full hover:bg-brand-700 transition shadow-lg shadow-brand-600/30 hover:-translate-y-0.5 transform duration-200 cursor-pointer">Daftar Sekarang</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-grow pt-20">
        @yield('content')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();

            const isHomePage = document.getElementById('home');
            if (isHomePage) {
                const sections = document.querySelectorAll('section');
                const navLinks = document.querySelectorAll('.nav-link');

                window.addEventListener('scroll', () => {
                    let current = '';
                    sections.forEach(section => {
                        const sectionTop = section.offsetTop;
                        if (scrollY >= (sectionTop - 150)) {
                            current = section.getAttribute('id');
                        }
                    });

                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href').includes('#' + current)) {
                            link.classList.add('active');
                        }
                    });
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>