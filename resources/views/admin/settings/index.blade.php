@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-foreground">Pengaturan Sistem</h1>
        <p class="text-secondary mt-1">Kelola profil kost, integrasi pembayaran, dan preferensi aplikasi</p>
    </div>
</div>

@if(session('success'))
<div class="bg-success-light border border-success/20 p-4 mb-6 rounded-2xl flex items-center gap-3">
    <div class="size-8 bg-success/20 rounded-full flex items-center justify-center shrink-0">
        <i data-lucide="check-circle" class="size-5 text-success"></i>
    </div>
    <p class="text-success font-medium">{{ session('success') }}</p>
</div>
@endif

<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 hidden lg:block">
            <div class="bg-white rounded-2xl shadow-sm border border-border p-4 sticky top-28">
                <h3 class="text-xs font-bold text-secondary uppercase tracking-wider mb-4 px-3">Menu Pengaturan</h3>
                <ul class="space-y-1">
                    <li>
                        <a href="#profil" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-primary bg-primary/10 transition-colors">
                            <i data-lucide="building" class="size-4"></i> Profil Kost
                        </a>
                    </li>
                    <li>
                        <a href="#pembayaran" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-secondary hover:text-foreground hover:bg-muted transition-colors">
                            <i class="size-4" data-lucide="credit-card"></i> Gateway Pembayaran
                        </a>
                    </li>
                    <li>
                        <a href="#sistem" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-secondary hover:text-foreground hover:bg-muted transition-colors">
                            <i class="size-4" data-lucide="sliders"></i> Preferensi Sistem
                        </a>
                    </li>
                </ul>
                
                <div class="mt-8 px-3">
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-primary-hover transition-colors shadow-sm shadow-primary/30 cursor-pointer">
                        <i data-lucide="save" class="size-4"></i> Simpan Semua
                    </button>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-8">
            
            <div id="profil" class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden scroll-mt-28">
                <div class="px-6 py-5 border-b border-border bg-muted/30">
                    <h2 class="text-lg font-bold text-foreground flex items-center gap-2">
                        <i data-lucide="building" class="size-5 text-primary"></i> Profil Kost
                    </h2>
                    <p class="text-xs text-secondary mt-1">Informasi utama yang akan ditampilkan ke calon penghuni.</p>
                </div>
                <div class="p-6 md:p-8 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-foreground mb-2">Nama Kost / Bisnis <span class="text-error">*</span></label>
                        <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name'] ?? '') }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Email Kontak</label>
                            <input type="email" name="app_email" value="{{ old('app_email', $settings['app_email'] ?? '') }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Nomor WhatsApp/Telepon</label>
                            <input type="text" name="app_phone" value="{{ old('app_phone', $settings['app_phone'] ?? '') }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-foreground mb-2">Alamat Lengkap</label>
                        <textarea name="app_address" rows="3" class="w-full px-4 py-3 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm">{{ old('app_address', $settings['app_address'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div id="pembayaran" class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden scroll-mt-28">
                <div class="px-6 py-5 border-b border-border bg-muted/30">
                    <h2 class="text-lg font-bold text-foreground flex items-center gap-2">
                        <i data-lucide="credit-card" class="size-5 text-success"></i> Konfigurasi Pembayaran
                    </h2>
                    <p class="text-xs text-secondary mt-1">Kredensial API Midtrans dan detail bank manual.</p>
                </div>
                <div class="p-6 md:p-8 space-y-6">
                    <div class="p-4 rounded-xl border border-warning/30 bg-warning-light/30">
                        <h4 class="text-sm font-bold text-warning-dark mb-4 flex items-center gap-2">
                            <i data-lucide="key" class="size-4"></i> Midtrans API Keys
                        </h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-secondary uppercase tracking-wider mb-1.5">Client Key</label>
                                <input type="text" name="midtrans_client_key" value="{{ old('midtrans_client_key', $settings['midtrans_client_key'] ?? '') }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm font-mono text-secondary">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-secondary uppercase tracking-wider mb-1.5">Server Key (Rahasia)</label>
                                <input type="password" name="midtrans_server_key" value="{{ old('midtrans_server_key', $settings['midtrans_server_key'] ?? '') }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm font-mono text-secondary">
                            </div>
                            
                            <div class="flex items-center justify-between pt-2">
                                <div>
                                    <p class="text-sm font-bold text-foreground">Production Mode</p>
                                    <p class="text-xs text-secondary">Aktifkan ini jika aplikasi sudah siap menerima uang sungguhan.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="midtrans_is_production" class="sr-only peer" {{ old('midtrans_is_production', $settings['midtrans_is_production'] ?? false) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-muted rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-success after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-border after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-foreground mb-2">Informasi Rekening Manual (Opsional)</label>
                        <textarea name="bank_account" rows="2" class="w-full px-4 py-3 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm" placeholder="Misal: BCA 1234567890 a.n John Doe">{{ old('bank_account', $settings['bank_account'] ?? '') }}</textarea>
                        <p class="text-[11px] text-secondary mt-1">Ditampilkan ke user jika pembayaran gateway error/tidak digunakan.</p>
                    </div>
                </div>
            </div>

            <div id="sistem" class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden scroll-mt-28">
                <div class="px-6 py-5 border-b border-border bg-muted/30">
                    <h2 class="text-lg font-bold text-foreground flex items-center gap-2">
                        <i data-lucide="sliders" class="size-5 text-purple-500"></i> Preferensi Sistem
                    </h2>
                    <p class="text-xs text-secondary mt-1">Pengaturan operasional aplikasi dasar.</p>
                </div>
                <div class="p-6 md:p-8 space-y-6">
                    <div class="flex items-center justify-between p-4 border border-error/20 bg-error-light/30 rounded-xl">
                        <div>
                            <p class="text-sm font-bold text-error">Maintenance Mode (Mode Perbaikan)</p>
                            <p class="text-xs text-secondary mt-0.5">Website tidak akan bisa diakses oleh penghuni/calon penghuni.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="maintenance_mode" class="sr-only peer" {{ old('maintenance_mode', $settings['maintenance_mode'] ?? false) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-muted rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-error after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-border after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        </label>
                    </div>

                    <div class="pt-4 lg:hidden">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary-hover transition-colors shadow-sm shadow-primary/30">
                            <i data-lucide="save" class="size-5"></i> Simpan Semua Pengaturan
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();

        // Script sederhana untuk highlight menu kiri saat discroll (Opsional UX enhancement)
        const sections = document.querySelectorAll("div[id]");
        const navLi = document.querySelectorAll(".sticky ul li a");

        window.addEventListener("scroll", () => {
            let current = "";
            sections.forEach((section) => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 150) {
                    current = section.getAttribute("id");
                }
            });

            navLi.forEach((a) => {
                a.className = "flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-secondary hover:text-foreground hover:bg-muted transition-colors";
                if (a.getAttribute("href").includes(current)) {
                    a.className = "flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-primary bg-primary/10 transition-colors";
                }
            });
        });
    });
</script>
@endsection