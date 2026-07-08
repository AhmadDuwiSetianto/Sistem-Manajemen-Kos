@php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
@endphp
@extends('layouts.admin')

@section('title', 'Edit Kamar')

@section('content')
<div class="flex-1 p-4 md:p-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 md:mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-foreground">Edit Kamar</h1>
            <p class="text-secondary mt-1 text-sm md:text-base">Perbarui informasi Kamar {{ $kamar->nomor_kamar }}</p>
        </div>
        <a href="{{ route('admin.kamar.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-border text-foreground font-bold text-sm rounded-xl hover:bg-muted transition-colors shadow-sm">
            <i data-lucide="arrow-left" class="size-4 mr-2 text-secondary"></i> Kembali
        </a>
    </div>

    <!-- Form Container -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-border p-5 md:p-8">
                <form action="{{ route('admin.kamar.update', $kamar) }}" method="POST" enctype="multipart/form-data" id="kamarForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Preview Current Image -->
                    @if($kamar->gambar)
                    <div class="mb-6 md:mb-8 p-3 md:p-4 bg-muted/50 rounded-xl border border-border flex items-center gap-4 md:gap-6" id="current-image-container">
                        @php
    $gambarUrl = asset('images/default-room.jpg');
    
    // Pastikan $kamar->gambar ada nilainya sebelum memproses
    if ($kamar->gambar) {
        if (\Illuminate\Support\Str::startsWith($kamar->gambar, ['http://', 'https://'])) {
            // Transformasi Cloudinary: Kompres otomatis + WebP + Resize 200px
            $gambarUrl = \Illuminate\Support\Str::replace('/upload/', '/upload/q_auto,f_auto,w_200/', $kamar->gambar);
        } elseif (\Illuminate\Support\Facades\Storage::disk('public')->exists($kamar->gambar)) {
            $gambarUrl = asset('storage/' . $kamar->gambar);
        }
    }
@endphp

