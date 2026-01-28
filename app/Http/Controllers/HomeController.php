<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $kamarTersedia = Kamar::where('status', 'tersedia')
                             ->orderBy('created_at', 'desc')
                             ->get();

        return view('home', compact('kamarTersedia'));
    }
}