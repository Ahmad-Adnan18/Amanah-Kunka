<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Pengajaran\KelasController;
use App\Http\Controllers\Pengajaran\SantriController;
use App\Http\Controllers\Perizinan\PerizinanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PelanggaranController;
use App\Http\Controllers\Pengajaran\MataPelajaranController;
use App\Http\Controllers\Akademik\NilaiController;
use App\Http\Controllers\SantriProfileController;
use App\Http\Controllers\Auth\WaliRegistrationController;
use App\Http\Controllers\Pengajaran\JabatanController;
use App\Http\Controllers\Keasramaan\CatatanHarianController;
use App\Http\Controllers\Keasramaan\PrestasiController;
use App\Http\Controllers\Akademik\PlacementController;
use App\Http\Controllers\Admin\SantriManagementController;
use App\Http\Controllers\Akademik\KurikulumController;



Route::get('/', function () {
    return redirect()->route('login');
});

// RUTE BARU UNTUK REGISTRASI WALI
Route::get('/wali-register', [WaliRegistrationController::class, 'create'])->name('wali.register');
Route::post('/wali-register', [WaliRegistrationController::class, 'store']);

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // RUTE BARU UNTUK PROFIL SANTRI
    Route::get('/santri/{santri}/profil', [SantriProfileController::class, 'show'])->name('santri.profil.show');
    Route::post('/santri/{santri}/profil/generate-wali-code', [SantriProfileController::class, 'generateWaliCode'])->name('santri.profil.generate_wali_code');
    Route::get('/santri/{santri}/profil/rapor/export', [SantriProfileController::class, 'exportRapor'])->name('santri.profil.rapor.export');
    // RUTE BARU UNTUK EXPORT PDF
    Route::get('/santri/{santri}/profil/rapor/export-pdf', [SantriProfileController::class, 'exportRaporPdf'])->name('santri.profil.rapor.export.pdf');

    // --- GRUP RUTE PENGELOLAAN PENGAJARAN ---
    Route::prefix('pengajaran')->name('pengajaran.')->group(function () {
        // Rute untuk Data Master Kelas
        Route::resource('kelas', KelasController::class)->except(['show']);

        // Rute untuk mengelola Santri di dalam Kelas
        Route::get('kelas/{kelas}/santri', [SantriController::class, 'index'])->name('santris.index');
        Route::get('kelas/{kelas}/santri/create', [SantriController::class, 'create'])->name('santris.create');
        Route::post('santri', [SantriController::class, 'store'])->name('santris.store');
        Route::get('santri/{santri}/edit', [SantriController::class, 'edit'])->name('santris.edit');
        Route::put('santri/{santri}', [SantriController::class, 'update'])->name('santris.update');
        Route::delete('santri/{santri}', [SantriController::class, 'destroy'])->name('santris.destroy');
        // RUTE BARU UNTUK MENGAMBIL DATA SANTRI VIA JSON
        Route::get('kelas/{kelas}/santri-json', [KelasController::class, 'getSantrisJson'])->name('kelas.santris.json');
        Route::resource('mata-pelajaran', MataPelajaranController::class)->except(['show']);
        // RUTE BARU UNTUK KODE WALI
        Route::post('generate-all-wali-codes', [KelasController::class, 'generateAllWaliCodes'])->name('kelas.generate_all_wali_codes');
        Route::get('export-wali-codes', [KelasController::class, 'exportWaliCodes'])->name('kelas.export_wali_codes');
        Route::resource('jabatan', JabatanController::class)->except(['show']);
        // RUTE BARU UNTUK PENUNJUKAN JABATAN
        Route::post('kelas/{kelas}/assign-jabatan', [KelasController::class, 'assignJabatan'])->name('kelas.assign_jabatan');
        Route::delete('remove-jabatan/{jabatanUser}', [KelasController::class, 'removeJabatan'])->name('kelas.remove_jabatan');
    });

    // --- RUTE UNTUK MANAJEMEN PERIZINAN ---
    Route::prefix('perizinan')->name('perizinan.')->group(function () {
        Route::get('create/{santri}', [PerizinanController::class, 'create'])->name('create');
        Route::post('store', [PerizinanController::class, 'store'])->name('store');
        Route::get('/', [PerizinanController::class, 'index'])->name('index');
        // RUTE BARU UNTUK HAPUS
        Route::delete('/{perizinan}', [PerizinanController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [PerizinanController::class, 'bulkDestroy'])->name('bulkDestroy');
    });

    // --- RUTE UNTUK LAPORAN & EXPORT ---
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/perizinan', [ReportController::class, 'index'])->name('perizinan.index');
        Route::get('/perizinan/export', [ReportController::class, 'export'])->name('perizinan.export');
    });

    // --- GRUP RUTE KHUSUS ADMIN ---
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    // --- RUTE UNTUK MANAJEMEN PELANGGARAN ---
    Route::resource('pelanggaran', PelanggaranController::class)->except(['show']);

    // --- RUTE UNTUK LAPORAN & EXPORT ---
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');

        Route::get('/perizinan', [ReportController::class, 'perizinan'])->name('perizinan');
        Route::get('/perizinan/export', [ReportController::class, 'exportPerizinan'])->name('perizinan.export');

        Route::get('/pelanggaran', [ReportController::class, 'pelanggaran'])->name('pelanggaran');
        Route::get('/pelanggaran/export', [ReportController::class, 'exportPelanggaran'])->name('pelanggaran.export');

        Route::get('/santri/export', [ReportController::class, 'exportSantri'])->name('santri.export');
    });

    // --- RUTE BARU UNTUK AKADEMIK ---
    Route::prefix('akademik')->name('akademik.')->group(function () {
        Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
        Route::post('/nilai', [NilaiController::class, 'store'])->name('nilai.store');
        // RUTE BARU UNTUK EXPORT LEGER
        Route::get('/nilai/export', [NilaiController::class, 'exportLeger'])->name('nilai.export');
        // RUTE BARU UNTUK PENEMPATAN KELAS
        Route::get('/placement', [PlacementController::class, 'index'])->name('placement.index');
        Route::post('/placement', [PlacementController::class, 'place'])->name('placement.place');
    
        // [PERBAIKAN] Ini adalah struktur rute yang benar untuk Kurikulum Terpadu
        Route::get('/kurikulum', [KurikulumController::class, 'index'])->name('kurikulum.index');
        Route::post('/kurikulum/template', [KurikulumController::class, 'storeTemplate'])->name('kurikulum.template.store');
        Route::get('/kurikulum/template/{template}/edit', [KurikulumController::class, 'editTemplate'])->name('kurikulum.template.edit');
        Route::put('/kurikulum/template/{template}', [KurikulumController::class, 'updateTemplate'])->name('kurikulum.template.update');
        Route::delete('/kurikulum/template/{template}', [KurikulumController::class, 'destroyTemplate'])->name('kurikulum.template.destroy');
        Route::post('/kurikulum/apply-template', [KurikulumController::class, 'applyTemplate'])->name('kurikulum.apply');
        // RUTE BARU UNTUK MENGAMBIL DATA MAPEL VIA JSON
            Route::get('/kurikulum/{kelas}/mapel-json', [KurikulumController::class, 'getMapelJson'])->name('kurikulum.mapel.json');
    });

    // --- RUTE BARU UNTUK KEASRAMAAN ---
    Route::prefix('keasramaan')->name('keasramaan.')->group(function () {
        // Catatan Harian
        Route::get('/santri/{santri}/catatan/create', [CatatanHarianController::class, 'create'])->name('catatan.create');
        Route::post('/santri/{santri}/catatan', [CatatanHarianController::class, 'store'])->name('catatan.store');
        Route::get('/catatan/{catatan}/edit', [CatatanHarianController::class, 'edit'])->name('catatan.edit');
        Route::put('/catatan/{catatan}', [CatatanHarianController::class, 'update'])->name('catatan.update');
        Route::delete('/catatan/{catatan}', [CatatanHarianController::class, 'destroy'])->name('catatan.destroy');

        // Prestasi
        Route::get('/santri/{santri}/prestasi/create', [PrestasiController::class, 'create'])->name('prestasi.create');
        Route::post('/santri/{santri}/prestasi', [PrestasiController::class, 'store'])->name('prestasi.store');
        Route::get('/prestasi/{prestasi}/edit', [PrestasiController::class, 'edit'])->name('prestasi.edit');
        Route::put('/prestasi/{prestasi}', [PrestasiController::class, 'update'])->name('prestasi.update');
        Route::delete('/prestasi/{prestasi}', [PrestasiController::class, 'destroy'])->name('prestasi.destroy');
    });
    // --- RUTE BARU UNTUK PUSAT MANAJEMEN SANTRI ---
    Route::prefix('manajemen-santri')->name('admin.santri-management.')->group(function () {
        Route::get('/', [SantriManagementController::class, 'index'])->name('index');
        Route::get('/import', [SantriManagementController::class, 'showImportForm'])->name('import.show');
        Route::post('/import', [SantriManagementController::class, 'import'])->name('import.store');
    });
});

require __DIR__ . '/auth.php';
