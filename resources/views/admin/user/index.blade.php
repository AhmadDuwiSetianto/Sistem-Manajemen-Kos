@extends('layouts.admin')

@section('title', 'Kelola User')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-foreground">Kelola User</h1>
        <p class="text-secondary mt-1">Kelola data penghuni dan administrator</p>
    </div>
    <a href="{{ route('admin.user.create') }}" class="inline-flex items-center px-5 py-2.5 bg-primary hover:bg-primary-hover text-white font-semibold rounded-xl transition-all shadow-sm shadow-primary/30">
        <i data-lucide="plus" class="size-5 mr-2"></i> Tambah User
    </a>
</div>

@if(session('success'))
<div class="bg-success-light border border-success/20 p-4 mb-6 rounded-2xl flex items-center gap-3">
    <div class="size-8 bg-success/20 rounded-full flex items-center justify-center shrink-0">
        <i data-lucide="check-circle" class="size-5 text-success"></i>
    </div>
    <p class="text-success font-medium">{{ session('success') }}</p>
</div>
@endif

@if(session('error'))
<div class="bg-error-light border border-error/20 p-4 mb-6 rounded-2xl flex items-center gap-3">
    <div class="size-8 bg-error/20 rounded-full flex items-center justify-center shrink-0">
        <i data-lucide="alert-circle" class="size-5 text-error"></i>
    </div>
    <p class="text-error font-medium">{{ session('error') }}</p>
