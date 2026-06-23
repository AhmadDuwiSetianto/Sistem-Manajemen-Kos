<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <meta name="description" content="Sistem Manajemen Inna Kos Premium Living Pekalongan. Cari dan pesan kamar kos nyaman, aman, dan strategis.">
    <meta property="og:title" content="@yield('title', 'Inna Kos Premium Living')">
    <meta property="og:description" content="Manajemen pemesanan dan pembayaran Inna Kos secara online.">
    <meta property="og:image" content="{{ asset('images/innakos.png') }}">

    <title>@yield('title', 'Inna Kos Premium Living')</title>
    
    <link rel="shortcut icon" href="{{ asset('images/innakos.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- KEMBALI MENGGUNAKAN CDN AGAR DESAIN DIJAMIN AMAN -->
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

        /* Transisi Default */
        #main-header, .dynamic-text, .dynamic-logo, .dynamic-brand, .dynamic-btn, .dynamic-user, .dynamic-role, .dynamic-icon {
            transition: all 0.3s ease-in-out;
        }

        /* STATE 1: HEADER TRANSPARAN */
        .header-transparent {
            background-color: transparent !important;
            border-color: transparent !important;
            box-shadow: none !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }
        .header-transparent .dynamic-logo { color: #ffffff; }
        .header-transparent .dynamic-brand { color: #ffffff; } 
        .header-transparent .dynamic-text { color: rgba(255, 255, 255, 0.9); }
        .header-transparent .dynamic-text:hover { color: #ffffff; }
        .header-transparent .dynamic-btn { background-color: #ffffff; color: #165DFF; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .header-transparent .dynamic-btn:hover { background-color: #f8fafc; transform: translateY(-2px); }
        .header-transparent .dynamic-user { color: #ffffff; }
        .header-transparent .dynamic-role { color: rgba(255, 255, 255, 0.7); }
        .header-transparent .dynamic-icon { color: rgba(255, 255, 255, 0.9); }
        .header-transparent .nav-link.active { color: #ffffff; font-weight: 800; }
        .header-transparent .nav-link.active::after { background-color: #ffffff; }

        /* STATE 2: HEADER SCROLLED (ATAU BUKAN DI HOMEPAGE) */
        .header-scrolled {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border-bottom: 1px solid rgba(226, 232, 240, 0.6) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
        }
        .header-scrolled .dynamic-logo { color: #1e293b; }
        .header-scrolled .dynamic-brand { color: #165DFF; } 
        .header-scrolled .dynamic-text { color: #64748b; }
        .header-scrolled .dynamic-text:hover { color: #165DFF; }
        .header-scrolled .dynamic-btn { background-color: #165DFF; color: #ffffff; }
        .header-scrolled .dynamic-btn:hover { background-color: #0E4BD9; transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(22, 93, 255, 0.3); }
        .header-scrolled .dynamic-user { color: #1e293b; }
        .header-scrolled .dynamic-role { color: #94a3b8; }
        .header-scrolled .dynamic-icon { color: #94a3b8; }
        .header-scrolled .nav-link.active { color: #165DFF; font-weight: 800; }
        .header-scrolled .nav-link.active::after { background-color: #165DFF; }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 50%;
            transform: translateX(-50%);
            width: 0; 
            height: 3px;
            border-radius: 4px;
            transition: all 0.3s ease-in-out;
        }
        .nav-link.active::after { width: 20px; }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex flex-col min-h-screen antialiased selection:bg-brand-600 selection:text-white">

    <header id="main-header" class="fixed w-full top-0 z-50 header-transparent transition-all duration-300">
        <div class="container mx-auto px-4 sm:px-6 h-20 flex justify-between items-center">
            
            <!-- ARIA LABEL ACCESSIBILITY -->
            <a href="{{ route('home') }}" aria-label="Beranda Inna Kos" class="flex items-center gap-3 group lg:px-0 lg:py-0">
                <div class="flex items-center justify-center transition-transform duration-300 group-hover:-translate-y-0.5">
                    @if(file_exists(public_path('images/innakos.png')))
                        <img src="{{ asset('images/innakos.png') }}" alt="Inna Kos Logo" class="h-10 w-auto object-contain">
                    @else
                        <i data-lucide="home" class="size-8 dynamic-brand"></i>
                    @endif
                </div>
                <span class="text-2xl font-black tracking-tight dynamic-logo">Inna<span class="dynamic-brand">Kos</span></span>
            </a>

            @if(!request()->routeIs('booking.*'))
            <!-- ARIA LABEL ACCESSIBILITY -->
            <nav class="hidden md:flex gap-10 text-sm font-bold lg:px-0 lg:py-0" aria-label="Navigasi Utama">
                <a href="{{ route('home') }}#home" class="nav-link relative py-2 dynamic-text {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('home') }}#fasilitas" class="nav-link relative py-2 dynamic-text">Fasilitas</a>
                <a href="{{ request()->routeIs('kamar.*') ? route('kamar.index') : route('home').'#kamar' }}" class="nav-link relative py-2 dynamic-text {{ request()->routeIs('kamar.*') ? 'active' : '' }}">Kamar</a>
                <a href="{{ route('home') }}#lokasi" class="nav-link relative py-2 dynamic-text">Lokasi</a>
            </nav>
            @endif

            <div class="flex items-center gap-5 lg:px-0 lg:py-0">
                @auth
                    <div class="relative group h-12 lg:h-20 flex items-center">
                        <!-- ARIA LABEL ACCESSIBILITY -->
                        <button aria-label="Buka menu profil" aria-haspopup="true" aria-expanded="false" class="flex items-center gap-3 font-medium transition focus:outline-none py-2 cursor-pointer">
                            <div class="text-right hidden sm:block leading-tight">
                                <div class="text-sm font-bold dynamic-user">{{ Auth::user()->name }}</div>
                                <div class="text-[10px] font-bold uppercase tracking-wider mt-0.5 dynamic-role">
                                    {{ Auth::user()->role == 'penghuni' ? 'Penghuni' : (Auth::user()->role == 'admin' ? 'Admin' : 'Calon Penghuni') }}
                                </div>
                            </div>
                            
                            <div class="relative shrink-0">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Foto Profil" class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-sm border border-slate-100">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=165DFF&color=fff&bold=true" alt="Foto Profil Default" class="w-10 h-10 rounded-full ring-2 ring-white shadow-sm border border-slate-100">
                                @endif
                                <span class="absolute bottom-0 right-0 w-3 h-3 bg-success border-2 border-white rounded-full"></span>
                            </div>
                            
                            <i data-lucide="chevron-down" class="size-4 group-hover:rotate-180 transition-transform duration-300 dynamic-icon"></i>
                        </button>
                        
                        <div class="absolute right-0 top-[100%] lg:top-[75%] pt-4 w-64 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right z-50">
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
                                        <!-- ARIA LABEL ACCESSIBILITY -->
                                        <button aria-label="Keluar dari Aplikasi" type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-error rounded-xl hover:bg-error-light transition-colors cursor-pointer">
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
                    <a href="{{ route('login') }}" class="hidden sm:block font-bold text-sm cursor-pointer dynamic-text">Masuk</a>
                    <a href="{{ route('register') }}" class="px-6 py-2.5 text-sm font-bold rounded-full transition shadow-lg cursor-pointer dynamic-btn">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();

            const header = document.getElementById('main-header');
            
            // Cek apakah ini halaman beranda
            const isHomePage = {{ request()->routeIs('home') ? 'true' : 'false' }};
            
            function toggleHeader() {
                // Jika BUKAN beranda ATAU sudah di-scroll ke bawah, jadikan header Solid (tidak transparan)
                if (!isHomePage || window.scrollY > 50) {
                    header.classList.remove('header-transparent');
                    header.classList.add('header-scrolled');
                } else {
                    header.classList.add('header-transparent');
                    header.classList.remove('header-scrolled');
                }
            }

            toggleHeader();
            window.addEventListener('scroll', toggleHeader);

            // Logika Scroll Spy (Menandai Menu Aktif)
            if (isHomePage) {
                const sections = document.querySelectorAll('#home, #fasilitas, #kamar, #lokasi');
                const navLinks = document.querySelectorAll('.nav-link');

                window.addEventListener('scroll', () => {
                    let current = '';
                    sections.forEach(section => {
                        const sectionTop = section.offsetTop;
                        if (scrollY >= (sectionTop - 200)) {
                            current = section.getAttribute('id');
                        }
                    });

                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (current && link.getAttribute('href').includes('#' + current)) {
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