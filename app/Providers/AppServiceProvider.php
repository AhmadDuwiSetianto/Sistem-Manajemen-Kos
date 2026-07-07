<?php

namespace App\Providers;

use App\Models\Booking;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL; // Wajib ditambahkan
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Memaksa HTTPS agar Laravel menghasilkan link yang aman di Vercel
        // Pengecekan environment memastikan ini tidak mengganggu saat Anda coding di localhost
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Logika View Composer Anda tetap aman di sini
        View::composer('admin.*', function ($view) {
            $activeBooking = Booking::whereIn('status', ['confirmed', 'checked_in'])->count();
            $view->with('activeBooking', $activeBooking);
        });
    }

    public function register()
    {
        //
    }
}