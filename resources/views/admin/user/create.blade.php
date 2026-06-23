@extends('layouts.admin')

@section('title', 'Tambah User Baru')

@section('content')
<div class="flex-1 p-4 md:p-8">

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 md:mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-foreground">Tambah User</h1>
            <p class="text-secondary mt-1 text-sm md:text-base">Daftarkan akun admin atau penghuni baru</p>
        </div>
        <a href="{{ route('admin.user.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-border text-foreground font-bold text-sm rounded-xl hover:bg-muted transition-colors shadow-sm">
            <i data-lucide="arrow-left" class="size-4 mr-2 text-secondary"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-border p-5 md:p-8">
                <form action="{{ route('admin.user.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6 md:mb-8">
                        <h3 class="text-sm md:text-base font-bold text-foreground flex items-center gap-2 mb-4">
                            <i data-lucide="user" class="size-4 md:size-5 text-primary"></i> Data Utama
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Nama Lengkap <span class="text-error">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all placeholder:text-secondary" placeholder="John Doe" required>
                                @error('name')<p class="text-[10px] text-error mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Email <span class="text-error">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all placeholder:text-secondary" placeholder="john@example.com" required>
                                @error('email')<p class="text-[10px] text-error mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Role <span class="text-error">*</span></label>
                                <select name="role" id="role_select" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all appearance-none cursor-pointer" required>
                                    <option value="" disabled selected>Pilih Role</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                    <option value="penghuni" {{ old('role') == 'penghuni' ? 'selected' : '' }}>Penghuni</option>
                                    <option value="calon_penghuni" {{ old('role') == 'calon_penghuni' ? 'selected' : '' }}>Calon Penghuni</option>
                                </select>
                                @error('role')<p class="text-[10px] text-error mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">No. Handphone</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all placeholder:text-secondary" placeholder="0812...">
                            </div>
                        </div>
                    </div>

                    <hr class="border-border mb-6 md:mb-8">

                    <div class="mb-6 md:mb-8">
                        <h3 class="text-sm md:text-base font-bold text-foreground flex items-center gap-2 mb-4">
                            <i data-lucide="lock" class="size-4 md:size-5 text-warning-dark"></i> Keamanan
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Password <span class="text-error">*</span></label>
                                <input type="password" id="password" name="password" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all placeholder:text-secondary" placeholder="Minimal 8 karakter" required>
                                <div id="password-strength" class="text-[10px] font-medium mt-1 h-3"></div>
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Konfirmasi Password <span class="text-error">*</span></label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all placeholder:text-secondary" placeholder="Ulangi password" required>
                                <div id="password-match" class="text-[10px] font-medium mt-1 h-3"></div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-border mb-6 md:mb-8">

                    <div class="mb-6 md:mb-8">
                        <h3 class="text-sm md:text-base font-bold text-foreground flex items-center gap-2 mb-4">
                            <i data-lucide="id-card" class="size-4 md:size-5 text-success"></i> Identitas Detail
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Nomor KTP/SIM</label>
                                <input type="text" name="identity_number" value="{{ old('identity_number') }}" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all placeholder:text-secondary" placeholder="16 digit angka">
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Alamat Asal</label>
                                <textarea name="address" rows="3" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm placeholder:text-secondary" placeholder="Tuliskan alamat lengkap sesuai KTP...">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr class="border-border mb-6 md:mb-8">

                    <div class="mb-6 md:mb-8">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-4">
                            <h3 class="text-sm md:text-base font-bold text-foreground flex items-center gap-2">
                                <i data-lucide="home" class="size-4 md:size-5 text-purple-500"></i> Alokasi Kamar <span class="text-xs text-secondary font-normal ml-1">(Khusus Penghuni Lama)</span>
                            </h3>
                            <label class="flex items-center gap-2 cursor-pointer bg-muted/50 px-3 py-1.5 rounded-lg hover:bg-muted transition-colors">
                                <input type="checkbox" id="toggle-kamar" name="is_assign_room" value="1" class="rounded text-primary focus:ring-primary/50 size-4 cursor-pointer" {{ old('is_assign_room') ? 'checked' : '' }}>
                                <span class="text-xs md:text-sm font-bold text-foreground cursor-pointer">Tetapkan Kamar Sekarang</span>
                            </label>
                        </div>
                        
                        <div id="kamar-fields" class="{{ old('is_assign_room') ? '' : 'hidden' }} grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-5 bg-primary/5 p-4 md:p-5 rounded-2xl border border-primary/20">
                            <div class="md:col-span-3">
                                <p class="text-[10px] md:text-xs text-secondary leading-relaxed mb-1"><i data-lucide="info" class="size-3 inline mr-1"></i> Fitur ini otomatis mendaftarkan transaksi inap dan mengubah status kamar menjadi terisi di database.</p>
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Pilih Kamar yang Ditempati <span class="text-error">*</span></label>
                                <div class="relative">
                                    <select name="kamar_id" id="kamar_id" class="w-full pl-3.5 pr-10 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm appearance-none cursor-pointer">
                                        <option value="" disabled selected>-- Pilih Nomor Kamar --</option>
                                        @forelse($kamarTersedia ?? [] as $k)
                                            <option value="{{ $k->id }}" {{ old('kamar_id') == $k->id ? 'selected' : '' }}>Kamar {{ $k->nomor_kamar }} (Rp {{ number_format($k->harga, 0, ',', '.') }}/bulan)</option>
                                        @empty
                                            <option value="" disabled>Maaf, semua kamar saat ini penuh</option>
                                        @endforelse
                                    </select>
                                    <i data-lucide="chevron-down" class="absolute right-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Tanggal Bergabung <span class="text-error">*</span></label>
                                <input type="date" name="tanggal_bergabung" id="tanggal_bergabung" value="{{ old('tanggal_bergabung', date('Y-m-d')) }}" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Durasi Sewa (Bulan) <span class="text-error">*</span></label>
                                <input type="number" name="durasi" value="{{ old('durasi', 1) }}" min="1" max="12" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Status Pembayaran <span class="text-error">*</span></label>
                                <select name="status_pembayaran_awal" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm appearance-none cursor-pointer">
                                    <option value="paid">Sudah Lunas</option>
                                    <option value="pending">Belum Bayar (Tagihan)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-border">
                        <a href="{{ route('admin.user.index') }}" class="w-full sm:w-auto px-5 py-2.5 font-bold text-sm text-secondary text-center hover:text-foreground transition-colors">Batal</a>
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-hover transition-colors shadow-sm shadow-primary/30">
                            Simpan User & Kamar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-primary/5 border border-primary/20 rounded-2xl p-5 sticky top-8">
                <h3 class="font-bold text-sm md:text-base text-primary flex items-center gap-2 mb-3">
                    <i data-lucide="lightbulb" class="size-4 md:size-5"></i> Panduan
                </h3>
                <ul class="space-y-2 text-xs md:text-sm text-foreground">
                    <li class="flex gap-2 items-start"><i data-lucide="check" class="size-3.5 md:size-4 text-primary shrink-0 mt-0.5"></i> Gunakan alamat email aktif untuk keperluan notifikasi invoice tagihan.</li>
                    <li class="flex gap-2 items-start"><i data-lucide="check" class="size-3.5 md:size-4 text-primary shrink-0 mt-0.5"></i> Password minimal diisi 8 karakter kombinasi.</li>
                    <li class="flex gap-2 items-start"><i data-lucide="check" class="size-3.5 md:size-4 text-primary shrink-0 mt-0.5"></i> <b>Alokasi Kamar</b> digunakan jika orang tersebut aslinya sudah menempati kamar fisik sebelum aplikasi ini dibuat.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();

        // 1. Logika Pengecekan Kekuatan Password
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
                strengthInd.className = `text-[10px] font-bold mt-1 h-3 ${colors[str]}`;
            } else {
                strengthInd.textContent = '';
            }
            checkMatch();
        });

        function checkMatch() {
            if (!confirmPwd.value) { matchInd.textContent = ''; return; }
            if (pwd.value === confirmPwd.value) {
                matchInd.textContent = 'Password cocok';
                matchInd.className = 'text-[10px] font-bold mt-1 h-3 text-success';
            } else {
                matchInd.textContent = 'Password tidak sama';
                matchInd.className = 'text-[10px] font-bold mt-1 h-3 text-error';
            }
        }
        confirmPwd.addEventListener('input', checkMatch);

        // 2. Logika Buka Tutup Form Kamar & Tanggal Bergabung
        const toggleKamar = document.getElementById('toggle-kamar');
        const kamarFields = document.getElementById('kamar-fields');
        const kamarIdSelect = document.getElementById('kamar_id');
        const tanggalBergabungInput = document.getElementById('tanggal_bergabung');
        const roleSelect = document.getElementById('role_select');

        toggleKamar.addEventListener('change', function() {
            if(this.checked) {
                kamarFields.classList.remove('hidden');
                kamarIdSelect.setAttribute('required', 'required');
                tanggalBergabungInput.setAttribute('required', 'required');
                // Otomatis set dropdown role ke pilihan 'penghuni'
                roleSelect.value = 'penghuni'; 
            } else {
                kamarFields.classList.add('hidden');
                kamarIdSelect.removeAttribute('required');
                tanggalBergabungInput.removeAttribute('required');
            }
        });
    });
</script>
@endsection