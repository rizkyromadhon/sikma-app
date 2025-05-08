<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProdiController extends Controller
{
    public function index()
    {
        $datas = ProgramStudi::all();
        return view('admin.prodi.index', compact('datas'));
    }

    public function create(Request $request)
    {
        $validate = $request->validate([
            "name" => "required|string"
        ]);

        $prodi = new ProgramStudi();
        $prodi->name = $validate["name"];
        $prodi->save();

        return redirect()->route('admin.prodi.index')->with("success", "Berhasil menambahkan program studi baru!");
    }

    public function update(Request $request, $id) {
        if(empty($request->name)) {
            return redirect()->route('admin.prodi.index')->with("error", "Field nama prodi tidak boleh kosong!");
        }

        $existProdi = ProgramStudi::where('name', $request->name)->first();

        if($existProdi) {
            return redirect()->route('admin.prodi.index')->with("error", "Prodi " . $request->name . " telah terdaftar!");
        }

        $prodi = ProgramStudi::where('id', $id)->first();
        $prodi->name = $request->name;
        $prodi->save();
        return redirect()->route('admin.prodi.index')->with("success", "Berhasil memperbarui program studi!");
    }

    public function destroy($id)
    {
        ProgramStudi::where('id', $id)->delete();
        return redirect()->route('admin.prodi.index')->with("success", "Berhasil menghapus program studi!");
    }
}
