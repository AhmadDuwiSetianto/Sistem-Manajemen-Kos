@extends('layouts.admin')

@section('title', 'Kelola User')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
    <div class="mb-4 md:mb-0">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Kelola User</h1>
        <p class="text-gray-600 mt-2">Kelola data penghuni dan administrator</p>
    </div>
<a href="{{ route('admin.user.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold rounded-lg shadow-md transition-all duration-200 transform hover:-translate-y-0.5">
    <i class="fas fa-plus mr-2"></i>Tambah User
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

@if(session('error'))
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg shadow-sm">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
        </div>
        <div class="ml-3">
            <p class="text-red-700 font-medium">{{ session('error') }}</p>
        </div>
    </div>
</div>
@endif

<!-- User List -->
<div class="card bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Daftar User</h2>
            <div class="mt-2 sm:mt-0 flex space-x-4">
                <!-- Filter Role -->
                <div class="relative">
                    <select id="roleFilter" class="pl-10 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 appearance-none bg-white">
                        <option value="">Semua Role</option>
                        <option value="admin">Admin</option>
                        <option value="penghuni">Penghuni</option>
                        <option value="calon_penghuni">Calon Penghuni</option>
                    </select>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user-tag text-gray-400"></i>
                    </div>
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
                
                <!-- Search -->
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Cari user..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 w-full sm:w-64">
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak & Identitas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Booking</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-primary-100 to-primary-200 rounded-lg flex items-center justify-center shadow-sm">
                                <span class="text-primary-600 font-semibold text-lg">
                                    {{ substr($user->name, 0, 1) }}
                                </span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500 mt-1">ID: {{ $user->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $roleColors = [
                                'admin' => 'bg-purple-100 text-purple-800 border border-purple-200',
                                'penghuni' => 'bg-green-100 text-green-800 border border-green-200',
                                'calon_penghuni' => 'bg-blue-100 text-blue-800 border border-blue-200'
                            ];
                            $roleIcons = [
                                'admin' => 'fa-user-shield',
                                'penghuni' => 'fa-user-check',
                                'calon_penghuni' => 'fa-user-clock'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                            <i class="fas {{ $roleIcons[$user->role] ?? 'fa-user' }} mr-1.5"></i>
                            {{ $user->isAdmin() ? 'Administrator' : ($user->isPenghuni() ? 'Penghuni' : 'Calon Penghuni') }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $user->email }}</div>
                        <div class="text-sm text-gray-500 mt-1">{{ $user->phone ?? '-' }}</div>
                        @if($user->identity_number)
                        <div class="text-xs text-gray-400 mt-1">ID: {{ $user->identity_number }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $activeBooking = $user->getActiveBooking();
                            $pendingBooking = $user->getPendingBooking();
                        @endphp
                        
                        @if($activeBooking)
                        <div class="flex items-center text-green-600 text-sm">
                            <i class="fas fa-home mr-2"></i>
                            <span>Menempati Kamar {{ $activeBooking->kamar->nomor_kamar ?? '-' }}</span>
                        </div>
                        @elseif($pendingBooking)
                        <div class="flex items-center text-yellow-600 text-sm">
                            <i class="fas fa-clock mr-2"></i>
                            <span>Booking Pending</span>
                        </div>
                        @elseif($user->isPenghuni())
                        <div class="flex items-center text-blue-600 text-sm">
                            <i class="fas fa-user mr-2"></i>
                            <span>Penghuni Aktif</span>
                        </div>
                        @else
                        <div class="flex items-center text-gray-500 text-sm">
                            <i class="fas fa-user mr-2"></i>
                            <span>{{ $user->isCalonPenghuni() ? 'Calon Penghuni' : 'Tidak Aktif' }}</span>
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $user->created_at->format('d/m/Y') }}
                        <div class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2">
                            <button class="inline-flex items-center p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                    title="Edit User"
                                    onclick="editUser({{ $user->id }})">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                            
                            <button class="inline-flex items-center p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-colors duration-200"
                                    title="Lihat Detail"
                                    onclick="viewUser({{ $user->id }})">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                            
                            @if($user->id !== Auth::id() && !$user->hasActiveBooking())
                            <form action="{{ route('admin.user.destroy', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus user {{ $user->name }}?')"
                                        title="Hapus User">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                            @else
                            <span class="inline-flex items-center p-2 text-gray-400 cursor-not-allowed"
                                  title="{{ $user->id === Auth::id() ? 'Tidak dapat menghapus akun sendiri' : 'User memiliki booking aktif' }}">
                                <i class="fas fa-trash text-sm"></i>
                            </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <i class="fas fa-users text-4xl mb-3"></i>
                            <p class="text-lg font-medium text-gray-500">Belum ada data user</p>
                            <p class="text-sm mt-1 text-gray-400">User akan muncul setelah mendaftar</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(method_exists($users, 'links'))
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                @if($users->total() > 0)
                Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} user
                @else
                Tidak ada data user
                @endif
            </div>
            <div class="flex space-x-2">
                @if($users->onFirstPage())
                <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                    <i class="fas fa-chevron-left mr-1"></i> Sebelumnya
                </span>
                @else
                <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1 bg-white text-gray-700 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors duration-200">
                    <i class="fas fa-chevron-left mr-1"></i> Sebelumnya
                </a>
                @endif

                @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1 bg-white text-gray-700 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors duration-200">
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
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium opacity-90">Total User</p>
                <p class="text-2xl font-bold mt-1">{{ $totalUsers ?? $users->count() }}</p>
            </div>
            <div class="p-3 rounded-full bg-white bg-opacity-20">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium opacity-90">Admin</p>
                <p class="text-2xl font-bold mt-1">{{ $adminCount ?? $users->where('role', 'admin')->count() }}</p>
            </div>
            <div class="p-3 rounded-full bg-white bg-opacity-20">
                <i class="fas fa-user-shield text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium opacity-90">Penghuni</p>
                <p class="text-2xl font-bold mt-1">{{ $penghuniCount ?? $users->where('role', 'penghuni')->count() }}</p>
            </div>
            <div class="p-3 rounded-full bg-white bg-opacity-20">
                <i class="fas fa-user-check text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium opacity-90">Calon Penghuni</p>
                <p class="text-2xl font-bold mt-1">{{ $calonPenghuniCount ?? $users->where('role', 'calon_penghuni')->count() }}</p>
            </div>
            <div class="p-3 rounded-full bg-white bg-opacity-20">
                <i class="fas fa-user-clock text-xl"></i>
            </div>
        </div>
    </div>
</div>

<script>
    // Search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const roleFilter = document.getElementById('roleFilter');
        
        function filterUsers() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedRole = roleFilter.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const roleCell = row.querySelector('td:nth-child(2)');
                const role = roleCell ? roleCell.textContent.toLowerCase() : '';
                
                const matchesSearch = text.includes(searchTerm);
                const matchesRole = !selectedRole || role.includes(selectedRole);
                
                if (matchesSearch && matchesRole) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        if (searchInput) {
            searchInput.addEventListener('input', filterUsers);
        }
        
        if (roleFilter) {
            roleFilter.addEventListener('change', filterUsers);
        }
    });

    function editUser(userId) {
        // Redirect to edit page or show modal
        window.location.href = `/admin/user/${userId}/edit`;
    }

    function viewUser(userId) {
        // Redirect to detail page or show modal
        window.location.href = `/admin/user/${userId}`;
    }
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