@php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
@endphp
@extends('layouts.admin')

@section('title', 'Kelola Kamar')
@push('styles')
<style>
    /* MEMAKSA SWEETALERT2 MENGIKUTI RADIUS NEXUS CRM */
    div:where(.swal2-container) div:where(.swal2-popup) {
        border-radius: 1.2rem !important;
        padding: 1.5rem !important;
    }
    div:where(.swal2-container) button:where(.swal2-styled) {
        border-radius: 0.75rem !important;
        font-weight: 600 !important;
        padding: 0.6rem 1.5rem !important;
    }
</style>
@endpush

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-foreground">Kelola Kamar</h1>
        <p class="text-secondary mt-1">Kelola data kamar kos yang tersedia</p>
    </div>
    <a href="{{ route('admin.kamar.create') }}" class="inline-flex items-center px-5 py-2.5 bg-primary hover:bg-primary-hover text-white font-semibold rounded-xl transition-all shadow-sm shadow-primary/30">
        <i data-lucide="plus" class="size-5 mr-2"></i> Tambah Kamar
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="flex flex-col rounded-2xl border border-border p-5 bg-white">
        <div class="flex items-center gap-3 mb-2">
            <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center">
                <i data-lucide="home" class="size-5 text-primary"></i>
            </div>
            <p class="font-medium text-secondary">Total Kamar</p>
        </div>
        <p class="font-bold text-3xl text-foreground">{{ $totalKamar ?? $kamars->count() }}</p>
    </div>

    <div class="flex flex-col rounded-2xl border border-border p-5 bg-white">
        <div class="flex items-center gap-3 mb-2">
            <div class="size-10 bg-success-light rounded-xl flex items-center justify-center">
                <i data-lucide="door-open" class="size-5 text-success"></i>
            </div>
            <p class="font-medium text-secondary">Tersedia</p>
        </div>
        <p class="font-bold text-3xl text-foreground">{{ $kamars->where('status', 'tersedia')->count() }}</p>
    </div>

    <div class="flex flex-col rounded-2xl border border-border p-5 bg-white">
        <div class="flex items-center gap-3 mb-2">
            <div class="size-10 bg-muted rounded-xl flex items-center justify-center">
                <i data-lucide="user-check" class="size-5 text-foreground"></i>
            </div>
            <p class="font-medium text-secondary">Terisi</p>
        </div>
        <p class="font-bold text-3xl text-foreground">{{ $kamars->where('status', 'terisi')->count() }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden">
    <div class="px-6 py-5 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h2 class="text-lg font-bold text-foreground">Daftar Kamar</h2>
        <div class="relative w-full sm:w-72">
            <input type="text" placeholder="Cari kamar..." class="w-full pl-10 pr-4 py-2.5 bg-muted border-none rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm placeholder:text-secondary text-foreground">
            <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-border">
            <thead class="bg-muted/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Kamar</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Tipe & Ukuran</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Harga/Bulan</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Fasilitas</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-secondary uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-border">
                @forelse($kamars as $kamar)
                <tr class="hover:bg-muted/30 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-4">
                            @if($kamar->gambar)
                                @php
                                    $gambarUrl = $kamar->gambar;
                                    if (!Str::startsWith($gambarUrl, ['http://', 'https://'])) {
                                        if (Storage::disk('public')->exists($kamar->gambar)) {
                                            $gambarUrl = asset('storage/' . $kamar->gambar);
                                        } else {
                                            $gambarUrl = asset('images/default-room.jpg');
                                        }
                                    }
                                @endphp
                                <img src="{{ $gambarUrl }}" alt="Kamar" class="size-12 rounded-xl object-cover ring-1 ring-border" onerror="this.src='{{ asset('images/default-room.jpg') }}'">
                            @else
                                <div class="size-12 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                                    <i data-lucide="home" class="size-5 text-primary"></i>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-bold text-foreground">Kamar {{ $kamar->nomor_kamar }}</p>
                                <p class="text-xs text-secondary mt-0.5 truncate max-w-[150px]">{{ Str::limit($kamar->deskripsi, 30) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-semibold text-foreground">{{ $kamar->tipe_kamar }}</p>
                        <p class="text-xs text-secondary mt-0.5">{{ $kamar->ukuran_kamar }} m²</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-bold text-foreground">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusMap = [
                                'tersedia' => ['bg' => 'bg-success-light', 'text' => 'text-success', 'icon' => 'check-circle'],
                                'dipesan' => ['bg' => 'bg-warning-light', 'text' => 'text-warning-dark', 'icon' => 'clock'],
                                'terisi' => ['bg' => 'bg-primary/10', 'text' => 'text-primary', 'icon' => 'user-check'],
                                'maintenance' => ['bg' => 'bg-error-light', 'text' => 'text-error', 'icon' => 'wrench']
                            ];
                            $currStatus = $statusMap[$kamar->status] ?? ['bg' => 'bg-muted', 'text' => 'text-secondary', 'icon' => 'info'];
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $currStatus['bg'] }} {{ $currStatus['text'] }}">
                            <i data-lucide="{{ $currStatus['icon'] }}" class="size-3"></i>
                            {{ $kamar->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1.5 max-w-[200px]">
                            @php
                                $fasilitasList = is_array($kamar->fasilitas) ? $kamar->fasilitas : explode(',', $kamar->fasilitas);
                                $fasilitasList = array_filter($fasilitasList, fn($item) => !empty(trim($item)));
                            @endphp
                            @foreach(array_slice($fasilitasList, 0, 2) as $fasilitas)
                            <span class="inline-flex items-center bg-muted text-secondary text-[10px] font-semibold px-2 py-0.5 rounded-md">
                                {{ trim($fasilitas) }}
                            </span>
                            @endforeach
                            @if(count($fasilitasList) > 2)
                            <span class="inline-flex items-center bg-primary/10 text-primary text-[10px] font-bold px-2 py-0.5 rounded-md">
                                +{{ count($fasilitasList) - 2 }}
                            </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.kamar.edit', $kamar) }}" class="size-8 flex items-center justify-center rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition-colors" title="Edit">
                                <i data-lucide="pencil" class="size-4"></i>
                            </a>
                            
                            <form action="{{ route('admin.kamar.destroy', $kamar) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="size-8 flex items-center justify-center rounded-lg bg-error-light text-error hover:bg-error hover:text-white transition-colors cursor-pointer" onclick="deleteConfirm(this, 'Kamar {{ $kamar->nomor_kamar }}')" title="Hapus">
                                    <i data-lucide="trash-2" class="size-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i data-lucide="home" class="size-12 text-muted mb-3"></i>
                            <p class="text-sm font-semibold text-foreground">Belum ada data kamar</p>
                            <p class="text-xs text-secondary mt-1">Mulai tambahkan kamar kos Anda.</p>
                            <a href="{{ route('admin.kamar.create') }}" class="mt-4 px-4 py-2 bg-primary text-white text-sm font-medium rounded-xl hover:bg-primary-hover transition-colors">Tambah Kamar</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($kamars, 'links'))
    <div class="px-6 py-4 border-t border-border bg-white flex items-center justify-between">
        <p class="text-xs text-secondary font-medium">
            @if($kamars->total() > 0)
            Menampilkan {{ $kamars->firstItem() }} - {{ $kamars->lastItem() }} dari {{ $kamars->total() }} kamar
            @endif
        </p>
        <div class="flex gap-2">
            @if(!$kamars->onFirstPage())
            <a href="{{ $kamars->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-border text-xs font-semibold text-foreground hover:bg-muted transition-colors">Prev</a>
            @endif
            @if($kamars->hasMorePages())
            <a href="{{ $kamars->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-border text-xs font-semibold text-foreground hover:bg-muted transition-colors">Next</a>
            @endif
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        const searchInput = document.querySelector('input[type="text"]');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        }
    });

    // FUNGSI UNTUK KONFIRMASI HAPUS
    function deleteConfirm(button, itemName) {
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: `Anda akan menghapus data ${itemName}. Data yang dihapus tidak dapat dikembalikan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ED6B60', // Warna merah error
            cancelButtonColor: '#6A7686', // Warna abu-abu secondary
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user klik "Ya, Hapus!", submit form tersebut
                button.closest('form').submit();
            }
        });
    }

    // FUNGSI UNTUK MENAMPILKAN POP UP SUKSES/GAGAL DARI SESSION
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
            confirmButtonColor: '#165DFF' // Warna primary
        });
    @endif
</script>
@endsection