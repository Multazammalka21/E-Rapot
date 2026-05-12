<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\EkstrakurikulerSiswa;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;

class EkskulController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswa;
        $ta    = TahunAjaran::where('is_active', true)->firstOrFail();
        $ekskul = EkstrakurikulerSiswa::with('ekstrakurikuler')
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $ta->id)
            ->get();
        return view('siswa.ekskul', compact('siswa', 'ta', 'ekskul'));
    }
}
