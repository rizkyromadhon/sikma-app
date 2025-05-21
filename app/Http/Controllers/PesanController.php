<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesanController extends Controller
{
    public function index()
    {
        $laporan = Laporan::where('user_id', Auth::user()->id)->orderBy('created_at', 'asc')->get();

        return view('pesan', compact('laporan'));
    }
}
