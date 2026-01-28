<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Untuk MySQL - update enum values
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE kamar MODIFY status ENUM('tersedia', 'dipesan', 'terisi', 'maintenance') DEFAULT 'tersedia'");
        }
        
        // Untuk SQLite atau database lain yang tidak support ALTER enum
        else {
            Schema::table('kamar', function (Blueprint $table) {
                $table->string('status', 20)->default('tersedia')->change();
            });
        }
    }

    public function down(): void
    {
        // Rollback - kembalikan ke enum sebelumnya
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE kamar MODIFY status ENUM('tersedia', 'terisi', 'maintenance') DEFAULT 'tersedia'");
        }
        
        // Untuk SQLite
        else {
            Schema::table('kamar', function (Blueprint $table) {
                $table->string('status', 20)->default('tersedia')->change();
            });
        }
    }
};