@extends('layouts.admin')

@section('title', 'Detail User')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
    <div class="mb-4 md:mb-0">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Detail User</h1>
        <p class="text-gray-600 mt-2">Informasi lengkap user {{ $user->name }}</p>
    </div>
    <div class="flex space-x-2">
        <a href="{{ route('admin.user.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>
        <a href="{{ route('admin.user.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
</div>

<!-- User Details -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Info -->
    <div class="lg:col-span-2">
        <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi User</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <p class="text-gray-900">{{ $user->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <p class="text-gray-900">{{ $user->email }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                        {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : 
                           ($user->role == 'penghuni' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                        {{ $user->isAdmin() ? 'Administrator' : ($user->isPenghuni() ? 'Penghuni' : 'Calon Penghuni') }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                    <p class="text-gray-900">{{ $user->phone ?? '-' }}</p>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <p class="text-gray-900">{{ $user->address ?? '-' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Identitas</label>
                    <p class="text-gray-900">{{ $user->identity_number ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Account Info -->
        <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Akun</h3>
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
                    <span class="text-sm font-medium text-gray-700">Status Email</span>
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $user->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection