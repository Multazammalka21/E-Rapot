<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\GuruMapel;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class GuruMapelSeeder extends Seeder
{
    public function run(): void
    {
        $ta    = TahunAjaran::where('is_active', true)->firstOrFail();
        $kelas = Kelas::where('tahun_ajaran_id', $ta->id)->get();
        $mapel = MataPelajaran::all();
        $guru  = Guru::pluck('id')->toArray();

        // Peta: kode_mapel → indeks guru (deterministik, guru mengajar mapel yang sesuai bidang studinya)
        // Urutan guru dibuat sesuai GuruSeeder: 0-2=MTK, 3-5=BIN, 6-8=IPA, 9-10=IPS,
        // 11-13=BING, 14-15=PAI, 16-17=PJOK, 18-19=SENI, 20=PKR, 21-22=INFO, 23=BJAWA, 24=PPKn
        $mapelGuruPool = [
            'MTK'   => [0, 1, 2],
            'BIN'   => [3, 4, 5],
            'IPA'   => [6, 7, 8],
            'IPS'   => [9, 10],
            'BING'  => [11, 12, 13],
            'PAI'   => [14, 15],
            'PJOK'  => [16, 17],
            'SENI'  => [18, 19],
            'PKR'   => [20],
            'INFO'  => [21, 22],
            'BJAWA' => [23],
            'PPKn'  => [24],
        ];

        foreach ($kelas as $k) {
            foreach ($mapel as $m) {
                $pool     = $mapelGuruPool[$m->kode_mapel] ?? [0];
                // Pilih guru berdasarkan indeks kelas agar satu guru tidak ngajar semua kelas
                $guruIdx  = $pool[$k->id % count($pool)];
                $guruId   = $guru[$guruIdx] ?? $guru[0];

                GuruMapel::updateOrCreate(
                    [
                        'mata_pelajaran_id' => $m->id,
                        'kelas_id'          => $k->id,
                        'tahun_ajaran_id'   => $ta->id,
                    ],
                    [
                        'guru_id'           => $guruId,
                        'mata_pelajaran_id' => $m->id,
                        'kelas_id'          => $k->id,
                        'tahun_ajaran_id'   => $ta->id,
                    ]
                );
            }
        }

        $this->command->info('✅ GuruMapelSeeder: ' . ($kelas->count() * $mapel->count()) . ' assignment guru-mapel berhasil dibuat.');
    }
}
