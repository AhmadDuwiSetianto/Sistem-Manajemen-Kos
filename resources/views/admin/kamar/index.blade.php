@php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
@endphp
@extends('layouts.admin')

@section('title', 'Kelola Kamar')

@push('styles')
<style>
    /* Custom SweetAlert2 untuk Inna Kos */
    div:where(.swal2-container) div:where(.swal2-popup) { border-radius: 1.2rem !important; padding: 1.5rem !important; }
    div:where(.swal2-container) button:where(.swal2-styled) { border-radius: 0.75rem !important; font-weight: 600 !important; padding: 0.6rem 1.5rem !important; }
</style>
@endpush

@section('content')
<div class="flex-1 p-4 md:p-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 md:mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-foreground">Kelola Kamar</h1>
            <p class="text-secondary mt-1 text-sm md:text-base">Kelola data kamar kos yang tersedia</p>
        </div>
        <a href="{{ route('admin.kamar.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-primary hover:bg-primary-hover text-white font-bold text-sm rounded-xl transition-all shadow-sm shadow-primary/30">
            <i data-lucide="plus" class="size-4 mr-2"></i> Tambah Kamar
        </a>
    </div>

    <!-- Stats Cards (Mobile: 1 Kolom, Tablet/Desktop: 3 Kolom) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4 mb-6 md:mb-8">
        <div class="flex flex-col rounded-xl border border-border p-4 md:p-5 bg-white shadow-sm hover:ring-1 hover:ring-primary transition-all">
            <div class="flex items-center gap-3 mb-2">
                <div class="size-9 md:size-10 bg-primary/10 rounded-lg flex items-center justify-center">
                    <i data-lucide="home" class="size-4 md:size-5 text-primary"></i>
                </div>
                <p class="font-medium text-secondary text-xs md:text-sm">Total Kamar</p>
            </div>
            <p class="font-black text-2xl md:text-3xl text-foreground">{{ $totalKamar ?? $kamars->count() }}</p>
        </div>

        <div class="flex flex-col rounded-xl border border-border p-4 md:p-5 bg-white shadow-sm hover:ring-1 hover:ring-success transition-all">
            <div class="flex items-center gap-3 mb-2">
                <div class="size-9 md:size-10 bg-success-light rounded-lg flex items-center justify-center">
                    <i data-lucide="door-open" class="size-4 md:size-5 text-success"></i>
                </div>
                <p class="font-medium text-secondary text-xs md:text-sm">Kamar Tersedia</p>
            </div>
            <p class="font-black text-2xl md:text-3xl text-foreground">{{ $kamars->where('status', 'tersedia')->count() }}</p>
        </div>

        <div class="flex flex-col rounded-xl border border-border p-4 md:p-5 bg-white shadow-sm hover:ring-1 hover:ring-foreground transition-all">
            <div class="flex items-center gap-3 mb-2">
                <div class="size-9 md:size-10 bg-muted rounded-lg flex items-center justify-center">
                    <i data-lucide="user-check" class="size-4 md:size-5 text-foreground"></i>
                </div>
                <p class="font-medium text-secondary text-xs md:text-sm">Kamar Terisi</p>
            </div>
            <p class="font-black text-2xl md:text-3xl text-foreground">{{ $kamars->where('status', 'terisi')->count() }}</p>
        </div>
    </div>

    <!-- Tabel Kamar -->
    <div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden">
        <div class="px-5 py-4 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h2 class="text-base md:text-lg font-bold text-foreground">Daftar Kamar</h2>
            <div class="relative w-full sm:w-64">
                <input type="text" placeholder="Cari kamar..." class="w-full pl-9 pr-4 py-2 bg-muted border-none rounded-xl text-xs md:text-sm outline-none placeholder:text-secondary text-foreground focus:ring-2 focus:ring-primary/20">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-muted/50">
                    <tr>
                        <th class="px-5 py-3 text-left text-[10px] md:text-xs font-semibold text-secondary uppercase">Kamar</th>
                        <th class="px-5 py-3 text-left text-[10px] md:text-xs font-semibold text-secondary uppercase">Tipe & Ukuran</th>
                        <th class="px-5 py-3 text-left text-[10px] md:text-xs font-semibold text-secondary uppercase">Harga/Bulan</th>
                        <th class="px-5 py-3 text-center text-[10px] md:text-xs font-semibold text-secondary uppercase">Status</th>
                        <th class="px-5 py-3 text-left text-[10px] md:text-xs font-semibold text-secondary uppercase">Fasilitas</th>
                        <th class="px-5 py-3 text-right text-[10px] md:text-xs font-semibold text-secondary uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse($kamars as $kamar)
                    <tr class="hover:bg-muted/30 transition-colors">
                        <td class="px-5 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                @php
                                    $gambarUrl = asset('images/default-room.jpg');
                                    if ($kamar->gambar) {
                                        if (Str::startsWith($kamar->gambar, ['http://', 'https://'])) {
                                            $gambarUrl = $kamar->gambar;
                                        } elseif (Storage::disk('public')->exists($kamar->gambar)) {
                                            $gambarUrl = asset('storage/' . $kamar->gambar);
                                        }
                                    }
                                @endphp
                                <img src="{{ $gambarUrl }}" alt="Kamar" class="size-10 md:size-12 rounded-lg object-cover ring-1 ring-border shrink-0">
                                <div>
                                    <p class="text-xs md:text-sm font-bold text-foreground">Kamar {{ $kamar->nomor_kamar }}</p>
                                    <p class="text-[10px] md:text-xs text-secondary mt-0.5 truncate max-w-[120px]">{{ Str::limit($kamar->deskripsi, 25) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <p class="text-xs md:text-sm font-semibold text-foreground">{{ $kamar->tipe_kamar }}</p>
                            <p class="text-[10px] md:text-xs text-secondary mt-0.5">{{ $kamar->ukuran_kamar }} m²</p>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <p class="text-xs md:text-sm font-bold text-foreground">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-center">
                            @php
                                $statusMap = [
                                    'tersedia' => ['bg' => 'bg-success-light', 'text' => 'text-success'],
                                    'dipesan' => ['bg' => 'bg-warning-light', 'text' => 'text-warning-dark'],
                                    'terisi' => ['bg' => 'bg-primary/10', 'text' => 'text-primary'],
                                    'maintenance' => ['bg' => 'bg-error-light', 'text' => 'text-error']
                                ];
                                $currStatus = $statusMap[$kamar->status] ?? ['bg' => 'bg-muted', 'text' => 'text-secondary'];
                            @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[9px] md:text-[10px] font-bold uppercase tracking-wider {{ $currStatus['bg'] }} {{ $currStatus['text'] }}">
                                {{ $kamar->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex flex-wrap gap-1 max-w-[150px] md:max-w-[200px]">
                                @php
                                    $fasilitasList = is_array($kamar->fasilitas) ? $kamar->fasilitas : explode(',', $kamar->fasilitas);
                                    $fasilitasList = array_filter($fasilitasList, fn($item) => !empty(trim($item)));
                                @endphp
                                @foreach(array_slice($fasilitasList, 0, 2) as $fasilitas)
                                <span class="inline-flex items-center bg-muted text-secondary text-[9px] md:text-[10px] font-semibold px-2 py-0.5 rounded-md">
                                    {{ trim($fasilitas) }}
                                </span>
                                @endforeach
                                @if(count($fasilitasList) > 2)
                                <span class="inline-flex items-center bg-primary/10 text-primary text-[9px] md:text-[10px] font-bold px-1.5 py-0.5 rounded-md">
                                    +{{ count($fasilitasList) - 2 }}
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.kamar.edit', $kamar) }}" class="size-7 md:size-8 flex items-center justify-center rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition-colors cursor-pointer" title="Edit">
                                    <i data-lucide="pencil" class="size-3.5 md:size-4"></i>
                                </a>
                                <form action="{{ route('admin.kamar.destroy', $kamar) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="size-7 md:size-8 flex items-center justify-center rounded-lg bg-error-light text-error hover:bg-error hover:text-white transition-colors cursor-pointer" onclick="deleteConfirm(this, 'Kamar {{ $kamar->nomor_kamar }}')" title="Hapus">
                                        <i data-lucide="trash-2" class="size-3.5 md:size-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i data-lucide="home" class="size-10 text-muted mb-2"></i>
                                <p class="text-xs font-semibold text-foreground">Belum ada data kamar</p>
                                <a href="{{ route('admin.kamar.create') }}" class="mt-3 px-4 py-2 bg-primary text-white text-[10px] md:text-xs font-bold rounded-lg hover:bg-primary-hover transition-colors">Tambah Kamar</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($kamars, 'links'))
        <div class="px-5 py-3 border-t border-border bg-white flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-[10px] md:text-xs text-secondary font-medium text-center sm:text-left">
                @if($kamars->total() > 0)
                Menampilkan {{ $kamars->firstItem() }} - {{ $kamars->lastItem() }} dari {{ $kamars->total() }} kamar
                @endif
            </p>
            <div class="flex gap-2">
                @if(!$kamars->onFirstPage())
                <a href="{{ $kamars->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-border text-[10px] md:text-xs font-semibold text-foreground hover:bg-muted transition-colors">Prev</a>
                @endif
                @if($kamars->hasMorePages())
                <a href="{{ $kamars->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-border text-[10px] md:text-xs font-semibold text-foreground hover:bg-muted transition-colors">Next</a>
                @endif
            </div>
        </div>
        @endif
    </div>
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

    function deleteConfirm(button, itemName) {
        Swal.fire({
            title: 'Hapus Kamar?',
            text: `Data ${itemName} akan dihapus permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ED6B60',
            cancelButtonColor: '#6A7686',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", showConfirmButton: false, timer: 2000 });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#165DFF' });
    @endif
</script>
@endsection