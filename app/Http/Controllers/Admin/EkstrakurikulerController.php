<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ekstrakurikuler;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EkstrakurikulerController extends Controller
{
    public function index(Request $request)
    {
        $ekskul = Ekstrakurikuler::with('pembina')
            ->when($request->search, fn($q, $s) =>
                $q->where('nama', 'like', "%$s%")
            )
            ->orderBy('nama')
            ->paginate(15)->withQueryString();

        return view('admin.ekstrakurikuler.index', compact('ekskul'));
    }

    public function create()
    {
        $gurus = Guru::orderBy('nama_lengkap')->get();
        return view('admin.ekstrakurikuler.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'       => 'required|string|max:80|unique:ekstrakurikuler,nama',
            'deskripsi'  => 'nullable|string',
            'pembina_id' => 'nullable|exists:guru,id',
            'is_active'  => 'nullable|boolean',
        ]);

        try {
            $data['is_active'] = $request->boolean('is_active', true);
            Ekstrakurikuler::create($data);

            return redirect()->route('admin.ekstrakurikuler.index')
                ->with('success', "Ekstrakurikuler {$data['nama']} berhasil ditambahkan.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', "Gagal menambahkan ekstrakurikuler: " . $e->getMessage());
        }
    }

    public function edit(Ekstrakurikuler $ekstrakurikuler)
    {
        $gurus = Guru::orderBy('nama_lengkap')->get();
        return view('admin.ekstrakurikuler.edit', compact('ekstrakurikuler', 'gurus'));
    }

    public function update(Request $request, Ekstrakurikuler $ekstrakurikuler)
    {
        $data = $request->validate([
            'nama'       => ['required', 'string', 'max:80', Rule::unique('ekstrakurikuler', 'nama')->ignore($ekstrakurikuler->id)],
            'deskripsi'  => 'nullable|string',
            'pembina_id' => 'nullable|exists:guru,id',
            'is_active'  => 'nullable|boolean',
        ]);

        try {
            $data['is_active'] = $request->boolean('is_active');
            $ekstrakurikuler->update($data);

            return redirect()->route('admin.ekstrakurikuler.index')
                ->with('success', "Ekstrakurikuler {$data['nama']} berhasil diperbarui.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', "Gagal memperbarui ekstrakurikuler: " . $e->getMessage());
        }
    }

    public function destroy(Ekstrakurikuler $ekstrakurikuler)
    {
        try {
            $nama = $ekstrakurikuler->nama;
            $ekstrakurikuler->delete();
            return redirect()->route('admin.ekstrakurikuler.index')
                ->with('success', "Ekstrakurikuler {$nama} berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->route('admin.ekstrakurikuler.index')
                ->with('error', "Gagal menghapus ekstrakurikuler: " . $e->getMessage());
        }
    }

    public function show(Ekstrakurikuler $ekstrakurikuler)
    {
        $ta = \App\Models\TahunAjaran::where('is_active', true)->first();
        $anggota = \App\Models\EkstrakurikulerSiswa::with(['siswa.kelas' => fn($q) => $ta ? $q->wherePivot('tahun_ajaran_id', $ta->id) : $q])
            ->where('ekstrakurikuler_id', $ekstrakurikuler->id)
            ->when($ta, fn($q) => $q->where('tahun_ajaran_id', $ta->id))
            ->get();
            
        $siswaLain = \App\Models\Siswa::with(['kelas' => fn($q) => $ta ? $q->wherePivot('tahun_ajaran_id', $ta->id) : $q])
            ->whereNotIn('id', $anggota->pluck('siswa_id'))
            ->orderBy('nama_lengkap')
            ->get();
        
        return view('admin.ekstrakurikuler.show', compact('ekstrakurikuler', 'anggota', 'siswaLain', 'ta'));
    }

    public function tambahAnggota(Request $request, Ekstrakurikuler $ekstrakurikuler)
    {
        $request->validate(['siswa_id' => 'required|exists:siswa,id']);
        $ta = \App\Models\TahunAjaran::where('is_active', true)->firstOrFail();
        
        \App\Models\EkstrakurikulerSiswa::firstOrCreate([
            'siswa_id' => $request->siswa_id,
            'ekstrakurikuler_id' => $ekstrakurikuler->id,
            'tahun_ajaran_id' => $ta->id,
        ]);
        
        return back()->with('success', 'Siswa berhasil ditambahkan ke ekstrakurikuler.');
    }

    public function hapusAnggota(Ekstrakurikuler $ekstrakurikuler, $idAnggota)
    {
        \App\Models\EkstrakurikulerSiswa::where('id', $idAnggota)->delete();
        return back()->with('success', 'Siswa berhasil dihapus dari ekstrakurikuler.');
    }
}
