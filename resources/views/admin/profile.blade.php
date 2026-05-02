@extends('layouts.admin')

@section('title', 'Profil Admin')

@section('content')
<div class="flex-1 p-4 md:p-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 md:mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-foreground">Pengaturan Profil</h1>
            <p class="text-secondary mt-1 text-sm md:text-base">Kelola informasi publik dan keamanan akun admin Anda.</p>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="bg-success-light border border-success/30 text-success px-4 py-3 md:px-5 md:py-4 rounded-xl mb-6 md:mb-8 flex items-center gap-3">
            <i data-lucide="check-circle-2" class="size-5 shrink-0"></i>
            <div>
                <strong class="font-bold block text-xs md:text-sm">Berhasil!</strong>
                <span class="text-[10px] md:text-xs font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Alert Error -->
    @if ($errors->any())
        <div class="bg-error-light border border-error/30 text-error px-4 py-3 md:px-5 md:py-4 rounded-xl mb-6 md:mb-8 flex items-start gap-3">
            <i data-lucide="x-octagon" class="size-4 md:size-5 shrink-0 mt-0.5"></i>
            <div>
                <strong class="font-bold block text-xs md:text-sm mb-1">Terdapat Kesalahan:</strong>
                <ul class="text-[10px] md:text-xs font-medium list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
        
        <!-- Kolom Kiri: Info Pribadi -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden">
                <div class="px-5 md:px-6 py-4 border-b border-border bg-muted/30 flex items-center gap-3">
                    <div class="size-8 md:size-10 bg-primary/10 text-primary rounded-lg flex items-center justify-center shrink-0">
                        <i data-lucide="user-cog" class="size-4 md:size-5"></i>
                    </div>
                    <h2 class="font-bold text-base md:text-lg text-foreground">Informasi Pribadi</h2>
                </div>

                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-5 md:p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Avatar Section -->
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 md:gap-6 pb-6 border-b border-border">
                        <div class="relative shrink-0 group mx-auto sm:mx-0">
                            <div class="size-20 md:size-24 rounded-full border-4 border-muted overflow-hidden bg-muted shadow-sm relative">
                                @if(Auth::user()->avatar)
                                    <img id="avatarPreview" src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover" alt="Foto Profil">
                                @else
                                    <img id="avatarPreview" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=165DFF&color=fff&size=150&bold=true" class="w-full h-full object-cover" alt="Foto Profil">
                                @endif
                                
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer" onclick="document.getElementById('avatarInput').click()">
                                    <i data-lucide="camera" class="size-5 md:size-6 text-white"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex-1 w-full text-center sm:text-left">
                            <label class="block text-xs md:text-sm font-bold text-foreground mb-2">Ganti Foto Profil</label>
                            <input type="file" id="avatarInput" name="avatar" accept="image/jpeg, image/png, image/jpg" 
                                   class="w-full text-xs text-secondary file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary hover:file:text-white transition-colors cursor-pointer"
                                   onchange="previewImage(event)">
                            <p class="text-[10px] text-secondary mt-2">Format: JPG/PNG. Maks 2MB. Resolusi 1:1.</p>
                        </div>
                    </div>

                    <!-- Input Section -->
                    <div class="space-y-4 md:space-y-5">
                        <div>
                            <label for="name" class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Nama Lengkap / Username</label>
                            <div class="relative">
                                <i data-lucide="user" class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                                <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required
                                       class="w-full pl-10 pr-4 py-2.5 bg-white border border-border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-foreground">
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Alamat Email</label>
                            <div class="relative">
                                <i data-lucide="mail" class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                                <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                                       class="w-full pl-10 pr-4 py-2.5 bg-white border border-border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-foreground">
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white rounded-xl text-sm font-bold hover:bg-primary-hover transition-colors shadow-sm shadow-primary/30">
                            <i data-lucide="save" class="size-4"></i> Simpan Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: Keamanan / Password -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden lg:sticky lg:top-28">
                <div class="px-5 md:px-6 py-4 border-b border-border bg-muted/30 flex items-center gap-3">
                    <div class="size-8 md:size-10 bg-warning-light text-warning-dark rounded-lg flex items-center justify-center shrink-0">
                        <i data-lucide="shield-check" class="size-4 md:size-5"></i>
                    </div>
                    <h2 class="font-bold text-base md:text-lg text-foreground">Keamanan Akun</h2>
                </div>

                <form action="{{ route('admin.profile.password') }}" method="POST" class="p-5 md:p-6 space-y-4 md:space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Password Saat Ini</label>
                        <div class="relative">
                            <i data-lucide="lock" class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                            <input type="password" id="current_password" name="current_password" required
                                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-warning/20 transition-all font-semibold text-foreground" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="border-b border-dashed border-border py-1"></div>

                    <div>
                        <label for="password" class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Password Baru</label>
                        <div class="relative">
                            <i data-lucide="key-round" class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                            <input type="password" id="password" name="password" required
                                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-warning/20 transition-all font-semibold text-foreground placeholder:text-secondary/50" placeholder="Min. 8 karakter">
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <i data-lucide="shield-alert" class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-warning/20 transition-all font-semibold text-foreground placeholder:text-secondary/50" placeholder="Ketik ulang password">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-foreground text-white rounded-xl text-sm font-bold hover:bg-black transition-colors shadow-sm">
                            <i data-lucide="lock-keyhole" class="size-4"></i> Perbarui Sandi
                        </button>
                    </div>
                </form>
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
</script>
@endsection