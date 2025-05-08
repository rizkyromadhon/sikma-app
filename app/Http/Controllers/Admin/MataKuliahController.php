<?php

namespace App\Http\Controllers\Admin;

use App\Models\Golongan;
use App\Models\Semester;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MataKuliahController extends Controller
{
    public function index()
    {
        $datas = MataKuliah::all();
        return view('admin.mata-kuliah.index', compact('datas'));
    }

    public function create()
    {
        return view('admin.mata-kuliah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:mata_kuliah,name,except,id',
            'kode' => 'required|max:5|unique:mata_kuliah,kode,except,id',
        ], [
            'name.required' => 'Nama mata kuliah harus diisi',
            'name.unique' => 'Nama mata kuliah sudah ada',
            'kode.required' => 'Kode mata kuliah harus diisi',
            'kode.unique' => 'Kode mata kuliah sudah ada',
            'kode.max' => 'Kode mata kuliah maksimal 5 karakter',
        ]);

        $mata_kuliah = new MataKuliah();
        $mata_kuliah->name = $request->name;
        $mata_kuliah->kode = strtoupper($request->kode);
        $mata_kuliah->save();

        return redirect()->route('admin.mata-kuliah.index')->with('success', 'Berhasil menambahkan mata kuliah baru!');
    }

    public function edit($id)
    {
        // dd($id);
        // $datas = MataKuliah::findOrFail($id);
        $datas = MataKuliah::where('id', (int) $id)->first();
        // dd($datas->kode);
        return view('admin.mata-kuliah.edit', compact('datas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:mata_kuliah,name,except,id',
            'kode' => 'required|max:5|unique:mata_kuliah,kode,except,id',
        ], [
            'name.required' => 'Nama mata kuliah harus diisi',
            'name.unique' => 'Nama mata kuliah sudah ada',
            'kode.required' => 'Kode mata kuliah harus diisi',
            'kode.unique' => 'Kode mata kuliah sudah ada',
            'kode.max' => 'Kode mata kuliah maksimal 5 karakter',
        ]);

        $mata_kuliah = MataKuliah::where('id', $id)->first();
        $mata_kuliah->name = $request->name;
        $mata_kuliah->kode = strtoupper($request->kode);
        $mata_kuliah->save();

        return redirect()->route('admin.mata-kuliah.index')->with('success', 'Berhasil mengubah mata kuliah!');
    }

    public function destroy($id)
    {
        MataKuliah::where('id', $id)->delete();
        return redirect()->route('admin.mata-kuliah.index')->with('success', 'Berhasil menghapus mata kuliah!');
    }
}
