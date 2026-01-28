<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    public function index()
    {
        // Ambil semua kamar yang tersedia dari database
        $semuaKamar = Kamar::where('status', 'tersedia')
                          ->orderBy('harga', 'asc')
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