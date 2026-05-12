<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $ta    = TahunAjaran::where('is_active', true)->firstOrFail();
        $guru  = Guru::pluck('id')->toArray();

        // 9 kelas: 7A-7C, 8A-8B, 9A-9C
        $daftarKelas = [
            ['nama_kelas' => '7A', 'tingkat' => '7', 'kapasitas' => 28],
            ['nama_kelas' => '7B', 'tingkat' => '7', 'kapasitas' => 28],
            ['nama_kelas' => '7C', 'tingkat' => '7', 'kapasitas' => 28],
            ['nama_kelas' => '8A', 'tingkat' => '8', 'kapasitas' => 28],
            ['nama_kelas' => '8B', 'tingkat' => '8', 'kapasitas' => 28],
            ['nama_kelas' => '8C', 'tingkat' => '8', 'kapasitas' => 28],
            ['nama_kelas' => '9A', 'tingkat' => '9', 'kapasitas' => 28],
            ['nama_kelas' => '9B', 'tingkat' => '9', 'kapasitas' => 28],
            ['nama_kelas' => '9C', 'tingkat' => '9', 'kapasitas' => 26],
        ];

        // Gunakan 9 guru pertama sebagai wali kelas (1 per kelas)
        foreach ($daftarKelas as $idx => $data) {
            Kelas::updateOrCreate(
                ['tahun_ajaran_id' => $ta->id, 'nama_kelas' => $data['nama_kelas']],
                [
                    'tahun_ajaran_id' => $ta->id,
                    'wali_kelas_id'   => $guru[$idx],
                    'nama_kelas'      => $data['nama_kelas'],
                    'tingkat'         => $data['tingkat'],
                    'kapasitas'       => $data['kapasitas'],
                ]
            );
        }

        $this->command->info('✅ KelasSeeder: 9 kelas (7A-7C, 8A-8C, 9A-9C) berhasil dibuat.');
    }
}
