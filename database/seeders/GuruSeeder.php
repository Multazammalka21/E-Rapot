<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $bidangStudi = [
            'Matematika','Matematika','Matematika',
            'Bahasa Indonesia','Bahasa Indonesia','Bahasa Indonesia',
            'Ilmu Pengetahuan Alam','Ilmu Pengetahuan Alam','Ilmu Pengetahuan Alam',
            'Ilmu Pengetahuan Sosial','Ilmu Pengetahuan Sosial',
            'Bahasa Inggris','Bahasa Inggris','Bahasa Inggris',
            'Pendidikan Agama Islam','Pendidikan Agama Islam',
            'Pendidikan Jasmani','Pendidikan Jasmani',
            'Seni Budaya','Seni Budaya',
            'Prakarya',
            'Informatika','Informatika',
            'Bahasa Jawa',
            'Pendidikan Pancasila',
        ];

        $gelar = ['S.Pd.', 'S.Pd., M.Pd.', 'S.Si., S.Pd.', 'S.Pd., M.Si.', 'S.Ag., S.Pd.', 'S.Pd.'];

        foreach ($bidangStudi as $i => $bidang) {
            $jk    = ($i % 3 === 0) ? 'P' : 'L';
            $nama  = $faker->name($jk === 'L' ? 'male' : 'female');
            $email = 'guru' . str_pad($i + 1, 2, '0', STR_PAD_LEFT) . '@smpn1sby.sch.id';

            $user = User::create([
                'name'      => $nama,
                'email'     => $email,
                'password'  => Hash::make('Guru@1234'),
                'role'      => 'guru',
                'is_active' => true,
            ]);

            Guru::create([
                'user_id'        => $user->id,
                'nip'            => '19' . $faker->numerify('######') . $faker->numerify('######'),
                'nama_lengkap'   => $nama,
                'jenis_kelamin'  => $jk,
                'tempat_lahir'   => $faker->city(),
                'tanggal_lahir'  => $faker->dateTimeBetween('-55 years', '-25 years')->format('Y-m-d'),
                'alamat'         => $faker->address(),
                'no_hp'          => '08' . $faker->numerify('#########'),
                'gelar_belakang' => $gelar[array_rand($gelar)],
                'bidang_studi'   => $bidang,
            ]);
        }

        $this->command->info('✅ GuruSeeder: 25 guru berhasil dibuat.');
    }
}
