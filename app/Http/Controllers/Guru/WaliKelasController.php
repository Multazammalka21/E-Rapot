<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\SiswaKelas;
use App\Models\Absensi;
use App\Models\CatatanWaliKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaliKelasController extends Controller
{
    /**
     * Tampilkan daftar kelas yang di-wali-kelasi oleh guru yang sedang login
     */
    public function index()
    {
        $guru = Auth::user()->guru;
        $ta = TahunAjaran::where('is_active', true)->firstOrFail();

        $kelasList = Kelas::where('wali_kelas_id', $guru->id)
            ->where('tahun_ajaran_id', $ta->id)
            ->withCount('siswa')
            ->get();

        return view('guru.walikelas.index', compact('kelasList', 'ta'));
    }

    /**
     * Form input absensi & catatan untuk kelas tertentu
     */
    public function input(Kelas $kelas)
    {
        $guru = Auth::user()->guru;
        $ta = TahunAjaran::where('is_active', true)->firstOrFail();

        // Pastikan guru ini memang wali kelasnya dan tahun ajaran kelas sesuai
        abort_unless(
            $kelas->wali_kelas_id === $guru->id && $kelas->tahun_ajaran_id === $ta->id,
            403,
            'Akses Ditolak: Anda bukan wali kelas untuk kelas ini pada tahun ajaran aktif.'
        );

        $siswaKelas = SiswaKelas::with(['siswa', 'siswa.absensi' => function($q) use ($ta) {
            $q->where('tahun_ajaran_id', $ta->id);
        }, 'siswa.catatanWaliKelas' => function($q) use ($ta) {
            $q->where('tahun_ajaran_id', $ta->id);
        }])
        ->where('kelas_id', $kelas->id)
        ->where('tahun_ajaran_id', $ta->id)
        ->orderBy('nomor_urut')
        ->get();

        return view('guru.walikelas.input', compact('kelas', 'siswaKelas', 'ta'));
    }

    /**
     * Simpan absensi & catatan wali kelas
     */
    public function store(Request $request, Kelas $kelas)
    {
        $guru = Auth::user()->guru;
        $ta = TahunAjaran::where('is_active', true)->firstOrFail();

        // Pastikan otorisasi
        abort_unless(
            $kelas->wali_kelas_id === $guru->id && $kelas->tahun_ajaran_id === $ta->id,
            403,
            'Akses Ditolak'
        );

        $request->validate([
            'data.*.hadir' => 'required|integer|min:0',
            'data.*.sakit' => 'required|integer|min:0',
            'data.*.izin' => 'required|integer|min:0',
            'data.*.alpha' => 'required|integer|min:0',
            'data.*.catatan' => 'nullable|string|max:500',
        ]);

        $data = $request->input('data', []);

        foreach ($data as $siswa_id => $input) {
            // Simpan Absensi
            Absensi::updateOrCreate(
                [
                    'siswa_id' => $siswa_id,
                    'kelas_id' => $kelas->id,
                    'tahun_ajaran_id' => $ta->id,
                ],
                [
                    'hadir' => $input['hadir'],
                    'sakit' => $input['sakit'],
                    'izin' => $input['izin'],
                    'alpha' => $input['alpha'],
                ]
            );

            // Simpan Catatan Wali Kelas
            if (isset($input['catatan'])) {
                CatatanWaliKelas::updateOrCreate(
                    [
                        'siswa_id' => $siswa_id,
                        'kelas_id' => $kelas->id,
                        'tahun_ajaran_id' => $ta->id,
                    ],
                    [
                        'wali_kelas_id' => $guru->id,
                        'catatan' => $input['catatan'],
                    ]
                );
            }
        }

        return redirect()->route('guru.walikelas.index')->with('success', 'Data absensi dan catatan wali kelas berhasil disimpan.');
    }
}
