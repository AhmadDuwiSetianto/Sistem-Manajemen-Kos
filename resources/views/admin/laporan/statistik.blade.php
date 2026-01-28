@extends('layouts.admin')

@section('title', 'Laporan Statistik')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
    <div class="mb-4 md:mb-0">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Laporan Statistik</h1>
        <p class="text-gray-600 mt-2">Analisis data dan trend KOSTKU</p>
    </div>
    <div class="flex space-x-3">
        <button class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
            <i class="fas fa-calendar mr-2"></i>Pilih Periode
        </button>
    </div>
</div>

<!-- Key Metrics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium opacity-90">Total Penghuni</p>
                <p class="text-2xl font-bold mt-1">{{ $totalPenghuni }}</p>
            </div>
            <div class="p-3 rounded-full bg-white bg-opacity-20">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium opacity-90">Occupancy Rate</p>
                <p class="text-2xl font-bold mt-1">{{ $occupancyRate }}%</p>
            </div>
            <div class="p-3 rounded-full bg-white bg-opacity-20">
                <i class="fas fa-chart-pie text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium opacity-90">Booking Bulan Ini</p>
                <p class="text-2xl font-bold mt-1">{{ $bookingBulanIni }}</p>
            </div>
            <div class="p-3 rounded-full bg-white bg-opacity-20">
                <i class="fas fa-calendar-check text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium opacity-90">Retention Rate</p>
                <p class="text-2xl font-bold mt-1">{{ $retentionRate }}%</p>
            </div>
            <div class="p-3 rounded-full bg-white bg-opacity-20">
                <i class="fas fa-sync text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Booking Trend -->
    <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Trend Booking 6 Bulan</h3>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
            <div class="text-center text-gray-500">
                <i class="fas fa-chart-line text-4xl mb-2"></i>
                <p>Grafik trend booking</p>
                <p class="text-sm">(Data visualisasi)</p>
            </div>
        </div>
    </div>

    <!-- Room Type Distribution -->
    <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Tipe Kamar</h3>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
            <div class="text-center text-gray-500">
                <i class="fas fa-chart-pie text-4xl mb-2"></i>
                <p>Pie chart distribusi</p>
                <p class="text-sm">(Data visualisasi)</p>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Statistics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- User Demographics -->
    <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Demografi User</h3>
        <div class="space-y-4">
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <span class="text-sm font-medium text-gray-700">Total User</span>
                <span class="text-sm font-semibold text-gray-900">{{ $totalUser }}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <span class="text-sm font-medium text-gray-700">User Aktif</span>
                <span class="text-sm font-semibold text-gray-900">{{ $userAktif }}</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <span class="text-sm font-medium text-gray-700">User Baru (Bulan Ini)</span>
                <span class="text-sm font-semibold text-gray-900">{{ $userBaru }}</span>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="card bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Metrik Kinerja</h3>
        <div class="space-y-4">
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <span class="text-sm font-medium text-gray-700">Avg. Length of Stay</span>
                <span class="text-sm font-semibold text-gray-900">{{ $avgStay }} bulan</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <span class="text-sm font-medium text-gray-700">Booking Conversion Rate</span>
                <span class="text-sm font-semibold text-gray-900">{{ $conversionRate }}%</span>
            </div>
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <span class="text-sm font-medium text-gray-700">Cancellation Rate</span>
                <span class="text-sm font-semibold text-gray-900">{{ $cancellationRate }}%</span>
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