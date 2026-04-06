<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil SEMUA kamar (Tersedia maupun Terisi) untuk ditampilkan di Landing Page
        // Diurutkan: yang 'tersedia' tampil lebih dulu, baru yang 'terisi'
        $semuaKamar = Kamar::orderByRaw("FIELD(status, 'tersedia', 'terisi')")
                           ->orderBy('created_at', 'desc')
                           ->get();

        // Kirim variabel $semuaKamar ke view 'home'
        return view('home', compact('semuaKamar'));
    }
}