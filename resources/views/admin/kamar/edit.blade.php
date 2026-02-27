@php
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
@endphp
@extends('layouts.admin')

@section('title', 'Edit Kamar')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-foreground">Edit Kamar</h1>
        <p class="text-secondary mt-1">Perbarui informasi Kamar {{ $kamar->nomor_kamar }}</p>
    </div>
    <a href="{{ route('admin.kamar.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white border border-border text-foreground font-semibold rounded-xl hover:bg-muted transition-colors">
        <i data-lucide="arrow-left" class="size-4 mr-2 text-secondary"></i> Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-border p-6 md:p-8">
            <form action="{{ route('admin.kamar.update', $kamar) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                @if($kamar->gambar)
                <div class="mb-8 p-4 bg-muted/50 rounded-xl border border-border flex items-center gap-6" id="current-image-container">
                    @php
                        $gambarUrl = $kamar->gambar;
                        if (!Str::startsWith($gambarUrl, ['http://', 'https://'])) {
                            $gambarUrl = Storage::disk('public')->exists($kamar->gambar) 
                                ? asset('storage/' . $kamar->gambar) 
                                : asset('images/default-room.jpg');
                        }
                    @endphp
                    <img src="{{ $gambarUrl }}" class="size-24 rounded-lg object-cover ring-1 ring-border shadow-sm">
                    <div>
                        <p class="text-sm font-semibold text-foreground mb-1">Gambar Terpasang</p>
                        <button type="button" onclick="removeCurrentImage()" class="text-sm font-bold text-error hover:text-error-light transition-colors flex items-center gap-1">
                            <i data-lucide="trash-2" class="size-4"></i> Hapus Gambar
                        </button>
                        <input type="hidden" id="hapus-gambar" name="hapus_gambar" value="0">
                    </div>
                </div>
                @endif

                <div class="mb-8">
                    <h3 class="text-base font-bold text-foreground flex items-center gap-2 mb-5">
                        <i data-lucide="info" class="size-5 text-primary"></i> Info Dasar
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Nomor Kamar <span class="text-error">*</span></label>
                            <input type="text" name="nomor_kamar" value="{{ old('nomor_kamar', $kamar->nomor_kamar) }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Tipe Kamar <span class="text-error">*</span></label>
                            <select name="tipe_kamar" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all appearance-none" required>
                                <option value="Standard" {{ old('tipe_kamar', $kamar->tipe_kamar) == 'Standard' ? 'selected' : '' }}>Standard</option>
                                <option value="Deluxe" {{ old('tipe_kamar', $kamar->tipe_kamar) == 'Deluxe' ? 'selected' : '' }}>Deluxe</option>
                                <option value="Executive" {{ old('tipe_kamar', $kamar->tipe_kamar) == 'Executive' ? 'selected' : '' }}>Executive</option>
                                <option value="Vip" {{ old('tipe_kamar', $kamar->tipe_kamar) == 'Vip' ? 'selected' : '' }}>VIP</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Harga/Bulan <span class="text-error">*</span></label>
                            <input type="number" name="harga" value="{{ old('harga', $kamar->harga) }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all" min="1000" step="1000" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Status <span class="text-error">*</span></label>
                            <select name="status" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all appearance-none" required>
                                <option value="tersedia" {{ old('status', $kamar->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="terisi" {{ old('status', $kamar->status) == 'terisi' ? 'selected' : '' }}>Terisi</option>
                                <option value="maintenance" {{ old('status', $kamar->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr class="border-border mb-8">

                <div class="mb-8">
                    <h3 class="text-base font-bold text-foreground flex items-center gap-2 mb-5">
                        <i data-lucide="ruler" class="size-5 text-warning-dark"></i> Spesifikasi
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Ukuran (m²)</label>
                            <input type="number" name="ukuran" value="{{ old('ukuran', $kamar->ukuran) }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Lantai</label>
                            <input type="number" name="lantai" value="{{ old('lantai', $kamar->lantai) }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Kapasitas</label>
                            <input type="number" name="kapasitas" value="{{ old('kapasitas', $kamar->kapasitas) }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none">
                        </div>
                    </div>
                </div>

                <hr class="border-border mb-8">

                <div class="mb-8">
                    <h3 class="text-base font-bold text-foreground flex items-center gap-2 mb-5">
                        <i data-lucide="align-left" class="size-5 text-success"></i> Fasilitas & Deskripsi
                    </h3>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Fasilitas <span class="text-error">*</span></label>
                            <textarea id="fasilitas" name="fasilitas" rows="2" class="w-full px-4 py-3 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none" required>{{ old('fasilitas', $kamar->fasilitas) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Deskripsi</label>
                            <textarea name="deskripsi" rows="4" class="w-full px-4 py-3 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none">{{ old('deskripsi', $kamar->deskripsi) }}</textarea>
                        </div>
                    </div>
                </div>

                <hr class="border-border mb-8">

                <div class="mb-8">
                    <h3 class="text-base font-bold text-foreground flex items-center gap-2 mb-5">
                        <i data-lucide="image" class="size-5 text-purple-500"></i> Ganti Gambar
                    </h3>
                    <div id="uploadArea" class="border-2 border-dashed border-border rounded-2xl p-8 text-center hover:bg-muted/50 hover:border-primary/50 transition-all cursor-pointer">
                        <i data-lucide="upload-cloud" class="size-10 text-secondary mx-auto mb-3"></i>
                        <p class="text-sm font-semibold text-foreground">Pilih gambar pengganti</p>
                        <input id="gambar" name="gambar" type="file" class="hidden" accept="image/*">
                    </div>
                    <div id="imagePreview" class="hidden mt-4 relative inline-block">
                        <img id="preview" class="h-40 rounded-xl object-cover ring-1 ring-border shadow-sm">
                        <button type="button" onclick="resetImage()" class="absolute -top-2 -right-2 size-8 bg-white rounded-full shadow-md flex items-center justify-center text-error hover:bg-error-light transition-colors">
                            <i data-lucide="x" class="size-4"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4">
                    <a href="{{ route('admin.kamar.index') }}" class="px-5 py-2.5 font-semibold text-secondary hover:text-foreground transition-colors">Batal</a>
                    <button type="submit" class="px-6 py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-primary-hover transition-colors shadow-sm shadow-primary/30">
                        Update Kamar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white border border-border rounded-2xl p-6 shadow-sm">
            <h3 class="font-bold text-foreground mb-4 border-b border-border pb-3">Status Pencatatan</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-xs text-secondary font-medium uppercase tracking-wide">Dibuat Pada</p>
                    <p class="text-sm font-bold text-foreground">{{ $kamar->created_at->format('d M Y - H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-secondary font-medium uppercase tracking-wide">Terakhir Update</p>
                    <p class="text-sm font-bold text-foreground">{{ $kamar->updated_at->format('d M Y - H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        
        // Image Upload Logic
        const input = document.getElementById('gambar');
        const uploadArea = document.getElementById('uploadArea');
        const previewArea = document.getElementById('imagePreview');
        const previewImg = document.getElementById('preview');

        uploadArea.addEventListener('click', () => input.click());

        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if(file) {
                previewImg.src = URL.createObjectURL(file);
                uploadArea.classList.add('hidden');
                previewArea.classList.remove('hidden');
            }
        });

        window.resetImage = function() {
            input.value = '';
            previewImg.src = '';
            uploadArea.classList.remove('hidden');
            previewArea.classList.add('hidden');
        };

        window.removeCurrentImage = function() {
            document.getElementById('hapus-gambar').value = '1';
            document.getElementById('current-image-container').classList.add('hidden');
        }

        // Format Fasilitas
        document.getElementById('fasilitas').addEventListener('blur', function(e) {
            e.target.value = e.target.value.replace(/\s*,\s*/g, ', ').replace(/\s+/g, ' ').trim();
        });
    });
</script>
@endsection