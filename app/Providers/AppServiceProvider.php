<?php

namespace App\Providers;

use App\Models\Booking;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Share activeBooking dengan semua view admin
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