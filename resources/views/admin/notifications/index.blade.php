@extends('layouts.admin') {{-- Sesuaikan jika nama file layout utama kamu berbeda, misal: 'admin.layout' atau 'layouts.app' --}}

@section('title', 'Semua Notifikasi')

@section('content')
<div class="flex flex-col gap-6">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-foreground">Semua Notifikasi</h2>
            <p class="text-sm text-secondary mt-1">Pantau aktivitas, pemesanan kamar, dan pembaruan sistem.</p>
        </div>

        @if(Auth::check() && Auth::user()->unreadNotifications->count() > 0)
            <form action="{{ route('admin.notifications.markAllRead') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="flex items-center gap-2 bg-white border border-border text-foreground hover:bg-muted px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm cursor-pointer">
                    <i data-lucide="check-check" class="size-4 text-primary"></i>
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white border border-border rounded-2xl shadow-sm overflow-hidden">
        @forelse($notifications as $notification)
            @php
                // Cek apakah notifikasi belum dibaca
                $isUnread = is_null($notification->read_at);
                
                // Ambil data dari array notifikasi (dengan fallback jika kosong)
                $type = $notification->data['type'] ?? 'info';
                $icon = $notification->data['icon'] ?? 'bell';
                $title = $notification->data['title'] ?? 'Notifikasi Sistem';
                $message = $notification->data['message'] ?? 'Ada pembaruan atau aktivitas baru di sistem Inna Kos.';
                $url = $notification->data['url'] ?? '#';

                // Pewarnaan dinamis berdasarkan tipe (misal: payment hijau, sisanya biru/primary)
                $bgIcon = $type == 'payment' ? 'bg-success-light' : 'bg-primary/10';
                $textIcon = $type == 'payment' ? 'text-success' : 'text-primary';
            @endphp

            <div class="flex flex-col sm:flex-row gap-4 p-5 sm:p-6 border-b border-border hover:bg-muted/30 transition-colors {{ $isUnread ? 'bg-primary/5' : '' }}">
                
                <div class="size-12 rounded-full {{ $bgIcon }} flex items-center justify-center shrink-0">
                    <i data-lucide="{{ $icon }}" class="size-6 {{ $textIcon }}"></i>
                </div>

                <div class="flex-1">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 mb-1.5">
                        <h4 class="text-base font-bold text-foreground {{ $isUnread ? 'text-primary' : '' }}">
                            {{ $title }}
                        </h4>
                        <span class="text-xs text-secondary font-medium flex items-center gap-1.5 shrink-0 sm:mt-1">
                            <i data-lucide="clock" class="size-3"></i>
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </div>
                    
                    <p class="text-sm text-secondary leading-relaxed mb-3">
                        {{ $message }}
                    </p>
                    
                    @if($url !== '#')
                        <a href="{{ $url }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:text-primary-hover transition-colors bg-primary/10 px-3 py-1.5 rounded-lg">
                            Lihat Detail <i data-lucide="arrow-right" class="size-3"></i>
                        </a>
                    @endif
                </div>

                @if($isUnread)
                    <div class="shrink-0 flex items-center justify-center sm:self-center mt-3 sm:mt-0">
                        <span class="size-3 bg-primary rounded-full shadow-sm shadow-primary/50" title="Belum dibaca"></span>
                    </div>
                @endif
            </div>
        @empty
            <div class="p-12 sm:p-20 flex flex-col items-center justify-center text-center">
                <div class="size-20 bg-muted rounded-full flex items-center justify-center mb-5">
                    <i data-lucide="bell-off" class="size-10 text-secondary/60"></i>
                </div>
                <h3 class="text-lg font-bold text-foreground mb-2">Belum Ada Notifikasi</h3>
                <p class="text-sm text-secondary max-w-md mx-auto">
                    Anda telah membaca semua notifikasi atau memang belum ada pembaruan aktivitas yang masuk ke sistem.
                </p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="mt-2 flex justify-center sm:justify-end">
            {{ $notifications->links() }}
        </div>
    @endif

</div>
@endsection