<?php

use Carbon\Carbon;
use App\Models\Tugas;
use App\Models\Materi;
use App\Models\Jurusan;
use App\Models\Silabus;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PembelajaranController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/', [AuthController::class, 'logout'])->name('logout');
Route::post('/auth', [AuthController::class, 'auth'])->name('auth');
Route::get('/pilih', [AuthController::class, 'choose'])->name('pilihan_daftar');
Route::get('/register/dosen', [AuthController::class, 'reg_dosen'])->name('register_dosen');
Route::post('/register/dosen/store', [AuthController::class, 'store_dosen'])->name('store.dosen');
Route::get('/register/mahasiswa', [AuthController::class, 'reg_mahasiswa'])->name('register_mahasiswa');
Route::post('/register/mahasiswa/store', [AuthController::class, 'store_mahasiswa'])->name('store.mahasiswa');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $today = Carbon::today();

        $silabus = silabus::whereDate('created_at', $today)->get();
        $materi = Materi::whereDate('created_at', $today)->get();
        $tugas = Tugas::whereDate('created_at', $today)->get();

        return view('dashboard.index', [
            'jumlah_mahasiswa' => Mahasiswa::count(),
            'jumlah_pembelajaran' => Silabus::count(),
            'jumlah_materi' => Materi::count(),
            'jumlah_tugas' => Tugas::count(),
            'pembelajaran' => $silabus,
            'materi' => $materi,
            'tugas' => $tugas,
        ]);
    })->name('dashboard');

    Route::get('/dashboard/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/dashboard/profile/{id_user}/update', [ProfileController::class, 'update'])->name('update.profile');
    Route::put('/dashboard/profile/{id_user}/update/password', [ProfileController::class, 'update_password'])->name('update.password');

    Route::get('/dashboard/pembelajaran', [PembelajaranController::class, 'index'])->name('pembelajaran');
    Route::post('/dashboard/pembelajaran', [PembelajaranController::class, 'store'])->name('store.pembelajaran');
    Route::put('/dashboard/pembelajaran/{id_silabus}/update', [PembelajaranController::class, 'update'])->name('update.pembelajaran');
    Route::delete('/dashboard/pembelajaran/{id_silabus}/delete', [PembelajaranController::class, 'destroy'])->name('delete.pembelajaran');

    Route::get('/dashboard/materi', [MateriController::class, 'index'])->name('materi');
    Route::post('/dashboard/materi', [MateriController::class, 'store'])->name('store.materi');
    Route::put('/dashboard/materi/{id_materi}/update', [materiController::class, 'update'])->name('update.materi');
    Route::delete('/dashboard/materi/{id_materi}/delete', [materiController::class, 'destroy'])->name('delete.materi');

    Route::get('/dashboard/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa');
    Route::delete('/dashboard/mahasiswa/{id_user}', [MahasiswaController::class, 'destroy'])->name('destroy.mahasiswa');

    Route::get('/dashboard/absensi', [AbsensiController::class, 'index'])->name('absensi');
    Route::post('/dashboard/absensi/store', [AbsensiController::class, 'store'])->name('store.absensi');
    Route::put('/dashboard/absensi/{id_absensi}/terima', [AbsensiController::class, 'terima'])->name('terima_absensi');
    Route::put('/dashboard/absensi/{id_absensi}/tolak', [AbsensiController::class, 'tolak'])->name('tolak_absensi');
    Route::delete('/dashboard/absensi/{id_absensi}/hapus', [AbsensiController::class, 'destroy'])->name('delete.absensi');

    Route::get('/dashboard/tugas', [TugasController::class, 'index'])->name('tugas');
    Route::post('/dashboard/tugas/store', [TugasController::class, 'store'])->name('store.tugas');
    Route::put('/dashboard/tugas/{id_tugas}/update', [TugasController::class, 'update'])->name('update.tugas');
    Route::delete('/dashboard/tugas/{id_tugas}/destroy', [TugasController::class, 'destroy'])->name('hapus.tugas');

    Route::post('/dashboard/tugas/{id_tugas}/kumpul', [TugasController::class, 'kumpul'])->name('kumpul_tugas');
    Route::delete('/dashboard/tugas/{id_tugas}/kumpul/{id_mahasiswa}/hapus', [TugasController::class, 'hapus_nilai'])->name('hapus_nilai');
    Route::put('/dashboard/tugas/nilai/{id_nilai}/store', [NilaiController::class, 'store'])->name('nilai.store');
    Route::put('/dashboard/tugas/nilai/{id_nilai}/update', [NilaiController::class, 'update'])->name('nilai.update');

    Route::get('/dashboard/nilai', [NilaiController::class, 'index'])->name('nilai');
    Route::post('/dashboard/nilai/store', [NilaiController::class, 'store'])->name('store.rekap');
    Route::put('/dashboard/nilai/{id_rekap_nilai}/update', [NilaiController::class, 'update_rekap'])->name('update.rekap');
    Route::delete('/dashboard/nilai/{id_rekap_nilai}/hapus', [NilaiController::class, 'destroy'])->name('destroy.rekap');

    Route::get('/dashboard/setting', [SettingController::class, 'index'])->name('setting');

    Route::post('/dashboard/setting/jurusan', [SettingController::class, 'storeJurusan'])->name('jurusan.store');
    Route::put('/dashboard/setting/jurusan/{id}', [SettingController::class, 'updateJurusan'])->name('jurusan.update');
    Route::delete('/dashboard/setting/jurusan/{id}/delete', [SettingController::class, 'destroyJurusan'])->name('jurusan.destroy');
    
    Route::post('/dashboard/setting/mata_kuliah', [SettingController::class, 'storeMataKuliah'])->name('mata_kuliah.store');
    Route::put('/dashboard/setting/mata_kuliah/{id}', [SettingController::class, 'updateMataKuliah'])->name('mata_kuliah.update');
    Route::delete('/dashboard/setting/mata_kuliah/{id}/delete', [SettingController::class, 'destroyMataKuliah'])->name('mata_kuliah.destroy');
});
