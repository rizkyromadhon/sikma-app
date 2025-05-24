<?php

use App\Models\Presensi;
use App\Events\MessageCreated;
use App\Events\PresensiCreated;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PesanController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Admin\RfidController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\ProdiController;
use App\Http\Controllers\JadwalKelasController;
use App\Http\Controllers\PusatBantuanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\RuanganController;
use App\Http\Controllers\Admin\GolonganController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\PresensiKuliahController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\MataKuliahController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Admin\AlatPresensiController;
use App\Http\Controllers\Admin\JadwalKuliahController;
use App\Http\Controllers\Auth\ForgotPasswordController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/jadwal-kuliah', [JadwalKelasController::class, 'index'])->name('jadwal-kuliah.index');

Route::get('/presensi-kuliah', [PresensiKuliahController::class, 'index'])->name('presensi-kuliah');
Route::get('/presensi-kuliah/download/{format}', [PresensiKuliahController::class, 'download'])
    ->name('presensi.download');
Route::get('/presensi-kuliah/preview-pdf', [PresensiKuliahController::class, 'previewPdf'])->name('presensi.preview');

Route::get('/pusat-bantuan', [PusatBantuanController::class, 'index'])->name('pusat-bantuan');

Route::get('/presensi/today', [HomeController::class, 'getPresensiToday']);
Route::get('/presensi/filtered', [HomeController::class, 'getPresensiFiltered']);
Route::get('/presensi-kuliah/ajax', [PresensiKuliahController::class, 'ajaxFilter']);
Route::get('/rekap/presensi/json', [HomeController::class, 'rekapPresensiJson']);
Route::get('/detail-presensi/{program_studi}', [HomeController::class, 'showDetail'])->name('detail.presensi');

Route::get('/login', function () {
    return view('login', ['title' => 'Halaman Login']);
})->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login')->name('login');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');

Route::get('/register', function () {
    return view('register', ['title' => 'Halaman Login']);
});
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// Route::get('/profile', function () {
//     return view('profile');
// })->middleware('auth')->name('profile');
Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth')->name('profile');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::get('/ganti-password', [ProfileController::class, 'changePassword'])->name('ganti-password');
Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');

Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');
Route::match(['GET', 'POST'], '/check-nim', [LaporanController::class, 'checkNIM']);

Route::get('/pesan', [PesanController::class, 'index'])->name('mahasiswa.pesan');


