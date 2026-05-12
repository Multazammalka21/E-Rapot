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
        Schema::create('ekstrakurikuler', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 80);
            $table->text('deskripsi')->nullable();
            $table->foreignId('pembina_id')->nullable()->constrained('guru')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('ekstrakurikuler_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('ekstrakurikuler_id')->constrained('ekstrakurikuler')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->enum('predikat', ['A', 'B', 'C', 'D'])->nullable(); // A=Sangat Baik
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(
                ['siswa_id', 'ekstrakurikuler_id', 'tahun_ajaran_id'],
                'uk_ekskul_siswa_ta'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ekstrakurikuler_siswa');
        Schema::dropIfExists('ekstrakurikuler');
    }
};
