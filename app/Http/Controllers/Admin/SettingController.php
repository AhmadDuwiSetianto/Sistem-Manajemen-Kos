<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // Dummy data pengaturan (Nantinya bisa kamu ambil dari database tabel settings)
        $settings = [
            'app_name' => 'MyKos Elite',
            'app_address' => 'Jl. Merdeka No. 123, Bandung, Jawa Barat',
            'app_phone' => '081234567890',
            'app_email' => 'admin@mykos.com',
            'midtrans_client_key' => 'SB-Mid-client-xxxxxxxxxxx',
            'midtrans_server_key' => 'SB-Mid-server-xxxxxxxxxxx',
            'midtrans_is_production' => false,
            'bank_account' => 'BCA - 1234567890 a.n. Budi Kost',
            'maintenance_mode' => false,
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Logic validasi dan update ke database nantinya ditaruh di sini
        // ...

        return redirect()->back()->with('success', 'Pengaturan sistem berhasil diperbarui!');
    }
}