// Routes Admin
Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Mahasiswa Routes
    Route::get('/admin/mahasiswa', [MahasiswaController::class, 'index'])->name('admin.mahasiswa.index');
    Route::get('/admin/mahasiswa/create', [MahasiswaController::class, 'create'])->name('admin.mahasiswa.create');
    Route::post('/admin/mahasiswa/store', [MahasiswaController::class, 'store'])->name('admin.mahasiswa.store');
    Route::get('/admin/mahasiswa/{id}/edit', [MahasiswaController::class, 'edit'])->name('admin.mahasiswa.edit');
    Route::post('/admin/mahasiswa/{id}/update', [MahasiswaController::class, 'update'])->name('admin.mahasiswa.update');
    Route::delete('/admin/mahasiswa/{id}/destroy', [MahasiswaController::class, 'destroy'])->name('admin.mahasiswa.destroy');

    // Semester Routes
    Route::get('/admin/semester', [SemesterController::class, 'index'])->name('admin.semester.index');
    Route::get('/admin/semester/create', [SemesterController::class, 'create'])->name('admin.semester.create');
    Route::post('/admin/semester/store', [SemesterController::class, 'store'])->name('admin.semester.store');
    Route::get('/admin/semester/{id}/edit', [SemesterController::class, 'edit'])->name('admin.semester.edit');
    Route::put('/admin/semester/{id}/update', [SemesterController::class, 'update'])->name('admin.semester.update');
    Route::delete('/admin/semester/{id}/destroy', [SemesterController::class, 'destroy'])->name('admin.semester.destroy');

    // Program Studi
    Route::get('/admin/program_studi', [ProdiController::class, 'index'])->name('admin.prodi.index');
    Route::post('/admin/program_studi/create', [ProdiController::class, 'create'])->name('admin.prodi.create');
    Route::put('/admin/program_studi/{id}/update', [ProdiController::class, 'update'])->name('admin.prodi.update');
    Route::delete('/admin/program_studi/{id}/destroy', [ProdiController::class, 'destroy'])->name('admin.prodi.destroy');

    // Golongan Routes
    Route::get('/admin/golongan', [GolonganController::class, 'index'])->name('admin.golongan.index');
    Route::post('/admin/golongan/create', [GolonganController::class, 'create'])->name('admin.golongan.create');
    Route::delete('/admin/golongan/{id}/destroy', [GolonganController::class, 'destroy'])->name('admin.golongan.destroy');

    // Dosen Routes
    Route::get('/admin/dosen', [DosenController::class, 'index'])->name('admin.dosen.index');
    Route::get('/admin/dosen/create', [DosenController::class, 'create'])->name('admin.dosen.create');
    Route::post('/admin/dosen/store', [DosenController::class, 'store'])->name('admin.dosen.store');
    Route::get('/admin/dosen/{id}/edit', [DosenController::class, 'edit'])->name('admin.dosen.edit');
    Route::put('/admin/dosen/{id}/update', [DosenController::class, 'update'])->name('admin.dosen.update');
    Route::delete('/admin/dosen/{id}/destroy', [DosenController::class, 'destroy'])->name('admin.dosen.destroy');

    // Ruangan Routes
    Route::get('/admin/ruangan', [RuanganController::class, 'index'])->name('admin.ruangan.index');
    Route::get('/admin/ruangan/create', [RuanganController::class, 'create'])->name('admin.ruangan.create');
    Route::post('/admin/ruangan/store', [RuanganController::class, 'store'])->name('admin.ruangan.store');
    Route::get('/admin/ruangan/{id}/edit', [RuanganController::class, 'edit'])->name('admin.ruangan.edit');
    Route::put('/admin/ruangan/{id}/update', [RuanganController::class, 'update'])->name('admin.ruangan.update');
    Route::delete('/admin/ruangan/{id}/destroy', [RuanganController::class, 'destroy'])->name('admin.ruangan.destroy');

    // Mata Kuliah Routes
    Route::get('/admin/mata-kuliah', [MataKuliahController::class, 'index'])->name('admin.mata-kuliah.index');
    Route::get('/admin/mata-kuliah/create', [MataKuliahController::class, 'create'])->name('admin.mata-kuliah.create');
    Route::post('/admin/mata-kuliah/store', [MataKuliahController::class, 'store'])->name('admin.mata-kuliah.store');
    Route::get('/admin/mata-kuliah/{id}/edit', [MataKuliahController::class, 'edit'])->name('admin.mata-kuliah.edit');
    Route::put('/admin/mata-kuliah/{id}/update', [MataKuliahController::class, 'update'])->name('admin.mata-kuliah.update');
    Route::delete('/admin/mata-kuliah/{id}/destroy', [MataKuliahController::class, 'destroy'])->name('admin.mata-kuliah.destroy');

    // Admin Jadwal Kuliah Routes
    Route::get('/admin/jadwal-kuliah', [JadwalKuliahController::class, 'index'])->name('admin.jadwal-kuliah.index');
    Route::get('/admin/jadwal-kuliah/create', [JadwalKuliahController::class, 'create'])->name('admin.jadwal-kuliah.create');
    Route::post('/admin/jadwal-kuliah/store', [JadwalKuliahController::class, 'store'])->name('admin.jadwal-kuliah.store');
    Route::get('/admin/jadwal-kuliah/{id}/edit', [JadwalKuliahController::class, 'edit'])->name('admin.jadwal-kuliah.edit');
    Route::put('/admin/jadwal-kuliah/{id}/update', [JadwalKuliahController::class, 'update'])->name('admin.jadwal-kuliah.update');
    Route::delete('/admin/jadwal-kuliah/{id}/destroy', [JadwalKuliahController::class, 'destroy'])->name('admin.jadwal-kuliah.destroy');

    // Alat Presensi Routes
    Route::get('/admin/alat-presensi', [AlatPresensiController::class, 'index'])->name('admin.alat-presensi.index');
    Route::get('/admin/alat-presensi/create', [AlatPresensiController::class, 'create'])->name('admin.alat-presensi.create');
    Route::post('/admin/alat-presensi/store', [AlatPresensiController::class, 'store'])->name('admin.alat-presensi.store');
    Route::get('/admin/alat-presensi/{id}/edit', [AlatPresensiController::class, 'edit'])->name('admin.alat-presensi.edit');
    Route::put('/admin/alat-presensi/{id}/update', [AlatPresensiController::class, 'update'])->name('admin.alat-presensi.update');
    Route::delete('/admin/alat-presensi/{id}/destroy', [AlatPresensiController::class, 'destroy'])->name('admin.alat-presensi.destroy');

    // RFID Routes
    Route::get('/admin/rfid', [RfidController::class, 'index'])->name('admin.rfid.index');
    Route::get('/admin/rfid/{id}/registrasi', [RfidController::class, 'registrasi'])->name('admin.rfid.registrasi');
    Route::get('/admin/rfid/{id}/edit', [RfidController::class, 'edit'])->name('admin.rfid.edit');
    Route::post('/admin/rfid/{id}/store', [RfidController::class, 'store'])->name('admin.rfid.store');
    Route::put('/admin/rfid/{id}/update', [RfidController::class, 'update'])->name('admin.rfid.update');
    Route::post('/admin/rfid/reset-mode', [RfidController::class, 'resetMode'])->name('admin.rfid.resetMode');

    // Laporan Mahasiswa Routes
    Route::get('/admin/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
    Route::get('/admin/laporan/{id}', [LaporanController::class, 'show'])->name('admin.laporan.show');
    Route::post('/admin/laporan/{id}/update', [LaporanController::class, 'update'])->name('admin.laporan.update');
    Route::post('/admin/laporan/aksi/{id}/{aksi}', [LaporanController::class, 'aksi'])->name('laporan.aksi');
    Route::delete('/admin/laporan/{id}/destroy', [LaporanController::class, 'destroy'])->name('admin.laporan.destroy');
    Route::post('/admin/laporan/{id}/balas', [LaporanController::class, 'balas'])->name('admin.laporan.balas');
});