</div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="flex flex-col rounded-2xl border border-border p-5 bg-white">
        <div class="flex items-center gap-3 mb-2">
            <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center">
                <i data-lucide="users" class="size-5 text-primary"></i>
            </div>
            <p class="font-medium text-secondary">Total User</p>
        </div>
        <p class="font-bold text-3xl text-foreground">{{ $totalUsers ?? $users->count() }}</p>
    </div>
    <div class="flex flex-col rounded-2xl border border-border p-5 bg-white">
        <div class="flex items-center gap-3 mb-2">
            <div class="size-10 bg-purple-100 rounded-xl flex items-center justify-center">
                <i data-lucide="shield" class="size-5 text-purple-600"></i>
            </div>
            <p class="font-medium text-secondary">Admin</p>
        </div>
        <p class="font-bold text-3xl text-foreground">{{ $adminCount ?? $users->where('role', 'admin')->count() }}</p>
    </div>
    <div class="flex flex-col rounded-2xl border border-border p-5 bg-white">
        <div class="flex items-center gap-3 mb-2">
            <div class="size-10 bg-success-light rounded-xl flex items-center justify-center">
                <i data-lucide="user-check" class="size-5 text-success"></i>
            </div>
            <p class="font-medium text-secondary">Penghuni</p>
        </div>
        <p class="font-bold text-3xl text-foreground">{{ $penghuniCount ?? $users->where('role', 'penghuni')->count() }}</p>
    </div>
    <div class="flex flex-col rounded-2xl border border-border p-5 bg-white">
        <div class="flex items-center gap-3 mb-2">
            <div class="size-10 bg-warning-light rounded-xl flex items-center justify-center">
                <i data-lucide="clock" class="size-5 text-warning-dark"></i>
            </div>
            <p class="font-medium text-secondary">Calon Penghuni</p>
        </div>
        <p class="font-bold text-3xl text-foreground">{{ $calonPenghuniCount ?? $users->where('role', 'calon_penghuni')->count() }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-border overflow-hidden">
    <div class="px-6 py-5 border-b border-border flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h2 class="text-lg font-bold text-foreground">Daftar User</h2>
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative w-full sm:w-48">
                <i data-lucide="filter" class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                <select id="roleFilter" class="w-full pl-10 pr-8 py-2.5 bg-muted border-none rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm appearance-none text-foreground cursor-pointer">
                    <option value="">Semua Role</option>
                    <option value="administrator">Admin</option>
                    <option value="penghuni">Penghuni</option>
                    <option value="calon penghuni">Calon Penghuni</option>
                </select>
                <i data-lucide="chevron-down" class="absolute right-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
            </div>
            
            <div class="relative w-full sm:w-64">
                <input type="text" id="searchInput" placeholder="Cari nama/email..." class="w-full pl-10 pr-4 py-2.5 bg-muted border-none rounded-xl focus:ring-2 focus:ring-primary/20 outline-none text-sm placeholder:text-secondary text-foreground">
                <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-border">
            <thead class="bg-muted/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">User</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Kontak & Identitas</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Status Booking</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-secondary uppercase tracking-wider">Bergabung</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-secondary uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-border">
                @forelse($users as $user)
                <tr class="hover:bg-muted/30 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="size-11 bg-primary/10 rounded-full flex items-center justify-center text-primary font-bold text-lg shrink-0">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-foreground">{{ $user->name }}</p>
                                <p class="text-xs text-secondary mt-0.5">ID: {{ $user->id }}</p>
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
                        <p class="text-xs text-secondary mt-0.5">{{ $user->phone ?? '-' }}</p>
                        @if($user->identity_number)
                        <p class="text-[10px] text-secondary mt-1 bg-muted inline-block px-1.5 py-0.5 rounded">KTP: {{ $user->identity_number }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $activeBooking = $user->getActiveBooking();
                            $pendingBooking = $user->getPendingBooking();
                        @endphp
                        
                        @if($activeBooking)
                        <div class="flex items-center gap-2 text-sm font-semibold text-success">
                            <i data-lucide="home" class="size-4"></i> Kamar {{ $activeBooking->kamar->nomor_kamar ?? '-' }}
                        </div>
                        @elseif($pendingBooking)
                        <div class="flex items-center gap-2 text-sm font-semibold text-warning-dark">
                            <i data-lucide="clock" class="size-4"></i> Pending
                        </div>
                        @elseif($user->isPenghuni())
                        <div class="flex items-center gap-2 text-sm font-semibold text-primary">
                            <i data-lucide="user-check" class="size-4"></i> Aktif
                        </div>
                        @else
                        <div class="flex items-center gap-2 text-sm font-medium text-secondary">
                            <i data-lucide="minus" class="size-4"></i> Tidak Ada
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <p class="text-sm font-medium text-foreground">{{ $user->created_at->format('d/m/Y') }}</p>
                        <p class="text-xs text-secondary mt-0.5">{{ $user->created_at->diffForHumans() }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="window.location.href='/admin/user/{{ $user->id }}'" class="size-8 flex items-center justify-center rounded-lg bg-success/10 text-success hover:bg-success hover:text-white transition-colors" title="Detail">
                                <i data-lucide="eye" class="size-4"></i>
                            </button>
                            <button onclick="window.location.href='/admin/user/{{ $user->id }}/edit'" class="size-8 flex items-center justify-center rounded-lg bg-primary/10 text-primary hover:bg-primary hover:text-white transition-colors" title="Edit">
                                <i data-lucide="pencil" class="size-4"></i>
                            </button>
                            
                            @if($user->id !== Auth::id() && !$user->hasActiveBooking())
                            <form action="{{ route('admin.user.destroy', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="size-8 flex items-center justify-center rounded-lg bg-error-light text-error hover:bg-error hover:text-white transition-colors" onclick="return confirm('Hapus user {{ $user->name }}?')" title="Hapus">
                                    <i data-lucide="trash-2" class="size-4"></i>
                                </button>
                            </form>
                            @else
                            <button class="size-8 flex items-center justify-center rounded-lg bg-muted text-secondary cursor-not-allowed" title="Akses Ditolak">
                                <i data-lucide="trash-2" class="size-4"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i data-lucide="users" class="size-12 text-muted mb-3"></i>
                            <p class="text-sm font-semibold text-foreground">Belum ada data user</p>
                            <p class="text-xs text-secondary mt-1">User akan muncul di sini setelah mendaftar.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($users, 'links'))
    <div class="px-6 py-4 border-t border-border bg-white flex items-center justify-between">
        <p class="text-xs text-secondary font-medium">
            @if($users->total() > 0)
            Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} user
            @endif
        </p>
        <div class="flex gap-2">
            @if(!$users->onFirstPage())
            <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-border text-xs font-semibold text-foreground hover:bg-muted transition-colors">Prev</a>
            @endif
            @if($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-border text-xs font-semibold text-foreground hover:bg-muted transition-colors">Next</a>
            @endif
        </div>
    </div>
    @endif
</div>

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
</script>
@endsection