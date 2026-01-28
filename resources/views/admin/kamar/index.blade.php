@php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
@endphp
@extends('layouts.admin')

@section('title', 'Kelola Kamar')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
    <div class="mb-4 md:mb-0">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Kelola Kamar</h1>
        <p class="text-gray-600 mt-2">Kelola data kamar kos yang tersedia</p>
    </div>
    <a href="{{ route('admin.kamar.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold rounded-lg shadow-md transition-all duration-200 transform hover:-translate-y-0.5">
        <i class="fas fa-plus mr-2"></i>Tambah Kamar
    </a>
</div>

<!-- Success Message -->
@if(session('success'))
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg shadow-sm">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <i class="fas fa-check-circle text-green-500 text-lg"></i>
        </div>
        <div class="ml-3">
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    </div>
</div>
@endif

<!-- Kamar List -->
<div class="card bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Daftar Kamar</h2>
            <div class="mt-2 sm:mt-0">
                <div class="relative">
                    <input type="text" placeholder="Cari kamar..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 w-full sm:w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kamar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fasilitas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($kamars as $kamar)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($kamar->gambar)
                                @php
                                    // Cek apakah gambar sudah berupa URL lengkap atau path storage
                                    $gambarUrl = $kamar->gambar;
                                    if (!Str::startsWith($gambarUrl, ['http://', 'https://'])) {
                                        // Jika menggunakan storage local
                                        if (Storage::disk('public')->exists($kamar->gambar)) {
                                            $gambarUrl = asset('storage/' . $kamar->gambar);
                                        } else {
                                            $gambarUrl = asset('images/default-room.jpg'); // Fallback image
                                        }
                                    }
                                @endphp
                                <div class="flex-shrink-0 h-12 w-12 relative">
                                    <img class="h-12 w-12 rounded-lg object-cover shadow-sm" 
                                         src="{{ $gambarUrl }}" 
                                         alt="Kamar {{ $kamar->nomor_kamar }}"
                                         onerror="this.src='{{ asset('images/default-room.jpg') }}'; this.onerror=null;">
                                </div>
                            @else
                                <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-primary-100 to-primary-200 rounded-lg flex items-center justify-center shadow-sm">
                                    <i class="fas fa-home text-primary-600"></i>
                                </div>
                            @endif
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900">Kamar {{ $kamar->nomor_kamar }}</div>
                                <div class="text-sm text-gray-500 mt-1">{{ Str::limit($kamar->deskripsi, 35) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $kamar->tipe_kamar }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $kamar->ukuran_kamar }} m²</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</div>
                        <div class="text-xs text-gray-500">per bulan</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            // PERBAIKAN: Tambahkan status 'dipesan' dan gunakan default value
                            $statusColors = [
                                'tersedia' => 'bg-green-100 text-green-800 border border-green-200',
                                'dipesan' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                'terisi' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                'maintenance' => 'bg-red-100 text-red-800 border border-red-200'
                            ];
                            $statusIcons = [
                                'tersedia' => 'fa-check-circle',
                                'dipesan' => 'fa-clock', // TAMBAHKAN INI
                                'terisi' => 'fa-user-check',
                                'maintenance' => 'fa-tools'
                            ];
                            
                            // Gunakan default jika status tidak ditemukan
                            $statusColor = $statusColors[$kamar->status] ?? 'bg-gray-100 text-gray-800 border border-gray-200';
                            $statusIcon = $statusIcons[$kamar->status] ?? 'fa-question-circle';
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                            <i class="fas {{ $statusIcon }} mr-1.5"></i>
                            {{ ucfirst($kamar->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1 max-w-xs">
                            @php
                                $fasilitasList = is_array($kamar->fasilitas) ? $kamar->fasilitas : explode(',', $kamar->fasilitas);
                                $fasilitasList = array_filter($fasilitasList, function($item) {
                                    return !empty(trim($item));
                                });
                            @endphp
                            @foreach(array_slice($fasilitasList, 0, 2) as $fasilitas)
                            <span class="inline-flex items-center bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full border border-gray-200">
                                <i class="fas fa-check mr-1 text-green-500 text-xs"></i>
                                {{ trim($fasilitas) }}
                            </span>
                            @endforeach
                            @if(count($fasilitasList) > 2)
                            <span class="inline-flex items-center bg-primary-50 text-primary-700 text-xs px-2 py-1 rounded-full border border-primary-200">
                                +{{ count($fasilitasList) - 2 }} lainnya
                            </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.kamar.edit', $kamar) }}" 
                               class="inline-flex items-center p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                               title="Edit Kamar">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            
                            <button type="button" 
                                    class="inline-flex items-center p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-colors duration-200"
                                    title="Lihat Detail">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                            
                            <form action="{{ route('admin.kamar.destroy', $kamar) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus kamar {{ $kamar->nomor_kamar }}?')"
                                        title="Hapus Kamar">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <i class="fas fa-home text-4xl mb-3"></i>
                            <p class="text-lg font-medium text-gray-500">Belum ada data kamar</p>
                            <p class="text-sm mt-1 text-gray-400">Mulai dengan menambahkan kamar pertama Anda</p>
                            <a href="{{ route('admin.kamar.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>Tambah Kamar Pertama
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination - Only show if $kamars is a paginator instance -->
    @if(method_exists($kamars, 'links'))
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                @if($kamars->total() > 0)
                Menampilkan {{ $kamars->firstItem() }} - {{ $kamars->lastItem() }} dari {{ $kamars->total() }} kamar
                @else
                Tidak ada data kamar
                @endif
            </div>
            <div class="flex space-x-2">
                @if($kamars->onFirstPage())
                <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                    <i class="fas fa-chevron-left mr-1"></i> Sebelumnya
                </span>
                @else
                <a href="{{ $kamars->previousPageUrl() }}" class="px-3 py-1 bg-white text-gray-700 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors duration-200">
                    <i class="fas fa-chevron-left mr-1"></i> Sebelumnya
                </a>
                @endif

                @if($kamars->hasMorePages())
                <a href="{{ $kamars->nextPageUrl() }}" class="px-3 py-1 bg-white text-gray-700 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors duration-200">
                    Selanjutnya <i class="fas fa-chevron-right ml-1"></i>
                </a>
                @else
                <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                    Selanjutnya <i class="fas fa-chevron-right ml-1"></i>
                </span>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Quick Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium opacity-90">Total Kamar</p>
                <p class="text-2xl font-bold mt-1">{{ $totalKamar ?? $kamars->count() }}</p>
            </div>
            <div class="p-3 rounded-full bg-white bg-opacity-20">
                <i class="fas fa-home text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium opacity-90">Kamar Tersedia</p>
                <p class="text-2xl font-bold mt-1">{{ $kamars->where('status', 'tersedia')->count() }}</p>
            </div>
            <div class="p-3 rounded-full bg-white bg-opacity-20">
                <i class="fas fa-door-open text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium opacity-90">Kamar Terisi</p>
                <p class="text-2xl font-bold mt-1">{{ $kamars->where('status', 'terisi')->count() }}</p>
            </div>
            <div class="p-3 rounded-full bg-white bg-opacity-20">
                <i class="fas fa-user-check text-xl"></i>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    /* Ensure images maintain aspect ratio */
    img {
        object-fit: cover;
    }
</style>

<script>
    // Search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[type="text"]');
        
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
@endsection