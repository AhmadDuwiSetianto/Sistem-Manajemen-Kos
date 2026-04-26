@extends('layouts.admin')

@section('title', 'Kelola User')

@push('styles')
<style>
    /* MEMAKSA SWEETALERT2 MENGIKUTI RADIUS NEXUS CRM */
    div:where(.swal2-container) div:where(.swal2-popup) {
        border-radius: 1.2rem !important; /* Setara dengan rounded-2xl/3xl */
        padding: 1.5rem !important;
    }
    div:where(.swal2-container) button:where(.swal2-styled) {
        border-radius: 0.75rem !important; /* Setara dengan rounded-xl */
        font-weight: 600 !important;
        padding: 0.6rem 1.5rem !important;
    }
</style>
@endpush

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-foreground">Kelola User</h1>
        <p class="text-sm text-secondary mt-1">Manajemen data penghuni dan administrator sistem</p>
    </div>
    <a href="{{ route('admin.user.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-primary hover:bg-primary-hover text-white text-sm font-semibold rounded-xl transition-all shadow-sm shadow-primary/20">
        <i data-lucide="plus" class="size-4 mr-2"></i> Tambah User Baru
    </a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white border border-border rounded-2xl p-5 hover:shadow-md transition-shadow">
        <div class="flex items-center gap-4 mb-3">
            <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="users" class="size-5 text-primary"></i>
            </div>
            <p class="font-medium text-sm text-secondary">Total User</p>
        </div>
        <p class="font-bold text-3xl text-foreground">{{ $totalUsers ?? $users->count() }}</p>
    </div>
    <div class="bg-white border border-border rounded-2xl p-5 hover:shadow-md transition-shadow">
        <div class="flex items-center gap-4 mb-3">
            <div class="size-10 bg-purple-100 rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="shield" class="size-5 text-purple-600"></i>
            </div>
            <p class="font-medium text-sm text-secondary">Admin</p>
        </div>
        <p class="font-bold text-3xl text-foreground">{{ $adminCount ?? $users->where('role', 'admin')->count() }}</p>
    </div>
    <div class="bg-white border border-border rounded-2xl p-5 hover:shadow-md transition-shadow">
        <div class="flex items-center gap-4 mb-3">
            <div class="size-10 bg-success-light rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="user-check" class="size-5 text-success"></i>
            </div>
            <p class="font-medium text-sm text-secondary">Penghuni</p>
        </div>
        <p class="font-bold text-3xl text-foreground">{{ $penghuniCount ?? $users->where('role', 'penghuni')->count() }}</p>
    </div>
    <div class="bg-white border border-border rounded-2xl p-5 hover:shadow-md transition-shadow">
        <div class="flex items-center gap-4 mb-3">
            <div class="size-10 bg-warning-light rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="clock" class="size-5 text-warning-dark"></i>
            </div>
            <p class="font-medium text-sm text-secondary">Calon Penghuni</p>
        </div>
        <p class="font-bold text-3xl text-foreground">{{ $calonPenghuniCount ?? $users->where('role', 'calon_penghuni')->count() }}</p>
    </div>
</div>

