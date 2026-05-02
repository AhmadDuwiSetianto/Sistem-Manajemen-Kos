@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="flex-1 p-4 md:p-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 md:mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-foreground">Edit User</h1>
            <p class="text-secondary mt-1 text-sm md:text-base">Perbarui data informasi akun {{ $user->name }}</p>
        </div>
        <a href="{{ route('admin.user.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-border text-foreground font-bold text-sm rounded-xl hover:bg-muted transition-colors">
            <i data-lucide="arrow-left" class="size-4 mr-2 text-secondary"></i> Kembali
        </a>
    </div>

    <!-- Error Alert -->
    @if ($errors->any())
    <div class="bg-error-light border border-error/20 p-3 md:p-4 mb-6 rounded-xl flex items-start gap-3">
        <div class="size-8 bg-error/20 rounded-full flex items-center justify-center shrink-0 mt-0.5">
            <i data-lucide="alert-circle" class="size-4 md:size-5 text-error"></i>
        </div>
        <div>
            <p class="text-error font-bold text-sm mb-1">Gagal menyimpan perubahan!</p>
            <ul class="text-error/80 text-xs list-disc pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Form Container -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-border p-5 md:p-8">
                <form action="{{ route('admin.user.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Data Utama -->
                    <div class="mb-6 md:mb-8">
                        <h3 class="text-sm md:text-base font-bold text-foreground flex items-center gap-2 mb-4">
                            <i data-lucide="user" class="size-4 md:size-5 text-primary"></i> Data Utama
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Nama Lengkap <span class="text-error">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm @error('name') border-error @enderror" required>
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Email <span class="text-error">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm @error('email') border-error @enderror" required>
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Role <span class="text-error">*</span></label>
                                <select name="role" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm appearance-none cursor-pointer @error('role') border-error @enderror" required>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                                    <option value="penghuni" {{ old('role', $user->role) == 'penghuni' ? 'selected' : '' }}>Penghuni</option>
                                    <option value="calon_penghuni" {{ old('role', $user->role) == 'calon_penghuni' ? 'selected' : '' }}>Calon Penghuni</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">No. Handphone</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm @error('phone') border-error @enderror">
                            </div>
                        </div>
                    </div>

                    <hr class="border-border mb-6 md:mb-8">

                    <!-- Keamanan -->
                    <div class="mb-6 md:mb-8">
                        <h3 class="text-sm md:text-base font-bold text-foreground flex items-center gap-2 mb-2">
                            <i data-lucide="key" class="size-4 md:size-5 text-warning-dark"></i> Ubah Password
                        </h3>
                        <p class="text-[10px] md:text-xs text-secondary mb-4">Biarkan kosong jika tidak ingin mengganti password pengguna ini.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Password Baru</label>
                                <input type="password" name="password" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm placeholder:text-secondary @error('password') border-error @enderror" placeholder="Ketik sandi baru">
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm placeholder:text-secondary" placeholder="Ulangi sandi">
                            </div>
                        </div>
                    </div>

                    <hr class="border-border mb-6 md:mb-8">

                    <!-- Identitas -->
                    <div class="mb-6 md:mb-8">
                        <h3 class="text-sm md:text-base font-bold text-foreground flex items-center gap-2 mb-4">
                            <i data-lucide="id-card" class="size-4 md:size-5 text-success"></i> Identitas Detail
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Nomor KTP/SIM</label>
                                <input type="text" name="identity_number" value="{{ old('identity_number', $user->identity_number) }}" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all @error('identity_number') border-error @enderror">
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Alamat Asal</label>
                                <textarea name="address" rows="3" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm @error('address') border-error @enderror">{{ old('address', $user->address) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-border">
                        <a href="{{ route('admin.user.index') }}" class="w-full sm:w-auto px-5 py-2.5 font-bold text-sm text-secondary text-center hover:text-foreground transition-colors">Batal</a>
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-hover transition-colors shadow-sm shadow-primary/30 cursor-pointer">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="lg:col-span-1 space-y-4 md:space-y-6">
            <div class="bg-white border border-border rounded-2xl p-5 shadow-sm">
                <h3 class="font-bold text-sm md:text-base text-foreground mb-4 border-b border-border pb-2">Status Pencatatan</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-[10px] md:text-xs text-secondary font-bold uppercase tracking-wide">Dibuat Pada</p>
                        <p class="text-xs md:text-sm font-bold text-foreground">{{ $user->created_at->format('d M Y - H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] md:text-xs text-secondary font-bold uppercase tracking-wide">Terakhir Update</p>
                        <p class="text-xs md:text-sm font-bold text-foreground">{{ $user->updated_at->format('d M Y - H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] md:text-xs text-secondary font-bold uppercase tracking-wide">Verifikasi Email</p>
                        @if($user->email_verified_at)
                            <span class="mt-1 inline-block px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider bg-success-light text-success rounded">Terverifikasi</span>
                        @else
                            <span class="mt-1 inline-block px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider bg-warning-light text-warning-dark rounded">Pending</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection