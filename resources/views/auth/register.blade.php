<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | MyKos</title>
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
        <div class="absolute -top-[10%] -right-[10%] w-[500px] h-[500px] rounded-full bg-brand-500 blur-[120px] opacity-20"></div>
        <div class="absolute top-[40%] -left-[10%] w-[400px] h-[400px] rounded-full bg-emerald-500 blur-[120px] opacity-10"></div>
    </div>

    <div class="w-full max-w-[460px] z-10 my-8">
        
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block mb-5 transition-transform hover:-translate-y-1 duration-300">
                @if(file_exists(public_path('images/innakos.png')))
                    <img src="{{ asset('images/innakos.png') }}" alt="MyKos Logo" class="h-14 md:h-16 w-auto mx-auto object-contain drop-shadow-md">
                @else
                    <div class="w-14 h-14 bg-brand-600 rounded-2xl flex items-center justify-center shadow-lg mx-auto">
                        <i data-lucide="home" class="w-7 h-7 text-white"></i>
                    </div>
                @endif
            </a>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Buat Akun Baru</h1>
            <p class="text-slate-500 text-sm mt-2 font-medium">Bergabunglah untuk mulai mencari kos impian Anda</p>
        </div>

        <div class="glass-panel rounded-3xl shadow-xl shadow-slate-200/50 p-8 sm:p-10">
            
            @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 shrink-0"></i>
                <div>
                    <h3 class="text-sm font-bold text-red-800">Terjadi Kesalahan</h3>
                    <ul class="text-xs text-red-700 mt-1 list-disc list-inside font-medium">
                        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <form action="{{ route('register') }}" method="POST" id="registerForm" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <input type="text" id="name" name="name" required value="{{ old('name') }}"
                            class="form-input w-full pl-12 pr-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none transition-all"
                            placeholder="Sesuai kartu identitas">
                    </div>
                </div>

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
                    <label for="phone" class="block text-sm font-bold text-slate-700 mb-2">Nomor WhatsApp</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="phone" class="w-5 h-5"></i>
                        </div>
                        <input type="tel" id="phone" name="phone" required value="{{ old('phone') }}"
                            class="form-input w-full pl-12 pr-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none transition-all"
                            placeholder="0812xxxxxxxx">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Kata Sandi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="lock" class="w-4 h-4"></i>
                            </div>
                            <input type="password" id="password" name="password" required
                                class="form-input w-full pl-10 pr-10 py-3.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none transition-all"
                                placeholder="Min. 8 karakter">
                            <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-brand-600 transition-colors cursor-pointer">
                                <i data-lucide="eye" id="passwordToggle" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Ulangi Sandi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="shield-check" class="w-4 h-4"></i>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="form-input w-full pl-10 pr-10 py-3.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none transition-all"
                                placeholder="Ulangi sandi">
                            <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-brand-600 transition-colors cursor-pointer">
                                <i data-lucide="eye" id="password_confirmationToggle" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex items-start gap-3 pt-2">
                    <input type="checkbox" id="terms" name="terms" required class="mt-1 w-4 h-4 text-brand-600 border-slate-300 rounded cursor-pointer accent-brand-600">
                    <label for="terms" class="text-xs text-slate-500 leading-relaxed font-medium">
                        Dengan mendaftar, saya menyetujui <a href="#" class="text-brand-600 hover:text-brand-700 font-bold hover:underline">Syarat & Ketentuan</a> serta <a href="#" class="text-brand-600 hover:text-brand-700 font-bold hover:underline">Kebijakan Privasi</a>.
                    </label>
                </div>

                <button type="submit" id="submitBtn" class="w-full mt-2 bg-brand-600 hover:bg-brand-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-brand-600/20 transition-all transform active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer group">
                    <span id="btnText">Daftar Sekarang</span>
                    <i data-lucide="arrow-right" id="btnIcon" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </button>

            </form>
        </div>

        <p class="text-center text-slate-500 text-sm mt-8 font-medium pb-8">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-brand-600 font-bold hover:text-brand-700 transition-colors ml-1 border-b border-transparent hover:border-brand-600">Masuk di sini</a>
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

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            if(!this.checkValidity()) return;

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