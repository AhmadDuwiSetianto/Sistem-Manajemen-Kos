<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    public function index()
    {
        // Ambil SEMUA kamar dari database (baik yang tersedia maupun terisi)
        // Diurutkan berdasarkan status (Tersedia tampil duluan), lalu berdasarkan nomor kamar
        $semuaKamar = Kamar::orderByRaw("FIELD(status, 'tersedia', 'terisi')")
                          ->orderBy('nomor_kamar', 'asc')
                          ->get();

        return view('kamar.index', compact('semuaKamar'));
    }

    public function show($id)
    {
        // Ambil detail kamar dari database
        $kamar = Kamar::findOrFail($id);

        return view('kamar.detail', compact('kamar'));
    }
}