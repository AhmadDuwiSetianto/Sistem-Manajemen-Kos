@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-foreground">Edit User</h1>
        <p class="text-secondary mt-1">Perbarui data informasi akun {{ $user->name }}</p>
    </div>
    <a href="{{ route('admin.user.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white border border-border text-foreground font-semibold rounded-xl hover:bg-muted transition-colors">
        <i data-lucide="arrow-left" class="size-4 mr-2 text-secondary"></i> Kembali
    </a>
</div>

@if ($errors->any())
<div class="bg-error-light border border-error/20 p-4 mb-6 rounded-2xl flex items-start gap-3">
    <div class="size-8 bg-error/20 rounded-full flex items-center justify-center shrink-0 mt-0.5">
        <i data-lucide="alert-circle" class="size-5 text-error"></i>
    </div>
    <div>
        <p class="text-error font-bold mb-1">Gagal menyimpan perubahan!</p>
        <ul class="text-error/80 text-sm list-disc pl-4">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-border p-6 md:p-8">
            <form action="{{ route('admin.user.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-8">
                    <h3 class="text-base font-bold text-foreground flex items-center gap-2 mb-5">
                        <i data-lucide="user" class="size-5 text-primary"></i> Data Utama
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Nama Lengkap <span class="text-error">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all @error('name') border-error @enderror" required>
                            @error('name')
                                <p class="text-xs text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Email <span class="text-error">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all @error('email') border-error @enderror" required>
                            @error('email')
                                <p class="text-xs text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Role <span class="text-error">*</span></label>
                            <select name="role" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all appearance-none @error('role') border-error @enderror" required>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                                <option value="penghuni" {{ old('role', $user->role) == 'penghuni' ? 'selected' : '' }}>Penghuni</option>
                                <option value="calon_penghuni" {{ old('role', $user->role) == 'calon_penghuni' ? 'selected' : '' }}>Calon Penghuni</option>
                            </select>
                            @error('role')
                                <p class="text-xs text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">No. Handphone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all @error('phone') border-error @enderror">
                            @error('phone')
                                <p class="text-xs text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-border mb-8">

                <div class="mb-8">
                    <h3 class="text-base font-bold text-foreground flex items-center gap-2 mb-5">
                        <i data-lucide="key" class="size-5 text-warning-dark"></i> Ubah Password
                    </h3>
                    <p class="text-xs text-secondary mb-4">Biarkan kosong jika tidak ingin mengganti password pengguna ini.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Password Baru</label>
                            <input type="password" name="password" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-secondary @error('password') border-error @enderror" placeholder="Ketik sandi baru">
                            @error('password')
                                <p class="text-xs text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-secondary" placeholder="Ulangi sandi">
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
                            <input type="text" name="identity_number" value="{{ old('identity_number', $user->identity_number) }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all @error('identity_number') border-error @enderror">
                            @error('identity_number')
                                <p class="text-xs text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Alamat Asal</label>
                            <textarea name="address" rows="3" class="w-full px-4 py-3 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none @error('address') border-error @enderror">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <p class="text-xs text-error mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                    <a href="{{ route('admin.user.index') }}" class="px-5 py-2.5 font-semibold text-secondary hover:text-foreground transition-colors">Batal</a>
                    <button type="submit" class="px-6 py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-primary-hover transition-colors shadow-sm shadow-primary/30 cursor-pointer">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
            <h3 class="font-bold text-foreground mb-4 border-b border-border pb-3">Status Pencatatan</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-secondary font-medium uppercase tracking-wide">Dibuat Pada</p>
                    <p class="text-sm font-bold text-foreground">{{ $user->created_at->format('d M Y - H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-secondary font-medium uppercase tracking-wide">Terakhir Update</p>
                    <p class="text-sm font-bold text-foreground">{{ $user->updated_at->format('d M Y - H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-secondary font-medium uppercase tracking-wide">Verifikasi Email</p>
                    @if($user->email_verified_at)
                        <span class="mt-1 inline-block px-2 py-1 text-[10px] font-bold uppercase tracking-wider bg-success-light text-success rounded-md">Terverifikasi</span>
                    @else
                        <span class="mt-1 inline-block px-2 py-1 text-[10px] font-bold uppercase tracking-wider bg-warning-light text-warning-dark rounded-md">Pending</span>
                    @endif
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