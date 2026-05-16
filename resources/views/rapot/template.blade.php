<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Rapot — {{ $siswa->nama_lengkap }}</title>
<style>
    :root {
        --primary: #1e3a8a;
        --primary-light: #2563eb;
        --text-main: #0f172a;
        --text-muted: #475569;
        --text-light: #64748b;
        --text-dark: #020617;
        --border-color: #cbd5e1;
        --border-light: #e2e8f0;
        --bg-light: #f8fafc;
        --bg-header: #f1f5f9;
        --border-box: #cbd5e1;
        --border-footer: #e2e8f0;
        --green-dark: #15803d;
        --red-dark: #b91c1c;
    }

    /* ── CETAK ── */
    @page {
        size: A4 portrait;
        margin: 15mm;
    }
    @media print {
        html, body { width: 210mm; }
        .page { margin: 0; border: none; box-shadow: none; background: none; }
        .print-btn { display: none !important; }
        /* Cegah baris tabel terpotong antar halaman */
        .nilai-table tbody tr { page-break-inside: avoid; }
        .sign-table { page-break-inside: avoid; }
        .catatan-box { page-break-inside: avoid; }
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 9.5pt;
        color: var(--text-main);
        background: #fff;
        line-height: 1.4;
    }

    .page { width: 100%; margin: 0 auto; }

    /* ── TOMBOL PREVIEW (bukan fixed, agar tidak merusak PDF) ── */
    .print-btn {
        display: block;
        text-align: right;
        margin-bottom: 6px;
    }
    .print-btn a {
        display: inline-block;
        padding: 8px 18px;
        background: var(--primary);
        color: white;
        border-radius: 6px;
        text-decoration: none;
        font-size: 10pt;
        font-weight: bold;
    }

    /* ── HEADER SEKOLAH ── */
    .school-header { 
        margin-bottom: 5mm; 
        border-bottom: 3px double var(--primary); 
        padding-bottom: 8px; 
    }
    .school-header-inner { display: table; width: 100%; }

    .school-logo { display: table-cell; width: 60px; vertical-align: middle; text-align: center; }
    /* Logo: lingkaran teks — tidak pakai emoji, tidak pakai gradient (buruk di PDF) */
    .school-logo-circle {
        width: 48px; height: 48px; border-radius: 50%;
        background: transparent;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; font-weight: bold; color: var(--primary);
        margin: 0 auto; letter-spacing: -0.5px;
        border: 2px solid var(--primary);
    }

    .school-info { display: table-cell; vertical-align: middle; text-align: center; padding: 0 10px; }
    .school-info .prov { font-size: 8pt; color: var(--text-muted); font-weight: bold; letter-spacing: 0.5px; }
    .school-info .name { font-size: 16pt; font-weight: bold; color: var(--primary); letter-spacing: 0.5px; margin: 2px 0; }
    .school-info .addr { font-size: 8pt; color: var(--text-muted); }
    .school-info .npsn { font-size: 7.5pt; color: var(--text-light); margin-top: 2px; }

    .school-badge { display: table-cell; width: 72px; vertical-align: middle; text-align: center; }
    .rapot-badge {
        background: white; color: var(--primary); border: 1px solid var(--primary); border-radius: 4px;
        padding: 4px 6px; font-size: 9pt; font-weight: bold; letter-spacing: 0.5px;
        display: inline-block;
    }
    .rapot-badge small { display: block; font-size: 6.5pt; font-weight: normal; margin-top: 1px; color: var(--text-muted); }

    /* ── JUDUL ── */
    .rapot-title { text-align: center; margin-bottom: 5mm; }
    .rapot-title h2 {
        font-size: 12pt; font-weight: bold; color: var(--text-main);
        text-transform: uppercase; letter-spacing: 1.5px;
    }
    .rapot-title p { font-size: 9pt; color: var(--text-muted); margin-top: 2px; }

    /* ── DATA SISWA ── */
    .student-info {
        background: var(--bg-light);
        border-left: 3px solid var(--primary);
        border-right: 1px solid var(--border-light);
        border-top: 1px solid var(--border-light);
        border-bottom: 1px solid var(--border-light);
        border-radius: 2px;
        margin-bottom: 5mm;
        padding: 8px 12px;
    }
    .info-grid { display: table; width: 100%; }
    .info-col { display: table-cell; width: 50%; vertical-align: top; }
    .info-col:first-child { padding-right: 12px; }
    .info-row { margin-bottom: 3px; font-size: 8.5pt; }
    .info-label { color: var(--text-muted); display: inline-block; min-width: 100px; }
    .info-colon { display: inline-block; width: 8px; }
    .info-value { font-weight: bold; color: var(--text-main); }

    /* ── RINGKASAN ── */
    .summary-row { display: table; width: 100%; margin-bottom: 5mm; border: 1px solid var(--border-color); border-radius: 4px; overflow: hidden; }
    .summary-box {
        display: table-cell;
        padding: 8px;
        text-align: center;
        vertical-align: middle;
        width: 25%;
        border-right: 1px solid var(--border-light);
    }
    .summary-box:last-child { border-right: none; }
    .summary-box .s-label { font-size: 7.5pt; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; font-weight: bold; }
    .summary-box .s-value { font-size: 16pt; font-weight: bold; color: var(--primary); line-height: 1.2; }
    .summary-box .s-sub { font-size: 7pt; color: var(--text-light); margin-top: 2px; }

    /* ── SECTION TITLE ── */
    .section-title {
        background: var(--bg-header); color: var(--primary);
        font-size: 9pt; font-weight: bold;
        padding: 5px 10px;
        border-left: 4px solid var(--primary);
        text-transform: uppercase; letter-spacing: 0.5px;
        margin-bottom: 0;
    }

    /* ── TABEL NILAI ── */
    .nilai-table {
        width: 100%; border-collapse: collapse;
        font-size: 8.5pt; margin-bottom: 5mm;
        table-layout: fixed; border: 1px solid var(--border-color);
    }
    .nilai-table th {
        background: var(--bg-light); color: var(--text-main); font-weight: bold;
        padding: 6px 4px; border: 1px solid var(--border-color);
        text-align: center; vertical-align: middle; font-size: 8pt; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .nilai-table th.left { text-align: left; padding-left: 6px; }
    .nilai-table td {
        padding: 4px 5px; border: 1px solid var(--border-light);
        vertical-align: top; word-wrap: break-word; overflow-wrap: break-word;
    }
    .nilai-table tr:nth-child(even) td { background: #fafbfc; }

    /* Lebar kolom eksplisit agar tidak ada kolom yang meluber */
    .col-no      { width: 25px;  text-align: center; }
    .col-kode    { width: 45px;  text-align: center; font-weight: bold; }
    .col-mapel   { width: 20%; }
    .col-num     { width: 35px;  text-align: center; }
    .col-predikat{ width: 35px;  text-align: center; font-weight: bold; }
    .col-capaian { /* sisa lebar otomatis */ }
    .catatan-td  { font-size: 8pt; color: var(--text-dark); line-height: 1.4; vertical-align: top; }

    .predikat-A { color: #15803d; font-weight: bold; }
    .predikat-B { color: #1d4ed8; font-weight: bold; }
    .predikat-C { color: #b45309; font-weight: bold; }
    .predikat-D { color: #b91c1c; font-weight: bold; }

    /* ── TABEL ABSENSI ── */
    .absensi-table { width: 100%; border-collapse: collapse; font-size: 8.5pt; margin-bottom: 5mm; border: 1px solid var(--border-color); }
    .absensi-table th {
        background: var(--bg-light); color: var(--text-main); font-weight: bold; text-transform: uppercase; font-size: 8pt; letter-spacing: 0.5px;
        border: 1px solid var(--border-color); padding: 5px 6px; text-align: center;
    }
    .absensi-table td { border: 1px solid var(--border-light); padding: 5px 6px; text-align: center; }

    /* ── TABEL EKSKUL ── */
    .ekskul-table { width: 100%; border-collapse: collapse; font-size: 8.5pt; margin-bottom: 5mm; border: 1px solid var(--border-color); }
    .ekskul-table th {
        background: var(--bg-light); color: var(--text-main); font-weight: bold; text-transform: uppercase; font-size: 8pt; letter-spacing: 0.5px;
        border: 1px solid var(--border-color); padding: 5px 6px; text-align: left;
    }
    .ekskul-table td { border: 1px solid var(--border-light); padding: 5px 6px; }
    .ekskul-empty { border: 1px solid var(--border-light); padding: 8px 12px; margin-bottom: 5mm; font-size: 8.5pt; color: var(--text-muted); font-style: italic; }

    /* ── CATATAN ── */
    .catatan-box {
        border: 1px solid var(--border-color); border-top: none;
        background: #fafbfc; padding: 8px 12px;
        min-height: 40px; margin-bottom: 6mm;
    }
    .catatan-text { font-size: 9pt; color: var(--text-dark); line-height: 1.5; font-style: italic; }

    /* ── TANDA TANGAN ── */
    .sign-table {
        width: 100%; margin-top: 5mm;
        table-layout: fixed; border-collapse: collapse;
    }
    .sign-table td { width: 33.33%; text-align: center; vertical-align: top; padding: 0 6px; border: none; }
    .sign-title { font-size: 8.5pt; color: var(--text-main); line-height: 1.5; }
    .sign-space { height: 18mm; }
    /* Garis tanda tangan orang tua — konsisten dengan kolom lain */
    .sign-line { width: 140px; border-bottom: 1px solid var(--text-main); margin: 0 auto 3px; height: 12px; display: block; }
    .sign-name { font-size: 9pt; font-weight: bold; color: var(--text-main); text-decoration: underline; margin-bottom: 2px; display: block; }
    .sign-nip { font-size: 8pt; color: var(--text-muted); }

    /* ── FOOTER ── */
    .rapot-footer {
        text-align: center; margin-top: 6mm;
        font-size: 7.5pt; color: var(--text-light);
        border-top: 1px solid var(--border-footer); padding-top: 6px;
    }
</style>
</head>
<body>

{{-- Tombol unduh hanya tampil saat preview, tidak saat generate PDF --}}
@if(request()->routeIs('*.preview'))
<div class="print-btn">
    @if(request()->routeIs('admin.*'))
        <a href="{{ route('admin.rapot.cetak', $siswa) }}">&#x2B73; Unduh PDF</a>
    @elseif(request()->routeIs('guru.*'))
        <a href="{{ route('guru.rapot.cetak', $siswa) }}">&#x2B73; Unduh PDF</a>
    @elseif(request()->routeIs('siswa.*'))
        <a href="{{ route('siswa.rapot.download') }}">&#x2B73; Unduh PDF</a>
    @endif
</div>
@endif

<div class="page">

    {{-- ══ HEADER SEKOLAH ══ --}}
    <div class="school-header">
        <div class="school-header-inner">
            <div class="school-logo">
                {{-- Teks singkatan, bukan emoji — aman di semua PDF engine --}}
                <div class="school-logo-circle">SMPN<br>1</div>
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

    {{-- ══ JUDUL ══ --}}
    <div class="rapot-title">
        <h2>Laporan Hasil Belajar Peserta Didik</h2>
        <p>{{ $ta->nama }} &nbsp;·&nbsp; Semester {{ ucfirst($ta->semester) }} &nbsp;·&nbsp; Fase D</p>
    </div>

    {{-- ══ DATA SISWA ══ --}}
    <div class="student-info">
        <div class="info-grid">
            <div class="info-col">
                <div class="info-row"><span class="info-label">Nama Lengkap</span><span class="info-colon">:</span><span class="info-value">{{ $siswa->nama_lengkap }}</span></div>
                <div class="info-row"><span class="info-label">NIS</span><span class="info-colon">:</span><span class="info-value">{{ $siswa->nis }}</span></div>
                <div class="info-row"><span class="info-label">NISN</span><span class="info-colon">:</span><span class="info-value">{{ $siswa->nisn }}</span></div>
                <div class="info-row"><span class="info-label">Jenis Kelamin</span><span class="info-colon">:</span><span class="info-value">{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
                <div class="info-row"><span class="info-label">Agama</span><span class="info-colon">:</span><span class="info-value">{{ $siswa->agama }}</span></div>
            </div>
            <div class="info-col">
                <div class="info-row"><span class="info-label">Kelas</span><span class="info-colon">:</span><span class="info-value">{{ $siswaKelas->kelas?->nama_kelas }} (Kelas {{ $siswaKelas->kelas?->tingkat }})</span></div>
                <div class="info-row"><span class="info-label">Tahun Ajaran</span><span class="info-colon">:</span><span class="info-value">{{ $ta->nama }}</span></div>
                <div class="info-row"><span class="info-label">Semester</span><span class="info-colon">:</span><span class="info-value">{{ ucfirst($ta->semester) }}</span></div>
                <div class="info-row"><span class="info-label">Wali Kelas</span><span class="info-colon">:</span><span class="info-value">{{ $siswaKelas->kelas?->waliKelas?->nama_gelar ?? '-' }}</span></div>
                {{-- Format tanggal bahasa Indonesia tanpa bergantung AppServiceProvider --}}
                <div class="info-row"><span class="info-label">Tempat, Tgl Lahir</span><span class="info-colon">:</span><span class="info-value">{{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir?->locale('id')->isoFormat('D MMMM Y') }}</span></div>
            </div>
        </div>
    </div>

    {{-- ══ RINGKASAN NILAI ══ --}}
    @php
        $pU = $rataRata >= 90 ? 'A' : ($rataRata >= 80 ? 'B' : ($rataRata >= 70 ? 'C' : 'D'));
        $pULabel = ['A' => 'Sangat Baik', 'B' => 'Baik', 'C' => 'Cukup', 'D' => 'Perlu Bimbingan'][$pU];
        $mapelTuntas = $nilai->where('is_lulus', true)->count();
        $totalMapel  = $nilai->count();
        $persenHadir = $absensi?->persentase_hadir ?? 100;
    @endphp
    <div class="summary-row">
        <div class="summary-box">
            <div class="s-label">Rata-rata Nilai<br><span style="font-size:6.5pt">Semua Mata Pelajaran</span></div>
            <div class="s-value" style="font-size:18pt">{{ number_format($rataRata, 1) }}</div>
        </div>
        <div class="summary-box">
            <div class="s-label">Peringkat Kelas</div>
            <div class="s-value">{{ $ranking['urutan'] }}</div>
            <div class="s-sub">dari {{ $ranking['dari'] }} siswa</div>
        </div>
        <div class="summary-box">
            <div class="s-label">Mapel Tuntas</div>
            <div class="s-value" style="color:{{ $mapelTuntas >= $totalMapel * 0.75 ? '#1b5e20' : '#b71c1c' }}">{{ $mapelTuntas }}</div>
            <div class="s-sub">dari {{ $totalMapel }} mapel</div>
        </div>
        <div class="summary-box">
            <div class="s-label">Predikat Umum</div>
            <div class="s-value" style="font-size:20pt"><span class="predikat-{{ $pU }}">{{ $pU }}</span></div>
            <div class="s-sub">{{ $pULabel }}</div>
        </div>
    </div>

    {{-- ══ TABEL NILAI ══ --}}
    <div class="section-title">A. Capaian Pembelajaran Mata Pelajaran</div>
    <table class="nilai-table">
        <thead>
            <tr>
                <th class="col-no">#</th>
                <th class="col-kode">Kode</th>
                <th class="left col-mapel">Mata Pelajaran</th>
                <th class="col-num">SH</th>
                <th class="col-num">STS</th>
                <th class="col-num">SAS</th>
                <th class="col-num">Akhir</th>
                <th class="col-predikat">P</th>
                <th class="left col-capaian">Capaian Kompetensi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nilai as $i => $n)
            <tr>
                <td class="col-no" style="text-align:center">{{ $i + 1 }}</td>
                <td class="col-kode">{{ $n->mataPelajaran?->kode_mapel }}</td>
                <td>{{ $n->mataPelajaran?->nama_mapel }}</td>
                <td class="col-num">{{ $n->nilai_sh ?? '-' }}</td>
                <td class="col-num">{{ $n->nilai_sts ?? '-' }}</td>
                <td class="col-num">{{ $n->nilai_sas ?? '-' }}</td>
                <td class="col-num" style="font-weight:bold">{{ $n->nilai_akhir ? number_format($n->nilai_akhir, 1) : '-' }}</td>
                <td class="col-predikat"><span class="predikat-{{ $n->predikat ?? 'C' }}">{{ $n->predikat ?? '-' }}</span></td>
                {{-- Batasi 150 karakter agar baris tidak meluber ke halaman berikutnya --}}
                <td class="catatan-td">{{ Str::limit($n->catatan_guru ?? 'Menunjukkan pemahaman yang baik terhadap materi pembelajaran.', 150) }}</td>
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
                <td style="font-weight:bold; color:{{ $persenHadir >= 80 ? '#1b5e20' : '#b71c1c' }}">
                    {{ $persenHadir }}%
                </td>
            </tr>
        </tbody>
    </table>

    {{-- ══ EKSTRAKURIKULER ══ --}}
    <div class="section-title">C. Ekstrakurikuler</div>
    @if($ekskul->isNotEmpty())
    <table class="ekskul-table">
        <thead>
            <tr>
                <th style="width:30px">#</th>
                <th>Nama Kegiatan</th>
                <th style="width:80px; text-align:center">Predikat</th>
            </tr>
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
    <div class="ekskul-empty">Tidak mengikuti ekstrakurikuler.</div>
    @endif

    {{-- ══ CATATAN WALI KELAS ══ --}}
    <div class="section-title">D. Catatan Wali Kelas</div>
    <div class="catatan-box">
        <p class="catatan-text">{{ $catatan?->catatan ?? 'Siswa telah mengikuti pembelajaran dengan baik. Diharapkan terus meningkatkan semangat belajar.' }}</p>
    </div>

    {{-- ══ TANDA TANGAN ══ --}}
    <table class="sign-table">
        <tr>
            <td>
                <div class="sign-title">Mengetahui,<br>Orang Tua / Wali</div>
                <div class="sign-space"></div>
                {{-- Garis kosong pakai border-bottom, konsisten dengan kolom lain --}}
                <span class="sign-line"></span>
            </td>
            <td>
                {{-- Locale 'id' dipanggil langsung di Carbon instance, bukan global --}}
                <div class="sign-title">Surabaya, {{ now()->locale('id')->isoFormat('D MMMM Y') }}<br>Wali Kelas {{ $siswaKelas->kelas?->nama_kelas }}</div>
                <div class="sign-space"></div>
                <span class="sign-name">{{ $siswaKelas->kelas?->waliKelas?->nama_gelar ?? '-' }}</span>
                <span class="sign-nip">NIP. {{ $siswaKelas->kelas?->waliKelas?->nip ?? '-' }}</span>
            </td>
            <td>
                <div class="sign-title">Mengetahui,<br>Kepala Sekolah</div>
                <div class="sign-space"></div>
                <span class="sign-name">Drs. H. Bambang Supriyono, M.Pd.</span>
                <span class="sign-nip">NIP. 196504121990011001</span>
            </td>
        </tr>
    </table>

    {{-- ══ FOOTER ══ --}}
    <div class="rapot-footer">
        Dokumen ini diterbitkan oleh sistem E-Rapot SMPN 1 Surabaya &nbsp;·&nbsp; {{ now()->format('d/m/Y H:i') }} &nbsp;·&nbsp; Kurikulum Merdeka Fase D
    </div>

</div>
</body>
</html>