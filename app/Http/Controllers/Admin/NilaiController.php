<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $ta = TahunAjaran::where('is_active', true)->first();
        
        $kelas = Kelas::with(['waliKelas'])
            ->withCount(['siswa' => function($q) use ($ta) {
                if ($ta) $q->where('tahun_ajaran_id', $ta->id);
            }])
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        return view('admin.nilai.index', compact('kelas', 'ta'));
    }
}
