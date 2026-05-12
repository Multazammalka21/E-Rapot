<?php

namespace Database\Seeders;

use App\Models\CatatanWaliKelas;
use App\Models\Kelas;
use App\Models\SiswaKelas;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatatanWaliKelasSeeder extends Seeder
{
    public function run(): void
    {
        $ta         = TahunAjaran::where('is_active', true)->firstOrFail();
        $siswaKelas = SiswaKelas::where('tahun_ajaran_id', $ta->id)->with('kelas')->get();
        $now        = now();
        $batch      = [];

        $template = [
            'Siswa menunjukkan sikap yang baik dan aktif dalam kegiatan belajar di kelas. Diharapkan terus meningkatkan semangat belajarnya.',
            'Siswa cukup aktif dalam mengikuti pembelajaran. Perlu lebih fokus dalam memperhatikan materi yang disampaikan oleh guru.',
            'Siswa memiliki potensi yang baik. Diharapkan dapat lebih meningkatkan kedisiplinan dalam mengerjakan tugas.',
            'Siswa menunjukkan perkembangan yang positif. Tetap jaga semangat dan konsistensi dalam belajar.',
            'Siswa perlu meningkatkan partisipasi aktif di kelas. Orang tua diharapkan mendukung kegiatan belajar di rumah.',
            'Siswa memiliki kemampuan yang baik dan mudah bergaul. Pertahankan prestasi dan perilaku yang sudah ditunjukkan.',
        ];

        foreach ($siswaKelas as $sk) {
            $batch[] = [
                'siswa_id'        => $sk->siswa_id,
                'kelas_id'        => $sk->kelas_id,
                'tahun_ajaran_id' => $ta->id,
                'wali_kelas_id'   => $sk->kelas->wali_kelas_id,
                'catatan'         => $template[array_rand($template)],
                'created_at'      => $now,
                'updated_at'      => $now,
            ];

            if (count($batch) >= 100) {
                DB::table('catatan_wali_kelas')->insert($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            DB::table('catatan_wali_kelas')->insert($batch);
        }

        $this->command->info('✅ CatatanWaliKelasSeeder: ' . $siswaKelas->count() . ' catatan wali kelas berhasil dibuat.');
    }
}
