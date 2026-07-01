<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembayaran_seragam', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_baru_id')->constrained('siswa_baru')->onDelete('cascade');
            $table->string('kode_tagihan')->unique();
            $table->decimal('total_tagihan', 12, 2);
            $table->decimal('jumlah_bayar', 12, 2)->default(0);
            $table->decimal('sisa_tagihan', 12, 2);
            $table->string('metode_bayar', 50)->nullable();
            $table->string('bukti_bayar')->nullable();
            $table->enum('status', ['belum', 'partial', 'lunas', 'tervalidasi'])->default('belum');
            $table->foreignId('divalidasi_oleh')->nullable()->constrained('users');
            $table->timestamp('tanggal_validasi')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_seragam');
    }
};
