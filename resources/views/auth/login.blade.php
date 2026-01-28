<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Akun | MyKos</title>
    <link rel="shortcut icon" href="{{ asset('images/mykos.png') }}" type="image/png">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
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
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #f8fafc;
        }
        .form-input:focus {
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-brand-100 blur-[100px] opacity-60"></div>
        <div class="absolute top-[20%] -right-[10%] w-[30%] h-[30%] rounded-full bg-purple-100 blur-[100px] opacity-60"></div>
    </div>

    <div class="w-full max-w-md">
        
        <div class="text-center mb-8">
            <a href="/" class="inline-block mb-4 transition-transform hover:scale-105">
                <img src="{{ asset('images/mykos.png') }}" alt="MyKos Logo" class="h-16 w-auto mx-auto drop-shadow-md">
            </a>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Selamat Datang Kembali</h1>
            <p class="text-slate-500 text-sm mt-2">Masuk untuk mengelola pesanan kos Anda</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-100 p-8">
            
            @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 flex items-start gap-3">
                <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                <div>
                    <h3 class="text-sm font-semibold text-green-800">Berhasil</h3>
                    <p class="text-xs text-green-600 mt-1">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3">
                <i class="fas fa-times-circle text-red-600 mt-0.5"></i>
                <div>
                    <h3 class="text-sm font-semibold text-red-800">Gagal Masuk</h3>
                    <p class="text-xs text-red-600 mt-1">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-600 mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-semibold text-red-800">Terjadi Kesalahan</h3>
                        <ul class="text-xs text-red-600 mt-1 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST" id="loginForm" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="far fa-envelope"></i>
                        </div>
                        <input type="email" id="email" name="email" required value="{{ old('email') }}"
                            class="form-input w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-brand-500 focus:bg-white transition-all"
                            placeholder="contoh@email.com" autocomplete="email">
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label for="password" class="block text-sm font-medium text-slate-700">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700 hover:underline">Lupa sandi?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fas fa-lock text-xs"></i>
                        </div>
                        <input type="password" id="password" name="password" required
                            class="form-input w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-brand-500 focus:bg-white transition-all"
                            placeholder="Masukkan kata sandi" autocomplete="current-password">
                        <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                            <i class="far fa-eye" id="passwordToggle"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded cursor-pointer">
                    <label for="remember" class="ml-2 block text-sm text-slate-600 cursor-pointer select-none">
                        Ingat saya di perangkat ini
                    </label>
                </div>

                <button type="submit" id="submitBtn" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-3 rounded-xl shadow-lg shadow-brand-500/30 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                    <span>Masuk Sekarang</span>
                    <i class="fas fa-arrow-right text-sm"></i>
                </button>

            </form>
        </div>

        <p class="text-center text-slate-500 text-sm mt-8">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="text-brand-600 font-semibold hover:text-brand-700 hover:underline transition-all">Daftar Gratis</a>
        </p>
    </div>

    <script>
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + 'Toggle');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (!email || !password) {
                e.preventDefault();
                alert('Email dan Password wajib diisi!');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i><span>Memproses...</span>';
            btn.classList.add('opacity-75', 'cursor-not-allowed');
        });
    </script>
</body>
</html>