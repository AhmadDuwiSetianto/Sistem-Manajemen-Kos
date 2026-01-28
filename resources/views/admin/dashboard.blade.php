@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
    <div class="mb-4 md:mb-0">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Dashboard Admin</h1>
        <p class="text-gray-600 mt-2">Overview dan statistik sistem MyKos</p>
    </div>
    <div class="flex items-center space-x-3">
        <span class="text-sm text-gray-500">Terakhir update: {{ now()->format('d/m/Y H:i') }}</span>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Kamar -->
    <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg mr-4">
                <i class="fas fa-door-open text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-blue-600 font-medium">Total Kamar</p>
                <p class="text-2xl font-bold text-blue-800">{{ $totalKamar ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Kamar Tersedia -->
    <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg mr-4">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-green-600 font-medium">Kamar Tersedia</p>
                <p class="text-2xl font-bold text-green-800">{{ $kamarTersedia ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Total Penghuni -->
    <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-lg mr-4">
                <i class="fas fa-users text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-purple-600 font-medium">Total Penghuni</p>
                <p class="text-2xl font-bold text-purple-800">{{ $totalPenghuni ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Pending Bookings -->
    <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-lg mr-4">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-yellow-600 font-medium">Pending Bookings</p>
                <p class="text-2xl font-bold text-yellow-800">{{ $pendingBookingsCount ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Second Row Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Kamar Terisi -->
    <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-orange-100 rounded-lg mr-4">
                <i class="fas fa-bed text-orange-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-orange-600 font-medium">Kamar Terisi</p>
                <p class="text-2xl font-bold text-orange-800">{{ $kamarTerisi ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Confirmed Bookings -->
    <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg mr-4">
                <i class="fas fa-calendar-check text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-green-600 font-medium">Confirmed</p>
                <p class="text-2xl font-bold text-green-800">{{ $confirmedBookingsCount ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Checked In -->
    <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg mr-4">
                <i class="fas fa-key text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-blue-600 font-medium">Checked In</p>
                <p class="text-2xl font-bold text-blue-800">{{ $checkedInBookingsCount ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Pending Payments -->
    <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center">
            <div class="p-3 bg-red-100 rounded-lg mr-4">
                <i class="fas fa-money-bill-wave text-red-600 text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-red-600 font-medium">Pending Payments</p>
                <p class="text-2xl font-bold text-red-800">{{ $pendingPaymentsCount ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings & Users -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Bookings -->
    <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Booking Terbaru</h3>
            <a href="{{ route('admin.booking.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                Lihat Semua
            </a>
        </div>
        <div class="space-y-4">
            @forelse($recentBookings ?? [] as $booking)
            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar text-gray-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">{{ $booking->user->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">Kamar {{ $booking->kamar->nomor_kamar ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'confirmed' => 'bg-green-100 text-green-800',
                            'checked_in' => 'bg-blue-100 text-blue-800',
                            'completed' => 'bg-gray-100 text-gray-800',
                            'cancelled' => 'bg-red-100 text-red-800'
                        ];
                    @endphp
                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $booking->created_at->format('d/m/Y') }}
                    </p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-calendar-times text-3xl mb-3"></i>
                <p class="text-gray-600">Tidak ada booking terbaru</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Users -->
    <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">User Terbaru</h3>
            <a href="{{ route('admin.user.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                Lihat Semua
            </a>
        </div>
        <div class="space-y-4">
            @forelse($recentUsers ?? [] as $user)
            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-gray-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">{{ $user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="text-right">
                    @php
                        $roleColors = [
                            'penghuni' => 'bg-green-100 text-green-800',
                            'calon_penghuni' => 'bg-blue-100 text-blue-800',
                            'admin' => 'bg-purple-100 text-purple-800'
                        ];
                    @endphp
                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $user->role == 'penghuni' ? 'Penghuni' : ($user->role == 'admin' ? 'Admin' : 'Calon Penghuni') }}
                    </span>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $user->created_at->format('d/m/Y') }}
                    </p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-users text-3xl mb-3"></i>
                <p class="text-gray-600">Tidak ada user terbaru</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Quick Actions --><!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h3 class="text-lg font-semibold mb-2">Kelola Kamar</h3>
                <p class="text-blue-100 text-sm mb-4">Tambah, edit, / hapus kamar kos</p>
                <a href="{{ route('admin.kamar.index') }}" class="inline-flex items-center px-4 py-2 bg-white text-blue-600 font-medium rounded-lg hover:bg-blue-50 transition-colors duration-200 shadow-md">
                    <i class="fas fa-door-open mr-2"></i>Kelola Kamar
                </a>
            </div>
            <div class="ml-4">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-home text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h3 class="text-lg font-semibold mb-2">Lihat Laporan</h3>
                <p class="text-green-100 text-sm mb-4">Analisis keuangan dan statistik</p>
                <a href="{{ route('admin.laporan.keuangan') }}" class="inline-flex items-center px-4 py-2 bg-white text-green-600 font-medium rounded-lg hover:bg-green-50 transition-colors duration-200 shadow-md">
                    <i class="fas fa-chart-bar mr-2"></i>Lihat Laporan
                </a>
            </div>
            <div class="ml-4">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h3 class="text-lg font-semibold mb-2">Kelola User</h3>
                <p class="text-purple-100 text-sm mb-4">Kelola data penghuni dan admin</p>
                <a href="{{ route('admin.user.index') }}" class="inline-flex items-center px-4 py-2 bg-white text-purple-600 font-medium rounded-lg hover:bg-purple-50 transition-colors duration-200 shadow-md">
                    <i class="fas fa-users mr-2"></i>Kelola User
                </a>
            </div>
            <div class="ml-4">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-cog text-xl"></i>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation to cards
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endpush