<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ruangan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AlatPresensi;

class RuanganController extends Controller
{
    public function index()
    {
        $datas = Ruangan::paginate(8);

        AlatPresensi::where('id', 1)->update(['mode' => 'attendance']);
        return view("admin.ruangan.index", compact("datas"));
    }

    public function create()
    {
        return view("admin.ruangan.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:ruangan,name,except,id',
            'kode_ruangan' => 'required|unique:ruangan,kode,except,id',
            'type' => 'required'
        ], [
            'name.required' => 'Nama ruangan harus diisi',
            'name.unique' => 'Nama ruangan sudah ada',
            'kode_ruangan.unique' => 'Kode ruangan sudah ada',
            'type.required' => 'Silahkan pilih tipe ruangan'
        ]);

        $ruangan = new Ruangan();
        $ruangan->name = $request->name;
        $ruangan->kode = $request->kode_ruangan;
        $ruangan->type = $request->type;
        $ruangan->save();

        return redirect()->route('admin.ruangan.index')->with('success', 'Berhasil menambahkan ruangan baru!');
    }

    public function edit($id)
    {
        $data = Ruangan::where('id', $id)->first();
        $kode = $data->kode ?? null;

        // parsing kode
        $lantai = substr($kode, -3, 1); // ambil karakter ke-3 dari belakang (misal '1')
        $no_ruangan = substr($kode, -2);
        return view("admin.ruangan.edit", compact("data", "lantai", "no_ruangan", 'kode'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:ruangan,name,except,id',
            'kode_ruangan' => 'required|unique:ruangan,kode,except,id',
            'type' => 'required'
        ], [
            'name.required' => 'Nama ruangan harus diisi',
            'name.unique' => 'Gunakan nama ruangan yang berbeda',
            'kode_ruangan.unique' => 'Gunakan kode ruangan yang berbeda',
            'type.required' => 'Silahkan pilih tipe ruangan'
        ]);

        $ruangan = Ruangan::where('id', $request->id)->first();
        $ruangan->name = $request->name;
        $ruangan->kode = $request->kode_ruangan;
        $ruangan->type = $request->type;
        $ruangan->save();

        return redirect()->route('admin.ruangan.index')->with('success', 'Berhasil mengubah ruangan!');
    }

    public function destroy($id)
    {
        Ruangan::where('id', $id)->delete();
        return redirect()->route('admin.ruangan.index')->with('success', 'Berhasil menghapus ruangan!');
    }
}
