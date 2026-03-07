<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Inna Kos Premium Living')</title>
    <link rel="shortcut icon" href="{{ asset('images/innakos.png') }}" type="image/png">
    
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Lexend Deca"', 'sans-serif'], // Font selaras
                    },
                    colors: {
                        brand: {
                            50: '#e8f0ff', 
                            100: '#d1e0ff',
                            500: '#3b7dff',
                            600: '#165DFF', // Primary NexusCRM
                            700: '#0E4BD9', // Primary Hover NexusCRM
                            800: '#0a36a3', 
                            900: '#082570',
                        },
                        slate: {
                            50: '#EFF2F7',  // Muted NexusCRM
                            100: '#F3F4F3', // Border NexusCRM
                            200: '#e2e8f0', 
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#6A7686', // Secondary NexusCRM
                            600: '#475569',
                            700: '#334155',
                            800: '#080C1A', // Foreground / Text Dark NexusCRM
                            900: '#0f172a',
                        }
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
            background: rgba(255, 255, 255, 0.85); 
            backdrop-filter: blur(12px); 
            -webkit-backdrop-filter: blur(12px);
        }
        .nav-link.active { 
            color: #165DFF; 
            font-weight: 700; 
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
<body class="flex flex-col min-h-screen antialiased">

    <header class="fixed w-full top-0 z-50 nav-blur border-b border-slate-100 transition-all duration-300">
        <div class="container mx-auto px-6 h-20 flex justify-between items-center">
            
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="flex items-center justify-center transition-transform duration-300 group-hover:-translate-y-0.5">
                    @if(file_exists(public_path('images/innakos.png')))
                        <img src="{{ asset('images/innakos.png') }}" alt="Inna Kos Logo" class="h-10 w-auto object-contain">
                    @elseif(file_exists(public_path('images/logo.svg')))
                        <img src="{{ asset('images/logo.svg') }}" alt="Inna Kos Logo" class="h-10 w-auto object-contain">
                    @else
                        <i data-lucide="home" class="w-8 h-8 text-brand-600"></i>
                    @endif
                </div>
                <span class="text-2xl font-extrabold text-slate-800 tracking-tight">Inna<span class="text-brand-600">Kos</span></span>
            </a>

            <nav class="hidden md:flex gap-10 text-sm font-medium text-slate-500">
                <a href="{{ route('home') }}#home" class="nav-link relative hover:text-brand-600 transition py-2 {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ request()->routeIs('kamar.*') ? route('kamar.index') : route('home').'#kamar' }}" class="nav-link relative hover:text-brand-600 transition py-2 {{ request()->routeIs('kamar.*') ? 'active' : '' }}">Kamar</a>
                <a href="{{ route('home') }}#fasilitas" class="nav-link relative hover:text-brand-600 transition py-2">Fasilitas</a>
                <a href="{{ route('home') }}#lokasi" class="nav-link relative hover:text-brand-600 transition py-2">Lokasi</a>
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
                                <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                            </div>
                            
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 group-hover:rotate-180 transition-transform duration-300"></i>
                        </button>
                        
                        <div class="absolute right-0 top-[75%] pt-4 w-64 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right z-50">
                            <div class="bg-white rounded-2xl shadow-floating border border-slate-100 overflow-hidden ring-1 ring-black ring-opacity-5">
                                
                                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 sm:hidden">
                                    <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-slate-500 truncate mt-0.5">{{ Auth::user()->email }}</p>
                                </div>

                                <div class="p-2 space-y-1">
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold text-slate-600 rounded-xl hover:bg-brand-50 hover:text-brand-600 transition-colors group">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-white transition-colors">
                                                <i data-lucide="shield" class="w-4 h-4"></i>
                                            </div>
                                            Panel Admin
                                        </a>
                                    @elseif(Auth::user()->role === 'penghuni' || Auth::user()->hasActiveBooking())
                                        <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold text-slate-600 rounded-xl hover:bg-brand-50 hover:text-brand-600 transition-colors group">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-white transition-colors">
                                                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                            </div>
                                            Dashboard Saya
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('home') }}#kamar" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold text-slate-600 rounded-xl hover:bg-brand-50 hover:text-brand-600 transition-colors group">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-white transition-colors">
                                            <i data-lucide="search" class="w-4 h-4"></i>
                                        </div>
                                        Cari Kamar Baru
                                    </a>

                                    <a href="{{ route('user.profile') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-semibold text-slate-600 rounded-xl hover:bg-brand-50 hover:text-brand-600 transition-colors group">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 group-hover:bg-white transition-colors">
                                            <i data-lucide="user-cog" class="w-4 h-4"></i>
                                        </div>
                                        Pengaturan Profil
                                    </a>
                                </div>

                                <div class="border-t border-slate-100 mx-2 my-1"></div>

                                <div class="p-2">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-red-600 rounded-xl hover:bg-red-50 transition-colors cursor-pointer">
                                            <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-500">
                                                <i data-lucide="log-out" class="w-4 h-4"></i>
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
                    <a href="{{ route('register') }}" class="px-6 py-2.5 bg-brand-600 text-white text-sm font-bold rounded-full hover:bg-brand-700 transition shadow-soft hover:-translate-y-0.5 transform duration-200 cursor-pointer">Daftar Sekarang</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-grow pt-20">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-200 pt-16 pb-8 mt-auto">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                
                <div class="col-span-1 md:col-span-2 shrink-0">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="flex items-center justify-center">
                            @if(file_exists(public_path('images/innakos.png')))
                                <img src="{{ asset('images/innakos.png') }}" alt="Inna Kos Logo" class="h-10 w-auto object-contain">
                            @elseif(file_exists(public_path('images/logo.svg')))
                                <img src="{{ asset('images/logo.svg') }}" alt="Inna Kos Logo" class="h-10 w-auto object-contain">
                            @else
                                <i data-lucide="home" class="w-8 h-8 text-brand-600"></i>
                            @endif
                        </div>
                        <span class="text-2xl font-extrabold text-slate-800 tracking-tight">My<span class="text-brand-600">Kos</span></span>
                    </div>
                    <p class="text-slate-500 leading-relaxed max-w-sm text-sm font-medium">
                        Platform penyewaan kos modern dengan fasilitas terlengkap, pembayaran mudah, dan pelayanan terbaik untuk kenyamanan tempat tinggal Anda.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-bold text-slate-800 mb-5">Jelajahi</h4>
                    <ul class="space-y-3 text-sm font-medium text-slate-500">
                        <li><a href="{{ route('kamar.index') }}" class="hover:text-brand-600 transition inline-flex items-center gap-2"><i data-lucide="chevron-right" class="w-3.5 h-3.5 text-brand-400"></i> Cari Kamar</a></li>
                        <li><a href="{{ route('home') }}#fasilitas" class="hover:text-brand-600 transition inline-flex items-center gap-2"><i data-lucide="chevron-right" class="w-3.5 h-3.5 text-brand-400"></i> Fasilitas Kami</a></li>
                        <li><a href="{{ route('home') }}#lokasi" class="hover:text-brand-600 transition inline-flex items-center gap-2"><i data-lucide="chevron-right" class="w-3.5 h-3.5 text-brand-400"></i> Peta Lokasi</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold text-slate-800 mb-5">Hubungi Kami</h4>
                    <ul class="space-y-4 text-sm font-medium text-slate-500">
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div>
                                <span class="text-slate-800 font-bold block mb-0.5">0812-3456-7890</span>
                                <span class="text-[11px] text-slate-400 uppercase tracking-wider font-bold">Layanan Chat 24/7</span>
                            </div>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                                <i data-lucide="mail" class="w-4 h-4"></i>
                            </div>
                            <span class="text-slate-600 font-semibold">hello@Inna Kos.id</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-slate-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-center text-sm font-medium text-slate-400">
                    &copy; {{ date('Y') }} Inna Kos Management. Hak Cipta Dilindungi.
                </p>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:bg-brand-50 hover:text-brand-600 flex items-center justify-center transition-all"><i class="fab fa-instagram text-lg"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:bg-brand-50 hover:text-brand-600 flex items-center justify-center transition-all"><i class="fab fa-facebook-f text-lg"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-50 text-slate-400 hover:bg-brand-50 hover:text-brand-600 flex items-center justify-center transition-all"><i class="fab fa-tiktok text-lg"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Render Lucide Icons
            lucide.createIcons();

            // ScrollSpy untuk Navbar Active State
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