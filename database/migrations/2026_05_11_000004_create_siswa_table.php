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
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nisn', 10)->unique(); // Nomor Induk Siswa Nasional (10 digit)
            $table->string('nis', 8)->unique();  // Nomor Induk Siswa lokal sekolah
            $table->string('nama_lengkap', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 60)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('agama', ['Islam', 'Protestan', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'])->default('Islam');
            $table->text('alamat')->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->string('foto')->nullable();
            // Data orang tua (inline, tidak perlu tabel terpisah)
            $table->string('nama_ayah', 80)->nullable();
            $table->string('pekerjaan_ayah', 60)->nullable();
            $table->string('nama_ibu', 80)->nullable();
            $table->string('pekerjaan_ibu', 60)->nullable();
            $table->string('nama_wali', 80)->nullable();
            $table->string('no_hp_ortu', 15)->nullable();
            $table->text('alamat_ortu')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
