<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Rapot — {{ $siswa->nama_lengkap }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; font-size: 10pt; color: #1a1a1a; background: #fff; }

    /* ── LAYOUT ── */
    .page { width: 100%; max-width: 210mm; margin: 0 auto; padding: 15mm 15mm 12mm; }

    /* ── HEADER SEKOLAH ── */
    .school-header { border: 2px solid #1a237e; border-radius: 4px; margin-bottom: 6mm; }
    .school-header-inner { display: table; width: 100%; padding: 6px 10px; }
    .school-logo { display: table-cell; width: 60px; vertical-align: middle; text-align: center; }
    .school-logo-circle {
        width: 52px; height: 52px; border-radius: 50%;
        background: linear-gradient(135deg, #1a237e, #283593);
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; color: white; margin: 0 auto;
    }
    .school-info { display: table-cell; vertical-align: middle; text-align: center; padding: 0 10px; }
    .school-info .prov { font-size: 8pt; color: #555; }
    .school-info .name { font-size: 15pt; font-weight: bold; color: #1a237e; letter-spacing: 0.5px; }
    .school-info .addr { font-size: 7.5pt; color: #444; margin-top: 1px; }
    .school-info .npsn { font-size: 7.5pt; color: #666; }
    .school-badge { display: table-cell; width: 80px; vertical-align: middle; text-align: center; }
    .rapot-badge {
        background: #1a237e; color: white; border-radius: 4px;
        padding: 4px 8px; font-size: 9pt; font-weight: bold; letter-spacing: 0.5px;
    }
    .rapot-badge small { display: block; font-size: 7pt; font-weight: normal; margin-top: 2px; }

    /* ── TITLE ── */
    .rapot-title { text-align: center; margin-bottom: 4mm; }
    .rapot-title h2 { font-size: 12pt; font-weight: bold; color: #1a237e; text-transform: uppercase; letter-spacing: 1px; }
    .rapot-title p { font-size: 9pt; color: #555; }

    /* ── STUDENT INFO ── */
    .student-info { border: 1px solid #aaa; border-radius: 3px; margin-bottom: 5mm; padding: 6px 10px; }
    .info-grid { display: table; width: 100%; }
    .info-col { display: table-cell; width: 50%; vertical-align: top; padding-right: 10px; }
    .info-row { margin-bottom: 3px; font-size: 9pt; }
    .info-label { color: #555; display: inline-block; min-width: 90px; }
    .info-colon { display: inline-block; width: 8px; }
    .info-value { font-weight: bold; color: #1a1a1a; }

    /* ── SECTION TITLE ── */
    .section-title {
        background: #1a237e; color: white; font-size: 9pt; font-weight: bold;
        padding: 4px 10px; margin-bottom: 0; border-radius: 2px 2px 0 0;
        text-transform: uppercase; letter-spacing: 0.5px;
    }

    /* ── NILAI TABLE ── */
    .nilai-table { width: 100%; border-collapse: collapse; font-size: 8.5pt; margin-bottom: 5mm; }
    .nilai-table th {
        background: #e8eaf6; color: #1a237e; font-weight: bold;
        padding: 5px 6px; border: 1px solid #9fa8da; text-align: center;
    }
    .nilai-table th.left { text-align: left; }
    .nilai-table td { padding: 4px 6px; border: 1px solid #c5cae9; vertical-align: middle; }
    .nilai-table tr:nth-child(even) td { background: #f8f9ff; }
    .nilai-table tr:hover td { background: #e8eaf6; }
    .nilai-table .no { text-align: center; width: 25px; }
    .nilai-table .kode { text-align: center; width: 45px; font-weight: bold; }
    .nilai-table .num { text-align: center; width: 40px; }
    .nilai-table .predikat { text-align: center; width: 30px; font-weight: bold; }

    .predikat-A { color: #1b5e20; background: #e8f5e9; border-radius: 3px; padding: 1px 5px; }
    .predikat-B { color: #0d47a1; background: #e3f2fd; border-radius: 3px; padding: 1px 5px; }
    .predikat-C { color: #e65100; background: #fff3e0; border-radius: 3px; padding: 1px 5px; }
    .predikat-D { color: #b71c1c; background: #ffebee; border-radius: 3px; padding: 1px 5px; }

    /* ── SUMMARY BOX ── */
    .summary-row { display: table; width: 100%; margin-bottom: 5mm; border-collapse: collapse; }
    .summary-box {
        display: table-cell; border: 1px solid #9fa8da; padding: 8px 12px;
        background: #f8f9ff; text-align: center; vertical-align: middle;
    }
    .summary-box + .summary-box { border-left: none; }
    .summary-box .label { font-size: 7.5pt; color: #555; text-transform: uppercase; letter-spacing: 0.3px; }
    .summary-box .value { font-size: 16pt; font-weight: bold; color: #1a237e; line-height: 1.2; }
    .summary-box .sub { font-size: 7pt; color: #888; }

    /* ── ABSENSI TABLE ── */
    .absensi-table { width: 100%; border-collapse: collapse; font-size: 8.5pt; margin-bottom: 5mm; }
    .absensi-table th { background: #e8eaf6; color: #1a237e; border: 1px solid #9fa8da; padding: 4px 8px; text-align: center; }
    .absensi-table td { border: 1px solid #c5cae9; padding: 5px 8px; text-align: center; }

    /* ── EKSKUL TABLE ── */
    .ekskul-table { width: 100%; border-collapse: collapse; font-size: 8.5pt; margin-bottom: 5mm; }
    .ekskul-table th { background: #e8eaf6; color: #1a237e; border: 1px solid #9fa8da; padding: 4px 8px; text-align: left; }
    .ekskul-table td { border: 1px solid #c5cae9; padding: 4px 8px; }

    /* ── CATATAN ── */
    .catatan-box { border: 1px solid #9fa8da; background: #f8f9ff; padding: 8px 12px; min-height: 45px; margin-bottom: 5mm; border-radius: 0 0 3px 3px; }
    .catatan-text { font-size: 9pt; color: #333; line-height: 1.6; font-style: italic; }

    /* ── SIGNATURES ── */
    .sign-area { display: table; width: 100%; margin-top: 6mm; }
    .sign-col { display: table-cell; width: 33.33%; text-align: center; padding: 0 8px; }
    .sign-title { font-size: 8.5pt; color: #333; margin-bottom: 20mm; }
    .sign-name { font-size: 9pt; font-weight: bold; color: #1a1a1a; border-top: 1px solid #555; padding-top: 4px; }
    .sign-nip { font-size: 7.5pt; color: #666; }

    /* ── FOOTER ── */
    .rapot-footer { text-align: center; margin-top: 8mm; font-size: 7.5pt; color: #888; border-top: 1px solid #ddd; padding-top: 4px; }

    /* Print button (hanya tampil di preview, bukan PDF) */
    .print-btn { position: fixed; top: 20px; right: 20px; z-index: 99; }
    .print-btn a { display: inline-block; padding: 10px 20px; background: #1a237e; color: white; border-radius: 8px; text-decoration: none; font-family: Arial; font-size: 11pt; font-weight: bold; }
    @media print { .print-btn { display: none; } }
</style>
</head>
<body>

{{-- Print button hanya saat preview --}}
@if(request()->routeIs('guru.rapot.preview'))
<div class="print-btn">
    <a href="{{ route('guru.rapot.cetak', $siswa) }}">📥 Unduh PDF</a>
</div>
@endif

<div class="page">

    {{-- ══ HEADER SEKOLAH ══ --}}
    <div class="school-header">
        <div class="school-header-inner">
            <div class="school-logo">
                <div class="school-logo-circle">🏫</div>
            </div>
            <div class="school-info">
                <div class="prov">PEMERINTAH KOTA SURABAYA — DINAS PENDIDIKAN</div>
                <div class="name">SMP NEGERI 1 SURABAYA</div>
                <div class="addr">Jl. Tembok Dukuh No.1, Bubutan, Surabaya, Jawa Timur 60174</div>
                <div class="npsn">NPSN: 20532385 &nbsp;|&nbsp; Telp: (031) 3524178 &nbsp;|&nbsp; Email: smpn1sby@surabaya.go.id</div>
            </div>
            <div class="school-badge">
                <div class="rapot-badge">
                    RAPOT
                    <small>Kurikulum Merdeka</small>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ TITLE ══ --}}
    <div class="rapot-title">
        <h2>Laporan Hasil Belajar Peserta Didik</h2>
        <p>{{ $ta->nama }} &nbsp;·&nbsp; Semester {{ ucfirst($ta->semester) }} &nbsp;·&nbsp; Fase D</p>
    </div>

    {{-- ══ DATA SISWA ══ --}}
    <div class="student-info">
        <div class="info-grid">
            <div class="info-col">
                <div class="info-row"><span class="info-label">Nama Lengkap</span><span class="info-colon">:</span> <span class="info-value">{{ $siswa->nama_lengkap }}</span></div>
                <div class="info-row"><span class="info-label">NIS</span><span class="info-colon">:</span> <span class="info-value">{{ $siswa->nis }}</span></div>
                <div class="info-row"><span class="info-label">NISN</span><span class="info-colon">:</span> <span class="info-value">{{ $siswa->nisn }}</span></div>
                <div class="info-row"><span class="info-label">Jenis Kelamin</span><span class="info-colon">:</span> <span class="info-value">{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
                <div class="info-row"><span class="info-label">Agama</span><span class="info-colon">:</span> <span class="info-value">{{ $siswa->agama }}</span></div>
            </div>
            <div class="info-col">
                <div class="info-row"><span class="info-label">Kelas</span><span class="info-colon">:</span> <span class="info-value">{{ $siswaKelas->kelas?->nama_kelas }} (Kelas {{ $siswaKelas->kelas?->tingkat }})</span></div>
                <div class="info-row"><span class="info-label">Tahun Ajaran</span><span class="info-colon">:</span> <span class="info-value">{{ $ta->nama }}</span></div>
                <div class="info-row"><span class="info-label">Semester</span><span class="info-colon">:</span> <span class="info-value">{{ ucfirst($ta->semester) }}</span></div>
                <div class="info-row"><span class="info-label">Wali Kelas</span><span class="info-colon">:</span> <span class="info-value">{{ $siswaKelas->kelas?->waliKelas?->nama_gelar ?? '-' }}</span></div>
                <div class="info-row"><span class="info-label">Tempat, Tgl Lahir</span><span class="info-colon">:</span> <span class="info-value">{{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir?->format('d F Y') }}</span></div>
            </div>
        </div>
    </div>

    {{-- ══ RINGKASAN NILAI ══ --}}
    <div class="summary-row">
        <div class="summary-box">
            <div class="label">Rata-rata Nilai</div>
            <div class="value" style="font-size:18pt">{{ number_format($rataRata, 1) }}</div>
            <div class="sub">Semua Mata Pelajaran</div>
        </div>
        <div class="summary-box">
            <div class="label">Peringkat Kelas</div>
            <div class="value">{{ $ranking['urutan'] }}</div>
            <div class="sub">dari {{ $ranking['dari'] }} siswa</div>
        </div>
        <div class="summary-box">
            <div class="label">Mapel Tuntas</div>
            <div class="value" style="color:#1b5e20">{{ $nilai->where('is_lulus', true)->count() }}</div>
            <div class="sub">dari {{ $nilai->count() }} mapel</div>
        </div>
        <div class="summary-box">
            <div class="label">Predikat Umum</div>
            <div class="value" style="font-size:20pt">
                @php $pU = $rataRata >= 90 ? 'A' : ($rataRata >= 80 ? 'B' : ($rataRata >= 70 ? 'C' : 'D')); @endphp
                <span class="predikat-{{ $pU }}">{{ $pU }}</span>
            </div>
            <div class="sub">{{ ['A'=>'Sangat Baik','B'=>'Baik','C'=>'Cukup','D'=>'Perlu Bimbingan'][$pU] }}</div>
        </div>
    </div>

    {{-- ══ TABEL NILAI ══ --}}
    <div class="section-title">A. Capaian Pembelajaran Mata Pelajaran</div>
    <table class="nilai-table">
        <thead>
            <tr>
                <th class="no">#</th>
                <th class="kode">Kode</th>
                <th class="left">Mata Pelajaran</th>
                <th class="num">SH</th>
                <th class="num">STS</th>
                <th class="num">SAS</th>
                <th class="num">Akhir</th>
                <th class="predikat">P</th>
                <th class="left">Capaian Kompetensi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nilai as $i => $n)
            <tr>
                <td class="no">{{ $i + 1 }}</td>
                <td class="kode">{{ $n->mataPelajaran?->kode_mapel }}</td>
                <td>{{ $n->mataPelajaran?->nama_mapel }}</td>
                <td class="num">{{ $n->nilai_sh ?? '-' }}</td>
                <td class="num">{{ $n->nilai_sts ?? '-' }}</td>
                <td class="num">{{ $n->nilai_sas ?? '-' }}</td>
                <td class="num" style="font-weight:bold">{{ $n->nilai_akhir ? number_format($n->nilai_akhir, 1) : '-' }}</td>
                <td class="predikat"><span class="predikat-{{ $n->predikat ?? 'C' }}">{{ $n->predikat ?? '-' }}</span></td>
                <td style="font-size:7.5pt;color:#333;font-style:italic">{{ Str::limit($n->catatan_guru ?? 'Menunjukkan pemahaman yang baik terhadap materi pembelajaran.', 90) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ══ ABSENSI ══ --}}
    <div class="section-title">B. Rekap Kehadiran</div>
    <table class="absensi-table">
        <thead>
            <tr>
                <th>Sakit (hari)</th>
                <th>Izin (hari)</th>
                <th>Tanpa Keterangan (hari)</th>
                <th>Total Hadir (hari)</th>
                <th>Persentase Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $absensi?->sakit ?? 0 }}</td>
                <td>{{ $absensi?->izin ?? 0 }}</td>
                <td>{{ $absensi?->alpha ?? 0 }}</td>
                <td style="font-weight:bold">{{ $absensi?->hadir ?? 0 }}</td>
                <td style="font-weight:bold;color:{{ ($absensi?->persentase_hadir ?? 100) >= 80 ? '#1b5e20' : '#b71c1c' }}">
                    {{ $absensi ? $absensi->persentase_hadir . '%' : '100%' }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- ══ EKSTRAKURIKULER ══ --}}
    <div class="section-title">C. Ekstrakurikuler</div>
    @if($ekskul->isNotEmpty())
    <table class="ekskul-table">
        <thead>
            <tr><th style="width:30px">#</th><th>Nama Kegiatan</th><th style="width:80px;text-align:center">Predikat</th></tr>
        </thead>
        <tbody>
            @foreach($ekskul as $i => $e)
            <tr>
                <td style="text-align:center">{{ $i + 1 }}</td>
                <td>{{ $e->ekstrakurikuler?->nama }}</td>
                <td style="text-align:center"><span class="predikat-{{ $e->predikat ?? 'C' }}">{{ $e->predikat ?? '-' }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="border:1px solid #c5cae9;padding:6px 12px;margin-bottom:5mm;font-size:8.5pt;color:#888">Tidak mengikuti ekstrakurikuler.</div>
    @endif

    {{-- ══ CATATAN WALI KELAS ══ --}}
    <div class="section-title">D. Catatan Wali Kelas</div>
    <div class="catatan-box">
        <p class="catatan-text">{{ $catatan?->catatan ?? 'Siswa telah mengikuti pembelajaran dengan baik. Diharapkan terus meningkatkan semangat belajar.' }}</p>
    </div>

    {{-- ══ TANDA TANGAN ══ --}}
    <div class="sign-area">
        <div class="sign-col">
            <div class="sign-title">Orang Tua / Wali</div>
            <div class="sign-name">___________________</div>
        </div>
        <div class="sign-col">
            <div class="sign-title">
                Surabaya, {{ now()->isoFormat('D MMMM Y') }}<br>
                Wali Kelas {{ $siswaKelas->kelas?->nama_kelas }}
            </div>
            <div class="sign-name">{{ $siswaKelas->kelas?->waliKelas?->nama_gelar ?? '-' }}</div>
            <div class="sign-nip">NIP. {{ $siswaKelas->kelas?->waliKelas?->nip ?? '-' }}</div>
        </div>
        <div class="sign-col">
            <div class="sign-title">Kepala Sekolah</div>
            <div class="sign-name">Drs. H. Bambang Supriyono, M.Pd.</div>
            <div class="sign-nip">NIP. 196504121990011001</div>
        </div>
    </div>

    {{-- ══ FOOTER ══ --}}
    <div class="rapot-footer">
        Dokumen ini diterbitkan oleh sistem E-Rapot SMPN 1 Surabaya &nbsp;·&nbsp; {{ now()->format('d/m/Y H:i') }} &nbsp;·&nbsp; Kurikulum Merdeka Fase D
    </div>

</div>
</body>
</html>
