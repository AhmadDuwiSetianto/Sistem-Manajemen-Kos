@extends('layouts.admin')

@section('title', 'Pengaturan Profil Admin | Inna Kos')

@section('content')
<div class="py-8 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Pengaturan Profil</h1>
                <p class="text-slate-500 mt-1 font-medium">Kelola informasi publik dan keamanan akun admin Anda.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-success-light/50 border border-success/30 text-success px-5 py-4 rounded-2xl relative mb-8 flex items-center gap-3">
                <i data-lucide="check-circle-2" class="size-5 shrink-0"></i>
                <div>
                    <strong class="font-bold block text-sm">Berhasil!</strong>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-error-light/50 border border-error/30 text-error px-5 py-4 rounded-2xl relative mb-8 flex items-start gap-3">
                <i data-lucide="x-octagon" class="size-5 shrink-0 mt-0.5"></i>
                <div>
                    <strong class="font-bold block text-sm mb-1">Terdapat Kesalahan:</strong>
                    <ul class="text-sm font-medium list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-7">
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
                    <div class="p-6 md:p-8 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                        <div class="size-10 bg-brand-50 text-brand-600 rounded-xl flex items-center justify-center shrink-0">
                            <i data-lucide="user-cog" class="size-5"></i>
                        </div>
                        <h2 class="font-black text-xl text-slate-800">Informasi Pribadi</h2>
                    </div>

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 pb-6 border-b border-slate-100">
                            <div class="relative shrink-0 group">
                                <div class="size-24 rounded-full border-4 border-slate-50 overflow-hidden bg-slate-100 shadow-sm relative">
                                    @if(Auth::user()->avatar)
                                        <img id="avatarPreview" src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover" alt="Foto Profil">
                                    @else
                                        <img id="avatarPreview" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=165DFF&color=fff&size=150&bold=true" class="w-full h-full object-cover" alt="Foto Profil">
                                    @endif
                                    
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer" onclick="document.getElementById('avatarInput').click()">
                                        <i data-lucide="camera" class="size-6 text-white"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex-1 w-full">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Foto Profil Baru</label>
                                <input type="file" id="avatarInput" name="avatar" accept="image/jpeg, image/png, image/jpg" 
                                       class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100 transition-colors cursor-pointer"
                                       onchange="previewImage(event)">
                                <p class="text-xs text-slate-400 mt-2 font-medium">Format: JPG, JPEG, PNG. Maksimal 2MB. Resolusi disarankan 1:1.</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap / Username</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i data-lucide="user" class="size-4 text-slate-400"></i>
                                    </div>
                                    <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required
                                           class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all font-medium">
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Alamat Email</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i data-lucide="mail" class="size-4 text-slate-400"></i>
                                    </div>
                                    <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                                           class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all font-medium">
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full sm:w-auto flex items-center justify-center gap-2 px-8 py-3.5 bg-brand-600 text-white rounded-xl font-bold hover:bg-brand-700 transition-colors shadow-lg shadow-brand-600/30">
                                <i data-lucide="save" class="size-4"></i> Simpan Perubahan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden sticky top-28">
                    <div class="p-6 md:p-8 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                        <div class="size-10 bg-warning-light text-warning-dark rounded-xl flex items-center justify-center shrink-0">
                            <i data-lucide="shield-check" class="size-5"></i>
                        </div>
                        <h2 class="font-black text-xl text-slate-800">Keamanan Akun</h2>
                    </div>

                    <form action="{{ route('admin.profile.password') }}" method="POST" class="p-6 md:p-8 space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="current_password" class="block text-sm font-bold text-slate-700 mb-2">Password Saat Ini</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="lock" class="size-4 text-slate-400"></i>
                                </div>
                                <input type="password" id="current_password" name="current_password" required
                                       class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-warning/20 focus:border-warning transition-all font-medium"
                                       placeholder="••••••••">
                            </div>
                        </div>

                        <div class="pt-2 pb-2 border-b border-dashed border-slate-200"></div>

                        <div>
                            <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password Baru</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="key-round" class="size-4 text-slate-400"></i>
                                </div>
                                <input type="password" id="password" name="password" required
                                       class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-warning/20 focus:border-warning transition-all font-medium"
                                       placeholder="Minimal 8 karakter">
                            </div>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="shield-alert" class="size-4 text-slate-400"></i>
                                </div>
                                <input type="password" id="password_confirmation" name="password_confirmation" required
                                       class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-warning/20 focus:border-warning transition-all font-medium"
                                       placeholder="Ketik ulang password baru">
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-8 py-3.5 bg-slate-800 text-white rounded-xl font-bold hover:bg-slate-900 transition-colors shadow-lg shadow-slate-800/30">
                                <i data-lucide="lock-keyhole" class="size-4"></i> Perbarui Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('avatarPreview');
            output.src = reader.result;
        };
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
    // Opsional: Real-time update notifikasi (Polling setiap 30 detik)
function fetchLatestNotifications() {
    // Pastikan kamu membuat route ini di web.php untuk mereturn JSON notifikasi
    fetch('/admin/api/notifications/latest') 
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('notification-list-container');
            const badge = document.getElementById('notif-badge');
            
            if (data.unread_count > 0) {
                // Munculkan titik merah jika ada notifikasi baru
                if(!badge && document.querySelector('button[onclick="toggleDropdown(\'notificationDropdown\')"]')) {
                    const btn = document.querySelector('button[onclick="toggleDropdown(\'notificationDropdown\')"]');
                    btn.innerHTML += '<span id="notif-badge" class="absolute top-2 right-2.5 size-2 bg-error rounded-full ring-2 ring-white"></span>';
                }
            } else if (badge) {
                badge.remove();
            }

            // Jika kamu mereturn HTML dari backend, langsung replace (Opsional, lebih mudah untuk UI Tailwind)
            if(data.html) {
                container.innerHTML = data.html;
                lucide.createIcons(); // Render ulang icon Lucide
            }
        })
        .catch(error => console.error('Error fetching notifications:', error));
}

// Jalankan pengecekan setiap 30 detik (30000 ms)
setInterval(fetchLatestNotifications, 30000);
</script>
@endsection