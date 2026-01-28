@extends('layouts.admin')

@section('title', 'Tambah User Baru')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
    <div class="mb-4 md:mb-0">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Tambah User Baru</h1>
        <p class="text-gray-600 mt-2">Tambah data user baru ke sistem</p>
    </div>
    <a href="{{ route('admin.user.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<!-- Form Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Form -->
    <div class="lg:col-span-2">
        <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <form action="{{ route('admin.user.store') }}" method="POST">
                @csrf
                
                <!-- Basic Information Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-info-circle text-primary-600 text-sm"></i>
                        </div>
                        Informasi Dasar
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
                                       placeholder="Masukkan nama lengkap" required>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                            </div>
                            @error('name')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
                                       placeholder="email@example.com" required>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                            </div>
                            @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="role" name="role"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 appearance-none"
                                        required>
                                    <option value="">Pilih Role</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                    <option value="penghuni" {{ old('role') == 'penghuni' ? 'selected' : '' }}>Penghuni</option>
                                    <option value="calon_penghuni" {{ old('role') == 'calon_penghuni' ? 'selected' : '' }}>Calon Penghuni</option>
                                </select>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-tag text-gray-400"></i>
                                </div>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            @error('role')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Telepon -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Telepon
                            </label>
                            <div class="relative">
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
                                       placeholder="081234567890">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400"></i>
                                </div>
                            </div>
                            @error('phone')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Password Section -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-lock text-green-600 text-sm"></i>
                        </div>
                        Password
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="password" name="password"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
                                       placeholder="Minimal 8 karakter" required>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-key text-gray-400"></i>
                                </div>
                            </div>
                            @error('password')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
                                       placeholder="Ulangi password" required>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-key text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                        Password minimal 8 karakter
                    </p>
                </div>

                <!-- Additional Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-address-card text-blue-600 text-sm"></i>
                        </div>
                        Informasi Tambahan
                    </h3>
                    
                    <div class="space-y-6">
                        <!-- Alamat -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                            <textarea id="address" name="address" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
                                      placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                            @error('address')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- Nomor Identitas -->
                        <div>
                            <label for="identity_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor Identitas</label>
                            <div class="relative">
                                <input type="text" id="identity_number" name="identity_number" value="{{ old('identity_number') }}"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
                                       placeholder="Nomor KTP/SIM">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-id-card text-gray-400"></i>
                                </div>
                            </div>
                            @error('identity_number')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.user.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-medium rounded-lg shadow-md transition-all duration-200 transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i>Simpan User
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
                    <h3 class="text-lg font-semibold text-blue-800 mb-2">Tips Penambahan User</h3>
                    <ul class="text-sm text-blue-700 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-blue-500 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Pastikan email belum terdaftar sebelumnya</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-blue-500 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Gunakan password yang kuat</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-blue-500 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Pilih role sesuai kebutuhan</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-blue-500 mr-2 mt-0.5 flex-shrink-0"></i>
                            <span>Isi data identitas untuk penghuni</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik User</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Total User</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ $totalUsers ?? 0 }}</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user-shield text-purple-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Admin</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ $adminCount ?? 0 }}</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user-check text-green-600"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Penghuni</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ $penghuniCount ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Password strength indicator
    document.getElementById('password').addEventListener('input', function(e) {
        const password = e.target.value;
        const strengthIndicator = document.getElementById('password-strength');
        
        if (!strengthIndicator) return;
        
        let strength = 0;
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
        if (password.match(/\d/)) strength++;
        if (password.match(/[^a-zA-Z\d]/)) strength++;
        
        const strengthText = ['Sangat Lemah', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
        const strengthColors = ['text-red-500', 'text-orange-500', 'text-yellow-500', 'text-blue-500', 'text-green-500'];
        
        strengthIndicator.textContent = strengthText[strength];
        strengthIndicator.className = `text-sm ${strengthColors[strength]} font-medium`;
    });

    // Confirm password match
    document.getElementById('password_confirmation').addEventListener('input', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = e.target.value;
        const matchIndicator = document.getElementById('password-match');
        
        if (!matchIndicator) return;
        
        if (confirmPassword === '') {
            matchIndicator.textContent = '';
        } else if (password === confirmPassword) {
            matchIndicator.textContent = 'Password cocok';
            matchIndicator.className = 'text-sm text-green-500 font-medium';
        } else {
            matchIndicator.textContent = 'Password tidak cocok';
            matchIndicator.className = 'text-sm text-red-500 font-medium';
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