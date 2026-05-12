<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel inti penilaian — Kurikulum Merdeka Fase D
     *
     * Strategi Enkripsi:
     *   - Kolom integer (nilai_sh, nilai_sts, nilai_sas, nilai_akhir) → untuk query/ranking/statistik
     *   - Kolom TEXT (*_enc) → nilai terenkripsi AES-256-CBC via Laravel Crypt (untuk tampilan rapot)
     *
     * Dengan dual-storage ini:
     *   - Admin/guru dapat melakukan ORDER BY dan statistik menggunakan kolom integer
     *   - Tampilan rapot menggunakan nilai terenkripsi yang di-decrypt di application layer
     *   - Jika database bocor, penyerang hanya melihat ciphertext, bukan nilai asli
     */
    public function up(): void
    {
        Schema::create('nilai_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('guru')->onDelete('cascade'); // yang menginput

            // ─── Nilai Plaintext (integer) — untuk query, ranking, statistik ───────────
            $table->tinyInteger('nilai_sh')->unsigned()->nullable();   // Sumatif Harian (0-100)
            $table->tinyInteger('nilai_sts')->unsigned()->nullable();  // Sumatif Tengah Semester
            $table->tinyInteger('nilai_sas')->unsigned()->nullable();  // Sumatif Akhir Semester
            $table->decimal('nilai_akhir', 5, 2)->unsigned()->nullable(); // Nilai Akhir (perhitungan bobot)

            // ─── Nilai Terenkripsi (AES-256-CBC) — untuk tampilan rapot ───────────────
            $table->text('nilai_sh_enc')->nullable();    // encrypted nilai_sh
            $table->text('nilai_sts_enc')->nullable();   // encrypted nilai_sts
            $table->text('nilai_sas_enc')->nullable();   // encrypted nilai_sas
            $table->text('nilai_akhir_enc')->nullable(); // encrypted nilai_akhir

            // ─── Metadata (plaintext — aman ditampilkan) ─────────────────────────────
            $table->enum('predikat', ['A', 'B', 'C', 'D'])->nullable(); // A=Sangat Baik, D=Perlu Bimbingan
            $table->boolean('is_lulus')->default(false); // apakah mencapai KKTP
            $table->text('catatan_guru')->nullable();    // deskripsi kompetensi Kurmer

            // ─── Status & Finalisasi ──────────────────────────────────────────────────
            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->foreignId('finalized_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('finalized_at')->nullable();

            $table->timestamps();

            // Satu siswa satu nilai per mapel per kelas per tahun ajaran
            $table->unique(
                ['siswa_id', 'mata_pelajaran_id', 'kelas_id', 'tahun_ajaran_id'],
                'uk_nilai_siswa_mapel_kelas_ta'
            );

            // Index untuk query ranking
            $table->index(['kelas_id', 'tahun_ajaran_id', 'mata_pelajaran_id'], 'idx_ranking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_siswa');
    }
};