<div class="bg-white border border-border rounded-2xl shadow-sm overflow-hidden">
    <div class="p-5 border-b border-border flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white">
        <h2 class="text-lg font-bold text-foreground">Daftar User</h2>
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative w-full sm:w-48">
                <i data-lucide="filter" class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                <select id="roleFilter" class="w-full pl-10 pr-8 py-2.5 bg-muted border border-transparent rounded-xl focus:bg-white focus:border-primary/30 focus:ring-4 focus:ring-primary/10 outline-none text-sm appearance-none text-foreground cursor-pointer transition-all">
                    <option value="">Semua Role</option>
                    <option value="administrator">Admin</option>
                    <option value="penghuni">Penghuni</option>
                    <option value="calon penghuni">Calon Penghuni</option>
                </select>
                <i data-lucide="chevron-down" class="absolute right-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
            </div>
            
            <div class="relative w-full sm:w-64">
                <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                <input type="text" id="searchInput" placeholder="Cari nama atau email..." class="w-full pl-10 pr-4 py-2.5 bg-muted border border-transparent rounded-xl focus:bg-white focus:border-primary/30 focus:ring-4 focus:ring-primary/10 outline-none text-sm placeholder:text-secondary text-foreground transition-all">
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-border">
            <thead class="bg-muted/30">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">User Profile</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Kontak</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Status Kamar</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Bergabung</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-secondary uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-border">
                @forelse($users as $user)
                <tr class="hover:bg-muted/30 transition-colors {{ !$user->is_active ? 'opacity-60 bg-muted/10' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="size-10 {{ $user->is_active ? 'bg-primary/10 text-primary' : 'bg-gray-200 text-gray-500' }} rounded-full flex items-center justify-center font-bold text-sm shrink-0">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-foreground">{{ $user->name }}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <p class="text-[11px] text-secondary font-medium">ID: {{ $user->id }}</p>
                                    @if(!$user->is_active)
                                        <span class="text-[9px] font-bold bg-error-light text-error px-1.5 py-0.5 rounded uppercase">Nonaktif</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $roleMap = [
                                'admin' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Administrator'],
                                'penghuni' => ['bg' => 'bg-success-light', 'text' => 'text-success', 'label' => 'Penghuni'],
                                'calon_penghuni' => ['bg' => 'bg-warning-light', 'text' => 'text-warning-dark', 'label' => 'Calon Penghuni']
                            ];
                            $currRole = $roleMap[$user->role] ?? ['bg' => 'bg-muted', 'text' => 'text-secondary', 'label' => 'Unknown'];
                        @endphp
                        <span class="inline-flex px-2.5 py-1 rounded-md text-[11px] font-bold uppercase tracking-wider {{ $currRole['bg'] }} {{ $currRole['text'] }}">
                            {{ $currRole['label'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium text-foreground">{{ $user->email }}</p>
                        @if($user->phone)
                            <p class="text-xs text-secondary mt-0.5">{{ $user->phone }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $activeBooking = $user->getActiveBooking();
                            $pendingBooking = $user->getPendingBooking();
                        @endphp
                        
                        @if($activeBooking)
                        <div class="flex items-center gap-1.5 text-sm font-semibold text-success">
                            <i data-lucide="home" class="size-4"></i> Kamar {{ $activeBooking->kamar->nomor_kamar ?? '-' }}
                        </div>
                        @elseif($pendingBooking)
                        <div class="flex items-center gap-1.5 text-sm font-semibold text-warning-dark">
                            <i data-lucide="clock" class="size-4"></i> Pending
                        </div>
                        @elseif($user->isPenghuni())
                        <div class="flex items-center gap-1.5 text-sm font-semibold text-primary">
                            <i data-lucide="user-check" class="size-4"></i> Aktif
                        </div>
                        @else
                        <div class="flex items-center gap-1.5 text-sm font-medium text-secondary">
                            <i data-lucide="minus" class="size-4"></i> Tidak Ada
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-foreground">{{ $user->created_at->format('d M Y') }}</p>
                        <p class="text-xs text-secondary mt-0.5">{{ $user->created_at->diffForHumans() }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-2">
                            
                            @if($user->id !== Auth::id())
                            <form action="{{ route('admin.user.toggle-status', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="size-8 flex items-center justify-center rounded-lg border {{ $user->is_active ? 'border-warning-light bg-warning/10 text-warning-dark hover:bg-warning hover:text-white hover:border-warning' : 'border-success-light bg-success/10 text-success hover:bg-success hover:text-white hover:border-success' }} transition-colors" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i data-lucide="{{ $user->is_active ? 'user-x' : 'user-check' }}" class="size-4"></i>
                                </button>
                            </form>
                            @endif

                            <a href="/admin/user/{{ $user->id }}" class="size-8 flex items-center justify-center rounded-lg border border-blue-100 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-colors" title="Detail">
                                <i data-lucide="eye" class="size-4"></i>
                            </a>
                            
                            <a href="/admin/user/{{ $user->id }}/edit" class="size-8 flex items-center justify-center rounded-lg border border-primary/20 bg-primary/10 text-primary hover:bg-primary hover:text-white hover:border-primary transition-colors" title="Edit">
                                <i data-lucide="pencil" class="size-4"></i>
                            </a>
                            
                            @if($user->id !== Auth::id() && !$user->hasActiveBooking())
                            <form action="{{ route('admin.user.destroy', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="size-8 flex items-center justify-center rounded-lg border border-error-light bg-error-light text-error hover:bg-error hover:text-white hover:border-error transition-colors cursor-pointer" onclick="deleteConfirm(this, 'User {{ $user->name }}')" title="Hapus">
                                    <i data-lucide="trash-2" class="size-4"></i>
                                </button>
                            </form>
                            @elseif($user->id !== Auth::id())
                            <button class="size-8 flex items-center justify-center rounded-lg border border-border bg-muted text-secondary/50 cursor-not-allowed" title="Akses Ditolak: User memiliki booking aktif">
                                <i data-lucide="trash-2" class="size-4"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="size-16 bg-muted rounded-full flex items-center justify-center mb-4">
                                <i data-lucide="users" class="size-8 text-secondary/50"></i>
                            </div>
                            <p class="text-base font-semibold text-foreground">Belum ada data user</p>
                            <p class="text-sm text-secondary mt-1">User yang mendaftar akan muncul di daftar ini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($users, 'links') && $users->hasPages())
    <div class="px-6 py-4 border-t border-border bg-white flex items-center justify-between">
        <p class="text-xs text-secondary font-medium">
            Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} user
        </p>
        <div class="flex gap-2">
            @if(!$users->onFirstPage())
            <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-border text-xs font-semibold text-secondary hover:text-foreground hover:bg-muted transition-colors">Prev</a>
            @endif
            @if($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-border text-xs font-semibold text-secondary hover:text-foreground hover:bg-muted transition-colors">Next</a>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        
        // Fitur Pencarian & Filter Instan (Client-side)
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
                
                row.style.display = (matchesSearch && matchesRole) ? '' : 'none';
            });
        }
        
        if (searchInput) searchInput.addEventListener('input', filterUsers);
        if (roleFilter) roleFilter.addEventListener('change', filterUsers);
    });

    // FUNGSI UNTUK KONFIRMASI HAPUS (SWEETALERT2)
    function deleteConfirm(button, itemName) {
        Swal.fire({
            title: 'Hapus Data?',
            text: `Anda akan menghapus ${itemName} secara permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ED6B60', 
            cancelButtonColor: '#EFF2F7', 
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: '<span style="color:#6A7686">Batal</span>',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }

    // TAMPILKAN NOTIFIKASI SUKSES / GAGAL DARI SESSION
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2500
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Ditolak',
            text: "{{ session('error') }}",
            confirmButtonColor: '#165DFF'
        });
    @endif
</script>
@endpush