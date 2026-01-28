@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Profil Saya</h1>
        <p class="text-gray-600">Kelola informasi profil dan akun Anda</p>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Form -->
        <div class="lg:col-span-2">
            <div class="card p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">Informasi Pribadi</h3>
                
                <form action="{{ route('user.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   required>
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" value="{{ $user->email }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50"
                                   readonly>
                            <p class="mt-1 text-sm text-gray-500">Email tidak dapat diubah</p>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon *</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   required>
                            @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Identity Number -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor KTP</label>
                            <input type="text" value="{{ $user->identity_number }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50"
                                   readonly>
                            <p class="mt-1 text-sm text-gray-500">Nomor KTP tidak dapat diubah</p>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mt-6">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat *</label>
                        <textarea id="address" name="address" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                  required>{{ old('address', $user->address) }}</textarea>
                        @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Account Info -->
        <div class="lg:col-span-1">
            <div class="card p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-6">Informasi Akun</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Akun</label>
                        <div class="flex items-center">
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Terdaftar Sejak</label>
                        <p class="text-gray-900">{{ $user->created_at->format('d F Y') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Booking</label>
                        <p class="text-gray-900">{{ $user->bookings->count() }} kali</p>
                    </div>

                    @if($user->getActiveBooking())
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <h4 class="font-semibold text-blue-800 mb-2">Kamar Aktif</h4>
                        <p class="text-blue-700">Kamar {{ $user->getActiveBooking()->kamar->nomor_kamar }}</p>
                        <p class="text-blue-600 text-sm">Sejak {{ $user->getActiveBooking()->tanggal_masuk->format('d/m/Y') }}</p>
                    </div>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div class="mt-8 space-y-3">
                    <a href="{{ route('user.dashboard') }}" class="w-full btn-secondary text-center block">
                        <i class="fas fa-tachometer-alt mr-2"></i>Ke Dashboard
                    </a>
                    @if($user->getActiveBooking())
                    <a href="#" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-300 text-center block">
                        <i class="fas fa-question-circle mr-2"></i>Butuh Bantuan?
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection