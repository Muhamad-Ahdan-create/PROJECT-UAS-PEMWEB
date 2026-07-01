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
        Schema::create('siswa_baru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nisn', 20)->unique();
            $table->string('nama_lengkap');
            $table->string('kelas', 20)->nullable();
            $table->string('jurusan', 100)->nullable();
            $table->year('tahun_masuk');
            $table->string('nama_orang_tua')->nullable();
            $table->string('no_telp_ortu', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_baru');
    }
};
