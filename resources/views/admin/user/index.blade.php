@extends('layouts.admin')

@section('title', 'Kelola User')

@push('styles')
<style>
    /* Custom SweetAlert2 untuk Inna Kos */
    div:where(.swal2-container) div:where(.swal2-popup) { border-radius: 1.2rem !important; padding: 1.5rem !important; }
    div:where(.swal2-container) button:where(.swal2-styled) { border-radius: 0.75rem !important; font-weight: 600 !important; padding: 0.6rem 1.5rem !important; }
</style>
@endpush

@section('content')
<div class="flex-1 p-4 md:p-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 md:mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-foreground">Kelola User</h1>
            <p class="text-secondary mt-1 text-sm md:text-base">Manajemen data penghuni dan administrator sistem</p>
        </div>
        <a href="{{ route('admin.user.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-primary hover:bg-primary-hover text-white text-xs md:text-sm font-bold rounded-xl transition-all shadow-sm shadow-primary/20">
            <i data-lucide="plus" class="size-4 mr-2"></i> Tambah User Baru
        </a>
    </div>

    <!-- Cards Statistik (Mobile: 2 Kolom, Desktop: 4 Kolom) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-5 mb-6 md:mb-8">
        <div class="bg-white border border-border rounded-xl p-4 md:p-5 hover:shadow-sm transition-shadow">
            <div class="flex items-center gap-2 md:gap-3 mb-2 md:mb-3">
                <div class="size-8 md:size-10 bg-primary/10 rounded-lg md:rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="users" class="size-4 md:size-5 text-primary"></i>
                </div>
                <p class="font-medium text-[10px] md:text-sm text-secondary leading-tight">Total<br class="md:hidden"> User</p>
            </div>
            <p class="font-black text-xl md:text-3xl text-foreground">{{ $totalUsers ?? $users->count() }}</p>
        </div>
        <div class="bg-white border border-border rounded-xl p-4 md:p-5 hover:shadow-sm transition-shadow">
            <div class="flex items-center gap-2 md:gap-3 mb-2 md:mb-3">
                <div class="size-8 md:size-10 bg-purple-100 rounded-lg md:rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="shield" class="size-4 md:size-5 text-purple-600"></i>
                </div>
                <p class="font-medium text-[10px] md:text-sm text-secondary leading-tight">Total<br class="md:hidden"> Admin</p>
            </div>
            <p class="font-black text-xl md:text-3xl text-foreground">{{ $adminCount ?? $users->where('role', 'admin')->count() }}</p>
        </div>
        <div class="bg-white border border-border rounded-xl p-4 md:p-5 hover:shadow-sm transition-shadow">
            <div class="flex items-center gap-2 md:gap-3 mb-2 md:mb-3">
                <div class="size-8 md:size-10 bg-success-light rounded-lg md:rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="user-check" class="size-4 md:size-5 text-success"></i>
                </div>
                <p class="font-medium text-[10px] md:text-sm text-secondary leading-tight">Total<br class="md:hidden"> Penghuni</p>
            </div>
            <p class="font-black text-xl md:text-3xl text-foreground">{{ $penghuniCount ?? $users->where('role', 'penghuni')->count() }}</p>
        </div>
        <div class="bg-white border border-border rounded-xl p-4 md:p-5 hover:shadow-sm transition-shadow">
            <div class="flex items-center gap-2 md:gap-3 mb-2 md:mb-3">
                <div class="size-8 md:size-10 bg-warning-light rounded-lg md:rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="clock" class="size-4 md:size-5 text-warning-dark"></i>
                </div>
                <p class="font-medium text-[10px] md:text-sm text-secondary leading-tight">Calon<br class="md:hidden"> Penghuni</p>
            </div>
            <p class="font-black text-xl md:text-3xl text-foreground">{{ $calonPenghuniCount ?? $users->where('role', 'calon_penghuni')->count() }}</p>
        </div>
    </div>

    <!-- Tabel User -->
    <div class="bg-white border border-border rounded-2xl shadow-sm overflow-hidden">
        <div class="p-4 md:p-5 border-b border-border flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="text-base md:text-lg font-bold text-foreground">Daftar User</h2>
            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                <div class="relative w-full sm:w-40">
                    <i data-lucide="filter" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                    <select id="roleFilter" class="w-full pl-9 pr-8 py-2 bg-muted border border-transparent rounded-xl focus:bg-white focus:border-primary/30 outline-none text-xs md:text-sm appearance-none cursor-pointer transition-all">
                        <option value="">Semua Role</option>
                        <option value="administrator">Admin</option>
                        <option value="penghuni">Penghuni</option>
                        <option value="calon penghuni">Calon Penghuni</option>
                    </select>
                    <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
                </div>
                <div class="relative w-full sm:w-56">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                    <input type="text" id="searchInput" placeholder="Cari nama/email..." class="w-full pl-9 pr-4 py-2 bg-muted border border-transparent rounded-xl focus:bg-white focus:border-primary/30 outline-none text-xs md:text-sm placeholder:text-secondary text-foreground transition-all">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-muted/30">
                    <tr>
                        <th class="px-5 py-3 text-left text-[10px] md:text-xs font-semibold text-secondary uppercase">Profil</th>
                        <th class="px-5 py-3 text-left text-[10px] md:text-xs font-semibold text-secondary uppercase">Role</th>
                        <th class="px-5 py-3 text-left text-[10px] md:text-xs font-semibold text-secondary uppercase">Kontak</th>
                        <th class="px-5 py-3 text-left text-[10px] md:text-xs font-semibold text-secondary uppercase">Status</th>
                        <th class="px-5 py-3 text-right text-[10px] md:text-xs font-semibold text-secondary uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    @forelse($users as $user)
                    <tr class="hover:bg-muted/30 transition-colors {{ !$user->is_active ? 'opacity-60 bg-muted/10' : '' }}">
                        <td class="px-5 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="size-8 md:size-10 {{ $user->is_active ? 'bg-primary/10 text-primary' : 'bg-gray-200 text-gray-500' }} rounded-full flex items-center justify-center font-bold text-xs md:text-sm shrink-0">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-xs md:text-sm font-bold text-foreground">{{ $user->name }}</p>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <p class="text-[9px] md:text-[11px] text-secondary font-medium">ID: {{ $user->id }}</p>
                                        @if(!$user->is_active)
                                            <span class="text-[8px] md:text-[9px] font-bold bg-error-light text-error px-1.5 py-0.5 rounded uppercase">Nonaktif</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            @php
                                $roleMap = [
                                    'admin' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Administrator'],
                                    'penghuni' => ['bg' => 'bg-success-light', 'text' => 'text-success', 'label' => 'Penghuni'],
                                    'calon_penghuni' => ['bg' => 'bg-warning-light', 'text' => 'text-warning-dark', 'label' => 'Calon Penghuni']
                                ];
                                $currRole = $roleMap[$user->role] ?? ['bg' => 'bg-muted', 'text' => 'text-secondary', 'label' => 'Unknown'];
                            @endphp
                            <span class="inline-flex px-2 py-1 rounded-md text-[9px] md:text-[11px] font-bold uppercase tracking-wider {{ $currRole['bg'] }} {{ $currRole['text'] }}">
                                {{ $currRole['label'] }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <p class="text-xs md:text-sm font-medium text-foreground">{{ $user->email }}</p>
                            @if($user->phone)
                                <p class="text-[10px] md:text-xs text-secondary mt-0.5">{{ $user->phone }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            @php
                                $activeBooking = $user->getActiveBooking();
                                $pendingBooking = $user->getPendingBooking();
                            @endphp
                            @if($activeBooking)
                            <div class="flex items-center gap-1 text-[11px] md:text-xs font-bold text-success">
                                <i data-lucide="home" class="size-3.5 md:size-4"></i> Kamar {{ $activeBooking->kamar->nomor_kamar ?? '-' }}
                            </div>
                            @elseif($pendingBooking)
                            <div class="flex items-center gap-1 text-[11px] md:text-xs font-bold text-warning-dark">
                                <i data-lucide="clock" class="size-3.5 md:size-4"></i> Pending
                            </div>
                            @elseif($user->isPenghuni())
                            <div class="flex items-center gap-1 text-[11px] md:text-xs font-bold text-primary">
                                <i data-lucide="user-check" class="size-3.5 md:size-4"></i> Aktif
                            </div>
                            @else
                            <div class="flex items-center gap-1 text-[11px] md:text-xs font-medium text-secondary">
                                <i data-lucide="minus" class="size-3.5 md:size-4"></i> Tidak Ada
                            </div>
                            @endif
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                @if($user->id !== Auth::id())
                                <form action="{{ route('admin.user.toggle-status', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="size-7 md:size-8 flex items-center justify-center rounded-lg border {{ $user->is_active ? 'border-warning-light bg-warning/10 text-warning-dark hover:bg-warning hover:text-white' : 'border-success-light bg-success/10 text-success hover:bg-success hover:text-white' }} transition-colors" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i data-lucide="{{ $user->is_active ? 'user-x' : 'user-check' }}" class="size-3.5 md:size-4"></i>
                                    </button>
                                </form>
                                @endif

                                <a href="/admin/user/{{ $user->id }}" class="size-7 md:size-8 flex items-center justify-center rounded-lg border border-blue-100 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors" title="Detail">
                                    <i data-lucide="eye" class="size-3.5 md:size-4"></i>
                                </a>
                                
                                <a href="/admin/user/{{ $user->id }}/edit" class="size-7 md:size-8 flex items-center justify-center rounded-lg border border-primary/20 bg-primary/10 text-primary hover:bg-primary hover:text-white transition-colors" title="Edit">
                                    <i data-lucide="pencil" class="size-3.5 md:size-4"></i>
                                </a>
                                
                                @if($user->id !== Auth::id() && !$user->hasActiveBooking())
                                <form action="{{ route('admin.user.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="size-7 md:size-8 flex items-center justify-center rounded-lg border border-error-light bg-error-light text-error hover:bg-error hover:text-white transition-colors cursor-pointer" onclick="deleteConfirm(this, 'User {{ $user->name }}')" title="Hapus">
                                        <i data-lucide="trash-2" class="size-3.5 md:size-4"></i>
                                    </button>
                                </form>
                                @elseif($user->id !== Auth::id())
                                <button class="size-7 md:size-8 flex items-center justify-center rounded-lg border border-border bg-muted text-secondary/50 cursor-not-allowed" title="Akses Ditolak: User memiliki booking aktif">
                                    <i data-lucide="trash-2" class="size-3.5 md:size-4"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="size-12 md:size-16 bg-muted rounded-full flex items-center justify-center mb-3">
                                    <i data-lucide="users" class="size-6 md:size-8 text-secondary/50"></i>
                                </div>
                                <p class="text-sm font-semibold text-foreground">Belum ada data user</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($users, 'links') && $users->hasPages())
        <div class="px-5 py-3 border-t border-border bg-white flex items-center justify-between">
            <p class="text-[10px] md:text-xs text-secondary font-medium">
                Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} user
            </p>
            <div class="flex gap-2">
                @if(!$users->onFirstPage())
                <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1 rounded-md border border-border text-[10px] md:text-xs font-semibold text-secondary hover:text-foreground hover:bg-muted transition-colors">Prev</a>
                @endif
                @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1 rounded-md border border-border text-[10px] md:text-xs font-semibold text-secondary hover:text-foreground hover:bg-muted transition-colors">Next</a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        
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

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", showConfirmButton: false, timer: 2000 });
    @endif

    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Ditolak', text: "{{ session('error') }}", confirmButtonColor: '#165DFF' });
    @endif
</script>
@endpush