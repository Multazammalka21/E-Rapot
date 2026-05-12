<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $table = 'absensi';

    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'tahun_ajaran_id',
        'hadir',
        'sakit',
        'izin',
        'alpha',
    ];

    protected $casts = [
        'hadir' => 'integer',
        'sakit' => 'integer',
        'izin'  => 'integer',
        'alpha' => 'integer',
    ];

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /** Total hari efektif (hadir + sakit + izin + alpha) */
    public function getTotalHariAttribute(): int
    {
        return $this->hadir + $this->sakit + $this->izin + $this->alpha;
    }

    /** Persentase kehadiran */
    public function getPersentaseHadirAttribute(): float
    {
        $total = $this->total_hari;
        if ($total === 0) return 0;
        return round(($this->hadir / $total) * 100, 1);
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
