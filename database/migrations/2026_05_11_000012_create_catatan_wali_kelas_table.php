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
        Schema::create('catatan_wali_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->foreignId('wali_kelas_id')->constrained('guru')->onDelete('cascade');
            $table->text('catatan'); // Catatan/deskripsi dari wali kelas untuk rapot
            $table->timestamps();

            $table->unique(
                ['siswa_id', 'kelas_id', 'tahun_ajaran_id'],
                'uk_catatan_siswa_kelas_ta'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_wali_kelas');
    }
};
