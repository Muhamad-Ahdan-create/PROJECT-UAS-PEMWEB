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
        Schema::create('rat_catatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dibuat_oleh')->constrained('users');
            $table->year('tahun_rat');
            $table->date('tanggal_rat');
            $table->string('tempat');
            $table->text('agenda');
            $table->longText('notulensi')->nullable();
            $table->text('hasil_keputusan')->nullable();
            $table->string('dokumen_lampiran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rat_catatan');
    }
};
