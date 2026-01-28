<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kamar', function (Blueprint $table) {
            // Tambahkan kolom yang diperlukan untuk model Kamar baru
            $table->integer('ukuran')->nullable()->after('harga');
            $table->integer('lantai')->nullable()->after('ukuran');
            $table->integer('kapasitas')->nullable()->after('lantai');
            $table->boolean('is_active')->default(true)->after('kapasitas');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('is_active');
            
            // Update kolom yang sudah ada jika diperlukan
            $table->text('fasilitas')->change(); // Pastikan tipe text
        });
    }

    public function down(): void
    {
        Schema::table('kamar', function (Blueprint $table) {
            $table->dropColumn(['ukuran', 'lantai', 'kapasitas', 'is_active', 'created_by']);
        });
    }
};