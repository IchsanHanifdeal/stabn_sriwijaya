<?php

use App\Models\Jurusan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

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
        return view('dashboard.index');
    })->name('dashboard');

    Route::get('/dashboard/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/dashboard/profile/update', [ProfileController::class, 'update'])->name('update.profile');
    Route::post('/dashboard/profile/update/password', [ProfileController::class, 'update_password'])->name('update.password');
});
