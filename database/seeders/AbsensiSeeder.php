<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\SiswaKelas;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbsensiSeeder extends Seeder
{
    public function run(): void
    {
        $ta         = TahunAjaran::where('is_active', true)->firstOrFail();
        $siswaKelas = SiswaKelas::where('tahun_ajaran_id', $ta->id)->get();
        $now        = now();
        $batch      = [];

        foreach ($siswaKelas as $sk) {
            $sakit = rand(0, 5);
            $izin  = rand(0, 3);
            $alpha = rand(0, 2);
            $total = 100; // total hari efektif semester
            $hadir = $total - $sakit - $izin - $alpha;

            $batch[] = [
                'siswa_id'        => $sk->siswa_id,
                'kelas_id'        => $sk->kelas_id,
                'tahun_ajaran_id' => $ta->id,
                'hadir'           => max(0, $hadir),
                'sakit'           => $sakit,
                'izin'            => $izin,
                'alpha'           => $alpha,
                'created_at'      => $now,
                'updated_at'      => $now,
            ];

            if (count($batch) >= 100) {
                DB::table('absensi')->insert($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            DB::table('absensi')->insert($batch);
        }

        $this->command->info('✅ AbsensiSeeder: ' . $siswaKelas->count() . ' rekap absensi berhasil dibuat.');
    }
}
