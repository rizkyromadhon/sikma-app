<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Golongan;
use App\Models\Presensi;
use App\Models\JadwalKuliah;
use App\Models\ProgramStudi;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

   

    public function jadwalKuliah()
    {
        return view('admin.pages.jadwal-kuliah');
    }

    public function presensi()
    {
        return view('admin.pages.presensi');
    }
}

