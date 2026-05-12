<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EkstrakurikulerSiswa extends Model
{
    protected $table = 'ekstrakurikuler_siswa';

    protected $fillable = [
        'siswa_id',
        'ekstrakurikuler_id',
        'tahun_ajaran_id',
        'predikat',
        'keterangan',
    ];

    public static function predikatLabel(): array
    {
        return [
            'A' => 'Sangat Baik',
            'B' => 'Baik',
            'C' => 'Cukup',
            'D' => 'Perlu Bimbingan',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function ekstrakurikuler(): BelongsTo
    {
        return $this->belongsTo(Ekstrakurikuler::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
