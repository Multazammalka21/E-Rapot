<?php

namespace App\Models;

use App\Casts\EncryptedNilai;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiSiswa extends Model
{
    protected $table = 'nilai_siswa';

    protected $fillable = [
        'siswa_id', 'mata_pelajaran_id', 'kelas_id', 'tahun_ajaran_id', 'guru_id',
        // Plaintext (untuk query/statistik)
        'nilai_sh', 'nilai_sts', 'nilai_sas', 'nilai_akhir',
        // Encrypted (untuk tampilan rapot)
        'nilai_sh_enc', 'nilai_sts_enc', 'nilai_sas_enc', 'nilai_akhir_enc',
        // Metadata
        'predikat', 'is_lulus', 'catatan_guru',
        // Status
        'status', 'finalized_by', 'finalized_at',
    ];

    protected $casts = [
        // Plaintext
        'nilai_sh'     => 'integer',
        'nilai_sts'    => 'integer',
        'nilai_sas'    => 'integer',
        'nilai_akhir'  => 'decimal:2',
        // Encrypted via custom cast — otomatis enkripsi/dekripsi
        'nilai_sh_enc'     => EncryptedNilai::class,
        'nilai_sts_enc'    => EncryptedNilai::class,
        'nilai_sas_enc'    => EncryptedNilai::class,
        'nilai_akhir_enc'  => EncryptedNilai::class,
        // Metadata
        'is_lulus'      => 'boolean',
        'finalized_at'  => 'datetime',
    ];

    // ─── Predikat Kurikulum Merdeka ───────────────────────────────────────────

    const PREDIKAT_A = 'A'; // 90-100  → Sangat Baik
    const PREDIKAT_B = 'B'; // 80-89   → Baik
    const PREDIKAT_C = 'C'; // 70-79   → Cukup
    const PREDIKAT_D = 'D'; // < 70    → Perlu Bimbingan

    public static function getPredikatLabel(): array
    {
        return [
            self::PREDIKAT_A => 'Sangat Baik',
            self::PREDIKAT_B => 'Baik',
            self::PREDIKAT_C => 'Cukup',
            self::PREDIKAT_D => 'Perlu Bimbingan',
        ];
    }

    // ─── Business Logic ───────────────────────────────────────────────────────

    /**
     * Tentukan predikat berdasarkan nilai akhir (Kurikulum Merdeka).
     */
    public static function hitungPredikat(float $nilaiAkhir): string
    {
        return match(true) {
            $nilaiAkhir >= 90 => self::PREDIKAT_A,
            $nilaiAkhir >= 80 => self::PREDIKAT_B,
            $nilaiAkhir >= 70 => self::PREDIKAT_C,
            default           => self::PREDIKAT_D,
        };
    }

    /**
     * Isi nilai, enkripsi, hitung nilai akhir + predikat secara otomatis.
     * Dipanggil saat guru input nilai via form.
     */
    public function inputNilai(int $sh, int $sts, int $sas): void
    {
        $mapel = $this->mataPelajaran;

        $nilaiAkhir = $mapel->hitungNilaiAkhir($sh, $sts, $sas);
        $predikat   = self::hitungPredikat($nilaiAkhir);
        $isLulus    = $nilaiAkhir >= $mapel->kktp;

        $this->fill([
            // Plaintext
            'nilai_sh'    => $sh,
            'nilai_sts'   => $sts,
            'nilai_sas'   => $sas,
            'nilai_akhir' => $nilaiAkhir,
            // Encrypted (otomatis dienkripsi via cast)
            'nilai_sh_enc'    => $sh,
            'nilai_sts_enc'   => $sts,
            'nilai_sas_enc'   => $sas,
            'nilai_akhir_enc' => $nilaiAkhir,
            // Metadata
            'predikat' => $predikat,
            'is_lulus' => $isLulus,
        ]);
    }

    /**
     * Finalisasi nilai oleh wali kelas.
     */
    public function finalize(int $userId): void
    {
        $this->update([
            'status'       => 'final',
            'finalized_by' => $userId,
            'finalized_at' => now(),
        ]);
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isFinal(): bool
    {
        return $this->status === 'final';
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function finalizedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }
}
