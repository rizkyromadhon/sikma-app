<?php

namespace App\Http\Controllers\Admin;

use App\Models\Golongan;
use App\Models\AlatPresensi;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GolonganController extends Controller
{
    public function index(Request $request)
    {
        $programStudi = ProgramStudi::all();

        $datas = Golongan::with('programStudi')
            ->join('program_studi', 'golongan.id_prodi', '=', 'program_studi.id')
            ->select('golongan.*')
            ->orderBy('program_studi.name', 'asc')
            ->orderBy('golongan.nama_golongan', 'asc');

        if ($request->has('id_prodi') && $request->id_prodi != '') {
            $datas = $datas->where('golongan.id_prodi', $request->id_prodi);
        }

        $datas = $datas->paginate(8);

        AlatPresensi::where('id', 1)->update(['mode' => 'attendance']);

        return view('admin.golongan.index', compact('datas', 'programStudi'));
    }


    public function create(Request $request)
    {
        $validate = $request->validate([
            'id_prodi' => 'required|exists:program_studi,id',
            'nama_golongan' => 'required'
        ]);

        $existGolongan = Golongan::where('nama_golongan', strtoupper($request->nama_golongan))
            ->where('id_prodi', $request->id_prodi)
            ->first();

        if ($existGolongan) {
            $prodi = ProgramStudi::find($request->id_prodi)->nama; // ambil nama prodi untuk notifikasi
            return redirect()->route('admin.golongan.index', ['id_prodi' => $request->id_prodi])
                ->with("error", "Golongan " . strtoupper($request->nama_golongan) . " di Program Studi " . $prodi . " telah terdaftar!");
        }

        $golongan = new Golongan();
        $golongan->nama_golongan = strtoupper($validate['nama_golongan']);
        $golongan->id_prodi = $validate['id_prodi'];
        $golongan->save();

        return redirect()->route('admin.golongan.index', ['id_prodi' => $request->id_prodi])
            ->with('success', 'Berhasil menambahkan golongan baru!');
    }

    public function destroy(Request $request, $id)
    {
        Golongan::where('id', $id)->delete();

        return redirect()->route('admin.golongan.index', [
            'id_prodi' => $request->id_prodi
        ])->with('success', 'Berhasil menghapus golongan');
    }
}
