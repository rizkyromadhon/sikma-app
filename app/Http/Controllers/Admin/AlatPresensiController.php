<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ruangan;
use App\Models\AlatPresensi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AlatPresensiController extends Controller
{
    public function index()
    {
        $alatPresensi = AlatPresensi::with('ruangan')->get();

        AlatPresensi::where('id', 1)->update(['mode' => 'attendance']);
        return view('admin.alat-presensi.index', compact('alatPresensi'));
    }

    public function create()
    {
        $ruangan = Ruangan::all();
        return view('admin.alat-presensi.create', compact('ruangan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:alat_presensi,name,except,id',
            'id_ruangan' => 'required',
            'ssid' => 'nullable|string|max:255',
            'wifi_password' => 'nullable|string|max:255',
            'jadwal_nyala' => 'nullable|date_format:H:i',
            'jadwal_mati' => 'nullable|date_format:H:i',
        ], [
            'name.unique' => 'Nama alat presensi sudah ada.',
        ]);

        $alatPresensi = new AlatPresensi();

        $alatPresensi->name = $validated['name'];
        $alatPresensi->id_ruangan = $validated['id_ruangan'];
        $alatPresensi->ssid = $validated['ssid'];
        $alatPresensi->wifi_password = $validated['wifi_password'];
        $alatPresensi->jadwal_nyala = $validated['jadwal_nyala'];
        $alatPresensi->jadwal_mati = $validated['jadwal_mati'];
        $alatPresensi->status_aktif = 0;

        $alatPresensi->save();

        return redirect()->route('admin.alat-presensi.index')->with('success', 'Alat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $ruangan = Ruangan::all();
        $alat = AlatPresensi::findOrFail($id);
        return view('admin.alat-presensi.edit', compact('alat', 'ruangan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:alat_presensi,name,' . $id,
            'id_ruangan' => 'required',
            'ssid' => 'nullable|string|max:255',
            'wifi_password' => 'nullable|string|max:255',
            'jadwal_nyala' => 'required|date_format:H:i:s',
            'jadwal_mati' => 'required|date_format:H:i:s',
        ]);

        $alat = AlatPresensi::where('id', $id)->first();

        $alat->name = $validated['name'];
        $alat->id_ruangan = $validated['id_ruangan'];
        $alat->ssid = $validated['ssid'];
        $alat->wifi_password = $validated['wifi_password'];
        $alat->jadwal_nyala = $validated['jadwal_nyala'];
        $alat->jadwal_mati = $validated['jadwal_mati'];

        $alat->save();

        return redirect()->route('admin.alat-presensi.index')->with('success', 'Alat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $alat = AlatPresensi::findOrFail($id);
        $alat->delete();

        return redirect()->route('alat.index')->with('success', 'Alat berhasil dihapus.');
    }

    public function show($id)
    {
        $alat = AlatPresensi::findOrFail($id);

        return response()->json([
            'id' => $alat->id,
            'name' => $alat->name,
            'ssid' => $alat->ssid,
            'wifi_password' => $alat->wifi_password,
            'jadwal_nyala' => $alat->jadwal_nyala,
            'jadwal_mati' => $alat->jadwal_mati,
            'status_aktif' => $alat->status_aktif,
            'mode' => $alat->mode,
        ]);
    }
}
