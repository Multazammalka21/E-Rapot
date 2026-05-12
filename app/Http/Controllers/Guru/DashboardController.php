<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GuruMapel;
use App\Models\Kelas;
use App\Models\NilaiSiswa;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guru = $user->guru;
        $ta   = TahunAjaran::where('is_active', true)->first();

        // Kelas yang diajar oleh guru ini
        $kelasDiajar = GuruMapel::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru?->id)
            ->where('tahun_ajaran_id', $ta?->id)
            ->get()
            ->groupBy('kelas_id');

        // Kelas yang diwali-kelasi
        $kelasWali = Kelas::with(['siswa', 'tahunAjaran'])
            ->where('wali_kelas_id', $guru?->id)
            ->where('tahun_ajaran_id', $ta?->id)
            ->first();

        // Statistik nilai yang sudah diinput oleh guru ini
        $statsNilai = [
            'total'   => NilaiSiswa::where('guru_id', $guru?->id)->where('tahun_ajaran_id', $ta?->id)->count(),
            'final'   => NilaiSiswa::where('guru_id', $guru?->id)->where('tahun_ajaran_id', $ta?->id)->where('status', 'final')->count(),
            'draft'   => NilaiSiswa::where('guru_id', $guru?->id)->where('tahun_ajaran_id', $ta?->id)->where('status', 'draft')->count(),
        ];

        // Distribusi predikat dari nilai yang diinput guru ini
        $distribusiPredikat = NilaiSiswa::where('guru_id', $guru?->id)
            ->where('tahun_ajaran_id', $ta?->id)
            ->selectRaw('predikat, count(*) as total')
            ->groupBy('predikat')
            ->orderBy('predikat')
            ->pluck('total', 'predikat');

        return view('guru.dashboard', compact(
            'user', 'guru', 'ta', 'kelasDiajar', 'kelasWali', 'statsNilai', 'distribusiPredikat'
        ));
    }
}
