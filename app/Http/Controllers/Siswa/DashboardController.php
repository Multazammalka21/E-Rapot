<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\CatatanWaliKelas;
use App\Models\EkstrakurikulerSiswa;
use App\Models\NilaiSiswa;
use App\Models\SiswaKelas;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $siswa = $user->siswa;
        $ta    = TahunAjaran::where('is_active', true)->first();

        // Info kelas aktif
        $siswaKelas = SiswaKelas::with(['kelas.waliKelas'])
            ->where('siswa_id', $siswa?->id)
            ->where('tahun_ajaran_id', $ta?->id)
            ->first();

        // Nilai semester ini
        $nilaiSemester = NilaiSiswa::with('mataPelajaran')
            ->where('siswa_id', $siswa?->id)
            ->where('tahun_ajaran_id', $ta?->id)
            ->get();

        // Statistik nilai
        $statsNilai = [
            'rata_rata' => round($nilaiSemester->avg('nilai_akhir'), 1),
            'tertinggi' => $nilaiSemester->max('nilai_akhir'),
            'terendah'  => $nilaiSemester->min('nilai_akhir'),
            'lulus'     => $nilaiSemester->where('is_lulus', true)->count(),
            'tidak_lulus' => $nilaiSemester->where('is_lulus', false)->count(),
        ];

        // Distribusi predikat
        $distribusiPredikat = $nilaiSemester->groupBy('predikat')
            ->map(fn($g) => $g->count());

        // Absensi
        $absensi = Absensi::where('siswa_id', $siswa?->id)
            ->where('tahun_ajaran_id', $ta?->id)
            ->first();

        // Ekstrakurikuler
        $ekskul = EkstrakurikulerSiswa::with('ekstrakurikuler')
            ->where('siswa_id', $siswa?->id)
            ->where('tahun_ajaran_id', $ta?->id)
            ->get();

        // Catatan wali kelas
        $catatan = CatatanWaliKelas::where('siswa_id', $siswa?->id)
            ->where('tahun_ajaran_id', $ta?->id)
            ->first();

        return view('siswa.dashboard', compact(
            'user', 'siswa', 'ta', 'siswaKelas',
            'nilaiSemester', 'statsNilai', 'distribusiPredikat',
            'absensi', 'ekskul', 'catatan'
        ));
    }
}
