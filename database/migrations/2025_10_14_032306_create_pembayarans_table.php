<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke User & Booking (Cascade Delete)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            
            // Data Pembayaran
            $table->string('kode_pembayaran')->unique();
            $table->decimal('jumlah', 12, 2); // 12 digit total, 2 desimal (cukup utk miliaran)
            
            // 🔥 PENTING: Nama kolom ini harus sama dengan di Model & Controller
            $table->string('metode_pembayaran')->nullable(); 
            
            // Status (Pastikan ejaan 'cancelled' double L)
            $table->enum('status', ['pending', 'paid', 'failed', 'expired', 'cancelled', 'challenge'])
                  ->default('pending');
            
            // Waktu & Token
            $table->timestamp('tanggal_jatuh_tempo')->nullable();
            $table->timestamp('tanggal_bayar')->nullable();
            $table->text('snap_token')->nullable(); // Text agar muat token panjang
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};