@extends('layouts.admin')

@section('title', 'Tambah User Baru')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-foreground">Tambah User</h1>
        <p class="text-secondary mt-1">Daftarkan akun admin atau penghuni baru</p>
    </div>
    <a href="{{ route('admin.user.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white border border-border text-foreground font-semibold rounded-xl hover:bg-muted transition-colors">
        <i data-lucide="arrow-left" class="size-4 mr-2 text-secondary"></i> Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-border p-6 md:p-8">
            <form action="{{ route('admin.user.store') }}" method="POST">
                @csrf
                
                <div class="mb-8">
                    <h3 class="text-base font-bold text-foreground flex items-center gap-2 mb-5">
                        <i data-lucide="user" class="size-5 text-primary"></i> Data Utama
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Nama Lengkap <span class="text-error">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-secondary text-sm" placeholder="John Doe" required>
                            @error('name')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Email <span class="text-error">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-secondary text-sm" placeholder="john@example.com" required>
                            @error('email')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Role <span class="text-error">*</span></label>
                            <select name="role" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm appearance-none" required>
                                <option value="">Pilih Role</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                <option value="penghuni" {{ old('role') == 'penghuni' ? 'selected' : '' }}>Penghuni</option>
                                <option value="calon_penghuni" {{ old('role') == 'calon_penghuni' ? 'selected' : '' }}>Calon Penghuni</option>
                            </select>
                            @error('role')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">No. Handphone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-secondary text-sm" placeholder="0812...">
                        </div>
                    </div>
                </div>

                <hr class="border-border mb-8">

                <div class="mb-8">
                    <h3 class="text-base font-bold text-foreground flex items-center gap-2 mb-5">
                        <i data-lucide="lock" class="size-5 text-warning-dark"></i> Keamanan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Password <span class="text-error">*</span></label>
                            <input type="password" id="password" name="password" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-secondary text-sm" placeholder="Minimal 8 karakter" required>
                            <div id="password-strength" class="text-[11px] font-medium mt-1.5 h-4"></div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Konfirmasi Password <span class="text-error">*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-secondary text-sm" placeholder="Ulangi password" required>
                            <div id="password-match" class="text-[11px] font-medium mt-1.5 h-4"></div>
                        </div>
                    </div>
                </div>

                <hr class="border-border mb-8">

                <div class="mb-8">
                    <h3 class="text-base font-bold text-foreground flex items-center gap-2 mb-5">
                        <i data-lucide="id-card" class="size-5 text-success"></i> Identitas Detail
                    </h3>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Nomor KTP/SIM</label>
                            <input type="text" name="identity_number" value="{{ old('identity_number') }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-secondary text-sm" placeholder="16 digit angka">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Alamat Asal</label>
                            <textarea name="address" rows="3" class="w-full px-4 py-3 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm placeholder:text-secondary" placeholder="Tuliskan alamat lengkap sesuai KTP...">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                    <a href="{{ route('admin.user.index') }}" class="px-5 py-2.5 font-semibold text-secondary hover:text-foreground transition-colors">Batal</a>
                    <button type="submit" class="px-6 py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-primary-hover transition-colors shadow-sm shadow-primary/30">
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="lg:col-span-1 space-y-6">
        <div class="bg-primary/5 border border-primary/20 rounded-2xl p-6">
            <h3 class="font-bold text-primary flex items-center gap-2 mb-4">
                <i data-lucide="lightbulb" class="size-5"></i> Panduan
            </h3>
            <ul class="space-y-3 text-sm text-foreground">
                <li class="flex gap-2"><i data-lucide="check" class="size-4 text-primary shrink-0 mt-0.5"></i> Gunakan email aktif.</li>
                <li class="flex gap-2"><i data-lucide="check" class="size-4 text-primary shrink-0 mt-0.5"></i> Kombinasi huruf & angka untuk password yang kuat.</li>
                <li class="flex gap-2"><i data-lucide="check" class="size-4 text-primary shrink-0 mt-0.5"></i> Admin memiliki kontrol penuh atas sistem ini.</li>
            </ul>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();

        // Security check scripts
        const pwd = document.getElementById('password');
        const confirmPwd = document.getElementById('password_confirmation');
        const strengthInd = document.getElementById('password-strength');
        const matchInd = document.getElementById('password-match');

        pwd.addEventListener('input', function() {
            const val = this.value;
            let str = 0;
            if (val.length >= 8) str++;
            if (val.match(/[a-z]/) && val.match(/[A-Z]/)) str++;
            if (val.match(/\d/)) str++;
            if (val.match(/[^a-zA-Z\d]/)) str++;

            const texts = ['Sangat Lemah', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
            const colors = ['text-error', 'text-warning-dark', 'text-warning', 'text-primary', 'text-success'];
            
            if(val) {
                strengthInd.textContent = texts[str];
                strengthInd.className = `text-[11px] font-bold mt-1.5 h-4 ${colors[str]}`;
            } else {
                strengthInd.textContent = '';
            }
            checkMatch();
        });

        function checkMatch() {
            if (!confirmPwd.value) { matchInd.textContent = ''; return; }
            if (pwd.value === confirmPwd.value) {
                matchInd.textContent = 'Password cocok';
                matchInd.className = 'text-[11px] font-bold mt-1.5 h-4 text-success';
            } else {
                matchInd.textContent = 'Password tidak sama';
                matchInd.className = 'text-[11px] font-bold mt-1.5 h-4 text-error';
            }
        }
        confirmPwd.addEventListener('input', checkMatch);
    });
</script>
@endsection