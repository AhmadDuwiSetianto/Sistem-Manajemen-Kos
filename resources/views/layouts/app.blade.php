<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'MyKos Premium Living')</title>
    <link rel="shortcut icon" href="{{ asset('images/mykos.png') }}" type="image/png">
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff', 
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb', 
                            700: '#1d4ed8', 
                            800: '#1e40af', 
                            900: '#1e3a8a',
                        }
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(37, 99, 235, 0.1)',
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #F8FAFC; color: #334155; }
        .nav-blur { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
        .nav-link.active { color: #2563eb; font-weight: 700; }
    </style>
</head>
<body class="flex flex-col min-h-screen antialiased">

    <header class="fixed w-full top-0 z-50 nav-blur border-b border-slate-100 transition-all duration-300 shadow-sm">
        <div class="container mx-auto px-6 h-20 flex justify-between items-center">
            
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                @if(file_exists(public_path('images/mykos.png')))
                    <img src="{{ asset('images/mykos.png') }}" alt="MyKos Logo" class="h-10 w-auto object-contain group-hover:scale-105 transition-transform duration-300">
                @elseif(file_exists(public_path('images/logo.svg')))
                    <img src="{{ asset('images/logo.svg') }}" alt="MyKos Logo" class="h-10 w-auto object-contain group-hover:scale-105 transition-transform duration-300">
                @else
                    <div class="w-10 h-10 bg-brand-600 text-white rounded-xl flex items-center justify-center text-xl font-bold shadow-soft group-hover:scale-105 transition-transform duration-300">M</div>
                @endif
                <span class="text-2xl font-bold text-slate-800 tracking-tight">My<span class="text-brand-600">Kos</span></span>
            </a>

            <nav class="hidden md:flex gap-10 text-sm font-medium text-slate-500">
                <a href="{{ route('home') }}#home" class="nav-link hover:text-brand-600 transition py-2 {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ request()->routeIs('kamar.*') ? route('kamar.index') : route('home').'#kamar' }}" class="nav-link hover:text-brand-600 transition py-2 {{ request()->routeIs('kamar.*') ? 'active' : '' }}">Kamar</a>
                <a href="{{ route('home') }}#fasilitas" class="nav-link hover:text-brand-600 transition py-2">Fasilitas</a>
                <a href="{{ route('home') }}#lokasi" class="nav-link hover:text-brand-600 transition py-2">Lokasi</a>
            </nav>

            <div class="flex items-center gap-4">
                @auth
                    <div class="relative group h-20 flex items-center">
                        <button class="flex items-center gap-3 font-bold text-slate-700 hover:text-brand-600 transition focus:outline-none py-2">
                            <div class="w-9 h-9 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center border border-brand-200">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="text-left hidden sm:block leading-tight">
                                <div class="text-sm">{{ Auth::user()->name }}</div>
                                <div class="text-[10px] font-normal text-slate-400 uppercase">
                                    {{ Auth::user()->role == 'penghuni' ? 'Penghuni' : (Auth::user()->role == 'admin' ? 'Admin' : 'Member') }}
                                </div>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-slate-400 group-hover:rotate-180 transition-transform duration-300"></i>
                        </button>
                        
                        <div class="absolute right-0 top-[70%] pt-4 w-56 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right z-50">
                            
                            <div class="bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden ring-1 ring-black ring-opacity-5">
                                
                                <div class="px-4 py-3 border-b border-slate-50 sm:hidden">
                                    <p class="text-sm font-medium text-slate-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <div class="py-1">
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-brand-50 hover:text-brand-600 transition flex items-center gap-2">
                                            <i class="fas fa-tachometer-alt w-4"></i> Dashboard Admin
                                        </a>
                                    @elseif(Auth::user()->role === 'penghuni' || Auth::user()->hasActiveBooking())
                                        <a href="{{ route('user.dashboard') }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-brand-50 hover:text-brand-600 transition flex items-center gap-2">
                                            <i class="fas fa-home w-4"></i> Dashboard Saya
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('home') }}#kamar" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-brand-50 hover:text-brand-600 transition flex items-center gap-2">
                                        <i class="fas fa-search w-4"></i> Cari Kamar
                                    </a>
                                </div>

                                <div class="border-t border-slate-100"></div>

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2 font-medium">
                                        <i class="fas fa-sign-out-alt w-4"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-slate-600 font-bold text-sm hover:text-brand-600 transition">Masuk</a>
                    <a href="{{ route('register') }}" class="px-6 py-2.5 bg-brand-600 text-white text-sm font-bold rounded-full hover:bg-brand-700 transition shadow-soft hover:-translate-y-0.5 transform duration-200">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-grow pt-20">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-200 pt-16 pb-8">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                         @if(file_exists(public_path('images/mykos.png')))
                            <img src="{{ asset('images/mykos.png') }}" alt="MyKos" class="h-10 w-auto object-contain">
                        @elseif(file_exists(public_path('images/logo.svg')))
                            <img src="{{ asset('images/logo.svg') }}" alt="MyKos" class="h-10 w-auto object-contain">
                        @else
                            <div class="w-10 h-10 bg-brand-600 text-white rounded-lg flex items-center justify-center font-bold shadow-sm">M</div>
                        @endif
                        <span class="text-2xl font-bold text-slate-800">My<span class="text-brand-600">Kos</span></span>
                    </div>
                    <p class="text-slate-500 leading-relaxed max-w-sm text-sm">
                        Platform penyewaan kos modern dengan fasilitas terlengkap, pembayaran mudah, dan pelayanan terbaik untuk kenyamanan tempat tinggal Anda.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 mb-4">Navigasi</h4>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li><a href="{{ route('kamar.index') }}" class="hover:text-brand-600 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-brand-200"></i> Cari Kamar</a></li>
                        <li><a href="{{ route('home') }}#fasilitas" class="hover:text-brand-600 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-brand-200"></i> Fasilitas</a></li>
                        <li><a href="{{ route('home') }}#lokasi" class="hover:text-brand-600 transition flex items-center gap-2"><i class="fas fa-chevron-right text-xs text-brand-200"></i> Lokasi Kami</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 mb-4">Hubungi Kami</h4>
                    <ul class="space-y-3 text-sm text-slate-500">
                        <li class="flex items-start gap-3">
                            <i class="fab fa-whatsapp text-brand-600 w-5 mt-0.5 text-lg"></i> 
                            <span>0812-3456-7890 <br> <span class="text-xs text-slate-400">(Chat Only)</span></span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="far fa-envelope text-brand-600 w-5 text-lg"></i> 
                            <span>hello@mykos.id</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-center text-sm text-slate-400">
                    © 2025 MyKos Management. All rights reserved.
                </p>
                <div class="flex gap-4 text-slate-400">
                    <a href="#" class="hover:text-brand-600 transition"><i class="fab fa-instagram text-xl"></i></a>
                    <a href="#" class="hover:text-brand-600 transition"><i class="fab fa-facebook text-xl"></i></a>
                    <a href="#" class="hover:text-brand-600 transition"><i class="fab fa-tiktok text-xl"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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