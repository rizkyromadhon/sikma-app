<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PusatBantuanController extends Controller
{
    public function index()
    {
        return view("pusat-bantuan");
    }
}
