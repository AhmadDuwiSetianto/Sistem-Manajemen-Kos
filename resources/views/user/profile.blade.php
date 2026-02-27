@extends('layouts.user')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-foreground">Profil Saya</h1>
            <p class="text-secondary mt-1">Kelola informasi data diri dan pengaturan akun Anda</p>
        </div>
        <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-border text-sm font-semibold text-foreground rounded-xl hover:bg-muted transition-colors shadow-sm">
            <i data-lucide="arrow-left" class="size-4 text-secondary"></i> Kembali ke Dashboard
        </a>
    </div>

    @if(session('success'))
    <div class="bg-success-light border border-success/20 p-4 mb-6 rounded-2xl flex items-center gap-3">
        <div class="size-10 bg-success/20 rounded-full flex items-center justify-center shrink-0">
            <i data-lucide="check-circle-2" class="size-5 text-success"></i>
        </div>
        <p class="text-success font-bold">{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-border overflow-hidden">
                <div class="px-6 py-5 border-b border-border bg-muted/30">
                    <h2 class="text-lg font-bold text-foreground flex items-center gap-2">
                        <i data-lucide="user-cog" class="size-5 text-primary"></i> Informasi Pribadi
                    </h2>
                </div>
                
                <form action="{{ route('user.profile.update') }}" method="POST" class="p-6 md:p-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-bold text-foreground mb-2">Nama Lengkap <span class="text-error">*</span></label>
                            <div class="relative">
                                <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                       class="w-full pl-11 pr-4 py-3 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all @error('name') border-error @enderror" required>
                            </div>
                            @error('name')
                            <p class="mt-1.5 text-xs font-medium text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-foreground mb-2">Alamat Email</label>
                            <div class="relative">
                                <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                                <input type="email" value="{{ $user->email }}" 
                                       class="w-full pl-11 pr-4 py-3 bg-muted/50 border border-border rounded-xl text-secondary cursor-not-allowed outline-none" readonly>
                            </div>
                            <p class="mt-1.5 text-[11px] font-medium text-secondary"><i data-lucide="info" class="inline size-3 mr-1"></i>Email digunakan untuk login dan tidak dapat diubah.</p>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-bold text-foreground mb-2">Nomor WhatsApp/Telepon <span class="text-error">*</span></label>
                            <div class="relative">
                                <i data-lucide="phone" class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                       class="w-full pl-11 pr-4 py-3 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all @error('phone') border-error @enderror" required>
                            </div>
                            @error('phone')
                            <p class="mt-1.5 text-xs font-medium text-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-foreground mb-2">Nomor KTP (NIK)</label>
                            <div class="relative">
                                <i data-lucide="credit-card" class="absolute left-4 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                                <input type="text" value="{{ $user->identity_number }}" 
                                       class="w-full pl-11 pr-4 py-3 bg-muted/50 border border-border rounded-xl text-secondary cursor-not-allowed outline-none" readonly>
                            </div>
                            <p class="mt-1.5 text-[11px] font-medium text-secondary"><i data-lucide="info" class="inline size-3 mr-1"></i>Hubungi admin jika ada kesalahan NIK.</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="address" class="block text-sm font-bold text-foreground mb-2">Alamat Asal <span class="text-error">*</span></label>
                        <textarea id="address" name="address" rows="3"
                                  class="w-full p-4 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all resize-y @error('address') border-error @enderror" required>{{ old('address', $user->address) }}</textarea>
                        @error('address')
                        <p class="mt-1.5 text-xs font-medium text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-8 pt-6 border-t border-border flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary-hover transition-colors shadow-lg shadow-primary/30 cursor-pointer">
                            <i data-lucide="save" class="size-5"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-white rounded-3xl shadow-sm border border-border overflow-hidden">
                <div class="px-6 py-5 border-b border-border">
                    <h3 class="font-bold text-foreground flex items-center gap-2">
                        <i data-lucide="shield-check" class="size-5 text-success"></i> Status Akun
                    </h3>
                </div>
                
                <div class="p-6 space-y-6">
                    
                    <div class="flex items-center gap-4 pb-6 border-b border-border">
                        <div class="size-16 rounded-full bg-muted p-1 ring-1 ring-border">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile" class="w-full h-full rounded-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=165DFF&color=fff&bold=true" alt="Profile" class="w-full h-full rounded-full object-cover">
                            @endif
                        </div>
                        <div>
                            <span class="inline-flex px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md {{ Auth::user()->role == 'penghuni' ? 'bg-success-light text-success' : 'bg-warning-light text-warning-dark' }}">
                                {{ str_replace('_', ' ', Auth::user()->role) }}
                            </span>
                            <p class="text-xs font-semibold text-secondary mt-1.5">Member Aktif</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <p class="text-[11px] font-bold text-secondary uppercase tracking-wider mb-1">Bergabung Sejak</p>
                            <p class="text-sm font-semibold text-foreground flex items-center gap-2">
                                <i data-lucide="calendar-days" class="size-4 text-primary"></i>
                                {{ $user->created_at->format('d F Y') }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-[11px] font-bold text-secondary uppercase tracking-wider mb-1">Total Transaksi</p>
                            <p class="text-sm font-semibold text-foreground flex items-center gap-2">
                                <i data-lucide="receipt" class="size-4 text-primary"></i>
                                {{ $user->bookings->count() }} Kali Booking
                            </p>
                        </div>
                    </div>

                    @if($user->getActiveBooking())
                    <div class="p-4 bg-primary/5 rounded-2xl border border-primary/20">
                        <p class="text-xs font-bold text-primary uppercase tracking-wider mb-2 flex items-center gap-2">
                            <i data-lucide="key" class="size-3.5"></i> Kamar Saat Ini
                        </p>
                        <p class="font-bold text-foreground text-lg">Kamar {{ $user->getActiveBooking()->kamar->nomor_kamar }}</p>
                        <p class="text-xs font-medium text-secondary mt-1">Sewa sejak {{ $user->getActiveBooking()->tanggal_masuk->format('d/m/Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-3xl p-6 text-white shadow-lg relative overflow-hidden">
                <div class="absolute -right-6 -top-6 opacity-10">
                    <i data-lucide="life-buoy" class="size-32"></i>
                </div>
                <div class="relative z-10">
                    <h4 class="font-bold text-lg mb-2">Butuh Bantuan?</h4>
                    <p class="text-sm text-slate-300 mb-5 leading-relaxed">Jika Anda mengalami kendala terkait aplikasi atau fasilitas kamar, hubungi admin kami.</p>
                    <a href="https://wa.me/6281234567890" target="_blank" class="inline-flex items-center justify-center w-full py-3 bg-success hover:bg-success/90 text-white font-bold text-sm rounded-xl transition-colors shadow-sm">
                        <i data-lucide="message-circle" class="size-4 mr-2"></i> Chat WhatsApp Admin
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection