@php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
@endphp

@extends('layouts.admin')

@section('title', 'Edit Kamar')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
    <div class="mb-4 md:mb-0">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Edit Kamar</h1>
        <p class="text-gray-600 mt-2">Edit data kamar {{ $kamar->nomor_kamar }}</p>
    </div>
    <a href="{{ route('admin.kamar.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<!-- Form Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Form -->
    <div class="lg:col-span-2">
        <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <form action="{{ route('admin.kamar.update', $kamar) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Current Image Preview -->
                @if($kamar->gambar)
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini</label>
                    <div class="flex items-center space-x-4">
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
                        <img src="{{ $gambarUrl }}" 
                             alt="Kamar {{ $kamar->nomor_kamar }}" 
                             class="w-32 h-32 rounded-lg object-cover shadow-md"
                             onerror="this.src='{{ asset('images/default-room.jpg') }}'">
                        <div>
                            <button type="button" 
                                    onclick="document.getElementById('hapus-gambar').value = '1'; document.getElementById('current-image').classList.add('hidden');"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                                <i class="fas fa-trash mr-1"></i>Hapus Gambar
                            </button>
                            <input type="hidden" id="hapus-gambar" name="hapus_gambar" value="0">
                        </div>
                    </div>
                </div>
                @endif

                <!-- Basic Information Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-info-circle text-primary-600 text-sm"></i>
                        </div>
                        Informasi Dasar
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nomor Kamar -->
                        <div>
                            <label for="nomor_kamar" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Kamar <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="nomor_kamar" name="nomor_kamar" value="{{ old('nomor_kamar', $kamar->nomor_kamar) }}"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
                                       placeholder="Contoh: A101" required>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-hashtag text-gray-400"></i>
                                </div>
                            </div>
                            @error('nomor_kamar')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Tipe Kamar -->
                        <div>
                            <label for="tipe_kamar" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipe Kamar <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="tipe_kamar" name="tipe_kamar"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 appearance-none"
                                        required>
                                    <option value="">Pilih Tipe Kamar</option>
            <option value="Standard" {{ old('tipe_kamar') == 'Standard' ? 'selected' : '' }}>Standard</option>
            <option value="Deluxe" {{ old('tipe_kamar') == 'Deluxe' ? 'selected' : '' }}>Deluxe</option>
            <option value="Executive" {{ old('tipe_kamar') == 'Executive' ? 'selected' : '' }}>Executive</option>
            <option value="Superior" {{ old('tipe_kamar') == 'Superior' ? 'selected' : '' }}>Superior</option>
            <option value="Vip" {{ old('tipe_kamar') == 'Vip' ? 'selected' : '' }}>VIP</option>
        </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-star text-gray-400"></i>
                                </div>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            @error('tipe_kamar')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Harga -->
                        <div>
                            <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">
                                Harga per Bulan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                 <input type="number" 
                                 id="harga" 
                                 name="harga" 
                                 value="{{ old('harga', isset($kamar) ? $kamar->harga : '') }}"
                                 class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
                                 placeholder="1000000" 
                                 min="1000" 
                                 step="1000"
                                 required>
                                 <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500">/bulan</span>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                Masukkan angka tanpa titik (contoh: 1000000 untuk Rp 1.000.000)
                            </p>
                             @error('harga')
                             <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="status" name="status"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 appearance-none"
                                        required>
                                    <option value="tersedia" {{ old('status', $kamar->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="terisi" {{ old('status', $kamar->status) == 'terisi' ? 'selected' : '' }}>Terisi</option>
                                    <option value="maintenance" {{ old('status', $kamar->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-circle text-gray-400"></i>
                                </div>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            @error('status')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Fasilitas Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-list text-green-600 text-sm"></i>
                        </div>
                        Fasilitas Kamar
                    </h3>
                    
                    <div>
                        <label for="fasilitas" class="block text-sm font-medium text-gray-700 mb-2">
                            Fasilitas <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <textarea id="fasilitas" name="fasilitas" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
                                      placeholder="Masukkan fasilitas, pisahkan dengan koma (contoh: AC, WiFi, Kamar Mandi Dalam, TV, Lemari)"
                                      required>{{ old('fasilitas', $kamar->fasilitas) }}</textarea>
                        </div>
                        <p class="mt-2 text-sm text-gray-500 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Pisahkan setiap fasilitas dengan koma
                        </p>
                        @error('fasilitas')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>

                <!-- Deskripsi Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-align-left text-blue-600 text-sm"></i>
                        </div>
                        Deskripsi Kamar
                    </h3>
                    
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
                                  placeholder="Deskripsi lengkap tentang kamar, ukuran, view, dan keunggulan lainnya">{{ old('deskripsi', $kamar->deskripsi) }}</textarea>
                        @error('deskripsi')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>

                <!-- Gambar Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-image text-purple-600 text-sm"></i>
                        </div>
                        Gambar Kamar
                    </h3>
                    
                    <div>
                        <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">Gambar Baru</label>
                        
                        <!-- File Upload Area -->
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg transition-colors duration-200 hover:border-primary-400" id="uploadArea">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mx-auto"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="gambar" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none">
                                        <span>Upload gambar baru</span>
                                        <input id="gambar" name="gambar" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF maksimal 2MB</p>
                            </div>
                        </div>
                        
                        <!-- Image Preview -->
                        <div id="imagePreview" class="mt-4 hidden">
                            <p class="text-sm font-medium text-gray-700 mb-2">Preview Gambar Baru:</p>
                            <img id="preview" class="max-w-xs rounded-lg shadow-md" alt="Preview gambar baru">
                        </div>
                        
                        @error('gambar')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.kamar.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-medium rounded-lg shadow-md transition-all duration-200 transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i>Update Kamar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar Information -->
    <div class="lg:col-span-1">
        <!-- Tips Card -->
        <div class="card bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-lightbulb text-blue-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-blue-800 mb-2">Tips Edit Kamar</h3>
                    <ul class="text-sm text-blue-700 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-blue-500 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Pastikan nomor kamar tetap unik</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-blue-500 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Update harga sesuai kondisi terkini</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-blue-500 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Perbarui status kamar secara berkala</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-blue-500 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Gambar yang jelas meningkatkan minat</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Room Info Card -->
        <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Kamar</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Dibuat</span>
                    <span class="text-sm text-gray-900">{{ $kamar->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Diupdate</span>
                    <span class="text-sm text-gray-900">{{ $kamar->updated_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Status Saat Ini</span>
                    @php
                        $statusColors = [
                            'tersedia' => 'bg-green-100 text-green-800',
                            'dipesan' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                            'terisi' => 'bg-blue-100 text-blue-800',
                            'maintenance' => 'bg-red-100 text-red-800'
                        ];
                    @endphp
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$kamar->status] }}">
                        {{ ucfirst($kamar->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Image preview functionality
    document.getElementById('gambar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('preview');
        const imagePreview = document.getElementById('imagePreview');
        const uploadArea = document.getElementById('uploadArea');
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                imagePreview.classList.remove('hidden');
                uploadArea.classList.add('hidden');
            }
            
            reader.readAsDataURL(file);
        } else {
            imagePreview.classList.add('hidden');
            uploadArea.classList.remove('hidden');
        }
    });

    // Drag and drop functionality
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('gambar');

    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('border-primary-500', 'bg-primary-50');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-primary-500', 'bg-primary-50');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-primary-500', 'bg-primary-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });
</script>

<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
</style>
@endsection 