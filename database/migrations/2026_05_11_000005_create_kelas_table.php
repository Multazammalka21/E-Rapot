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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->foreignId('wali_kelas_id')->nullable()->constrained('guru')->onDelete('set null');
            $table->string('nama_kelas', 10); // e.g. 7A, 8B, 9C
            $table->enum('tingkat', ['7', '8', '9']);
            $table->tinyInteger('kapasitas')->default(32);
            $table->timestamps();

            $table->unique(['tahun_ajaran_id', 'nama_kelas']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
