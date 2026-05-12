<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Pivot: guru mengajar mapel apa di kelas mana pada tahun ajaran berapa
     */
    public function up(): void
    {
        Schema::create('guru_mapel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('guru')->onDelete('cascade');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->timestamps();

            // Satu mapel di kelas tertentu hanya diajar satu guru pada tahun ajaran yang sama
            $table->unique(
                ['mata_pelajaran_id', 'kelas_id', 'tahun_ajaran_id'],
                'uk_mapel_kelas_tahun'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_mapel');
    }
};
