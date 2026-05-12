<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $faker    = Faker::create('id_ID');
        $password = Hash::make('Siswa@1234'); // Hitung sekali, reuse untuk performa
        $agama    = ['Islam','Islam','Islam','Islam','Protestan','Katolik','Hindu','Buddha'];

        for ($i = 1; $i <= 250; $i++) {
            $jk    = ($i % 2 === 0) ? 'L' : 'P';
            $nama  = $faker->name($jk === 'L' ? 'male' : 'female');
            $nis   = '26' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $nisn  = $faker->unique()->numerify('##########');
            $email = 'siswa' . str_pad($i, 3, '0', STR_PAD_LEFT) . '@smpn1sby.sch.id';

            $user = User::create([
                'name'      => $nama,
                'email'     => $email,
                'password'  => $password,
                'role'      => 'siswa',
                'is_active' => true,
            ]);

            Siswa::create([
                'user_id'        => $user->id,
                'nisn'           => $nisn,
                'nis'            => $nis,
                'nama_lengkap'   => $nama,
                'jenis_kelamin'  => $jk,
                'tempat_lahir'   => $faker->city(),
                'tanggal_lahir'  => $faker->dateTimeBetween('-15 years', '-12 years')->format('Y-m-d'),
                'agama'          => $agama[array_rand($agama)],
                'alamat'         => $faker->address(),
                'no_hp'          => '08' . $faker->numerify('#########'),
                'nama_ayah'      => $faker->name('male'),
                'pekerjaan_ayah' => $faker->jobTitle(),
                'nama_ibu'       => $faker->name('female'),
                'pekerjaan_ibu'  => $faker->jobTitle(),
                'no_hp_ortu'     => '08' . $faker->numerify('#########'),
                'alamat_ortu'    => $faker->address(),
            ]);
        }

        $this->command->info('✅ SiswaSeeder: 250 siswa berhasil dibuat.');
    }
}
