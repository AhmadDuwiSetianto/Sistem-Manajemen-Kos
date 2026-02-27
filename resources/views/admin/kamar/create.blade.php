@extends('layouts.admin')

@section('title', 'Tambah Kamar Baru')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-foreground">Tambah Kamar</h1>
        <p class="text-secondary mt-1">Masukkan detail informasi kamar baru</p>
    </div>
    <a href="{{ route('admin.kamar.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white border border-border text-foreground font-semibold rounded-xl hover:bg-muted transition-colors">
        <i data-lucide="arrow-left" class="size-4 mr-2 text-secondary"></i> Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm border border-border p-6 md:p-8">
            <form action="{{ route('admin.kamar.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-8">
                    <h3 class="text-base font-bold text-foreground flex items-center gap-2 mb-5">
                        <i data-lucide="info" class="size-5 text-primary"></i> Info Dasar
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Nomor Kamar <span class="text-error">*</span></label>
                            <input type="text" name="nomor_kamar" value="{{ old('nomor_kamar') }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-secondary text-sm" placeholder="Ex: A101" required>
                            @error('nomor_kamar')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Tipe Kamar <span class="text-error">*</span></label>
                            <select name="tipe_kamar" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm appearance-none" required>
                                <option value="">Pilih Tipe</option>
                                <option value="Standard" {{ old('tipe_kamar') == 'Standard' ? 'selected' : '' }}>Standard</option>
                                <option value="Deluxe" {{ old('tipe_kamar') == 'Deluxe' ? 'selected' : '' }}>Deluxe</option>
                                <option value="Executive" {{ old('tipe_kamar') == 'Executive' ? 'selected' : '' }}>Executive</option>
                                <option value="Vip" {{ old('tipe_kamar') == 'Vip' ? 'selected' : '' }}>VIP</option>
                            </select>
                            @error('tipe_kamar')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Harga/Bulan <span class="text-error">*</span></label>
                            <input type="number" name="harga" value="{{ old('harga') }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-secondary text-sm" placeholder="1000000" min="1000" step="1000" required>
                            @error('harga')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Status <span class="text-error">*</span></label>
                            <select name="status" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none transition-all text-sm appearance-none" required>
                                <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                            @error('status')<p class="text-xs text-error mt-1">{{ $message }}</p>@enderror
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
                            <input type="number" name="ukuran" value="{{ old('ukuran') }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm placeholder:text-secondary" placeholder="20" min="0">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Lantai</label>
                            <input type="number" name="lantai" value="{{ old('lantai') }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm placeholder:text-secondary" placeholder="1" min="1">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Kapasitas</label>
                            <input type="number" name="kapasitas" value="{{ old('kapasitas') }}" class="w-full px-4 py-2.5 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm placeholder:text-secondary" placeholder="2" min="1">
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
                            <textarea id="fasilitas" name="fasilitas" rows="2" class="w-full px-4 py-3 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm placeholder:text-secondary" placeholder="AC, WiFi, Kamar Mandi Dalam" required>{{ old('fasilitas') }}</textarea>
                            <p class="text-[11px] text-secondary mt-1">Pisahkan dengan tanda koma.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-foreground mb-2">Deskripsi</label>
                            <textarea name="deskripsi" rows="4" class="w-full px-4 py-3 bg-white border border-border rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm placeholder:text-secondary" placeholder="Tambahkan informasi rinci...">{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>
                </div>

                <hr class="border-border mb-8">

                <div class="mb-8">
                    <h3 class="text-base font-bold text-foreground flex items-center gap-2 mb-5">
                        <i data-lucide="image" class="size-5 text-purple-500"></i> Gambar Kamar
                    </h3>
                    <div id="uploadArea" class="border-2 border-dashed border-border rounded-2xl p-8 text-center hover:bg-muted/50 hover:border-primary/50 transition-all cursor-pointer">
                        <i data-lucide="upload-cloud" class="size-10 text-secondary mx-auto mb-3"></i>
                        <p class="text-sm font-semibold text-foreground">Klik untuk upload atau drag & drop</p>
                        <p class="text-xs text-secondary mt-1">PNG, JPG up to 2MB</p>
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
                        Simpan Kamar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="lg:col-span-1 space-y-6">
        <div class="bg-primary/5 border border-primary/20 rounded-2xl p-6">
            <h3 class="font-bold text-primary flex items-center gap-2 mb-4">
                <i data-lucide="lightbulb" class="size-5"></i> Tips Menambah Kamar
            </h3>
            <ul class="space-y-3 text-sm text-foreground">
                <li class="flex gap-2"><i data-lucide="check" class="size-4 text-primary shrink-0 mt-0.5"></i> Pastikan nomor kamar unik.</li>
                <li class="flex gap-2"><i data-lucide="check" class="size-4 text-primary shrink-0 mt-0.5"></i> Isi harga tanpa tanda titik.</li>
                <li class="flex gap-2"><i data-lucide="check" class="size-4 text-primary shrink-0 mt-0.5"></i> Gambar rasio 4:3 paling optimal.</li>
                <li class="flex gap-2"><i data-lucide="check" class="size-4 text-primary shrink-0 mt-0.5"></i> Pisahkan fasilitas menggunakan koma.</li>
            </ul>
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

        // Format Fasilitas
        document.getElementById('fasilitas').addEventListener('blur', function(e) {
            e.target.value = e.target.value.replace(/\s*,\s*/g, ', ').replace(/\s+/g, ' ').trim();
        });
    });
</script>
@endsection