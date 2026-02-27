<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Akun | MyKos</title>
    <link rel="shortcut icon" href="{{ asset('images/mykos.png') }}" type="image/png">
    
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Lexend Deca"', 'sans-serif'] },
                    colors: {
                        brand: { 50: '#e8f0ff', 100: '#d1e0ff', 500: '#3b7dff', 600: '#165DFF', 700: '#0E4BD9' },
                        slate: { 50: '#EFF2F7', 100: '#F3F4F3', 500: '#6A7686', 800: '#080C1A' }
                    }
                }
            }
        }
    </script>

    <style>
        body { background-color: #F8FAFC; color: #080C1A; }
        .form-input:focus { border-color: #165DFF; box-shadow: 0 0 0 4px rgba(22, 93, 255, 0.1); }
        .glass-panel { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.5); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 py-10 relative">

    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.02]"></div>
        <div class="absolute -top-[10%] -left-[10%] w-[500px] h-[500px] rounded-full bg-brand-500 blur-[120px] opacity-20"></div>
        <div class="absolute top-[30%] -right-[10%] w-[400px] h-[400px] rounded-full bg-indigo-500 blur-[120px] opacity-10"></div>
    </div>

    <div class="w-full max-w-[420px] z-10">
        
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block mb-5 transition-transform hover:-translate-y-1 duration-300">
                @if(file_exists(public_path('images/mykos.png')))
                    <img src="{{ asset('images/mykos.png') }}" alt="MyKos Logo" class="h-14 md:h-16 w-auto mx-auto object-contain drop-shadow-md">
                @else
                    <div class="w-14 h-14 bg-brand-600 rounded-2xl flex items-center justify-center shadow-lg mx-auto">
                        <i data-lucide="home" class="w-7 h-7 text-white"></i>
                    </div>
                @endif
            </a>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Selamat Datang Kembali</h1>
            <p class="text-slate-500 text-sm mt-2 font-medium">Masuk untuk mengelola pesanan kos Anda</p>
        </div>

        <div class="glass-panel rounded-3xl shadow-xl shadow-slate-200/50 p-8 sm:p-10">
            
            @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-start gap-3">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-green-600 shrink-0"></i>
                <div>
                    <h3 class="text-sm font-bold text-green-800">Berhasil</h3>
                    <p class="text-xs text-green-700 mt-0.5 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error') || $errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 shrink-0"></i>
                <div>
                    <h3 class="text-sm font-bold text-red-800">Gagal Masuk</h3>
                    @if(session('error'))
                        <p class="text-xs text-red-700 mt-0.5 font-medium">{{ session('error') }}</p>
                    @endif
                    @if($errors->any())
                        <ul class="text-xs text-red-700 mt-1 list-disc list-inside font-medium">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST" id="loginForm" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <input type="email" id="email" name="email" required value="{{ old('email') }}"
                            class="form-input w-full pl-12 pr-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none transition-all"
                            placeholder="contoh@email.com" autocomplete="email">
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-sm font-bold text-slate-700">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 transition-colors">Lupa sandi?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>
                        <input type="password" id="password" name="password" required
                            class="form-input w-full pl-12 pr-12 py-3.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none transition-all"
                            placeholder="Masukkan kata sandi" autocomplete="current-password">
                        <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-brand-600 transition-colors cursor-pointer">
                            <i data-lucide="eye" id="passwordToggle" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center pt-1">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-brand-600 border-slate-300 rounded cursor-pointer accent-brand-600">
                    <label for="remember" class="ml-2 block text-sm text-slate-600 font-medium cursor-pointer select-none">
                        Ingat saya di perangkat ini
                    </label>
                </div>

                <button type="submit" id="submitBtn" class="w-full mt-2 bg-brand-600 hover:bg-brand-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-brand-600/20 transition-all transform active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer group">
                    <span id="btnText">Masuk Sekarang</span>
                    <i data-lucide="arrow-right" id="btnIcon" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </button>

            </form>
        </div>

        <p class="text-center text-slate-500 text-sm mt-8 font-medium">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="text-brand-600 font-bold hover:text-brand-700 transition-colors ml-1 border-b border-transparent hover:border-brand-600">Daftar Gratis</a>
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() { lucide.createIcons(); });

        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + 'Toggle');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons(); 
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            if (!email || !password) return;

            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');

            btn.disabled = true;
            btnText.innerText = 'Memproses...';
            btnIcon.setAttribute('data-lucide', 'loader-2');
            btnIcon.classList.add('animate-spin');
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            lucide.createIcons();
        });
    </script>
</body>
</html>