<img src="{{ $gambarUrl }}" 
     loading="lazy" 
     class="size-16 md:size-24 rounded-lg object-cover ring-1 ring-border shadow-sm shrink-0" 
     alt="Gambar Kamar">
                        <div>
                            <p class="text-xs md:text-sm font-semibold text-foreground mb-1">Gambar Terpasang</p>
                            <button type="button" onclick="removeCurrentImage()" class="text-[10px] md:text-xs font-bold text-error hover:text-error-light transition-colors flex items-center gap-1 cursor-pointer">
                                <i data-lucide="trash-2" class="size-3 md:size-4"></i> Hapus Gambar
                            </button>
                            <input type="hidden" id="hapus-gambar" name="hapus_gambar" value="0">
                        </div>
                    </div>
                    @endif

                    <!-- Info Dasar -->
                    <div class="mb-6 md:mb-8">
                        <h3 class="text-sm md:text-base font-bold text-foreground flex items-center gap-2 mb-4">
                            <i data-lucide="info" class="size-4 md:size-5 text-primary"></i> Info Dasar
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Nomor Kamar <span class="text-error">*</span></label>
                                <input type="text" name="nomor_kamar" value="{{ old('nomor_kamar', $kamar->nomor_kamar) }}" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all" required>
                                @error('nomor_kamar')<p class="text-[10px] md:text-xs text-error mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Tipe Kamar <span class="text-error">*</span></label>
                                <select name="tipe_kamar" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm appearance-none cursor-pointer" required>
                                    <option value="Standard" {{ old('tipe_kamar', $kamar->tipe_kamar) == 'Standard' ? 'selected' : '' }}>Standard</option>
                                    <option value="Deluxe" {{ old('tipe_kamar', $kamar->tipe_kamar) == 'Deluxe' ? 'selected' : '' }}>Deluxe</option>
                                    <option value="Executive" {{ old('tipe_kamar', $kamar->tipe_kamar) == 'Executive' ? 'selected' : '' }}>Executive</option>
                                    <option value="Vip" {{ old('tipe_kamar', $kamar->tipe_kamar) == 'Vip' ? 'selected' : '' }}>VIP</option>
                                </select>
                                @error('tipe_kamar')<p class="text-[10px] md:text-xs text-error mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Harga/Bulan <span class="text-error">*</span></label>
                                <input type="number" name="harga" value="{{ old('harga', $kamar->harga) }}" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm transition-all" min="1000" step="1000" required>
                                @error('harga')<p class="text-[10px] md:text-xs text-error mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Status <span class="text-error">*</span></label>
                                <select name="status" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm appearance-none cursor-pointer" required>
                                    <option value="tersedia" {{ old('status', $kamar->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="terisi" {{ old('status', $kamar->status) == 'terisi' ? 'selected' : '' }}>Terisi</option>
                                    <option value="maintenance" {{ old('status', $kamar->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                                @error('status')<p class="text-[10px] md:text-xs text-error mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <hr class="border-border mb-6 md:mb-8">

                    <!-- Spesifikasi -->
                    <div class="mb-6 md:mb-8">
                        <h3 class="text-sm md:text-base font-bold text-foreground flex items-center gap-2 mb-4">
                            <i data-lucide="ruler" class="size-4 md:size-5 text-warning-dark"></i> Spesifikasi
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-5">
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Ukuran (m²)</label>
                                <input type="number" name="ukuran" value="{{ old('ukuran', $kamar->ukuran) }}" class="w-full px-3.5 py-2 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Lantai</label>
                                <input type="number" name="lantai" value="{{ old('lantai', $kamar->lantai) }}" class="w-full px-3.5 py-2 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm">
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Kapasitas</label>
                                <input type="number" name="kapasitas" value="{{ old('kapasitas', $kamar->kapasitas) }}" class="w-full px-3.5 py-2 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm">
                            </div>
                        </div>
                    </div>

                    <hr class="border-border mb-6 md:mb-8">

                    <!-- Fasilitas & Deskripsi -->
                    <div class="mb-6 md:mb-8">
                        <h3 class="text-sm md:text-base font-bold text-foreground flex items-center gap-2 mb-4">
                            <i data-lucide="align-left" class="size-4 md:size-5 text-success"></i> Fasilitas & Deskripsi
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Fasilitas <span class="text-error">*</span></label>
                                @php
                                    $currentFasilitas = old('fasilitas', $kamar->fasilitas ?? '');
                                    $currentFasilitas = str_replace(['[', ']', '"', '\\'], '', $currentFasilitas);
                                @endphp
                                <textarea id="fasilitas" name="fasilitas" rows="2" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm" required>{{ $currentFasilitas }}</textarea>
                                @error('fasilitas')<p class="text-[10px] md:text-xs text-error mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-foreground mb-1.5">Deskripsi</label>
                                <textarea name="deskripsi" rows="3" class="w-full px-3.5 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm">{{ old('deskripsi', $kamar->deskripsi) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr class="border-border mb-6 md:mb-8">

                    <!-- Gambar Baru -->
                    <div class="mb-6 md:mb-8">
                        <h3 class="text-sm md:text-base font-bold text-foreground flex items-center gap-2 mb-4">
                            <i data-lucide="image" class="size-4 md:size-5 text-purple-500"></i> Ganti Gambar
                        </h3>
                        
                        <div id="uploadArea" class="border-2 border-dashed border-border rounded-xl p-6 md:p-8 text-center hover:bg-muted/50 transition-all cursor-pointer">
                            <i data-lucide="upload-cloud" class="size-8 md:size-10 text-secondary mx-auto mb-2"></i>
                            <p class="text-xs md:text-sm font-bold text-foreground">Pilih gambar pengganti</p>
                            <input id="gambar" name="gambar" type="file" class="hidden" accept="image/*">
                        </div>
                        
                        <!-- ✅ PERBAIKAN: Gunakan style="display: none;" -->
                        <div id="imagePreview" style="display: none;" class="mt-4 relative inline-block">
                            <img id="preview" class="h-32 md:h-40 rounded-xl object-cover ring-1 ring-border shadow-sm" alt="Preview Baru">
                            <button type="button" onclick="resetImage()" class="absolute -top-2 -right-2 size-7 md:size-8 bg-white rounded-full shadow-md flex items-center justify-center text-error hover:bg-error-light transition-colors cursor-pointer">
                                <i data-lucide="x" class="size-3 md:size-4"></i>
                            </button>
                        </div>
                        @error('gambar')<p class="text-[10px] md:text-xs text-error mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4">
                        <a href="{{ route('admin.kamar.index') }}" class="w-full sm:w-auto px-5 py-2.5 font-bold text-sm text-secondary text-center hover:text-foreground transition-colors">Batal</a>
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-hover transition-colors shadow-sm shadow-primary/30">
                            Update Kamar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="lg:col-span-1 space-y-4 md:space-y-6">
            <div class="bg-white border border-border rounded-2xl p-5 shadow-sm">
                <h3 class="font-bold text-sm md:text-base text-foreground mb-4 border-b border-border pb-2 md:pb-3">Status Pencatatan</h3>
                <div class="space-y-3 md:space-y-4">
                    <div>
                        <p class="text-[10px] md:text-xs text-secondary font-bold uppercase tracking-wide">Dibuat Pada</p>
                        <p class="text-xs md:text-sm font-bold text-foreground">{{ $kamar->created_at->format('d M Y - H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] md:text-xs text-secondary font-bold uppercase tracking-wide">Terakhir Update</p>
                        <p class="text-xs md:text-sm font-bold text-foreground">{{ $kamar->updated_at->format('d M Y - H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        
        // --- SCRIPT UPLOAD GAMBAR ---
        const input = document.getElementById('gambar');
        const uploadArea = document.getElementById('uploadArea');
        const previewArea = document.getElementById('imagePreview');
        const previewImg = document.getElementById('preview');

        uploadArea.addEventListener('click', () => input.click());

        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if(file) {
                if(file.size > 2 * 1024 * 1024) {
                    alert('Ukuran gambar terlalu besar! Maksimal 2MB.');
                    input.value = '';
                    return;
                }
                previewImg.src = URL.createObjectURL(file);
                // Menukar tampilan dengan memanipulasi property display
                uploadArea.style.display = 'none';
                previewArea.style.display = 'inline-block';
            }
        });

        window.resetImage = function() {
            input.value = '';
            previewImg.src = '';
            // Mengembalikan ke tampilan semula
            uploadArea.style.display = 'block';
            previewArea.style.display = 'none';
        };

        window.removeCurrentImage = function() {
            document.getElementById('hapus-gambar').value = '1';
            document.getElementById('current-image-container').classList.add('hidden');
        }

        // --- SCRIPT PEMBERSIHAN FASILITAS ---
        const fasilitasInput = document.getElementById('fasilitas');
        const kamarForm = document.getElementById('kamarForm');

        function cleanFasilitasString(val) {
            val = val.replace(/[\[\]"\\]/g, ''); 
            return val.split(',').map(item => item.trim()).filter(item => item !== "").join(', ');
        }

        fasilitasInput.addEventListener('blur', function(e) {
            e.target.value = cleanFasilitasString(e.target.value);
        });

        kamarForm.addEventListener('submit', function() {
            fasilitasInput.value = cleanFasilitasString(fasilitasInput.value);
        });
    });
</script>
@endsection