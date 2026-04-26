<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login dan memiliki role 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }
        
        // Jika bukan admin, tendang kembali ke halaman utama
        return redirect('/')->with('error', 'Akses ditolak! Anda bukan administrator.');
    }
}