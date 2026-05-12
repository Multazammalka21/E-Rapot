<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Rekap kehadiran siswa per semester (bukan per hari, tapi total per semester)
     */
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->unsignedSmallInteger('hadir')->default(0);   // jumlah hari hadir
            $table->unsignedSmallInteger('sakit')->default(0);   // jumlah hari sakit (dengan surat)
            $table->unsignedSmallInteger('izin')->default(0);    // jumlah hari izin
            $table->unsignedSmallInteger('alpha')->default(0);   // jumlah hari tanpa keterangan
            $table->timestamps();

            $table->unique(['siswa_id', 'kelas_id', 'tahun_ajaran_id'], 'uk_absensi_siswa_kelas_ta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
