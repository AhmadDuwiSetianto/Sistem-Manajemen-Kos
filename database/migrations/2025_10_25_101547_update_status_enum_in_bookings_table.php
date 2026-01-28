<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Untuk MySQL - update enum values di tabel bookings
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE bookings MODIFY status ENUM('pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled', 'expired') DEFAULT 'pending'");
        }
        
        // Untuk SQLite atau database lain
        else {
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('status', 20)->default('pending')->change();
            });
        }
    }

    public function down(): void
    {
        // Rollback ke enum sebelumnya
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE bookings MODIFY status ENUM('pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled') DEFAULT 'pending'");
        }
        
        // Untuk SQLite
        else {
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('status', 20)->default('pending')->change();
            });
        }
    }
};