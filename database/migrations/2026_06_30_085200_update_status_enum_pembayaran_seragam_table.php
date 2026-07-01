<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE pembayaran_seragam MODIFY COLUMN status ENUM('belum', 'partial', 'lunas', 'menunggu_validasi', 'tervalidasi') DEFAULT 'belum'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE pembayaran_seragam MODIFY COLUMN status ENUM('belum', 'partial', 'lunas', 'tervalidasi') DEFAULT 'belum'");
    }
};