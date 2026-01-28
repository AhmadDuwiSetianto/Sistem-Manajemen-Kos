<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - MyKos</title>
    <link rel="shortcut icon" href="{{ asset('images/mykos.png') }}" type="image/png">
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        },
                        success: '#10b981',
                        warning: '#f59e0b',
                        danger: '#ef4444'
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-in-out',
                        'slide-down': 'slideDown 0.2s ease-out',
                        'slide-in-left': 'slideInLeft 0.3s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideDown: {
                            '0%': { opacity: '0', transform: 'translateY(-10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        slideInLeft: {
                            '0%': { transform: 'translateX(-100%)' },
                            '100%': { transform: 'translateX(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar {
            transition: all 0.3s ease;
            box-shadow: 4px 0 6px -1px rgba(0, 0, 0, 0.1);
        }
        .sidebar.collapsed {
            width: 70px;
        }
        .sidebar.collapsed .sidebar-content {
            opacity: 0;
            pointer-events: none;
        }
        .sidebar.collapsed .logo-text,
        .sidebar.collapsed .nav-text,
        .sidebar.collapsed .section-text {
            opacity: 0;
            width: 0;
            height: 0;
            overflow: hidden;
        }
        .sidebar.collapsed .nav-item {
            justify-content: center;
            padding: 12px;
            margin: 4px 8px;
        }
        .sidebar.collapsed .nav-icon {
            margin-right: 0;
            transform: scale(1.1);
        }
        .sidebar.collapsed .user-info {
            opacity: 0;
            width: 0;
            height: 0;
            overflow: hidden;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 50;
                height: 100vh;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0 !important;
            }
        }
        .nav-item {
            position: relative;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 2px 8px;
        }
        .nav-item.active {
            background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%);
            border-right: 3px solid #0ea5e9;
            box-shadow: 0 2px 4px rgba(14, 165, 233, 0.1);
        }
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: #0ea5e9;
            border-radius: 0 4px 4px 0;
        }
        .nav-item:hover:not(.active) {
            background-color: #f8fafc;
            transform: translateX(4px);
        }
        .nav-icon {
            width: 20px;
            text-align: center;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }
        .nav-item.active .nav-icon {
            transform: scale(1.1);
        }
        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }
        .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .notification-dot {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar-content {
            height: calc(100vh - 140px);
            overflow-y: auto;
            transition: all 0.3s ease;
        }
        .sidebar-content::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-content::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .sidebar-content::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .sidebar-content::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .toggle-btn {
            transition: all 0.3s ease;
        }
        .sidebar.collapsed .toggle-btn {
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Mobile Overlay -->
    <div id="mobileOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>

    <!-- Main Layout -->
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar w-64 bg-white fixed h-full z-30 animate-slide-in-left">
            <!-- Logo & Close Button -->
            <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-white sticky top-0 z-10">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="logo">
                    @if(file_exists(public_path('images/mykos.png')))
                        <img src="{{ asset('images/mykos.png') }}" alt="MyKos" class="h-10 w-auto">
                    @elseif(file_exists(public_path('images/mykos.svg')))
                        <img src="{{ asset('images/mykos.svg') }}" alt="MyKos" class="h-10 w-auto">
                    @else
                        <div class="text-2xl font-bold text-blue-600">MyKos</div>
                    @endif
                </a>
                    <div class="ml-3 logo-text">
                        <h1 class="text-lg font-bold text-gray-800">MyKos</h1>
                        <p class="text-xs text-gray-500">Admin Panel</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-1">
                    <!-- Toggle Button for Desktop -->
                    <button id="sidebarToggleDesktop" class="hidden md:flex toggle-btn text-gray-500 hover:text-gray-700 transition-colors duration-200 p-1 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-chevron-left text-sm"></i>
                    </button>
                    
                    <!-- Close Button for Mobile -->
                    <button id="sidebarClose" class="md:hidden text-gray-500 hover:text-gray-700 transition-colors duration-200 p-1 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Navigation -->
            <div class="sidebar-content">
                <nav class="py-4">
                    <div class="px-4 mb-4 section-text">
                        <p class="text-xs uppercase text-gray-400 font-semibold tracking-wider">Main Menu</p>
                    </div>
                    
                    <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center px-3 py-2 text-gray-700 transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'active text-primary-600' : '' }}">
                        <i class="fas fa-chart-pie nav-icon mr-3"></i>
                        <span class="font-medium nav-text">Dashboard</span>
                    </a>
                    
                    <a href="{{ route('admin.kamar.index') }}" class="nav-item flex items-center px-3 py-2 text-gray-700 transition-all duration-200 {{ request()->routeIs('admin.kamar.*') ? 'active text-primary-600' : '' }}">
                        <i class="fas fa-bed nav-icon mr-3"></i>
                        <span class="font-medium nav-text">Kelola Kamar</span>
                    </a>
                    
                    <a href="{{ route('admin.user.index') }}" class="nav-item flex items-center px-3 py-2 text-gray-700 transition-all duration-200 {{ request()->routeIs('admin.user.*') ? 'active text-primary-600' : '' }}">
                        <i class="fas fa-users nav-icon mr-3"></i>
                        <span class="font-medium nav-text">Kelola User</span>
                    </a>
                      
                    <a href="#" class="nav-item flex items-center px-3 py-2 text-gray-700 transition-all duration-200">
                        <i class="fas fa-calendar-check nav-icon mr-3"></i>
                        <span class="font-medium nav-text">Booking</span>
                    </a>

                    <a href="#" class="nav-item flex items-center px-3 py-2 text-gray-700 transition-all duration-200">
                        <i class="fas fa-credit-card nav-icon mr-3"></i>
                        <span class="font-medium nav-text">Pembayaran</span>
                        <span class="ml-auto bg-warning text-white text-xs px-2 py-1 rounded-full nav-text">New</span>
                    </a>

                    <div class="px-4 mt-6 mb-4 section-text">
                        <p class="text-xs uppercase text-gray-400 font-semibold tracking-wider">Laporan</p>
                    </div>
                    
                    <a href="{{ route('admin.laporan.keuangan') }}" class="nav-item flex items-center px-3 py-2 text-gray-700 transition-all duration-200 {{ request()->routeIs('admin.laporan.keuangan') ? 'active text-primary-600' : '' }}">
                        <i class="fas fa-file-invoice-dollar nav-icon mr-3"></i>
                        <span class="font-medium nav-text">Laporan Keuangan</span>
                    </a>

                    <a href="{{ route('admin.laporan.statistik') }}" class="nav-item flex items-center px-3 py-2 text-gray-700 transition-all duration-200 {{ request()->routeIs('admin.laporan.statistik') ? 'active text-primary-600' : '' }}">
                        <i class="fas fa-chart-bar nav-icon mr-3"></i>
                        <span class="font-medium nav-text">Statistik</span>
                    </a>

                    <div class="px-4 mt-6 mb-4 section-text">
                        <p class="text-xs uppercase text-gray-400 font-semibold tracking-wider">Pengaturan</p>
                    </div>
                    
                    <a href="#" class="nav-item flex items-center px-3 py-2 text-gray-700 transition-all duration-200">
                        <i class="fas fa-cogs nav-icon mr-3"></i>
                        <span class="font-medium nav-text">Pengaturan Sistem</span>
                    </a>

                    <a href="#" class="nav-item flex items-center px-3 py-2 text-gray-700 transition-all duration-200">
                        <i class="fas fa-bell nav-icon mr-3"></i>
                        <span class="font-medium nav-text">Notifikasi</span>
                    </a>

                    <a href="#" class="nav-item flex items-center px-3 py-2 text-gray-700 transition-all duration-200">
                        <i class="fas fa-question-circle nav-icon mr-3"></i>
                        <span class="font-medium nav-text">Bantuan</span>
                    </a>
                </nav>
            </div>

            <!-- User Profile -->
            <div class="absolute bottom-0 left-0 right-0 p-3 border-t border-gray-100 bg-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center user-info">
                        <div class="w-8 h-8 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full flex items-center justify-center text-white font-semibold shadow-md">
                            <i class="fas fa-user-shield text-xs"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-800">Administrator</p>
                            <p class="text-xs text-gray-500">Super Admin</p>
                        </div>
                    </div>
                    <button onclick="toggleLogoutDropdown()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200 p-1 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-ellipsis-v text-sm"></i>
                    </button>
                </div>
                <!-- Logout Dropdown -->
                <div id="logoutDropdown" class="absolute bottom-14 left-3 right-3 bg-white rounded-xl shadow-lg border border-gray-200 py-2 hidden animate-slide-down">
                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-user-edit mr-3 text-gray-400 text-xs"></i>
                        <span>Edit Profile</span>
                    </a>
                    <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-shield-alt mr-3 text-gray-400 text-xs"></i>
                        <span>Security</span>
                    </a>
                    <div class="border-t border-gray-100 mt-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-3 py-2 text-sm text-danger hover:bg-red-50 transition-colors duration-200">
                                <i class="fas fa-sign-out-alt mr-3 text-xs"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div id="mainContent" class="main-content ml-0 md:ml-64 flex-1 flex flex-col overflow-hidden transition-all duration-300">
            <!-- Top Header -->
            <header class="bg-white shadow-sm z-20 sticky top-0 border-b border-gray-100">
                <div class="flex justify-between items-center px-4 py-3">
                    <div class="flex items-center">
                        <button id="sidebarToggleMobile" class="md:hidden text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 rounded-lg p-2 transition-colors duration-200">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                        <div class="ml-2 md:ml-0">
                            <h2 class="text-lg font-semibold text-gray-800">@yield('title', 'Dashboard')</h2>
                            <p class="text-xs text-gray-500 hidden md:block">
                                @yield('subtitle', 'Overview dan statistik sistem')
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <!-- Search Bar -->
                        <div class="hidden md:block relative">
                            <div class="relative">
                                <input type="text" placeholder="Search..." class="w-56 pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 text-sm">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                            </div>
                        </div>
                        
                        <!-- Notifications -->
                        <div class="relative">
                            <button id="notificationButton" class="relative text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 rounded-full p-2 transition-colors duration-200">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="absolute -top-1 -right-1 bg-danger text-white rounded-full w-4 h-4 text-xs flex items-center justify-center font-semibold notification-dot">3</span>
                            </button>
                            <!-- Notification Dropdown -->
                            <div id="notificationDropdown" class="dropdown-menu absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <h3 class="font-semibold text-gray-800">Notifications</h3>
                                    <p class="text-xs text-gray-500">You have 3 unread messages</p>
                                </div>
                                <div class="max-h-60 overflow-y-auto">
                                    <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0 transition-colors duration-200">
                                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-primary-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-800">New user registered</p>
                                            <p class="text-xs text-gray-500">2 minutes ago</p>
                                        </div>
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0 transition-colors duration-200">
                                        <div class="w-10 h-10 bg-success-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-home text-success-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-800">New booking received</p>
                                            <p class="text-xs text-gray-500">1 hour ago</p>
                                        </div>
                                    </a>
                                    <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0 transition-colors duration-200">
                                        <div class="w-10 h-10 bg-warning-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-exclamation-triangle text-warning-600"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-800">System maintenance</p>
                                            <p class="text-xs text-gray-500">5 hours ago</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="px-4 py-2 border-t border-gray-100">
                                    <a href="#" class="block text-center text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors duration-200">
                                        View all notifications
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Messages -->
                        <div class="relative">
                            <button id="messageButton" class="relative text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 rounded-full p-2 transition-colors duration-200">
                                <i class="fas fa-envelope text-lg"></i>
                                <span class="absolute -top-1 -right-1 bg-warning text-white rounded-full w-4 h-4 text-xs flex items-center justify-center font-semibold">5</span>
                            </button>
                        </div>
                        
                        <!-- User Menu -->
                        <div class="relative">
                            <button id="userMenuButton" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 rounded-lg p-2 transition-colors duration-200">
                                <div class="w-8 h-8 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full flex items-center justify-center text-white font-semibold shadow-md">
                                    <i class="fas fa-user-shield text-xs"></i>
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-medium">Administrator</p>
                                    <p class="text-xs text-gray-500">Super Admin</p>
                                </div>
                                <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                            </button>
                            
                            <!-- User Dropdown Menu -->
                            <div id="userDropdown" class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-800">Signed in as</p>
                                    <p class="text-sm text-primary-600 font-semibold">administrator</p>
                                </div>
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                    <i class="fas fa-user mr-3 text-gray-400"></i>
                                    <span>My Profile</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                    <i class="fas fa-cog mr-3 text-gray-400"></i>
                                    <span>Settings</span>
                                </a>
                                <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                    <i class="fas fa-question-circle mr-3 text-gray-400"></i>
                                    <span>Help & Support</span>
                                </a>
                                <div class="border-t border-gray-100">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-danger hover:bg-red-50 transition-colors duration-200">
                                            <i class="fas fa-sign-out-alt mr-3"></i>
                                            <span>Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto bg-gray-50">
                <div class="p-4">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarToggleMobile = document.getElementById('sidebarToggleMobile');
            const sidebarToggleDesktop = document.getElementById('sidebarToggleDesktop');
            const sidebarClose = document.getElementById('sidebarClose');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const userMenuButton = document.getElementById('userMenuButton');
            const userDropdown = document.getElementById('userDropdown');
            const notificationButton = document.getElementById('notificationButton');
            const notificationDropdown = document.getElementById('notificationDropdown');

            // Toggle sidebar for mobile
            function toggleMobileSidebar() {
                sidebar.classList.toggle('active');
                mobileOverlay.classList.toggle('hidden');
                document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
            }

            // Toggle sidebar for desktop (collapse/expand)
            function toggleDesktopSidebar() {
                sidebar.classList.toggle('collapsed');
                if (sidebar.classList.contains('collapsed')) {
                    mainContent.style.marginLeft = '70px';
                } else {
                    mainContent.style.marginLeft = '256px';
                }
            }

            // Close mobile sidebar
            function closeMobileSidebar() {
                sidebar.classList.remove('active');
                mobileOverlay.classList.add('hidden');
                document.body.style.overflow = '';
            }

            // Toggle dropdown
            function toggleDropdown(dropdown) {
                dropdown.classList.toggle('show');
            }

            // Close all dropdowns
            function closeAllDropdowns() {
                userDropdown.classList.remove('show');
                notificationDropdown.classList.remove('show');
                document.getElementById('logoutDropdown').classList.add('hidden');
            }

            // Event listeners
            sidebarToggleMobile.addEventListener('click', toggleMobileSidebar);
            sidebarToggleDesktop.addEventListener('click', toggleDesktopSidebar);
            sidebarClose.addEventListener('click', closeMobileSidebar);
            mobileOverlay.addEventListener('click', closeMobileSidebar);

            // User dropdown
            userMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleDropdown(userDropdown);
                notificationDropdown.classList.remove('show');
            });

            // Notification dropdown
            notificationButton.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleDropdown(notificationDropdown);
                userDropdown.classList.remove('show');
            });

            // Close sidebar when clicking on nav links in mobile
            const navLinks = document.querySelectorAll('.sidebar nav a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 768) {
                        closeMobileSidebar();
                    }
                });
            });

            // Close sidebar and dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (window.innerWidth < 768) {
                    if (!sidebar.contains(e.target) && !sidebarToggleMobile.contains(e.target)) {
                        closeMobileSidebar();
                    }
                }
                
                if (!userDropdown.contains(e.target) && !userMenuButton.contains(e.target)) {
                    userDropdown.classList.remove('show');
                }
                if (!notificationDropdown.contains(e.target) && !notificationButton.contains(e.target)) {
                    notificationDropdown.classList.remove('show');
                }
            });

            // Handle resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    closeMobileSidebar();
                }
            });

            // Close sidebar when pressing Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeMobileSidebar();
                    closeAllDropdowns();
                }
            });

            // Active nav item highlighting
            function setActiveNavItem() {
                const currentPath = window.location.pathname;
                const navItems = document.querySelectorAll('.nav-item');
                
                navItems.forEach(item => {
                    item.classList.remove('active', 'text-primary-600');
                    if (item.getAttribute('href') === currentPath) {
                        item.classList.add('active', 'text-primary-600');
                    }
                });
            }

            setActiveNavItem();
        });

        // Toggle logout dropdown in sidebar
        function toggleLogoutDropdown() {
            const dropdown = document.getElementById('logoutDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close logout dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const logoutDropdown = document.getElementById('logoutDropdown');
            if (!e.target.closest('#logoutDropdown') && !e.target.closest('.absolute.bottom-0')) {
                logoutDropdown.classList.add('hidden');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>