<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Rapot — {{ $siswa->nama_lengkap }}</title>
<style>
    :root {
        --primary: #1a237e;
        --primary-light: #283593;
        --text-main: #1a1a1a;
        --text-muted: #555;
        --text-light: #888;
        --text-dark: #333;
        --border-color: #9fa8da;
        --border-light: #c5cae9;
        --bg-light: #f8f9ff;
        --bg-header: #e8eaf6;
        --border-box: #aaa;
        --border-footer: #ddd;
        --green-dark: #1b5e20;
        --red-dark: #b71c1c;
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
        font-family: Arial, sans-serif;
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
    .school-header { border: 2px solid var(--primary); border-radius: 4px; margin-bottom: 4mm; }
    .school-header-inner { display: table; width: 100%; padding: 5px 10px; }

    .school-logo { display: table-cell; width: 52px; vertical-align: middle; text-align: center; }
    /* Logo: lingkaran teks — tidak pakai emoji, tidak pakai gradient (buruk di PDF) */
    .school-logo-circle {
        width: 46px; height: 46px; border-radius: 50%;
        background: var(--primary);
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: bold; color: white;
        margin: 0 auto; letter-spacing: -0.5px;
        border: 2px solid var(--primary-light);
    }

    .school-info { display: table-cell; vertical-align: middle; text-align: center; padding: 0 10px; }
    .school-info .prov { font-size: 7.5pt; color: var(--text-muted); }
    .school-info .name { font-size: 14pt; font-weight: bold; color: var(--primary); letter-spacing: 0.5px; margin: 1px 0; }
    .school-info .addr { font-size: 7.5pt; color: var(--text-muted); }
    .school-info .npsn { font-size: 7.5pt; color: var(--text-light); margin-top: 1px; }

    .school-badge { display: table-cell; width: 72px; vertical-align: middle; text-align: center; }
    .rapot-badge {
        background: var(--primary); color: white; border-radius: 4px;
        padding: 5px 7px; font-size: 9pt; font-weight: bold; letter-spacing: 0.5px;
        display: inline-block;
    }
    .rapot-badge small { display: block; font-size: 6.5pt; font-weight: normal; margin-top: 2px; }

    /* ── JUDUL ── */
    .rapot-title { text-align: center; margin-bottom: 4mm; }
    .rapot-title h2 {
        font-size: 12pt; font-weight: bold; color: var(--primary);
        text-transform: uppercase; letter-spacing: 1px;
    }
    .rapot-title p { font-size: 9pt; color: var(--text-muted); margin-top: 2px; }

    /* ── DATA SISWA ── */
    .student-info {
        border: 1px solid var(--border-box);
        border-radius: 3px;
        margin-bottom: 4mm;
        padding: 6px 10px;
    }
    .info-grid { display: table; width: 100%; }
    .info-col { display: table-cell; width: 50%; vertical-align: top; }
    .info-col:first-child { padding-right: 12px; }
    .info-row { margin-bottom: 2px; font-size: 8.5pt; }
    .info-label { color: var(--text-muted); display: inline-block; min-width: 95px; }
    .info-colon { display: inline-block; width: 8px; }
    .info-value { font-weight: bold; color: var(--text-main); }

    /* ── RINGKASAN ── */
    .summary-row { display: table; width: 100%; margin-bottom: 4mm; }
    .summary-box {
        display: table-cell;
        border: 1px solid var(--border-color);
        padding: 6px 8px;
        background: var(--bg-light);
        text-align: center;
        vertical-align: middle;
        width: 25%;
    }
    .summary-box + .summary-box { border-left: none; }
    .summary-box .s-label { font-size: 7pt; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 3px; }
    .summary-box .s-value { font-size: 17pt; font-weight: bold; color: var(--primary); line-height: 1.2; }
    .summary-box .s-sub { font-size: 7pt; color: var(--text-light); margin-top: 2px; }

    /* ── SECTION TITLE ── */
    .section-title {
        background: var(--primary); color: white;
        font-size: 9pt; font-weight: bold;
        padding: 4px 8px;
        border-radius: 2px 2px 0 0;
        text-transform: uppercase; letter-spacing: 0.5px;
        margin-bottom: 0;
    }

    /* ── TABEL NILAI ── */
    .nilai-table {
        width: 100%; border-collapse: collapse;
        font-size: 8.5pt; margin-bottom: 4mm;
        table-layout: fixed;
    }
    .nilai-table th {
        background: var(--bg-header); color: var(--primary); font-weight: bold;
        padding: 4px 3px; border: 1px solid var(--border-color);
        text-align: center; vertical-align: middle;
    }
    .nilai-table th.left { text-align: left; padding-left: 5px; }
    .nilai-table td {
        padding: 3px 4px; border: 1px solid var(--border-light);
        vertical-align: top; word-wrap: break-word; overflow-wrap: break-word;
    }
    .nilai-table tr:nth-child(even) td { background: var(--bg-light); }
    /* Hapus hover — tidak berguna di PDF dan bisa membingungkan di print */

    /* Lebar kolom eksplisit agar tidak ada kolom yang meluber */
    .col-no      { width: 22px;  text-align: center; }
    .col-kode    { width: 40px;  text-align: center; font-weight: bold; }
    .col-mapel   { width: 18%; }
    .col-num     { width: 30px;  text-align: center; }
    .col-predikat{ width: 28px;  text-align: center; font-weight: bold; }
    .col-capaian { /* sisa lebar otomatis */ }
    .catatan-td  { font-size: 7.8pt; color: var(--text-dark); font-style: italic; line-height: 1.35; vertical-align: top; }

    .predikat-A { color: #1b5e20; background: #e8f5e9; border-radius: 3px; padding: 1px 4px; display: inline-block; }
    .predikat-B { color: #0d47a1; background: #e3f2fd; border-radius: 3px; padding: 1px 4px; display: inline-block; }
    .predikat-C { color: #e65100; background: #fff3e0; border-radius: 3px; padding: 1px 4px; display: inline-block; }
    .predikat-D { color: #b71c1c; background: #ffebee; border-radius: 3px; padding: 1px 4px; display: inline-block; }

    /* ── TABEL ABSENSI ── */
    .absensi-table { width: 100%; border-collapse: collapse; font-size: 8.5pt; margin-bottom: 4mm; }
    .absensi-table th {
        background: var(--bg-header); color: var(--primary);
        border: 1px solid var(--border-color); padding: 4px 6px; text-align: center;
    }
    .absensi-table td { border: 1px solid var(--border-light); padding: 4px 6px; text-align: center; }

    /* ── TABEL EKSKUL ── */
    .ekskul-table { width: 100%; border-collapse: collapse; font-size: 8.5pt; margin-bottom: 4mm; }
    .ekskul-table th {
        background: var(--bg-header); color: var(--primary);
        border: 1px solid var(--border-color); padding: 4px 6px; text-align: left;
    }
    .ekskul-table td { border: 1px solid var(--border-light); padding: 4px 6px; }
    .ekskul-empty { border: 1px solid var(--border-light); padding: 6px 12px; margin-bottom: 4mm; font-size: 8.5pt; color: var(--text-light); }

    /* ── CATATAN ── */
    .catatan-box {
        border: 1px solid var(--border-color); border-top: none;
        background: var(--bg-light); padding: 6px 10px;
        min-height: 35px; margin-bottom: 5mm;
        border-radius: 0 0 3px 3px;
    }
    .catatan-text { font-size: 9pt; color: var(--text-dark); line-height: 1.5; font-style: italic; }

    /* ── TANDA TANGAN ── */
    .sign-table {
        width: 100%; margin-top: 5mm;
        table-layout: fixed; border-collapse: collapse;
    }
    .sign-table td { width: 33.33%; text-align: center; vertical-align: top; padding: 0 6px; border: none; }
    .sign-title { font-size: 8.5pt; color: var(--text-dark); line-height: 1.5; }
    .sign-space { height: 16mm; }
    /* Garis tanda tangan orang tua — konsisten dengan kolom lain */
    .sign-line { width: 130px; border-bottom: 1px solid var(--text-main); margin: 0 auto 3px; height: 12px; display: block; }
    .sign-name { font-size: 9pt; font-weight: bold; color: var(--text-main); text-decoration: underline; margin-bottom: 2px; display: block; }
    .sign-nip { font-size: 7.5pt; color: var(--text-light); }

    /* ── FOOTER ── */
    .rapot-footer {
        text-align: center; margin-top: 5mm;
        font-size: 7.5pt; color: var(--text-light);
        border-top: 1px solid var(--border-footer); padding-top: 4px;
    }
</style>
</head>
<body>

{{-- Tombol unduh hanya tampil saat preview, tidak saat generate PDF --}}
@if(request()->routeIs('guru.rapot.preview'))
<div class="print-btn">
    <a href="{{ route('guru.rapot.cetak', $siswa) }}">&#x2B73; Unduh PDF</a>
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