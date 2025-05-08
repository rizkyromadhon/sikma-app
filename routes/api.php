<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PresensiKuliahController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::post('/store-presensi', [HomeController::class, 'storePresensi']);
Route::post('/store-presensi', [PresensiKuliahController::class, 'store']);
