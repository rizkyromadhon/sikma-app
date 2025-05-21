<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PresensiKuliahController;
use App\Http\Controllers\Admin\AlatPresensiController;
use App\Http\Controllers\Admin\RfidController;

// Route::post('/store-presensi', [HomeController::class, 'storePresensi']);
Route::post('/store-presensi', [PresensiKuliahController::class, 'store']);
Route::get('/alat-presensi/{id}', [AlatPresensiController::class, 'show']);
Route::post('/register-rfid', [RfidController::class, 'getRfid']);
Route::get('/get-uid', [RfidController::class, 'getUid']);
