<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // ── Akun Admin ────────────────────────────────────────────────────────
        $admin = User::updateOrCreate(
            ['email' => 'admin@smpn1sby.sch.id'],
            [
                'name'      => 'Administrator',
                'email'     => 'admin@smpn1sby.sch.id',
                'password'  => Hash::make('Admin@1234'),
                'role'      => User::ROLE_ADMIN,
                'is_active' => true,
            ]
        );

        // ── Akun Guru Demo (Kepala Sekolah / Wali Kelas Demo) ─────────────────
        $guruUser = User::updateOrCreate(
            ['email' => 'guru.demo@smpn1sby.sch.id'],
            [
                'name'      => 'Demo Guru',
                'email'     => 'guru.demo@smpn1sby.sch.id',
                'password'  => Hash::make('Guru@1234'),
                'role'      => User::ROLE_GURU,
                'is_active' => true,
            ]
        );

        Guru::updateOrCreate(
            ['user_id' => $guruUser->id],
            [
                'user_id'       => $guruUser->id,
                'nip'           => '197001012000011001',
                'nama_lengkap'  => 'Budi Santoso',
                'jenis_kelamin' => 'L',
                'tempat_lahir'  => 'Surabaya',
                'tanggal_lahir' => '1970-01-01',
                'alamat'        => 'Jl. Contoh No. 1, Surabaya',
                'no_hp'         => '08123456789',
                'gelar_belakang'=> 'S.Pd.',
                'bidang_studi'  => 'Matematika',
            ]
        );

        // ── Akun Siswa Demo ───────────────────────────────────────────────────
        $siswaUser = User::updateOrCreate(
            ['email' => 'siswa.demo@smpn1sby.sch.id'],
            [
                'name'      => 'Demo Siswa',
                'email'     => 'siswa.demo@smpn1sby.sch.id',
                'password'  => Hash::make('Siswa@1234'),
                'role'      => User::ROLE_SISWA,
                'is_active' => true,
            ]
        );

        $this->command->info('✅ AdminSeeder selesai.');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin', 'admin@smpn1sby.sch.id',      'Admin@1234'],
                ['Guru',  'guru.demo@smpn1sby.sch.id',  'Guru@1234'],
                ['Siswa', 'siswa.demo@smpn1sby.sch.id', 'Siswa@1234'],
            ]
        );
    }
}
