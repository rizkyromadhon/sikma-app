<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;


Route::get('/', function () {
    return view('home', ['title' => 'Halaman Home']);
});

Route::get('/jadwal-kelas', function () {
    return view('jadwal-kelas', ['title' => 'Halaman Jadwal Kelas']);
});

Route::get('/presensi-kuliah', function () {
    return view('presensi-kuliah', ['title' => 'Halaman Presensi Kuliah']);
});

Route::get('/tentang-kami', function () {
    return view('tentang-kami', ['title' => 'Halaman Tentang Kami']);
});

Route::get('/login', function () {
    return view('login', ['title' => 'Halaman Login']);
})->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/register', function () {
    return view('register', ['title' => 'Halaman Login']);
});
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/profile', function () {
    return view('profile');
})->middleware('auth')->name('profile');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');


Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

