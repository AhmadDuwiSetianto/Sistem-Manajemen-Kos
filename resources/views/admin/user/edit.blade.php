@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
    <div class="mb-4 md:mb-0">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Edit User</h1>
        <p class="text-gray-600 mt-2">Edit data user {{ $user->name }}</p>
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
            <form action="{{ route('admin.user.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
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
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
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
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
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
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                                    <option value="penghuni" {{ old('role', $user->role) == 'penghuni' ? 'selected' : '' }}>Penghuni</option>
                                    <option value="calon_penghuni" {{ old('role', $user->role) == 'calon_penghuni' ? 'selected' : '' }}>Calon Penghuni</option>
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
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
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
                                Password Baru
                            </label>
                            <div class="relative">
                                <input type="password" id="password" name="password"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
                                       placeholder="Kosongkan jika tidak diubah">
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
                                Konfirmasi Password
                            </label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
                                       placeholder="Ulangi password baru">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-key text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                        Biarkan kosong jika tidak ingin mengubah password
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
                                      placeholder="Masukkan alamat lengkap">{{ old('address', $user->address) }}</textarea>
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
                                <input type="text" id="identity_number" name="identity_number" value="{{ old('identity_number', $user->identity_number) }}"
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
                        <i class="fas fa-save mr-2"></i>Update User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar Information -->
    <div class="lg:col-span-1">
        <!-- User Info Card -->
        <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi User</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">ID User</span>
                    <span class="text-sm text-gray-900 font-mono">{{ $user->id }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Bergabung</span>
                    <span class="text-sm text-gray-900">{{ $user->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Terakhir Update</span>
                    <span class="text-sm text-gray-900">{{ $user->updated_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">Status Email</span>
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $user->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Booking Info Card -->
        <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Booking</h3>
            <div class="space-y-3">
                @php
                    $activeBooking = $user->getActiveBooking();
                    $pendingBooking = $user->getPendingBooking();
                @endphp
                
                @if($activeBooking)
                <div class="p-3 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-center">
                        <i class="fas fa-home text-green-600 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-green-800">Sedang Menempati</p>
                            <p class="text-xs text-green-600">Kamar {{ $activeBooking->kamar->nomor_kamar ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                @elseif($pendingBooking)
                <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-yellow-600 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-yellow-800">Booking Pending</p>
                            <p class="text-xs text-yellow-600">Menunggu konfirmasi</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center">
                        <i class="fas fa-user text-gray-600 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Tidak Ada Booking</p>
                            <p class="text-xs text-gray-600">User belum memiliki booking aktif</p>
                        </div>
                    </div>
                </div>
                @endif
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
</style>
@endsection
