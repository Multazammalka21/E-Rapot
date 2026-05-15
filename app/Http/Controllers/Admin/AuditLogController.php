<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RapotLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = RapotLog::with(['siswa', 'tahunAjaran', 'kelas', 'actor'])
            ->latest('created_at');

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('actor', function ($qActor) use ($search) {
                    $qActor->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('siswa', function ($qSiswa) use ($search) {
                    $qSiswa->where('nama_lengkap', 'like', "%{$search}%");
                })
                ->orWhere('action', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.audit-log.index', compact('logs'));
    }
}
