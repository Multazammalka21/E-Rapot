<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\SiswaKelas;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class SiswaKelasSeeder extends Seeder
{
    public function run(): void
    {
        $ta     = TahunAjaran::where('is_active', true)->firstOrFail();
        $kelas  = Kelas::where('tahun_ajaran_id', $ta->id)->orderBy('tingkat')->orderBy('nama_kelas')->get();
        $siswa  = Siswa::pluck('id')->toArray();

        $chunks = array_chunk($siswa, (int) ceil(count($siswa) / $kelas->count()));

        foreach ($kelas as $i => $k) {
            $siswaChunk = $chunks[$i] ?? [];
            foreach ($siswaChunk as $no => $siswaId) {
                SiswaKelas::updateOrCreate(
                    ['siswa_id' => $siswaId, 'tahun_ajaran_id' => $ta->id],
                    [
                        'siswa_id'        => $siswaId,
                        'kelas_id'        => $k->id,
                        'tahun_ajaran_id' => $ta->id,
                        'nomor_urut'      => $no + 1,
                    ]
                );
            }
        }

        $this->command->info('✅ SiswaKelasSeeder: 250 siswa terdistribusi ke 9 kelas.');
    }
}
