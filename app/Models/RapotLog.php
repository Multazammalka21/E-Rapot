<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RapotLog extends Model
{
    protected $table = 'rapot_log';

    public $timestamps = false; // hanya created_at via useCurrent()

    protected $fillable = [
        'siswa_id',
        'tahun_ajaran_id',
        'kelas_id',
        'action',
        'actor_id',
        'actor_role',
        'ip_address',
        'user_agent',
        'meta',
    ];

    protected $casts = [
        'meta'       => 'array',
        'created_at' => 'datetime',
    ];

    // ─── Factory method ───────────────────────────────────────────────────────

    /**
     * Catat aksi pada rapot secara mudah.
     *
     * Contoh:
     *   RapotLog::catat($siswa->id, $ta->id, $kelas->id, 'print', $user);
     */
    public static function catat(
        int $siswaId,
        int $tahunAjaranId,
        int $kelasId,
        string $action,
        ?User $actor = null,
        array $meta = []
    ): self {
        return static::create([
            'siswa_id'       => $siswaId,
            'tahun_ajaran_id'=> $tahunAjaranId,
            'kelas_id'       => $kelasId,
            'action'         => $action,
            'actor_id'       => $actor?->id,
            'actor_role'     => $actor?->role,
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
            'meta'           => $meta ?: null,
        ]);
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
