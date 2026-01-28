<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kamar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@kos.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1',
            'identity_number' => '1234567890123456',
        ]);

        // Sample Rooms
        Kamar::create([
            'nomor_kamar' => 'A101',
            'tipe_kamar' => 'Standard',
            'harga' => 1500000,
            'fasilitas' => json_encode(['AC', 'Kamar Mandi Dalam', 'WiFi', 'Kasur', 'Lemari']),
            'status' => 'tersedia',
            'deskripsi' => 'Kamar nyaman dengan fasilitas lengkap',
        ]);

        Kamar::create([
            'nomor_kamar' => 'A102',
            'tipe_kamar' => 'Deluxe',
            'harga' => 2000000,
            'fasilitas' => json_encode(['AC', 'Kamar Mandi Dalam', 'WiFi', 'Kasur', 'Lemari', 'TV', 'Kulkas']),
            'status' => 'tersedia',
            'deskripsi' => 'Kamar mewah dengan fasilitas premium',
        ]);

        Kamar::create([
            'nomor_kamar' => 'A103',
            'tipe_kamar' => 'Standard',
            'harga' => 1200000,
            'fasilitas' => json_encode(['Kipas Angin', 'Kamar Mandi Dalam', 'WiFi', 'Kasur', 'Lemari']),
            'status' => 'terisi',
            'deskripsi' => 'Kamar ekonomis dengan fasilitas memadai',
        ]);
    }
}