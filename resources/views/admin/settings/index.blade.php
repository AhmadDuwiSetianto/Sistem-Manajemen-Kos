@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="flex-1 p-4 md:p-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 md:mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-foreground">Pengaturan Sistem</h1>
            <p class="text-secondary mt-1 text-sm md:text-base">Kelola profil kos, integrasi pembayaran, dan preferensi aplikasi</p>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="bg-success-light border border-success/20 p-3 md:p-4 mb-6 md:mb-8 rounded-xl flex items-center gap-3">
        <div class="size-8 bg-success/20 rounded-full flex items-center justify-center shrink-0">
            <i data-lucide="check-circle" class="size-4 md:size-5 text-success"></i>
        </div>
        <p class="text-success text-sm font-medium">{{ session('success') }}</p>
    </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
            
            <!-- Sidebar Menu (Desktop Only) -->
            <div class="lg:col-span-1 hidden lg:block">
                <div class="bg-white rounded-2xl shadow-sm border border-border p-5 sticky top-28">
                    <h3 class="text-[10px] md:text-xs font-bold text-secondary uppercase tracking-wider mb-4 px-2">Menu Pengaturan</h3>
                    <ul class="space-y-1.5">
                        <li>
                            <a href="#profil" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-primary bg-primary/10 transition-colors">
                                <i data-lucide="building" class="size-4"></i> Profil Kost
                            </a>
                        </li>
                        <li>
                            <a href="#pembayaran" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-secondary hover:text-foreground hover:bg-muted transition-colors">
                                <i data-lucide="credit-card" class="size-4"></i> Gateway Pembayaran
                            </a>
                        </li>
                        <li>
                            <a href="#sistem" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-secondary hover:text-foreground hover:bg-muted transition-colors">
                                <i data-lucide="sliders" class="size-4"></i> Preferensi Sistem
                            </a>
                        </li>
                    </ul>
                    
                    <div class="mt-8 pt-6 border-t border-border">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-hover transition-colors shadow-sm shadow-primary/30 cursor-pointer">
                            <i data-lucide="save" class="size-4"></i> Simpan Semua
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6 md:space-y-8">
                
                <!-- Section: Profil Kost -->
                <div id="profil" class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden scroll-mt-28">
                    <div class="px-5 md:px-6 py-4 border-b border-border bg-muted/30">
                        <h2 class="text-base md:text-lg font-bold text-foreground flex items-center gap-2">
                            <i data-lucide="building" class="size-4 md:size-5 text-primary"></i> Profil Kost
                        </h2>
                        <p class="text-[10px] md:text-xs text-secondary mt-1">Informasi utama yang akan ditampilkan ke publik.</p>
                    </div>
                    <div class="p-5 md:p-6 space-y-4 md:space-y-5">
                        <div>
                            <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Nama Kost / Bisnis <span class="text-error">*</span></label>
                            <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name'] ?? '') }}" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Email Kontak</label>
                                <input type="email" name="app_email" value="{{ old('app_email', $settings['app_email'] ?? '') }}" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Nomor WhatsApp/Telp</label>
                                <input type="text" name="app_phone" value="{{ old('app_phone', $settings['app_phone'] ?? '') }}" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Alamat Lengkap</label>
                            <textarea name="app_address" rows="3" class="w-full px-3.5 py-3 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all">{{ old('app_address', $settings['app_address'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section: Konfigurasi Pembayaran -->
                <div id="pembayaran" class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden scroll-mt-28">
                    <div class="px-5 md:px-6 py-4 border-b border-border bg-muted/30">
                        <h2 class="text-base md:text-lg font-bold text-foreground flex items-center gap-2">
                            <i data-lucide="credit-card" class="size-4 md:size-5 text-success"></i> Gateway Pembayaran
                        </h2>
                        <p class="text-[10px] md:text-xs text-secondary mt-1">Kredensial API Midtrans dan detail bank manual.</p>
                    </div>
                    <div class="p-5 md:p-6 space-y-5 md:space-y-6">
                        <div class="p-4 md:p-5 rounded-xl border border-warning/30 bg-warning-light/30">
                            <h4 class="text-sm font-bold text-warning-dark mb-4 flex items-center gap-2">
                                <i data-lucide="key" class="size-4"></i> Midtrans API Keys
                            </h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] md:text-xs font-bold text-secondary uppercase tracking-wider mb-1.5">Client Key</label>
                                    <input type="text" name="midtrans_client_key" value="{{ old('midtrans_client_key', $settings['midtrans_client_key'] ?? '') }}" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm font-mono text-secondary transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] md:text-xs font-bold text-secondary uppercase tracking-wider mb-1.5">Server Key (Rahasia)</label>
                                    <input type="password" name="midtrans_server_key" value="{{ old('midtrans_server_key', $settings['midtrans_server_key'] ?? '') }}" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm font-mono text-secondary transition-all">
                                </div>
                                
                                <div class="flex items-center justify-between pt-3 border-t border-warning/20">
                                    <div>
                                        <p class="text-xs md:text-sm font-bold text-foreground">Production Mode</p>
                                        <p class="text-[10px] md:text-xs text-secondary">Aktifkan saat siap menerima uang sungguhan.</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="midtrans_is_production" class="sr-only peer" {{ old('midtrans_is_production', $settings['midtrans_is_production'] ?? false) ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-muted rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-success after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-border after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Informasi Rekening Manual (Opsional)</label>
                            <textarea name="bank_account" rows="2" class="w-full px-3.5 py-3 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all" placeholder="BCA 1234567890 a.n John Doe">{{ old('bank_account', $settings['bank_account'] ?? '') }}</textarea>
                            <p class="text-[10px] text-secondary mt-1">Ditampilkan jika gateway pembayaran dinonaktifkan.</p>
                        </div>
                    </div>
                </div>

                <!-- Section: Preferensi Sistem -->
                <div id="sistem" class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden scroll-mt-28">
                    <div class="px-5 md:px-6 py-4 border-b border-border bg-muted/30">
                        <h2 class="text-base md:text-lg font-bold text-foreground flex items-center gap-2">
                            <i data-lucide="sliders" class="size-4 md:size-5 text-purple-500"></i> Preferensi Sistem
                        </h2>
                        <p class="text-[10px] md:text-xs text-secondary mt-1">Pengaturan operasional dan pemeliharaan.</p>
                    </div>
                    <div class="p-5 md:p-6 space-y-5 md:space-y-6">
                        <div class="flex items-center justify-between p-4 border border-error/20 bg-error-light/30 rounded-xl">
                            <div>
                                <p class="text-xs md:text-sm font-bold text-error">Maintenance Mode</p>
                                <p class="text-[10px] md:text-xs text-error/80 mt-0.5">Website akan dikunci dari penghuni (mode perbaikan).</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="maintenance_mode" class="sr-only peer" {{ old('maintenance_mode', $settings['maintenance_mode'] ?? false) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-muted rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-error after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-border after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                            </label>
                        </div>

                        <!-- Tombol Simpan (Mobile Only) -->
                        <div class="pt-2 lg:hidden">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-hover transition-colors shadow-sm shadow-primary/30">
                                <i data-lucide="save" class="size-4"></i> Simpan Semua Pengaturan
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();

        // Highlight Active Sidebar Menu on Scroll
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
                a.className = "flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-secondary hover:text-foreground hover:bg-muted transition-colors";
                if (a.getAttribute("href").includes(current)) {
                    a.className = "flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-primary bg-primary/10 transition-colors";
                }
            });
        });
    });
</script>
@endsection