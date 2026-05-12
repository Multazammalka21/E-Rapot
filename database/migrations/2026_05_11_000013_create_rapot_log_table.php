<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Audit trail: siapa mengakses/mencetak rapot siapa, kapan, dari IP mana
     */
    public function up(): void
    {
        Schema::create('rapot_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->enum('action', ['view', 'print', 'download', 'input_nilai', 'finalize']);
            $table->foreignId('actor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('actor_role', 20)->nullable(); // snapshot role saat aksi
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('meta')->nullable(); // data tambahan (mis: mapel yang diubah)
            $table->timestamp('created_at')->useCurrent();

            $table->index(['siswa_id', 'created_at']);
            $table->index(['actor_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapot_log');
    }
};
