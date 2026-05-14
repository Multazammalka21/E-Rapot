<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Guru\DashboardController as GuruDashboard;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboard;
use Illuminate\Support\Facades\Route;

// ── Redirect root ke login ─────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// ── Auth Routes ────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ── Admin Routes ───────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('guru',  \App\Http\Controllers\Admin\GuruController::class)->except('show');
    Route::resource('siswa', \App\Http\Controllers\Admin\SiswaController::class)->except('show');
    Route::resource('kelas', \App\Http\Controllers\Admin\KelasController::class)->except('show');
    Route::resource('mapel', \App\Http\Controllers\Admin\MataPelajaranController::class)->except('show');
    Route::post('ekstrakurikuler/{ekstrakurikuler}/anggota', [\App\Http\Controllers\Admin\EkstrakurikulerController::class, 'tambahAnggota'])->name('ekstrakurikuler.tambah-anggota');
    Route::delete('ekstrakurikuler/{ekstrakurikuler}/anggota/{id}', [\App\Http\Controllers\Admin\EkstrakurikulerController::class, 'hapusAnggota'])->name('ekstrakurikuler.hapus-anggota');
    Route::resource('ekstrakurikuler', \App\Http\Controllers\Admin\EkstrakurikulerController::class);
    Route::resource('tahun-ajaran', \App\Http\Controllers\Admin\TahunAjaranController::class)->except('show');
    Route::get('/nilai', [\App\Http\Controllers\Admin\NilaiController::class, 'index'])->name('nilai.index');
});

// ── Guru Routes ────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruDashboard::class, 'index'])->name('dashboard');

    // Input Nilai
    Route::get('/nilai', [\App\Http\Controllers\Guru\NilaiController::class, 'index'])->name('nilai.index');
    Route::get('/nilai/{kelas}/{mapel}/input', [\App\Http\Controllers\Guru\NilaiController::class, 'input'])->name('nilai.input');
    Route::post('/nilai/{kelas}/{mapel}/store', [\App\Http\Controllers\Guru\NilaiController::class, 'store'])->name('nilai.store');
    Route::post('/nilai/{kelas}/finalize', [\App\Http\Controllers\Guru\NilaiController::class, 'finalize'])->name('nilai.finalize');
    // Rapot PDF
    Route::get('/rapot', [\App\Http\Controllers\Guru\RapotController::class, 'index'])->name('rapot.index');
    Route::get('/rapot/{siswa}/preview', [\App\Http\Controllers\Guru\RapotController::class, 'preview'])->name('rapot.preview');
    Route::get('/rapot/{siswa}/cetak', [\App\Http\Controllers\Guru\RapotController::class, 'cetak'])->name('rapot.cetak');

    // Import Nilai Excel
    Route::get('/import', [\App\Http\Controllers\Guru\ImportNilaiController::class, 'index'])->name('import.index');
    Route::get('/import/template/{kelas}/{mapel}', [\App\Http\Controllers\Guru\ImportNilaiController::class, 'template'])->name('import.template');
    Route::post('/import/{kelas}/{mapel}', [\App\Http\Controllers\Guru\ImportNilaiController::class, 'store'])->name('import.store');
});

// ── Siswa Routes ───────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboard::class, 'index'])->name('dashboard');
    Route::get('/nilai',     [\App\Http\Controllers\Siswa\NilaiController::class,   'index'])->name('nilai');
    Route::get('/rapot',     [\App\Http\Controllers\Siswa\RapotController::class,   'index'])->name('rapot');
    Route::get('/rapot/download', [\App\Http\Controllers\Siswa\RapotController::class, 'download'])->name('rapot.download');
    Route::get('/absensi',   [\App\Http\Controllers\Siswa\AbsensiController::class, 'index'])->name('absensi');
    Route::get('/ekskul',    [\App\Http\Controllers\Siswa\EkskulController::class,  'index'])->name('ekskul');
});

