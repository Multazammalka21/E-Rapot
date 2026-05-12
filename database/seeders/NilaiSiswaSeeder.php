<?php

namespace Database\Seeders;

use App\Models\MataPelajaran;
use App\Models\NilaiSiswa;
use App\Models\SiswaKelas;
use App\Models\GuruMapel;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class NilaiSiswaSeeder extends Seeder
{
    public function run(): void
    {
        $ta      = TahunAjaran::where('is_active', true)->firstOrFail();
        $mapels  = MataPelajaran::all()->keyBy('id');

        // Ambil semua guru_mapel per kelas → (kelas_id, mapel_id) → guru_id
        $guruMapelMap = GuruMapel::where('tahun_ajaran_id', $ta->id)
            ->pluck('guru_id', DB::raw("CONCAT(kelas_id, '-', mata_pelajaran_id)"));

        // Ambil semua siswa_kelas untuk tahun ajaran aktif
        $siswaKelas = SiswaKelas::where('tahun_ajaran_id', $ta->id)->get();

        $batch = [];
        $now   = now();

        foreach ($siswaKelas as $sk) {
            // Kemampuan dasar tiap siswa (55–95) agar ada variasi realistis
            $base = rand(55, 95);

            foreach ($mapels as $mapel) {
                $sh  = max(40, min(100, $base + rand(-8, 12)));
                $sts = max(40, min(100, $base + rand(-8, 12)));
                $sas = max(40, min(100, $base + rand(-8, 12)));

                $nilaiAkhir = round(
                    ($sh * $mapel->bobot_sumatif_harian +
                     $sts * $mapel->bobot_sumatif_tengah +
                     $sas * $mapel->bobot_sumatif_akhir) / 100, 2
                );

                $predikat = match(true) {
                    $nilaiAkhir >= 90 => 'A',
                    $nilaiAkhir >= 80 => 'B',
                    $nilaiAkhir >= 70 => 'C',
                    default           => 'D',
                };

                $isLulus  = $nilaiAkhir >= $mapel->kktp;
                $mapelKey = $sk->kelas_id . '-' . $mapel->id;
                $guruId   = $guruMapelMap[$mapelKey] ?? null;
                if (!$guruId) continue;

                $batch[] = [
                    'siswa_id'          => $sk->siswa_id,
                    'mata_pelajaran_id' => $mapel->id,
                    'kelas_id'          => $sk->kelas_id,
                    'tahun_ajaran_id'   => $ta->id,
                    'guru_id'           => $guruId,
                    // Plaintext (untuk query/ranking)
                    'nilai_sh'          => $sh,
                    'nilai_sts'         => $sts,
                    'nilai_sas'         => $sas,
                    'nilai_akhir'       => $nilaiAkhir,
                    // Encrypted (untuk tampilan rapot)
                    'nilai_sh_enc'      => Crypt::encryptString((string)$sh),
                    'nilai_sts_enc'     => Crypt::encryptString((string)$sts),
                    'nilai_sas_enc'     => Crypt::encryptString((string)$sas),
                    'nilai_akhir_enc'   => Crypt::encryptString((string)$nilaiAkhir),
                    // Metadata
                    'predikat'          => $predikat,
                    'is_lulus'          => $isLulus,
                    'catatan_guru'      => $this->catatanGuru($predikat, $mapel->nama_mapel),
                    'status'            => 'final',
                    'finalized_at'      => $now,
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ];

                // Batch insert setiap 200 record
                if (count($batch) >= 200) {
                    DB::table('nilai_siswa')->insert($batch);
                    $batch = [];
                }
            }
        }

        if (!empty($batch)) {
            DB::table('nilai_siswa')->insert($batch);
        }

        $total = SiswaKelas::where('tahun_ajaran_id', $ta->id)->count() * $mapels->count();
        $this->command->info("✅ NilaiSiswaSeeder: ±{$total} nilai berhasil dibuat (dengan enkripsi AES-256).");
    }

    private function catatanGuru(string $predikat, string $mapel): string
    {
        return match($predikat) {
            'A' => "Siswa menunjukkan pemahaman yang sangat baik dalam {$mapel}. Terus pertahankan prestasi.",
            'B' => "Siswa menunjukkan pemahaman yang baik dalam {$mapel}. Tingkatkan latihan soal.",
            'C' => "Siswa cukup memahami {$mapel}. Perlu meningkatkan frekuensi belajar mandiri.",
            default => "Siswa perlu bimbingan lebih intensif dalam {$mapel}. Disarankan mengikuti remedial.",
        };
    }
}
