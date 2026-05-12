<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::with(['tahunAjaran','waliKelas','siswa'])
            ->when($request->search, fn($q,$s) => $q->where('nama_kelas','like',"%$s%"))
            ->when($request->tahun_ajaran_id, fn($q,$id) => $q->where('tahun_ajaran_id',$id))
            ->orderBy('tingkat')->orderBy('nama_kelas')
            ->paginate(15)->withQueryString();

        $tahunAjaran = TahunAjaran::orderByDesc('nama')->get();
        return view('admin.kelas.index', compact('kelas','tahunAjaran'));
    }

    public function create()
    {
        $tahunAjaran = TahunAjaran::orderByDesc('nama')->get();
        $guru        = Guru::orderBy('nama_lengkap')->get();
        return view('admin.kelas.create', compact('tahunAjaran','guru'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'wali_kelas_id'   => 'nullable|exists:guru,id',
            'nama_kelas'      => 'required|string|max:10',
            'tingkat'         => 'required|in:7,8,9',
            'kapasitas'       => 'required|integer|min:1|max:50',
        ]);

        // Cek duplikat nama kelas di tahun ajaran yang sama
        $request->validate([
            'nama_kelas' => Rule::unique('kelas')->where(fn($q) =>
                $q->where('tahun_ajaran_id', $data['tahun_ajaran_id'])
            ),
        ]);

        Kelas::create($data);

        return redirect()->route('admin.kelas.index')
            ->with('success', "Kelas {$data['nama_kelas']} berhasil ditambahkan.");
    }

    public function edit(Kelas $kelas)
    {
        $tahunAjaran = TahunAjaran::orderByDesc('nama')->get();
        $guru        = Guru::orderBy('nama_lengkap')->get();
        return view('admin.kelas.edit', compact('kelas','tahunAjaran','guru'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $data = $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'wali_kelas_id'   => 'nullable|exists:guru,id',
            'nama_kelas'      => 'required|string|max:10',
            'tingkat'         => 'required|in:7,8,9',
            'kapasitas'       => 'required|integer|min:1|max:50',
        ]);

        $kelas->update($data);

        return redirect()->route('admin.kelas.index')
            ->with('success', "Kelas {$data['nama_kelas']} berhasil diperbarui.");
    }

    public function destroy(Kelas $kelas)
    {
        $nama = $kelas->nama_kelas;
        $kelas->delete();
        return redirect()->route('admin.kelas.index')
            ->with('success', "Kelas {$nama} berhasil dihapus.");
    }
}
