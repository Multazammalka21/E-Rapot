<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Sesuai Kurikulum Merdeka Fase D (SMP Kelas 7-9)
     */
    public function up(): void
    {
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mapel', 10)->unique(); // e.g. MTK, BIN, IPA
            $table->string('nama_mapel', 80);
            $table->enum('kelompok', [
                'Umum',         // Kelompok A & B Kurikulum Merdeka
                'Pilihan',      // Seni, Prakarya (siswa pilih)
                'Muatan Lokal', // Bahasa Jawa, dll
            ])->default('Umum');
            // Kurikulum Merdeka: KKTP (Kriteria Ketercapaian Tujuan Pembelajaran)
            $table->tinyInteger('kktp')->default(70)->unsigned(); // 0-100, disebut KKTP di Kurmer
            // Bobot penilaian (total harus = 100)
            $table->tinyInteger('bobot_sumatif_harian')->default(60)->unsigned();     // SH (replaces tugas/UH)
            $table->tinyInteger('bobot_sumatif_tengah')->default(20)->unsigned();    // STS (replaces UTS/PTS)
            $table->tinyInteger('bobot_sumatif_akhir')->default(20)->unsigned();     // SAS (replaces UAS/PAS)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_pelajaran');
    }
};
