<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('siswaKelas.kelas')
            ->when($request->search, fn($q, $s) =>
                $q->where('nama_lengkap', 'like', "%$s%")
                  ->orWhere('nis', 'like', "%$s%")
                  ->orWhere('nisn', 'like', "%$s%")
            )
            ->latest();

        $siswa = $query->paginate(20)->withQueryString();
        return view('admin.siswa.index', compact('siswa'));
    }

    public function create()
    {
        return view('admin.siswa.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nisn'           => 'required|string|size:10|unique:siswa,nisn',
            'nis'            => 'required|string|max:8|unique:siswa,nis',
            'nama_lengkap'   => 'required|string|max:100',
            'jenis_kelamin'  => 'required|in:L,P',
            'tempat_lahir'   => 'nullable|string|max:60',
            'tanggal_lahir'  => 'nullable|date',
            'agama'          => 'nullable|string',
            'alamat'         => 'nullable|string',
            'no_hp'          => 'nullable|string|max:15',
            'nama_ayah'      => 'nullable|string|max:80',
            'pekerjaan_ayah' => 'nullable|string|max:60',
            'nama_ibu'       => 'nullable|string|max:80',
            'pekerjaan_ibu'  => 'nullable|string|max:60',
            'no_hp_ortu'     => 'nullable|string|max:15',
            'alamat_ortu'    => 'nullable|string',
            // Akun opsional
            'buat_akun'      => 'nullable|boolean',
            'email'          => 'nullable|email|unique:users,email|required_if:buat_akun,1',
            'password'       => 'nullable|min:6|required_if:buat_akun,1',
        ]);

        $userId = null;
        if ($request->boolean('buat_akun')) {
            $user = User::create([
                'name'      => $data['nama_lengkap'],
                'email'     => $data['email'],
                'password'  => Hash::make($data['password']),
                'role'      => 'siswa',
                'is_active' => true,
            ]);
            $userId = $user->id;
        }

        Siswa::create(array_merge(
            collect($data)->except(['buat_akun','email','password'])->toArray(),
            ['user_id' => $userId]
        ));

        return redirect()->route('admin.siswa.index')
            ->with('success', "Siswa {$data['nama_lengkap']} berhasil ditambahkan.");
    }

    public function edit(Siswa $siswa)
    {
        return view('admin.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $data = $request->validate([
            'nisn'           => ['required','string','size:10', Rule::unique('siswa','nisn')->ignore($siswa->id)],
            'nis'            => ['required','string','max:8', Rule::unique('siswa','nis')->ignore($siswa->id)],
            'nama_lengkap'   => 'required|string|max:100',
            'jenis_kelamin'  => 'required|in:L,P',
            'tempat_lahir'   => 'nullable|string|max:60',
            'tanggal_lahir'  => 'nullable|date',
            'agama'          => 'nullable|string',
            'alamat'         => 'nullable|string',
            'no_hp'          => 'nullable|string|max:15',
            'nama_ayah'      => 'nullable|string|max:80',
            'pekerjaan_ayah' => 'nullable|string|max:60',
            'nama_ibu'       => 'nullable|string|max:80',
            'pekerjaan_ibu'  => 'nullable|string|max:60',
            'no_hp_ortu'     => 'nullable|string|max:15',
            'alamat_ortu'    => 'nullable|string',
        ]);

        $siswa->update($data);
        $siswa->user?->update(['name' => $data['nama_lengkap']]);

        return redirect()->route('admin.siswa.index')
            ->with('success', "Data siswa {$data['nama_lengkap']} berhasil diperbarui.");
    }

    public function destroy(Siswa $siswa)
    {
        $nama = $siswa->nama_lengkap;
        $siswa->user?->delete();
        $siswa->delete();

        return redirect()->route('admin.siswa.index')
            ->with('success', "Siswa {$nama} berhasil dihapus.");
    }
}
